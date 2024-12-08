<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use phpbb\json_response;
use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\charts;
use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\verify;
use sylver35\breizhcharts\core\points;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\pagination;
use phpbb\log\log;
use phpbb\cache\service as cache;
use phpbb\request\request;
use phpbb\config\config;
use phpbb\extension\manager as ext_manager;
use phpbb\path_helper;
use phpbb\event\dispatcher_interface as phpbb_dispatcher;

class breizhcharts
{
	/** @var \sylver35\breizhcharts\core\charts */
	protected $charts;

	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\verify */
	protected $verify;

	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;	

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php_ext */
	protected $php_ext;

	/** @var string ext_path */
	protected $ext_path;

	/** @var string ext_path_web */
	protected $ext_path_web;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(charts $charts, work $work, verify $verify, points $points, template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_voters_table)
	{
		$this->charts = $charts;
		$this->work = $work;
		$this->verify = $verify;
		$this->points = $points;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->auth = $auth;
		$this->helper = $helper;
		$this->db = $db;
		$this->pagination = $pagination;
		$this->log = $log;
		$this->cache = $cache;
		$this->request = $request;
		$this->config = $config;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function handle()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_view', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'BC_NOT_AUTHORISED');
		}

		$data = [
			'mode'			=> (string) $this->request->variable('mode', 'list_newest'),
			'start'			=> (int) $this->request->variable('start', 0),
			'song_id'		=> (int) $this->request->variable('id', 0),
			'userid'		=> (int) $this->request->variable('user', 0),
			'winner'		=> (int) $this->request->variable('winner', 0),
			'name'			=> (string) $this->request->variable('name', '', true),
			'title_mode'	=> $this->language->lang('BC_NEWEST'),
			'order_by'		=> 'c.song_id DESC',
			'body'			=> 'breizhcharts.html',
		];

		/**
		 * You can use this event before all modes listed here
		 *
		 * @event breizhcharts.list_mode_before
		 * @var	array
		 * @since 1.1.0
		 */
		$vars = ['data'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.list_mode_before', compact($vars)));

		// Switch the mode
		switch ($data['mode'])
		{
			case 'list_newest':
				$data = array_merge($data, [
					'rules'			=> true,
					'title_mode'	=> $this->language->lang('BC_NEWEST'),
				]);
				$this->charts->get_list_mode_charts($data);
			break;

			case 'list':
				$data = array_merge($data, [
					'rules'			=> true,
					'title_mode'	=> $this->language->lang('BC_BEST_RATED'),
					'order_by'		=> 'c.song_note DESC, c.nb_note DESC, c.last_pos ASC, c.best_pos ASC',
				]);
				$this->charts->get_list_mode_charts($data);
			break;

			case 'own':
				$data = array_merge($data, [
					'rules'			=> true,
					'title_mode'	=> $this->language->lang('BC_OWN'),
				]);
				$this->charts->get_list_mode_charts($data);
			break;

			case 'user':
				$data = array_merge($data, [
					'rules'			=> true,
					'title_mode'	=> $this->language->lang('BC_OF_USER', $data['name']),
				]);
				$this->charts->get_list_mode_charts($data);
			break;

			case 'winners':
				$data = array_merge($data, [
					'rules'			=> false,
					'body'			=> 'breizhcharts_winners.html',
					'title_mode'	=> $this->language->lang('BC_LAST_WINNERS'),
				]);
				$this->charts->get_winners_charts($data['winner']);
			break;
		}

		$this->charts->get_voters();
		$this->charts->create_navigation($data);

		/**
		 * You can use this event after all modes listed here
		 *
		 * @event breizhcharts.list_mode_after
		 * @var	array
		 * @since 1.1.0
		 */
		$vars = ['data'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.list_mode_after', compact($vars)));

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $data['title_mode']);

		// Load charts template
		$this->template->set_filenames([
			'body' => $data['body'],
		]);

		page_footer();
	}

