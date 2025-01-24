<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\report;

use phpbb\json_response;
use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\charts;
use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\verify;
use sylver35\breizhcharts\core\contact;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\db\driver\driver_interface as db;
use phpbb\log\log;
use phpbb\cache\service as cache;
use phpbb\request\request;
use phpbb\config\config;
use phpbb\auth\auth;
use phpbb\controller\helper;
use phpbb\event\dispatcher_interface as phpbb_dispatcher;

class report
{
	/** @var \sylver35\breizhcharts\core\charts */
	protected $charts;

	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

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

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php_ext */
	protected $php_ext;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;
	protected $breizhcharts_cats_table;

	/**
	 * Constructor
	 */
	public function __construct(charts $charts, work $work, verify $verify, contact $contact, template $template, language $language, user $user, db $db, log $log, cache $cache, request $request, config $config, auth $auth, helper $helper, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_cats_table)
	{
		$this->charts = $charts;
		$this->work = $work;
		$this->verify = $verify;
		$this->contact = $contact;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->db = $db;
		$this->log = $log;
		$this->cache = $cache;
		$this->request = $request;
		$this->config = $config;
		$this->auth = $auth;
		$this->helper = $helper;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
	}

	public function report_video($id)
	{
		$action = $this->request->variable('action', '');
		$this->charts->get_template_charts(false);

		if ($action == 'validate')
		{
			$this->report_validate((int) $id);
		}
		else
		{
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'b.*, u.user_id, u.username, u.user_colour',
				'FROM'		=> [$this->breizhcharts_table => 'b'],
				'LEFT_JOIN'	=> [
					[
						'FROM'	=> [USERS_TABLE => 'u'],
						'ON'	=> 'u.user_id = b.poster_id',
					],
				],
				'WHERE'		=> 'b.song_id = ' . (int) $id,
			]);
			$result = $this->db->sql_query_limit($sql, 1);
			$row = $this->db->sql_fetchrow($result);

			$this->template->assign_vars([
				'S_IN_REPORT'		=> true,
				'NAV_ID'			=> 'report',
				'CHART_ID'			=> $id,
				'POSTER_ID'			=> $row['user_id'],
				'POSTER_NAME'		=> $row['username'],
				'POSTER_COLOUR'		=> $row['user_colour'],
				'TITLE_PAGE'		=> $this->language->lang('REPORT_VIDEO'),
				'TITLE_REPORT'		=> $this->language->lang('BC_REPORT_TO', $row['song_name'], $row['artist']),
				'THUMBNAIL'			=> $this->work->get_youtube_img($row['video'], true),
				'TITLE'				=> $row['song_name'],
				'ARTIST'			=> $row['artist'],
				'U_VALIDATE'		=> $this->helper->route('sylver35_breizhcharts_report_validate', ['id' => $row['song_id']]) . '?action=validate',
			]);
			$this->db->sql_freeresult($result);

			$i = 0;
			foreach ($this->user->lang['bc_report_reasons']['DESCRIPTION'] as $key => $value)
			{
				if ($key == 'AUTO')
				{
					continue;
				}
				$this->template->assign_block_vars('reasons', array(
					'ID'			=> $i,
					'VALUE'			=> ($i == 0) ? '' : $key,
					'DESCRIPTION'	=> $this->language->lang($value),
					'SELECTED'		=> ($i == 0) ? ' disabled selected="selected"' : '',
				));
				$i++;
			}

			// Output the page
			page_header($this->language->lang('BC_CHARTS') . ' - ' . $this->language->lang('REPORT_VIDEO'));

			// Load report template
			$this->template->set_filenames([
				'body' => 'report_body.html',
			]);

