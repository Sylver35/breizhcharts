<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use phpbb\json_response;
use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\verify;
use sylver35\breizhcharts\core\points;
use phpbb\language\language;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\db\driver\driver_interface as db;
use phpbb\cache\service as cache;
use phpbb\request\request;

class ajax
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\verify */
	protected $verify;

	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(work $work, verify $verify, points $points, language $language, user $user, auth $auth, db $db, cache $cache, request $request, $breizhcharts_table, $breizhcharts_voters_table)
	{
		$this->work = $work;
		$this->verify = $verify;
		$this->points = $points;
		$this->language = $language;
		$this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
		$this->cache = $cache;
		$this->request = $request;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
	}

	public function song_view($id, $song_view)
	{
		$new_song_view = $song_view + 1;
		$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET song_view = song_view + 1 WHERE song_id = ' . (int) $id);

		$json_response = new json_response;
		$json_response->send([
			'song_view'	=> $new_song_view,
			'title'		=> $this->language->lang('BC_SONG_VIEW', $new_song_view),
		]);
	}

	public function handle_vote()
	{
		$id = (int) $this->request->variable('id', 0);
		$rate = (int) $this->request->variable('rate', 0);
		$ids = (string) $this->request->variable('ids', '');
		$json_response = new json_response;

		if (!$this->auth->acl_gets(['u_breizhcharts_vote', 'a_breizhcharts_manage', 'm_breizhcharts_manage']))
		{
			// No permission ! Send the response to the browser now
			$json_response->send([
				'sort'		=> 0,
				'id'		=> $id,
				'message'	=> $this->language->lang('BC_AJAX_NO_VOTE'),
			]);
			return;
		}

		// Check if user have already voted
		$sql = 'SELECT COUNT(vote_song_id) AS counter
			FROM ' . $this->breizhcharts_voters_table . '
				WHERE vote_song_id = ' . (int) $id . '
				AND vote_user_id = ' . (int) $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$counter = (int) $this->db->sql_fetchfield('counter');
		$this->db->sql_freeresult($result);

		if ($counter)
		{
			// Already voted ! Send the response to the browser now
			$json_response->send([
				'sort'		=> 0,
				'id'		=> $id,
				'message'	=> $this->language->lang('BC_ALREADY_VOTED'),
			]);
		}
		else
		{
			if (!in_array($this->user->data['user_id'], explode(', ', $ids)))
			{
				// First time, add this user in the cache list
				$this->cache->destroy('_breizhcharts_voters');
			}
			// Send the vote in the db
			$data = $this->run_vote_ajax($id, $rate);

			// Good ! Send the response to the browser now
			$json_response->send([
				'sort'		=> 1,
				'id'		=> (int) $id,
				'total'		=> (int) $data['total'],
				'newResult'	=> number_format($data['new_note'] * 10, 2) . '%',
				'totalRate'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($data['new_note'], 2)),
				'songRated'	=> $this->language->lang('BC_AJAX_NOTE_NB', $data['new_nb']),
				'userVote'	=> $this->language->lang('BC_AJAX_NOTE', $rate),
				'message'	=> $data['message'],
			]);
		}
	}

	private function run_vote_ajax($id, $rate)
	{
		// Grab some data from the breizhcharts table
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.song_name, c.artist, c.song_note, c.nb_note, SUM(v.vote_rate) as total',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [$this->breizhcharts_voters_table => 'v'],
					'ON'	=> 'v.vote_song_id = c.song_id',
				],
			],
			'GROUP_BY'	=> 'c.song_name, c.artist, c.song_note, c.nb_note',
			'WHERE'		=> 'song_id = ' . (int) $id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Create array for the voting
		$sql_ary = [
			'vote_user_id'	=> (int) $this->user->data['user_id'],
			'vote_song_id'	=> (int) $id,
			'vote_rate'		=> (int) $rate,
		];
		$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_voters_table . $this->db->sql_build_array('INSERT', $sql_ary));

		// Update the breizhcharts table
		$total = (isset($row['total'])) ? (int) $row['total'] : 0;
		$new_nb = $row['nb_note'] + 1;
		$new_note = ($total + $rate) / $new_nb;
		$data = [
			'song_note'	=> (float) $new_note,
			'nb_note'	=> (int) $new_nb,
		];
		$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE song_id = ' . (int) $id);
		// Refresh the cache positions
		$this->cache->destroy('_breizhcharts_positions');

		$message = $this->points->points_in_vote($this->language->lang('BC_VOTE_SUCCESS', $row['song_name'], $row['artist']));

		return [
			'total'		=> $total,
			'new_note'	=> $new_note,
			'new_nb'	=> $new_nb,
			'message'	=> $message,
		];
	}

	public function check_song()
	{
		// Check if new song probably already exist
		$id = (int) $this->request->variable('id', 0);
		$song = (string) $this->request->variable('song', '', true);
		$artist = (string) $this->request->variable('artist', '', true);
		$json_response = new json_response;

		if ($this->verify->verify_song_name($id, $song, $artist) !== false)
		{
			$json_response->send([
				'sort'		=> 2,
				'message'	=> $this->language->lang('BC_ALREADY_EXISTS_ERROR', $song, $artist),
			]);
		}
		else
		{
			$json_response->send([
				'sort'		=> 1,
				'message'	=> $this->language->lang('BC_NO_EXISTS', $song, $artist),
			]);
		}
	}

	public function check_youtube_video($check, $song_id)
	{
		$url = (string) $this->request->variable('url', '', true);
		$json_response = new json_response;

		// First, check if video exist in the chart if needed
		if ($check == 1)
		{
			$video = $this->verify->verify_url_in_db($url, $song_id);
			if (isset($video['name']))
			{
				$json_response->send([
					'sort'		=> 3,
					'name'		=> $video['name'],
					'time'		=> $video['time'],
					'image'		=> $video['image'],
					'url'		=> $video['url'],
				]);
			}
		}
		// Second, check existing video on YouTube
		if ($youtube_id = $this->work->get_youtube_id($url))
		{
			$json_response->send([
				'sort'		=> 1,
				'message'	=> $this->language->lang('BC_AJAX_VIDEO'),
				'content'	=> $this->work->get_youtube_img($youtube_id),
			]);
		}
		else
		{
			$json_response->send([
				'sort'		=> 2,
				'message'	=> $this->language->lang('BC_AJAX_VIDEO_NO'),
			]);
		}
	}
}