	public function handle_popup()
	{
		$video_id = (int) $this->request->variable('id', 0);

		$sql = 'SELECT song_id, song_name, artist, video
			FROM ' . $this->breizhcharts_table . '
				WHERE song_id = ' . $video_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'S_IN_VIDEO'	=> true,
			'VIDEO_TITLE' 	=> $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']),
			'YOUTUBE_ID'	=> $this->work->get_youtube_id($row['video']),
		]);

		return $this->helper->render('breizhcharts_video_popup.html', $this->language->lang('BC_CHARTS') . ' - ' . $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']));
	}

	public function handle_vote()
	{
		$json_response = new json_response;
		$song_id = (int) $this->request->variable('id', 0);
		$rate = (int) $this->request->variable('rate', 0);

		if (!$this->auth->acl_gets(['u_breizhcharts_vote', 'a_breizhcharts_manage']))
		{
			// Send the response to the browser now
			$json_response->send([
				'sort'		=> 0,
				'id'		=> $song_id,
				'message'	=> $this->language->lang('BC_AJAX_NO_VOTE'),
			]);
			return;
		}

		// Check if user have already voted
		$sql = 'SELECT COUNT(vote_song_id) AS counter
			FROM ' . $this->breizhcharts_voters_table . '
				WHERE vote_song_id = ' . $song_id . '
				AND vote_user_id = ' . $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$counter = (int) $this->db->sql_fetchfield('counter');
		$this->db->sql_freeresult($result);

		if (!$counter)
		{
			$data = $this->run_vote_ajax($song_id, $rate);

			// Send the response to the browser now
			$json_response->send([
				'sort'		=> 1,
				'id'		=> $song_id,
				'total'		=> $data['total'],
				'newResult'	=> number_format($data['new_note'] * 10, 2) . '%',
				'totalRate'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($data['new_note'], 2)),
				'songRated'	=> $this->language->lang('BC_AJAX_NOTE_NB', $data['new_nb']),
				'userVote'	=> $this->language->lang('BC_AJAX_NOTE', $rate),
				'message'	=> $data['message'],
			]);
		}
		else
		{
			// Send the response to the browser now
			$json_response->send([
				'sort'		=> 0,
				'id'		=> $song_id,
				'message'	=> $this->language->lang('BC_ALREADY_VOTED'),
			]);
		}
	}

	private function run_vote_ajax($song_id, $rate)
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
			'WHERE'		=> 'song_id = ' . (int) $song_id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Create array for the voting
		$sql_ary = [
			'vote_user_id'	=> $this->user->data['user_id'],
			'vote_song_id'	=> $song_id,
			'vote_rate'		=> $rate,
		];
		$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_voters_table . $this->db->sql_build_array('INSERT', $sql_ary));

		// Update the breizhcharts table
		$total = (isset($row['total'])) ? (int) $row['total'] : 0;
		$new_nb = (int) $row['nb_note'] + 1;
		$new_note = ($total + $rate) / $new_nb;
		$data = [
			'song_note'	=> (float) $new_note,
			'nb_note'	=> $new_nb,
		];
		$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE song_id = ' . (int) $song_id);
		$this->cache->destroy('sql', $this->breizhcharts_table);

		$message = $this->language->lang('BC_VOTE_SUCCESS', $row['song_name'], $row['artist']);
		if ($this->points->points_active() && $this->config['breizhcharts_points_per_vote'] > 0)
		{
			// Giving points for voting, if UPS is installed and active
			$this->points->add_user_points($this->user->data['user_id'], $this->config['breizhcharts_points_per_vote']);
			$message .= $this->language->lang('BC_VOTE_SUCCESS_UPS', $this->config['breizhcharts_points_per_vote'], $this->config['points_name']);
		}

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

	public function check_youtube_video()
	{
		$json_response = new json_response;
		$url = (string) $this->request->variable('url', '', true);

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

	public function add_song()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_add', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'BC_SONG_ADD_NO');
		}

		$error = [];
		$data = [
			'song_name'		=> $this->request->variable('song_name', '', true),
			'artist'		=> $this->request->variable('artist', '', true),
			'album'			=> $this->request->variable('album', '', true),
			'year'			=> $this->request->variable('year', ''),
			'video'			=> $this->request->variable('video', '', true),
			'poster_id'		=> $this->user->data['user_id'],
			'add_time'		=> time(),
			'topic_id'		=> 0,
		];

		if ($this->request->is_set_post('post'))
		{
			$error = $this->validate_add_song($data);
		}
		else
		{
			$error = $this->verify->verify_max_entries();
		}

		$this->charts->get_template_charts(false);
		$this->charts->create_navigation(['mode' => 'add', 'title_mode' => $this->language->lang('BC_ADD_SONG'), 'song_id' => 0, 'name' => '', 'userid' => 0]);

		$this->template->assign_vars([
			'NAV_ID'			=> 'add',
			'S_ADD_SONG'		=> true,
			'BC_ERROR'			=> $error,
			'TITLE_PAGE'		=> $this->language->lang('BC_ADD_SONG'),
			'CHART_SONG_NAME'	=> $data['song_name'],
			'CHART_ARTIST'		=> $data['artist'],
			'CHART_ALBUM'		=> $data['album'],
			'CHART_YEAR'		=> $data['year'],
			'CHART_VIDEO'		=> $data['video'],
			'S_REQ_1'			=> $this->config['breizhcharts_required_1'],
			'S_REQ_2'			=> $this->config['breizhcharts_required_2'],
			'U_EXT_PATH'		=> $this->ext_path_web,
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'U_CHECK_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_check_video'),
		]);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $this->language->lang('BC_ADD_SONG'));

		// Load template
		$this->template->set_filenames([
			'body' => 'breizhcharts_add.html',
		]);

		page_footer();
	}

	private function validate_add_song($data)
	{
		if ($error = $this->verify->verify_chart_before_send($data, 0))
		{
			return implode('<br>', $error);
		}
		else
		{
			// Announce new songs, if enabled, create topic
			$url = '';
			if ($this->config['breizhcharts_announce_enable'])
			{
				$url = $this->charts->create_topic($data['song_name'], $data['artist'], $data['video']);
				$data['topic_id'] = (int) $this->charts->find_string($url . '#', 't=', '#');
			}

			$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_table . $this->db->sql_build_array('INSERT', $data));
			$id = $this->db->sql_last_inserted_id();
			$this->config->increment('breizhcharts_songs_nb', 1, true);
			$this->config->set('breizhcharts_last_song', time(), true);

			/**
			 * You can use this event when a song is added
			 *
			 * @event breizhcharts.add_song_after
			 * @var	array
			 * @since 1.1.0
			 */
			$vars = ['data', 'id', 'url'];
			extract($this->phpbb_dispatcher->trigger_event('breizhcharts.add_song_after', compact($vars)));

			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_ADDED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $data['song_name'], $data['artist'])]);
			meta_refresh(3, $this->helper->route('sylver35_breizhcharts_page_music'));
			if ($this->points->points_active() && $this->config['breizhcharts_ups_points'] > 0)
			{
				$this->points->add_user_points($this->user->data['user_id'], $this->config['breizhcharts_ups_points']);
				trigger_error($this->language->lang('BC_SONG_ADDED_UPS', $this->config['breizhcharts_ups_points'], $this->config['points_name']) . '<br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
			}
			else
			{
				trigger_error($this->language->lang('BC_SONG_ADDED') . '<br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
			}
		}
	}

	public function edit_song()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_edit', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}
		
		$error = '';
		$song_id = (int) $this->request->variable('id', 0);
		$start = (int) $this->request->variable('start', 0);
		if ($this->request->is_set_post('post'))
		{
			$error = $this->validate_edit_song($song_id, $start);
		}
		$this->charts->get_template_charts(false);

		$sql = 'SELECT *
			FROM ' . $this->breizhcharts_table . '
				WHERE song_id = ' . (int) $song_id;
		$result = $this->db->sql_query($sql);
		$data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$title_mode = $this->language->lang('BC_EDIT_SONG') . ' : ' . $data['song_name'];
		$this->template->assign_vars([
			'S_EDIT_SONG'		=> true,
			'ERROR'				=> $error,
			'TITLE_PAGE'		=> $title_mode,
			'CHART_ID'			=> $data['song_id'],
			'CHART_SONG_NAME'	=> $data['song_name'],
			'CHART_ARTIST'		=> $data['artist'],
			'CHART_ALBUM'		=> $data['album'],
			'CHART_YEAR'		=> $data['year'],
			'CHART_VIDEO'		=> $data['video'],
			'S_REQ_1'			=> $this->config['breizhcharts_required_1'],
			'S_REQ_2'			=> $this->config['breizhcharts_required_2'],
			'U_EXT_PATH'		=> $this->ext_path_web,
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'U_CHECK_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_check_video'),
		]);

		$this->charts->create_navigation(['mode' => 'edit', 'title_mode' => $title_mode, 'song_id' => $song_id, 'name' => '', 'userid' => 0]);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $title_mode);

		// Load template
		$this->template->set_filenames([
			'body' => 'breizhcharts_add.html',
		]);

		page_footer();
	}

	private function validate_edit_song($song_id, $start)
	{
		$data = [
			'song_name'		=> $this->request->variable('song_name', '', true),
			'artist'		=> $this->request->variable('artist', '', true),
			'album'			=> $this->request->variable('album', '', true),
			'year'			=> $this->request->variable('year', ''),
			'video'			=> $this->request->variable('video', '', true),
		];

		if ($error = $this->verify->verify_chart_before_send($data, $song_id))
		{
			return implode('<br>', $error);
		}
		else
		{
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE song_id = ' . $song_id);

			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_EDITED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $data['song_name'], $data['artist'])]);
			$this->cache->destroy('sql', $this->breizhcharts_table);

			$redirect_url = $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list', 'start' => $start]);
			meta_refresh(3, $redirect_url);
			trigger_error($this->language->lang('BC_SONG_EDIT_SUCCESS', $data['song_name']) . '<br><br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $redirect_url . '">', '</a>'));
		}
	}

	public function delete_song()
	{
		$delete_id = (int) $this->request->variable('id', 0);
		if (confirm_box(true))
		{
			$sql = 'SELECT poster_id, song_name, artist, topic_id
				FROM ' . $this->breizhcharts_table . '
					WHERE song_id = ' . $delete_id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$title = $row['song_name'];
			$artist = $row['artist'];
			$topic_id = (int) $row['topic_id'];
			$this->db->sql_freeresult($result);

			$this->db->sql_query('DELETE FROM ' . $this->breizhcharts_table . ' WHERE song_id = ' . $delete_id);
			$this->db->sql_query('DELETE FROM ' . $this->breizhcharts_voters_table . ' WHERE vote_song_id = ' . $delete_id);
			$this->cache->destroy('sql', $this->breizhcharts_table);
			
			if ($topic_id)
			{
				include_once($this->root_path . 'includes/functions_admin.' . $this->php_ext);
				delete_topics('topic_id', [$topic_id], false);
			}

			$this->config->increment('breizhcharts_songs_nb', -1, true);
			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_DELETED', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $title, $artist)]);

			meta_refresh(3, $this->helper->route('sylver35_breizhcharts_page_music'));
			trigger_error($this->language->lang('BC_DELETE_SUCCESS', $title) . '<br><br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
		}
		else
		{
			if ($this->request->is_set_post('cancel'))
			{
				redirect($this->helper->route('sylver35_breizhcharts_page_music'));
			}
			else
			{
				confirm_box(false, $this->language->lang('BC_DELETE_SONG_EXPLAIN'), build_hidden_fields([
					'id'		=> $delete_id,
					'action'	=> 'delete',
				]));
			}
		}
	}
}
