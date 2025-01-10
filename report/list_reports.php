<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\report;

use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\charts;
use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\verify;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\db\driver\driver_interface as db;
use phpbb\log\log;
use phpbb\request\request;
use phpbb\config\config;
use phpbb\auth\auth;
use phpbb\controller\helper;

class list_reports
{
	/** @var \sylver35\breizhcharts\core\charts */
	protected $charts;

	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\verify */
	protected $verify;

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

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;

	/**
	 * Constructor
	 */
	public function __construct(charts $charts, work $work, verify $verify, template $template, language $language, user $user, db $db, log $log, request $request, config $config, auth $auth, helper $helper, $breizhcharts_table)
	{
		$this->charts = $charts;
		$this->work = $work;
		$this->verify = $verify;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->db = $db;
		$this->log = $log;
		$this->request = $request;
		$this->config = $config;
		$this->auth = $auth;
		$this->helper = $helper;
		$this->breizhcharts_table = $breizhcharts_table;
	}

	public function display_list()
	{
		if (!$this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		$data = [
			'title_mode'	=> $this->language->lang('BC_REPORTED_LIST'),
			'url' 			=> 'sylver35_breizhcharts_list_report',
			'cat'			=> 0,
			'url_array'		=> [],
		];
		$this->verify->create_phpbb_navigation($data);
		$this->charts->get_template_charts(false);
		$reports = $this->work->get_reported_videos();

		$size = count($reports['reports']);
		for ($i = 0; $i < $size; $i++)
		{
			$row = $reports['reports'][$i];
			$this->template->assign_block_vars('charts', [
				'SONG_ID'		=> $row['song_id'],
				'TITLE'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'REASON'		=> $this->user->lang['bc_report_reasons']['TITLE'][$row['reason']],
				'TIME'			=> $row['reported_time'],
				'USERNAME'		=> get_username_string('full', $row['poster_id'], $row['name'], $row['colour']),
				'REPORTNAME'	=> ((int) $row['user_id'] === 3) ? '<strong>' . $this->language->lang('BC_AUTO_NAME') . '</strong>' : get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'THUMBNAIL'		=> $this->work->get_youtube_img($row['video'], true),
				'ADDED_TIME'	=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'])),
				'REPORT_TIME'	=> $this->language->lang('BC_REPORTED_TIME', $this->user->format_date($row['reported_time'])),
				'U_SHOW_POPUP'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'U_VIEW_REPORT'	=> $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]),
			]);
		}

		$this->template->assign_vars([
			'S_IN_REPORT_LIST'	=> true,
			'S_REPORTS_LIST'	=> true,
			'NAV_ID'			=> 'reports-list',
			'HAS_REPORT'		=> ($i > 0) ? ' red-icon' : '',
			'TITLE_PAGE'		=> $data['title_mode'],
		]);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $data['title_mode']);

		// Load report template
		$this->template->set_filenames([
			'body' => 'report_list.html',
		]);

		page_footer();
	}
}