			page_footer();
		}
	}

	public function view_report($id)
	{
		$error = '';
		$action = $this->request->variable('action', '');
		if ($action === 'validate')
		{
			$error = $this->validate_report_song($id);
		}
		else if ($action === 'close_report')
		{
			$this->validate_close_report($id);
		}
		else if ($action === 'send_pm')
		{
			$this->contact->send_pm_view_report($id);
		}

		$song_name = $this->get_report_data($id, $error);
		$data = [
			'title_mode'	=> $this->language->lang('BC_REPORTED_LIST'),
			'url' 			=> 'sylver35_breizhcharts_list_report',
			'url_array'		=> [],
			'title_mode2'	=> $this->language->lang('BC_REPORT_GO'),
			'url2' 			=> 'sylver35_breizhcharts_reported_video',
			'url_array2'	=> ['id' => $id],
		];
		$this->verify->create_phpbb_navigation($data);
		$this->charts->get_template_charts(false);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $this->language->lang('BC_REPORT_TITLE', $song_name));

		// Load report template
		$this->template->set_filenames([
			'body' => 'report_view.html',
		]);

		page_footer();
	}

	private function get_report_data($id, $error)
	{
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'b.*, a.*, u.user_id, u.username, u.user_colour, v.user_id as id, v.username as name, v.user_colour as colour',
			'FROM'		=> [$this->breizhcharts_table => 'b'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = b.poster_id',
				],
				[
					'FROM'	=> [USERS_TABLE => 'v'],
					'ON'	=> 'v.user_id = b.reported',
				],
				[
					'FROM'	=> [$this->breizhcharts_cats_table => 'a'],
					'ON'	=> 'a.cat_id = b.cat',
				],
			],
			'WHERE'		=> 'b.song_id = ' . (int) $id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		if (!$row)
		{
			throw new http_exception(403, 'BC_REPORT_NO_REPORT');
		}

		if (((int) $row['user_id'] !== (int) $this->user->data['user_id']) && !$this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		if (!$row['reported'])
		{
			
			$redirect_url = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? $this->helper->route('sylver35_breizhcharts_list_report') : $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own', 'cat' => 0]);
			$return_msg = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? 'BC_REPORT_BACKLINK' : 'BC_REPORT_BACKLINK_OWN';
			meta_refresh(3, $redirect_url);
			trigger_error($this->language->lang('BC_REPORT_CLOSE_END') . '<br><br>' . $this->language->lang($return_msg, '<a href="' . $redirect_url . '">', '</a>'));
		}

		$row = $this->initialize_report($row);
		$this->enable_posting();

		$this->template->assign_vars([
			'S_IN_VIEW_REPORT'	=> true,
			'CHART_ID'			=> $id,
			'BC_ERROR'			=> $error,
			'NAV_ID'			=> 'reports-list',
			'S_IS_POSTER'		=> $row['is_poster'],
			'S_REPORT_AUTO'		=> (int) $row['reported'] === 3,
			'TITLE_PAGE'		=> $this->language->lang('BC_REPORT_TITLE', $row['song_name']),
			'TITLE_REPORT'		=> $this->language->lang('BC_REPORT_TITLE', $row['song_name']) . $this->language->lang('BC_FROM') . $row['artist'],
			'THUMBNAIL'			=> $this->work->get_youtube_img($row['video'], true),
			'ON_TIME'			=> $this->language->lang('BC_ADDED_TIME_SHORT', $this->user->format_date($row['add_time'], $this->language->lang('BC_FORMAT_DATE_PM'))),
			'INFORM'			=> $this->language->lang('BC_REPORT_INFORM', $row['username']),
			'REPORTNAME'		=> get_username_string('full', $row['id'], $row['name'], $row['colour']),
			'TITLE'				=> $row['song_name'],
			'ARTIST'			=> $row['artist'],
			'GENRE'				=> $row['cat_name'],
			'CHART_CAT'			=> $row['cat'] ? $row['cat'] : 0,
			'CAT_NB'			=> $row['cat_nb'],
			'CHART_VIDEO'		=> $row['video'],
			'USERNAME'			=> $row['user'],
			'USER_ID'			=> $row['user_id'],
			'NAME'				=> $row['username'],
			'COLOUR'			=> $row['user_colour'],
			'ADDED_BY'			=> $this->language->lang('BC_ADDED_BY') . ' ' . $row['user'],
			'EXPLAIN'			=> $this->language->lang('BC_REPORT_FROM', $row['user_report'], $row['reason_title'], $this->user->format_date($row['reported_time'], $this->language->lang('BC_FORMAT_DATE_PM'))),
			'DESCRIPTION'		=> $row['description'],
			'REPORT_TEXT'		=> $row['reported_text'],
			'CLOSE_NO_REASON'	=> $row['is_poster'] ? $this->language->lang('BC_REPORT_CLOSE_NO_REASON_OWN', $row['user_report']) : $this->language->lang('BC_REPORT_CLOSE_NO_REASON', $row['user'], $row['user_report']),
			'SELECT_CATS'		=> $this->work->get_cats_select($row['cat']),
			'U_DELETE_SONG'		=> $this->auth->acl_get('u_breizhcharts_delete') ? $this->helper->route('sylver35_breizhcharts_delete_music', ['id' => $row['song_id']]) : '',
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'U_CHECK_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_check_video', ['check' => 1, 'song_id' => $row['song_id']]),
			'S_POST_ACTION'		=> $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]) . '?action=validate',
			'S_POST_MESSAGE'	=> $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]) . '?action=send_pm',
			'S_POST_CLOSE'		=> $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]) . '?action=close_report&amp;song=' . $row['song_name'] . '&amp;artist=' . $row['artist'] . '&amp;reason=' . $row['reason_title'] . '&amp;poster_id=' . $row['user_id'] . '&amp;report_id=' . $row['id'],
		]);
		$this->db->sql_freeresult($result);

		return $row['song_name'];
	}

	private function initialize_report($row)
	{
		if ((int) $row['reported'] === 3)
		{
			$row['user_report'] = $this->contact->colorize('', $this->language->lang('BC_AUTO_NAME'));
			list($yt_error, $text1, $text2) = explode('||', $row['reported_text']);
			$row['reported_text'] = $this->language->lang($text1);
			$row['reported_text'] .= ($yt_error == 150) ? '<br>' . $this->language->lang('BC_101_RETURN') . $this->language->lang($text2) : '';
		}
		else
		{
			$row['user_report'] = get_username_string('full', $row['id'], $row['name'], $row['colour']);
			$row['reported_text'] = $row['reported_text'] ? $row['reported_text'] : $this->language->lang('BC_REPORT_NEANT');
		}
		$row['reason_title'] = $this->user->lang['bc_report_reasons']['TITLE'][$row['reason']];
		$row['description'] = $this->user->lang['bc_report_reasons']['DESCRIPTION'][$row['reason']];
		$row['user'] = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
		$row['is_poster'] = (int) $row['user_id'] === (int) $this->user->data['user_id'];

		return $row;
	}

	private function validate_close_report($id)
	{
		$song = $this->request->variable('song', '', true);
		$artist = $this->request->variable('artist', '', true);
		$reason = $this->request->variable('reason', '', true);
		$poster_id = $this->request->variable('poster_id', 0);
		$report_id = $this->request->variable('report_id', 0);

		// Close report in db
		$data_video = [
			'reported'			=> 0,
			'reason'			=> '',
			'reported_time'		=> 0,
			'reported_text'		=> '',
		];
		$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data_video) . ' WHERE song_id = ' . (int) $id);
		$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_REPORT_CLOSE', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $song, $artist)]);
		$this->cache->destroy('_breizhcharts_reported');

		$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $poster_id;
		$result = $this->db->sql_query($sql);
		$poster = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $report_id;
		$result = $this->db->sql_query($sql);
		$report = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Send pm to song poster
		if ((int) $poster_id !== (int) $this->user->data['user_id'])
		{
			$this->contact->send_pm_close('poster', $poster['user_lang'], $song, $artist, $reason, $poster['user_id'], $poster['username'], $poster['user_colour'], $report['user_id'], $report['username'], $report['user_colour']);
		}

		// Send pm to song reporter
		$this->contact->send_pm_close('reporter', $report['user_lang'], $song, $artist, $reason, $poster['user_id'], $poster['username'], $poster['user_colour'], $report['user_id'], $report['username'], $report['user_colour']);
		
		// Redirect now
		$redirect_url = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? $this->helper->route('sylver35_breizhcharts_list_report') : $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own', 'cat' => 0]) . '#nav';
		$return_msg = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? 'BC_REPORT_BACKLINK' : 'BC_REPORT_BACKLINK_OWN';
		meta_refresh(3, $redirect_url);
		trigger_error($this->language->lang('BC_REPORT_CLOSE_FINISH_TO', get_username_string('full', $poster['user_id'], $poster['username'], $poster['user_colour']), get_username_string('full', $report['user_id'], $report['username'], $report['user_colour'])) . '<br><br>' . $this->language->lang($return_msg, '<a href="' . $redirect_url . '">', '</a>'));
	}

	private function validate_report_song($id)
	{
		$data_video = [
			'song_name'			=> $this->request->variable('song_name', '', true),
			'artist'			=> $this->request->variable('artist', '', true),
			'video'				=> $this->request->variable('video', '', true),
			'cat'				=> $this->request->variable('cat', 0),
			'reported'			=> 0,
			'reason'			=> '',
			'reported_time'		=> 0,
			'reported_text'		=> '',
		];
		$ex_cat = $this->request->variable('ex_cat', 0);
		$ex_cat_nb = $this->request->variable('ex_cat_nb', 0);
		$user_id = $this->request->variable('user_id', 0);
		$name = $this->request->variable('name', '', true);
		$colour = $this->request->variable('colour', '', true);

		$error = $this->verify->verify_chart_before_send($data_video, $id);
		if (count($error))
		{
			return $error;
		}
		else
		{
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data_video) . ' WHERE song_id = ' . (int) $id);
			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_EDITED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $data_video['song_name'], $data_video['artist'])]);
			$this->cache->destroy('sql', $this->breizhcharts_table);
			$this->cache->destroy('_breizhcharts_reported');
			$this->verify->update_song_cat('update', $data_video['cat'], $ex_cat, $ex_cat_nb);

			$redirect_url = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? $this->helper->route('sylver35_breizhcharts_list_report') : $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own', 'cat' => 0]) . '#nav';
			meta_refresh(3, $redirect_url);
			trigger_error($this->language->lang('BC_SONG_EDIT_SUCCESS', $data_video['song_name']) . '<br>' . $this->language->lang('BC_REPORT_CLOSE_FINISH', get_username_string('full', $user_id, $name, $colour)) . '<br><br>' . $this->language->lang('BC_REPORT_BACKLINK', '<a href="' . $redirect_url . '">', '</a>'));
		}
	}

	public function report_validate($id)
	{
		$reason_id = $this->request->variable('reason_id', '');
		$report_text = $this->request->variable('report_text', '', true);
		$song_name = $this->request->variable('song_name', '', true);
		$artist = $this->request->variable('artist', '', true);
		$poster_id = $this->request->variable('poster_id', 0);
		$poster_name = $this->request->variable('poster_name', '', true);
		$poster_colour = $this->request->variable('poster_colour', '', true);

		$data_chart = [
			'reported'			=> (int) $this->user->data['user_id'],
			'reason'			=> (string) $reason_id,
			'reported_time'		=> time(),
			'reported_text'		=> (string) $report_text,
		];

		$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data_chart) . ' WHERE song_id = ' . (int) $id);
		$this->cache->destroy('_breizhcharts_reported');

		$this->contact->send_pms_report((int) $id, $song_name, $artist, $poster_id, $poster_name, $poster_colour, $reason_id);

		$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_REPORT_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $this->user->data['username'], $this->language->lang('BC_FROM_OF', $song_name, $artist)]);
		$redirect_url = $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => 0, 'start' => 0]);
		meta_refresh(3, $redirect_url);
		trigger_error($this->language->lang('BC_REPORTED_THANKS', $song_name) . '<br><br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $redirect_url . '">', '</a>'));
	}

	public function report_validate_auto($id)
	{
		$error = $this->request->variable('error', 0);
		$json_response = new json_response;
		$list_error = [2, 5, 100, 101, 150];
	
		if (!$this->user->data['is_registered'] || $this->user->data['is_bot'] || !in_array($error, $list_error))
		{
			return;
		}

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'b.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'b'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = b.poster_id',
				],
			],
			'WHERE'		=> 'song_id = ' . (int) $id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row['reported'] > 0)
		{
			$json_response->send([
				'sort'		=> 1,
				'error'		=> $error,
			]);
			return;
		}

		$data_chart = [
			'reported'			=> 3,
			'reason'			=> 'AUTO',
			'reported_time'		=> time(),
			'reported_text'		=> $error . '||BC_AUTO_' . $error . '||' . (($error == 150) ? 'BC_AUTO_101' : ''),
		];

		$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data_chart) . ' WHERE song_id = ' . (int) $id);
		$this->cache->destroy('_breizhcharts_reported');

		$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_REPORT_SONG_AUTO', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist'])]);
		$this->contact->send_pms_report((int) $id, $row['song_name'], $row['artist'], $row['user_id'], $row['username'], $row['user_colour'], 'AUTO', $error);

		$json_response->send([
			'sort'		=> 2,
			'error'		=> $error,
			'content'	=> $this->language->lang('BC_AUTO_' . $error),
		]);
	}

	private function enable_posting()
	{
		if (!function_exists('display_custom_bbcodes'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}
		if (!function_exists('generate_smilies'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}
		$this->language->add_lang('posting');
		$this->template->assign_vars([
			'S_BBCODE_ALLOWED'		=> true,
			'S_BBCODE_IMG'			=> true,
			'S_LINKS_ALLOWED'		=> true,
			'S_BBCODE_QUOTE'		=> true,
			'S_SMILIES_ALLOWED'		=> true,
		]);

		// Generate smiley listing
		generate_smilies('inline', 0);
		$mode = 'inline';
		// Build custom bbcodes array
		display_custom_bbcodes();

		/**
		 * You can use this event after enable posting
		 *
		 * @event breizhcharts.enable_posting
		 * @var	array
		 * @since 1.4.0
		 */
		$vars = ['mode'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.enable_posting', compact($vars)));
	}
}
