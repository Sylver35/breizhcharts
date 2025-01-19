<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\check;
use sylver35\breizhcharts\core\points;
use sylver35\breizhcharts\core\contact;
use phpbb\language\language;
use phpbb\user;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\log\log;
use phpbb\cache\service as cache;
use phpbb\config\config;
use phpbb\extension\manager as ext_manager;
use phpbb\path_helper;
use phpbb\event\dispatcher_interface as phpbb_dispatcher;

class result
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\check */
	protected $check;

	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

	/** @var \sylver35\breizhcharts\core\contact */
	protected $contact;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\controller\helper */
	protected $helper;	

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	
	/** @var \phpbb\log\log */
	protected $log;
	
	/** @var \phpbb\cache\service */
	protected $cache;

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
	protected $breizhcharts_result_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(work $work, check $check, points $points, contact $contact, language $language, user $user, helper $helper, db $db, log $log, cache $cache, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_result_table, $breizhcharts_voters_table)
	{
		$this->work = $work;
		$this->check = $check;
		$this->points = $points;
		$this->contact = $contact;
		$this->language = $language;
		$this->user = $user;
		$this->helper = $helper;
		$this->db = $db;
		$this->log = $log;
		$this->cache = $cache;
		$this->config = $config;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_result_table = $breizhcharts_result_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function create_topic($song, $artist, $video, $cat, $comment)
	{
		if (!function_exists('submit_post'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}

		$flags = 0;
		$poll_ary = $uid = $bitfield = '';
		// The futur new id is the last at this time + 1
		$video_id = $this->check->get_last_chart() + 1;
		$cat_name = $this->work->get_cat_name($cat);

		// Switch language to default lang if needed
		$switch_lang = $this->work->language_switch($this->config['default_lang'], false);

		// First, create the title and message in default language
		$song_title = utf8_encode_ucr($this->language->lang('BC_ANNOUNCE_TITLE', $song, $artist));
		$message = $this->language->lang('BC_ANNOUNCE_MSG',
			$this->work->get_youtube_img($video, true),
			$song,
			$artist,
			$cat_name,
			$comment ? $this->language->lang('BC_ANNOUNCE_USER', $comment) : '',
			$this->helper->route('sylver35_breizhcharts_video', ['id' => $video_id, 'song_name' => $this->work->display_url($song)]) . '#nav',
			$this->language->lang('BC_CLICK_VIDEO'),
		);

		// Switch language to user lang if needed
		$switch_lang = $this->work->language_switch($this->config['default_lang'], $switch_lang);
		unset($switch_lang);

		// Second, add the second part of message in user language if different...
		if ((string) $this->user->data['user_lang'] !== (string) $this->config['default_lang'])
		{
			$message .= $this->language->lang('BC_ANNOUNCE_SEPARATE');
			$message .= $this->language->lang('BC_ANNOUNCE_MSG',
				$this->work->get_youtube_img($video, true),
				$song,
				$artist,
				$cat_name,
				$comment ? $this->language->lang('BC_ANNOUNCE_USER', $comment) : '',
				$this->helper->route('sylver35_breizhcharts_video', ['id' => $video_id, 'song_name' => $this->work->display_url($song)]) . '#nav',
				$this->language->lang('BC_CLICK_VIDEO'),
			);
		}

		generate_text_for_storage($message, $uid, $bitfield, $flags, true, true, true);

		$data = [
			'forum_id'			=> (int) $this->config['breizhcharts_song_forum'],
			'icon_id'			=> false,
			'enable_bbcode'		=> true,
			'enable_smilies'	=> true,
			'enable_urls'		=> true,
			'enable_sig'		=> true,
			'message'			=> (string) $message,
			'message_md5'		=> (string) md5($message),
			'bbcode_bitfield'	=> (string) $bitfield,
			'bbcode_uid'		=> (string) $uid,
			'post_edit_locked'	=> 0,
			'topic_title'		=> (string) $song_title,
			'notify_set'		=> false,
			'notify'			=> false,
			'post_time'			=> (int) time(),
			'poster_id'			=> (int) $this->user->data['user_id'],
			'forum_name'		=> '',
			'enable_indexing'	=> true,
		];
		$post = submit_post('post', $song_title, '', POST_NORMAL, $poll_ary, $data);

		return $post;
	}

	public function run_vote_charts_period()
	{
		// Reset time
		$new_period = $this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'];
		$this->config->set('breizhcharts_start_time', $new_period);
		$this->config->set('breizhcharts_start_time_bis', date('d-m-Y H:i', $new_period));
		$this->config->set('breizhcharts_last_result', time());

		// Check if there are any voters - needed to decide, if we have winners in this period
		$sql = 'SELECT COUNT(vote_id) AS total_votes
			FROM ' . $this->breizhcharts_voters_table;
		$result = $this->db->sql_query($sql);
		$total_votes = (int) $this->db->sql_fetchfield('total_votes');
		$this->db->sql_freeresult($result);
		if (!empty($total_votes))
		{
			$points_active = $this->points->points_active();
			if ($points_active)
			{
				$this->contact->run_random_winner();
				$this->points->points_to_winners();
			}

			// Send PM to the winners
			if ($this->config['breizhcharts_pm_enable'])
			{
				$this->contact->send_pm_to_winners($points_active);
			}

			// Reset all notes
			$this->reset_all_notes();

			// Truncate the voters table
			$this->db->sql_query('TRUNCATE ' . $this->breizhcharts_voters_table);
			$this->cache->destroy('sql', $this->breizhcharts_voters_table);
			$this->cache->destroy('_breizhcharts_voters');
		}

		// Reset the users checks value
		$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 0, breizhchart_check_2 = 0 WHERE breizhchart_check_1 <> 0');

		// Add a log entry, when the job is done
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_RESET', time());
	}

	private function reset_all_notes()
	{
		$i = 1;
		$last_nb = 0;
		$time = time();
		$sql_insert = $winner = [];

		$sql = 'SELECT MAX(result_nb) AS old_result
			FROM ' . $this->breizhcharts_result_table;
		$result = $this->db->sql_query($sql);
		$old_result = (int) $this->db->sql_fetchfield('old_result');
		$this->db->sql_freeresult($result);
		$new_result = $old_result + 1;

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> '*',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> 'nb_note > 0',
			'ORDER_BY'	=> 'song_note DESC, nb_note DESC, last_pos ASC, best_pos ASC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			// reset the note and update the position
			$sql_ary = [
				'song_note'	=> 0,
				'nb_note'	=> 0,
				'last_pos'	=> $i,
				'best_pos'	=> ($i < (int) $row['best_pos'] || (int) $row['best_pos'] === 0) ? $i : (int) $row['best_pos'],
			];

			// create insert of 10 bests
			if (($i < 11) && ($row['nb_note'] > 0))
			{
				$sql_insert[] = $this->create_insert($row, $time, $new_result, $i);
			}
			// create insert of winner
			if ($i == 1)
			{
				$winner = $this->create_winner($row);
			}

			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE song_id = ' . (int) $row['song_id']);
			$last_nb = $new_result;
			$i++;
		}
		$this->db->sql_freeresult($result);

		/**
		 * You can use this event when the period has come to an end
		 *
		 * @event breizhcharts.reset_all_notes
		 * @var	array
		 * @since 1.1.0
		 */
		$vars = ['sql_ary', 'sql_insert', 'winner'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.reset_all_notes', compact($vars)));

		$this->db->sql_multi_insert($this->breizhcharts_result_table, $sql_insert);
		$this->config->set('breizhcharts_last_nb', $last_nb);
	}

	private function create_insert($row, $time, $new_result, $i)
	{
		return [
			'result_song_id'		=> $row['song_id'],
			'result_nb'				=> $new_result,
			'result_time'			=> $time,
			'result_pos'			=> $i,
			'result_song_name'		=> $row['song_name'],
			'result_artist'			=> $row['artist'],
			'result_video'			=> $row['video'],
			'result_poster_id'		=> $row['poster_id'],
			'result_song_note'		=> $row['song_note'],
			'result_nb_note'		=> $row['nb_note'],
			'result_add_time'		=> $row['add_time'],
		];
	}

	private function create_winner($row)
	{
		return [
			'song_id'		=> $row['song_id'],
			'song_name'		=> $row['song_name'],
			'artist'		=> $row['artist'],
			'video'			=> $row['video'],
			'poster_id'		=> $row['poster_id'],
			'song_note'		=> $row['song_note'],
		];
	}
}
