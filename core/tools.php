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

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

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
	public function __construct(charts $charts, work $work, verify $verify, auth $auth, user $user, language $language, template $template, helper $helper, cache $cache, db $db, config $config, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_voters_table)
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
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
	}

	public function display_tools($mode)
	{
		$data = [
			'mode'			=> $mode,
			'is_user'		=> ((int) $this->user->data['user_id'] !== ANONYMOUS) && !$this->user->data['is_bot'],
			'moderate'		=> $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']),
			'title_mode'	=> 'Outils du Hit Parade',
			'url'			=> 'sylver35_breizhcharts_tools',
			'url_array'		=> ['mode' => 'all'],
		];
		$this->verify->create_phpbb_navigation($data);
		$this->charts->get_template_charts(false);
		
		$this->template->assign_vars([
			'S_IN_TOOLS'		=> true,
			'NAV_ID'			=> 'tools',
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
