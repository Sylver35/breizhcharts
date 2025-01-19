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
use sylver35\breizhcharts\core\verify;
use sylver35\breizhcharts\core\contact;
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

	/** @var \sylver35\breizhcharts\core\verify */
	protected $verify;

	/** @var \sylver35\breizhcharts\core\contact */
	protected $contact;

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
	protected $breizhcharts_cats_table;
	protected $breizhcharts_result_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(work $work, check $check, points $points, verify $verify, contact $contact, template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_container $phpbb_container, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_result_table, $breizhcharts_voters_table)
	{
		$this->work = $work;
		$this->check = $check;
		$this->points = $points;
		$this->verify = $verify;
		$this->contact = $contact;
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
		//$this->phpbb_container = $phpbb_container;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
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

	public function get_total_charts($select)
	{
		$sql = 'SELECT COUNT(song_id) AS total_charts
			FROM ' . $this->breizhcharts_table . $select;
		$result = $this->db->sql_query($sql);
		$total_charts = (int) $this->db->sql_fetchfield('total_charts');
		$this->db->sql_freeresult($result);

		return $total_charts;
	}

	public function get_list_charts($data)
	{
		$i = 1;
		$title_cat = '';
		$this->check->update_breizhcharts_check();
		$data = $this->verify->build_data_in_mode($data);
		$total_charts = $this->get_total_charts($data['select']);
		$this->get_template_charts($data['rules']);
		$number = (int) $this->config['breizhcharts_user_page'];
		$position = $this->work->get_positions();

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, a.*, v.vote_rate as user_vote, u.user_id, u.username, u.user_colour',
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
				[
					'FROM'	=> [$this->breizhcharts_cats_table => 'a'],
					'ON'	=> 'a.cat_id = c.cat',
				],
			],
			'WHERE'		=> $data['where'],
			'ORDER_BY'	=> $data['order_by'],
		]);
		$result = $this->db->sql_query_limit($sql, $number, $data['start']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$is_user = (int) $row['poster_id'] === (int) $this->user->data['user_id'];
			$can_edit = ($this->auth->acl_get('u_breizhcharts_edit') && $is_user || $data['moderate']);
			$can_delete = ($this->auth->acl_get('u_breizhcharts_delete') && $is_user || $data['moderate']);
			$title_cat = $data['cat'] ? ' - ' . $row['cat_name'] : '';
			$can_vote = $data['is_user'] && $this->auth->acl_get('u_breizhcharts_vote');

			$this->template->assign_block_vars('charts', [
				'POSITION'			=> $position[$row['song_id']]['position'],
				'SONG_ID'			=> $row['song_id'],
				'TITLE'				=> $row['song_name'],
				'ARTIST'			=> $row['artist'],
				'ALBUM'				=> $row['album'],
				'YEAR'				=> $row['year'],
				'CAT'				=> isset($row['cat_name']) ? $row['cat_name'] : '',
				'USERNAME'			=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'TENDENCY_IMG'		=> $this->work->get_tendency_image($position[$row['song_id']]['position'], (int) $row['last_pos']),
				'ACTUAL'			=> $this->language->lang('BC_ACTUAL', $position[$row['song_id']]['position']),
				'LAST'				=> ((int) $row['last_pos'] === 0) ? $this->language->lang('BC_ENTER') : $this->language->lang('BC_LATEST', $row['last_pos']),
				'BEST'				=> $this->language->lang('BC_BEST_POS', $row['best_pos']),
				'VIDEO'				=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'THUMBNAIL'			=> $this->work->get_youtube_img($row['video'], true),
				'STARS_VOTE'		=> $this->work->stars_vote($row['song_id'], $row['song_note'], $row['user_vote'], $can_vote),
				'TOTAL_RATE'		=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['song_note'], 2)),
				'SONG_RATED'		=> $this->language->lang('BC_AJAX_NOTE_NB', (int) $row['nb_note']),
				'USER_VOTE'			=> $can_vote ? $this->language->lang('BC_AJAX_NOTE', (int) $row['user_vote']) : $this->language->lang('BC_AJAX_NOTE_NO'),
				'VOTED_IMG'			=> $row['user_vote'] ? 'rated' : 'not-rated',
				'ADDED_TIME'		=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'])),
				'TITLE_SONG_VIEW'	=> $this->language->lang('BC_SONG_VIEW_SHORT', (int) $row['song_view']),
				'S_REPORT'			=> $this->auth->acl_get('u_breizhcharts_report'),
				'S_REPORTED'		=> $row['reported'],
				'U_REPORTED'		=> ($row['reported'] && $can_edit) ? $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]) : '',
				'U_TOPIC_VIDEO'		=> $row['topic_id'] ? append_sid("{$this->root_path}viewtopic.{$this->php_ext}", 't=' . $row['topic_id']) : '',
				'U_SHOW_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_video', ['id' => (int) $row['song_id'], 'song_name' => $this->work->display_url($row['song_name'])]) . '#nav',
				'U_SHOW_POPUP'		=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'U_DELETE_SONG'		=> $can_delete ? $this->helper->route('sylver35_breizhcharts_delete_music', ['id' => $row['song_id']]) : '',
				'U_EDIT_SONG'		=> $can_edit ? $this->helper->route('sylver35_breizhcharts_edit_video', ['id' => $row['song_id'], 'start' => $data['start'], 'cat' => $data['cat']]) : '',
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$data['title_mode'] = ($data['userid'] ? $this->language->lang('BC_OF_USER', $data['name']) : $data['title_mode']) . $title_cat;
		$this->template->assign_vars([
			'S_LIST'			=> true,
			'NAV_ID'			=> $data['mode'],
			'TITLE_PAGE'		=> $data['title_mode'],
			'U_USER_NAV'		=> $data['userid'] ? $data['pagin'] : '',
			'USER_NAV_TITLE'	=> $data['userid'] ? $this->language->lang('BC_OF_USER', $data['name']) : '',
			'SELECT_CATS'		=> $this->work->get_cats_select($data['cat'], $data['url'], $data['url_sel']),
			'TOTAL_CHARTS'		=> $this->language->lang('BC_SONG_NB', $total_charts),
			'S_ON_PAGE'			=> $total_charts > $number,
			'PAGE_NUMBER'		=> $this->pagination->validate_start($total_charts, $number, $data['start']),
		]);
		$this->pagination->generate_template_pagination($data['pagin'], 'pagination', 'start', $total_charts, $number, $data['start']);

		return $data['title_mode'];
	}

	public function get_winners_charts($data)
	{
		$i = $result_time = 0;
		$points_active = $this->points->points_active();
		$result_id = !$data['result_id'] ? $this->config['breizhcharts_last_nb'] : $data['result_id'];
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'r.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_result_table => 'r'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = r.result_poster_id',
				],
			],
			'WHERE'		=> 'r.result_nb = ' . (int) $result_id,
			'ORDER_BY'	=> 'r.result_nb DESC, r.result_pos ASC',
		]);
		$result = $this->db->sql_query_limit($sql, 10);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$result_time = $row['result_time'];
			$list = $this->check->get_win_charts((int) $row['result_pos'], $points_active);

			$this->template->assign_block_vars('winners', [
				'NB'			=> $i + 1,
				'RANK'			=> $list['img'],
				'WIN'			=> $list['win'],
				'SONG'			=> $row['result_song_name'],
				'ARTIST'		=> $row['result_artist'],
				'USER'			=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['result_song_name']),
				'THUMBNAIL'		=> $this->work->get_youtube_img($row['result_video'], true),
				'U_SHOW_VIDEO'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['result_song_id']]),
				'RESULT'		=> number_format($row['result_song_note'] * 10, 2),
				'TITLE_RATE'	=> strip_tags($this->language->lang('BC_AJAX_NOTE_TOTAL', number_format((int) $row['result_song_note'], 2))),
				'TOTAL_RATE'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format((int) $row['result_song_note'], 2)),
				'SONG_RATED'	=> $this->language->lang('BC_AJAX_NOTE_NB', (int) $row['result_nb_note']),
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->get_template_charts(false);
		$this->points->return_last_winner();
		$title_mode = $this->language->lang('BC_LAST_WINNERS') . ' - ' . $this->language->lang('BC_LAST_WINNERS_DATE', $this->user->format_date($result_time, $this->language->lang('BC_LAST_WINNERS_FORMAT')));

		$this->template->assign_vars([
			'S_LAST_WINNERS'	=> true,
			'NAV_ID'			=> 'winners',
			'SELECT_WINS'		=> $this->work->get_all_wins($result_id),
			'TITLE_PAGE'		=> $title_mode,
		]);

		$data = array_merge($data, [
			'url'			=> 'sylver35_breizhcharts_result',
			'url_array'		=> [],
			'url_param'		=> '?result_id=' . $this->config['breizhcharts_last_nb'],
			'title_mode'	=> $this->language->lang('BC_LAST_WINNERS'),
			'url2' 			=> 'sylver35_breizhcharts_result',
			'url_array2'	=> [],
			'url_param2'	=> '?result_id=' . $result_id,
			'title_mode2'	=> $this->user->format_date($result_time, $this->language->lang('BC_LAST_WINNERS_FORMAT')),
			'body'			=> 'breizhcharts_winners.html',
		]);

		return $data;
	}

	public function get_template_charts($rules)
	{
		$title_explain = '';
		$is_user = $this->user->data['is_registered'] && !$this->user->data['is_bot'];
		$reports = !$is_user ?: $this->work->get_reported_videos();
		if ($this->config['breizhcharts_period_activ'])
		{
			$lang_period = ((int) $this->config['breizhcharts_period_val'] === 86400) ? 'BC_DAY' : 'BC_WEEK';
			$period = $this->language->lang($lang_period, $this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']);
			$date_finish = $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE'));
			$title_explain = $this->language->lang('BC_HEADER_BIS_EXPLAIN') . $this->language->lang('BC_HEADER_TER_EXPLAIN', $period, $date_finish);
		}

		$this->template->assign_vars([
			'S_IN_CHARTS'		=> true,
			'S_RULES'			=> $rules,
			'NB_REPORT'			=> $is_user ? $reports['nb'] : 0,
			'HAS_REPORT'		=> !$is_user ? '' : (($reports['nb'] > 0) ? ' red-icon' : ''),
			'MC_TITLE_EXPLAIN'	=> $title_explain,
			'MC_TOP_XX'			=> $this->language->lang('BC_TOP_TEN', $this->config['breizhcharts_num_top']),
			'U_ADD_SONG'		=> ($is_user && $this->auth->acl_get('u_breizhcharts_add')) ? $this->helper->route('sylver35_breizhcharts_add_video') . '#nav' : '',
			'U_LIST_TOP'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list', 'cat' => 0]) . '#nav',
			'U_LIST_NEWEST'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => 0]) . '#nav',
			'U_LIST_OWN'		=> $is_user ? $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own', 'cat' => 0]) . '#nav' : '',
			'U_LAST_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_result'),
			'U_REPORTS_LIST'	=> $is_user ? $this->helper->route('sylver35_breizhcharts_list_report') : '',
			'U_VOTE_MUSIC'		=> ($is_user && $this->auth->acl_get('u_breizhcharts_vote')) ? $this->helper->route('sylver35_breizhcharts_vote') : '',
			'U_EXT_PATH'		=> $this->ext_path_web,
			'S_REPORT'			=> $this->auth->acl_get('u_breizhcharts_report'),
			'S_ACTIV_PERIOD'	=> $this->config['breizhcharts_period_activ'],
			'S_REPORTS_LIST'	=> $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']),
			'U_BC_TOOLS'		=> $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? $this->helper->route('sylver35_breizhcharts_tools', ['mode' => 'all']) : '',
		]);
	}

	public function display_video($data)
	{
		$this->get_template_charts(false);
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, v.vote_rate',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [$this->breizhcharts_voters_table => 'v'],
					'ON'	=> 'v.vote_song_id = c.song_id AND v.vote_user_id = ' . $this->user->data['user_id'],
				],
			],
			'WHERE'		=> 'song_id = ' . (int) $data['song_id'],
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$poster = ((int) $row['poster_id'] === (int) $this->user->data['user_id']) && $data['is_user'];
		$can_edit = ($this->auth->acl_get('u_breizhcharts_edit') && $poster || $data['moderate']);
		$can_report = ($this->auth->acl_get('u_breizhcharts_report') && $data['is_user'] || $data['moderate']);
		$can_vote = $data['is_user'] && $this->auth->acl_get('u_breizhcharts_vote');

		$this->template->assign_vars([
			'S_IN_VIDEO'		=> true,
			'SONG_ID'			=> $row['song_id'],
			'SONG_VIEW'			=> $row['song_view'],
			'REPORTED_TITLE'	=> $row['reported'] ? $this->user->lang['bc_report_reasons']['TITLE'][$row['reason']] : '',
			'REPORTED_DESC'		=> $row['reported'] ? $this->user->lang['bc_report_reasons']['DESCRIPTION'][$row['reason']] : '',
			'TITLE_PAGE' 		=> $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']),
			'YOUTUBE_ID'		=> $this->work->get_youtube_id($row['video']),
			'VIDEO_WIDTH'		=> $data['mobile'] ? '640' : $this->config['breizhcharts_video_width'],
			'VIDEO_HEIGHT'		=> $data['mobile'] ? '360' : $this->config['breizhcharts_video_height'],
			'TITLE_SONG_VIEW'	=> $this->language->lang('BC_SONG_VIEW', (int) $row['song_view']),
			'STARS_VOTE'		=> $this->work->stars_vote($row['song_id'], $row['song_note'], $row['vote_rate'], $can_vote),
			'TOTAL_RATE'		=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['song_note'], 2)),
			'SONG_RATED'		=> $this->language->lang('BC_AJAX_NOTE_NB', (int) $row['nb_note']),
			'USER_VOTE'			=> $can_vote ? $this->language->lang('BC_AJAX_NOTE', (int) $row['vote_rate']) : $this->language->lang('BC_AJAX_NOTE_NO'),
			'VOTED_IMG'			=> ($row['vote_rate'] && $data['is_user']) ? 'rated' : 'not-rated',
			'U_VIEW_SONG'		=> $this->helper->route('sylver35_breizhcharts_song_view', ['id' => $row['song_id'], 'song_view' => $row['song_view']]),
			'U_REPORT'			=> $this->helper->route('sylver35_breizhcharts_report_video', ['id' => $row['song_id']]),
			'U_REPORT_AUTO'		=> $this->helper->route('sylver35_breizhcharts_report_video_auto', ['id' => $row['song_id']]),
			'U_REPORTED'		=> $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]),
			'U_EDIT_SONG'		=> $can_edit ? $this->helper->route('sylver35_breizhcharts_edit_video', ['id' => $row['song_id'], 'start' => $data['start'], 'cat' => $data['cat']]) : '',
			'S_REPORT'			=> !$poster && $can_report,
			'S_VIEW_REPORT'		=> $poster || $data['moderate'],
			'S_REPORTED'		=> $row['reported'],
		]);

		$data = array_merge($data, [
			'rules'			=> false,
			'url'			=> 'sylver35_breizhcharts_video',
			'song_name'		=> $row['song_name'],
			'url_array'		=> ['id' => $row['song_id'], 'song_name' => $this->work->display_url($row['song_name'])],
			'body'			=> 'breizhcharts_video.html',
			'title_mode'	=> $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']),
		]);

		return $data;
	}
}
