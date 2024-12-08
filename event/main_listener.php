<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use sylver35\breizhcharts\core\charts;
use sylver35\breizhcharts\core\check;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\config\config;

class main_listener implements EventSubscriberInterface
{
	/** @var \sylver35\breizhcharts\core\charts */
	protected $charts;

	/** @var \sylver35\breizhcharts\core\check */
	protected $check;

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

	/** @var \phpbb\config\config */
	protected $config;

	/**
	 * The database tables
	 *
	 * @var string
	 */
	protected $breizhcharts_table;

	/**
	 * Constructor
	 */
	public function __construct(charts $charts, check $check, template $template, language $language, user $user, auth $auth, helper $helper, db $db, config $config, $breizhcharts_table)
	{
		$this->charts = $charts;
		$this->check = $check;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->auth = $auth;
		$this->helper = $helper;
		$this->db = $db;
		$this->config = $config;
		$this->breizhcharts_table = $breizhcharts_table;
	}

	/**
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup'				=> 'user_setup',
			'core.permissions'				=> 'permissions',
			'core.page_header'				=> 'page_header',
			'core.page_footer'				=> 'page_footer',
			'core.index_modify_page_title'	=> 'index_modify_page_title',
			'core.memberlist_view_profile'	=> 'memberlist_view_profile',
		];
	}

	/**
	 * @param array $event
	 */
	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'sylver35/breizhcharts',
			'lang_set' => 'breizhcharts',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * @param array $event
	 */
	public function memberlist_view_profile($event)
	{
		if ($this->auth->acl_get('u_breizhcharts_view'))
		{
			$sql = 'SELECT COUNT(song_id) AS own_charts
				FROM ' . $this->breizhcharts_table . '
					WHERE poster_id = ' . (int) $event['member']['user_id'];
			$result = $this->db->sql_query($sql);
			$own_charts = (int) $this->db->sql_fetchfield('own_charts');
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'USER_OWN_CHARTS'		=> $this->language->lang('BC_OWN_CHARTS', $own_charts),
				'USER_CHARTS_TITLE'		=> $this->language->lang('BC_OF_USER_TITLE', $event['member']['username']),
				'U_USER_BREIZH_CHART'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $event['member']['user_id'], 'name' => $event['member']['username']]),
			]);
		}
	}

	public function page_header()
	{
		if ($this->auth->acl_get('u_breizhcharts_view'))
		{
			$this->template->assign_vars([
				'S_CHARTS_EXIST'	=> true,
				'U_BC_CHARTS'		=> $this->helper->route('sylver35_breizhcharts_page_music'),
				'NEW_SONG'			=> $this->user->data['is_registered'] && ($this->user->data['breizhchart_last'] < $this->config['breizhcharts_last_song']),
			]);
		}

		if ($this->config['breizhcharts_period_activ'] && $this->config['breizhcharts_start_time'])
		{
			if (time() - $this->config['breizhcharts_start_time'] > $this->config['breizhcharts_period'])
			{
				$this->charts->run_vote_charts_period();
			}
		}
	}

	public function page_footer()
	{
		$this->check->get_version();
	}

	public function index_modify_page_title()
	{
		if ($this->user->data['is_registered'] && !$this->user->data['is_bot'])
		{
			if ($this->config['breizhcharts_period_activ'] && $this->auth->acl_gets(['u_breizhcharts_view', 'u_breizhcharts_vote']))
			{
				$this->check->check_charts_voted();
			}
		}
	}

	/**
	 * @param array $event
	 */
	public function permissions($event)
	{
		$event['categories'] = array_merge($event['categories'], [
			'breizhcharts' =>	'BC_TITLE',
		]);

		$event['permissions'] = array_merge($event['permissions'], [
			'a_breizhcharts_manage'	=> [
				'lang'		=> 'ACL_A_BC_MANAGE',
				'cat'		=> 'breizhcharts',
			],
			'u_breizhcharts_view'		=> [
				'lang'		=> 'ACL_U_BC_VIEW',
				'cat'		=> 'breizhcharts',
			],
			'u_breizhcharts_vote'		=> [
				'lang'		=> 'ACL_U_BC_VOTE',
				'cat'		=> 'breizhcharts',
			],
			'u_breizhcharts_add'		=> [
				'lang'		=> 'ACL_U_BC_ADD',
				'cat'		=> 'breizhcharts',
			],
			'u_breizhcharts_edit'		=> [
				'lang'		=> 'ACL_U_BC_EDIT',
				'cat'		=> 'breizhcharts',
			],
		]);
	}	
}
