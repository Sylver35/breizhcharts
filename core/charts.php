<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\check;
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
use Symfony\Component\DependencyInjection\Container as phpbb_container;
use phpbb\event\dispatcher_interface as phpbb_dispatcher;

class charts
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\check */
	protected $check;

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

	/** @var \Symfony\Component\DependencyInjection\Container */
	protected $phpbb_container;

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
	public function __construct(work $work, check $check, points $points, template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_container $phpbb_container, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_result_table, $breizhcharts_voters_table)
	{
		$this->work = $work;
		$this->check = $check;
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
		$this->phpbb_container = $phpbb_container;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_result_table = $breizhcharts_result_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function find_string($string, $start, $end)
	{
		$ini = strpos($string, $start);
		if ($ini == 0)
		{
			return $ini;
		}
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		$value = substr($string, $ini, $len);

		return $value;
	}

	public function create_topic($song, $artist, $video)
	{
		if (!function_exists('submit_post'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}

		$flags = 0;
		$poll_ary = $uid = $bitfield = '';
		$url = '[url=' . $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']) . ']' . $this->language->lang('BC_GO_CHARTS') . '[/url]';
		$song_title = utf8_encode_ucr($this->language->lang('BC_ANNOUNCE_TITLE', $song, $artist));
		$song_msg = $this->language->lang('BC_ANNOUNCE_MSG', $song, $artist, $url, $this->work->get_youtube_img($video, true));
		generate_text_for_storage($song_msg, $uid, $bitfield, $flags, true, true, true);

		$data = [
			'forum_id'			=> (int) $this->config['breizhcharts_song_forum'],
			'icon_id'			=> false,
			'enable_bbcode'		=> true,
			'enable_smilies'	=> true,
			'enable_urls'		=> true,
			'enable_sig'		=> true,
			'message'			=> (string) $song_msg,
			'message_md5'		=> (string) md5($song_msg),
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

	public function get_voters()
	{
		$i = 0;
		$usernames = [];
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_voters_table => 'v'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = v.vote_user_id',
				],
			],
			'GROUP_BY'	=> 'v.vote_user_id, u.user_id, u.username, u.user_colour',
		]);
		$result = $this->db->sql_query($sql, 800);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$usernames[$i] = $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']);
			$i++;
		}
		$this->db->sql_freeresult($result);

		if ($i > 0)
		{
			$this->template->assign_vars([
				'S_USERS_VOTED'		=> true,
				'VOTED_USERS'		=> $this->language->lang('BC_VOTED_USERS', $i),
				'LIST_USER_VOTED'	=> implode(', ', $usernames),
			]);  
		}
	}

	public function get_total_charts($select)
	{
		if ($select !== '')
		{
			$sql = 'SELECT COUNT(song_id) AS total_charts
				FROM ' . $this->breizhcharts_table . $select;
			$result = $this->db->sql_query($sql, 800);
			$total_charts = (int) $this->db->sql_fetchfield('total_charts');
			$this->db->sql_freeresult($result);
		}
		else
		{
			$total_charts = (int) $this->config['breizhcharts_songs_nb'];
		}

		return $total_charts;
	}

	public function create_navigation($data)
	{
		$url = 'sylver35_breizhcharts_page_music';
		$url_array = [];

		if ($data['mode'] == 'add')
		{
			$url = 'sylver35_breizhcharts_add_music';
		}
		else if ($data['mode'] == 'edit')
		{
			$url = 'sylver35_breizhcharts_edit_music';
		}
		else
		{
			$url_array = [
				'mode'	=> $data['mode'],
			];
		}

		if ($data['song_id'])
		{
			$url_array['id'] = $data['song_id'];
		}
		else if ($data['userid'] && $data['name'])
		{
			$url_array = [
				'user'	=> $data['userid'],
				'name'	=> $data['name'],
			];
		}

		// Main template variables for the navigation
		$this->add_navlinks([
			$this->language->lang('BC_CHARTS')	=> $this->helper->route('sylver35_breizhcharts_page_music'),
			$data['title_mode']					=> $this->helper->route($url, $url_array),
		]);
	}

	private function add_navlinks($links)
	{
		foreach ($links as $name => $link)
		{
			$this->template->assign_block_vars('navlinks', [
				'FORUM_NAME'	=> $name,
				'U_VIEW_FORUM'	=> $link,
			]);
		}
	}

	public function get_list_mode_charts($data)
	{
		$i = 1;
		$this->check->update_breizhcharts_check();
		$data = $this->work->build_data_in_mode($data);
		$total_charts = $this->get_total_charts($data['select']);
		$this->get_template_charts($data['rules']);
		$tendency = $this->work->build_tendency();
		$number = (int) $this->config['breizhcharts_user_page'];

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, v.vote_rate as user_vote, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				],
				[
					'FROM'	=> [$this->breizhcharts_voters_table => 'v'],
					'ON'	=> 'v.vote_song_id = c.song_id AND v.vote_user_id = ' . $this->user->data['user_id'],
				],
			],
			'WHERE'		=> $data['where'],
			'ORDER_BY'	=> $data['order_by'],
		]);
		$result = $this->db->sql_query_limit($sql, $number, $data['start']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$is_user = (int) $row['poster_id'] === (int) $this->user->data['user_id'];
			$can_edit = ($this->auth->acl_get('u_breizhcharts_edit') && $is_user || $this->auth->acl_get('a_breizhcharts_manage'));
			$row['user_vote'] = isset($row['user_vote']) ? (int) $row['user_vote'] : 0;

			$this->template->assign_block_vars('charts', [
				'POSITION'		=> $i + $data['start'],
				'SONG_ID'		=> $row['song_id'],
				'USERNAME'		=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'TITLE'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'ALBUM'			=> $row['album'],
				'YEAR'			=> $row['year'],
				'TENDENCY_IMG'	=> $tendency[$row['song_id']]['image'],
				'ACTUAL'		=> $tendency[$row['song_id']]['actual'],
				'LAST'			=> $tendency[$row['song_id']]['last'],
				'BEST'			=> $tendency[$row['song_id']]['best_pos'],
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'THUMBNAIL'		=> $this->work->get_youtube_img($row['video'], true),
				'STARS_VOTE'	=> $this->work->stars_vote($row['song_id'], $row['user_vote'], $this->language->lang('BC_AJAX_NOTE', $row['user_vote']), $row['song_note']),
				'TOTAL_RATE'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['song_note'], 2)),
				'SONG_RATED'	=> $this->language->lang('BC_AJAX_NOTE_NB', (int) $row['nb_note']),
				'USER_VOTE'		=> $this->language->lang('BC_AJAX_NOTE', $row['user_vote']),
				'VOTED_IMG'		=> (!$row['user_vote']) ? 'not-rated' : 'rated',
				'ADDED_TIME'	=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'])),
				'U_SHOW_VIDEO'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'U_DELETE_SONG'	=> $this->auth->acl_get('a_breizhcharts_manage') ? $this->helper->route('sylver35_breizhcharts_delete_music', ['id' => $row['song_id']]) : '',
				'U_EDIT_SONG'	=> $can_edit ? $this->helper->route('sylver35_breizhcharts_edit_music', ['id' => $row['song_id'], 'start' => $data['start']]) : '',
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'S_LIST'			=> true,
			'NAV_ID'			=> $data['mode'],
			'TITLE_PAGE'		=> $data['title_mode'],
			'U_USER_NAV'		=> $data['userid'] ? $data['pagin'] : '',
			'USER_NAV_TITLE'	=> $data['userid'] ? $data['title_mode'] : '',
			'TOTAL_CHARTS'		=> $this->language->lang('BC_SONG_NB', $total_charts),
			'S_ON_PAGE'			=> $total_charts > $number,
			'PAGE_NUMBER'		=> $this->pagination->validate_start($total_charts, $number, $data['start']),
		]);
		$this->pagination->generate_template_pagination($data['pagin'], 'pagination', 'start', $total_charts, $number, $data['start']);
	}

	public function get_winners_charts($winner)
	{
		$i = $nb_win = 0;
		$points_active = $this->points->points_active();
		$date_result = $this->config['breizhcharts_last_result'];
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_result_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.result_poster_id',
				],
			],
			'WHERE'		=> ($winner) ? 'c.result_nb = ' . $winner : "c.result_time = '$date_result'",
			'ORDER_BY'	=> 'c.result_nb DESC, c.result_pos ASC',
		]);
		$result = $this->db->sql_query_limit($sql, (int) $this->config['breizhcharts_winners_per_page']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$nb_win = (int) $row['result_nb'];
			$date_result = $row['result_time'];
			$data = $this->check->get_win_charts((int) $row['result_pos'], $points_active);

			$this->template->assign_block_vars('winners', [
				'NB'			=> $i,
				'RANK'			=> $data['img'],
				'WIN'			=> $data['win'],
				'SONG'			=> $row['result_song_name'],
				'ARTIST'		=> $row['result_artist'],
				'USER'			=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['result_song_name']),
				'THUMBNAIL'		=> $this->work->get_youtube_img($row['result_video'], true),
				'U_SHOW_VIDEO'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['result_song_id']]),
				'RESULT'		=> number_format($row['result_song_note'] * 10, 2),
				'TITLE_RATE'	=> strip_tags($this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['result_song_note'], 2))),
				'TOTAL_RATE'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['result_song_note'], 2)),
				'SONG_RATED'	=> $this->language->lang('BC_AJAX_NOTE_NB', $row['result_nb_note']),
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		// Last bonus winner
		if ($points_active && $this->config['breizhcharts_winner_id'] > 0)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_winner_id'];
			$result = $this->db->sql_query($sql, 6800);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'S_BONUS_WINNER'	=> true,
				'BONUS_WINNER'		=> $this->language->lang('BC_BONUS_WINNER', $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']), $this->config['breizhcharts_voters_points'], $this->config['points_name']),
			]);
		}

		$this->get_template_charts(false);
		$this->template->assign_vars([
			'S_LAST_WINNERS'	=> true,
			'NAV_ID'			=> 'winners',
			'SELECT_WINS'		=> $this->work->get_all_wins($nb_win),
			'TITLE_PAGE'		=> $this->language->lang('BC_LAST_WINNERS_DATE', $this->user->format_date($date_result, $this->language->lang('BC_LAST_WINNERS_FORMAT'))),
		]);
	}

	public function get_template_charts($rules)
	{
		$title_explain = '';
		if ($this->config['breizhcharts_period_activ'])
		{
			$lang_period = ((int) $this->config['breizhcharts_period_val'] === 86400) ? 'BC_DAY' : 'BC_WEEK';
			$period = $this->language->lang($lang_period, $this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']);
			$date_finish = $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE'));
			$title_explain = $this->language->lang('BC_HEADER_BIS_EXPLAIN') . $this->language->lang('BC_HEADER_TER_EXPLAIN', $period, $date_finish);
		}

		$this->template->assign_vars([
			'S_LIST_NAV'		=> true,
			'S_RULES'			=> $rules,
			'U_EXT_PATH'		=> $this->ext_path_web,
			'S_ACTIV_PERIOD'	=> $this->config['breizhcharts_period_activ'],
			'MC_TITLE_EXPLAIN'	=> $title_explain,
			'MC_TOP_XX'			=> $this->language->lang('BC_TOP_TEN', $this->config['breizhcharts_num_top']),
			'U_ADD_SONG'		=> $this->auth->acl_get('u_breizhcharts_add') ? $this->helper->route('sylver35_breizhcharts_add_music') . '#start' : '',
			'U_LIST_TOP'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list']) . '#start',
			'U_LIST_NEWEST'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']) . '#start',
			'U_LIST_OWN'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own']) . '#start',
			'U_LAST_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'winners']) . '#start',
			'U_SELECT_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'winners']),
			'U_VOTE_MUSIC'		=> $this->helper->route('sylver35_breizhcharts_vote'),
		]);
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
			// Reset all notes
			$this->reset_all_notes();
			$points_active = $this->points->points_active();
			if ($points_active)
			{
				$this->work->run_random_winner();
				$this->points->points_to_winners();
			}

			// Send PM to the winners
			if ($this->config['breizhcharts_pm_enable'])
			{
				$this->work->send_pm_to_winners($points_active);
			}

			// Truncate the voters table
			$this->db->sql_query('TRUNCATE ' . $this->breizhcharts_voters_table);
			$this->cache->destroy('sql', $this->breizhcharts_voters_table);
		}

		// Reset the users checks value
		$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 0, breizhchart_check_2 = 0 WHERE breizhchart_check_1 <> 0');

		// Add a log entry, when the job is done
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_RESET', time());
	}

	private function reset_all_notes()
	{
		$i = 1;
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
			$winner = $this->create_winner($row, $i);

			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE song_id = ' . (int) $row['song_id']);
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

	private function create_winner($row, $i)
	{
		if ($i === 1)
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
}
