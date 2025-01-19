<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\charts;
use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\verify;
use phpbb\auth\auth;
use phpbb\user;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\controller\helper;
use phpbb\cache\service as cache;
use phpbb\db\driver\driver_interface as db;
use phpbb\config\config;
use phpbb\extension\manager as ext_manager;
use phpbb\path_helper;

class tools
{
	/** @var \sylver35\breizhcharts\core\charts */
	protected $charts;

	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\verify */
	protected $verify;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

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
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(charts $charts, work $work, verify $verify, auth $auth, user $user, language $language, template $template, helper $helper, cache $cache, db $db, config $config, ext_manager $ext_manager, path_helper $path_helper, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_voters_table)
	{
		$this->charts = $charts;
		$this->work = $work;
		$this->verify = $verify;
		$this->auth = $auth;
		$this->user = $user;
		$this->language = $language;
		$this->template = $template;
		$this->helper = $helper;
		$this->cache = $cache;
		$this->db = $db;
		$this->config = $config;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function display_tools($mode)
	{
		$data = [
			'mode'			=> $mode,
			'is_user'		=> $this->user->data['is_registered'] && !$this->user->data['is_bot'],
			'moderate'		=> $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']),
			'title_mode'	=> $this->language->lang('BC_TOOLS_PAGE'),
			'url'			=> 'sylver35_breizhcharts_tools',
			'url_array'		=> ['mode' => 'all'],
		];
		$this->verify->create_phpbb_navigation($data);
		$this->charts->get_template_charts(false);
		
		$this->template->assign_vars([
			'S_IN_TOOLS'		=> true,
			'NAV_ID'			=> 'tools',
			'TITLE_PAGE'		=> $data['title_mode'],
			'IMAGE_FUTURE'		=> $this->ext_path_web . 'images/the_future_start_here.png',
		]);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $data['title_mode']);

		// Load charts template
		$this->template->set_filenames([
			'body'	=> 'tools.html',
		]);

		page_footer();
	}
}
