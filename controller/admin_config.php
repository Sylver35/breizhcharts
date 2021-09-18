<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

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

class admin_config
{
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

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php_ext */
	protected $php_ext;

	/** @var string ext_path */
	protected $ext_path;

	/** @var string ext_path_web */
	protected $ext_path_web;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_voters_table)
	{
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
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;	
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;	
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function acp_breizhcharts_config()
	{
		add_form_key('acp_breizhcharts');

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_breizhcharts'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$date = $this->request->variable('breizhcharts_start_time_bis', '');
			$period = $this->request->variable('breizhcharts_period', 1) * $this->request->variable('breizhcharts_period_val', 604800);
			$data = [
				'breizhcharts_start_time'			=> strtotime($date),
				'breizhcharts_start_time_bis'		=> $date,
				'breizhcharts_period'				=> $period,
				'breizhcharts_period_val'			=> $this->request->variable('breizhcharts_period_val', 604800),
				'breizhcharts_max_entries'			=> $this->request->variable('breizhcharts_max_entries', 100),
				'breizhcharts_acp_page'				=> $this->request->variable('breizhcharts_acp_page', 10),
				'breizhcharts_user_page'			=> $this->request->variable('breizhcharts_user_page', 10),
				'breizhcharts_ups_points'			=> $this->request->variable('breizhcharts_ups_points', 0),
				'breizhcharts_check_1'				=> $this->request->variable('breizhcharts_check_1', 0),
				'breizhcharts_check_2'				=> $this->request->variable('breizhcharts_check_2', 0),
				'breizhcharts_check_time'			=> $this->request->variable('breizhcharts_check_time', 1),
				'breizhcharts_place_1'				=> $this->request->variable('breizhcharts_place_1', 0),
				'breizhcharts_place_2'				=> $this->request->variable('breizhcharts_place_2', 0),
				'breizhcharts_place_3'				=> $this->request->variable('breizhcharts_place_3', 0),
				'breizhcharts_required_1'			=> $this->request->variable('breizhcharts_required_1', 0),
				'breizhcharts_required_2'			=> $this->request->variable('breizhcharts_required_2', 0),
				'breizhcharts_pm_user'				=> $this->request->variable('breizhcharts_pm_user', 0),
				'breizhcharts_pm_enable'			=> $this->request->variable('breizhcharts_pm_enable', 0),
				'breizhcharts_announce_enable'		=> $this->request->variable('breizhcharts_announce_enable', 0),
				'breizhcharts_song_forum'			=> $this->request->variable('breizhcharts_song_forum', 0),
				'breizhcharts_points_per_vote'		=> $this->request->variable('breizhcharts_points_per_vote', 0),
				'breizhcharts_voters_points'		=> $this->request->variable('breizhcharts_voters_points', 0),
				'breizhcharts_winners_per_page'		=> $this->request->variable('breizhcharts_winners_per_page', 10),
				'breizhcharts_winner_id'			=> $this->request->variable('breizhcharts_winner_id', 0),
			];

			$this->update_config($data);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_BC_UPDATED');
			trigger_error($this->language->lang('BC_CONFIG_UPDATED') . adm_back_link($this->u_action));
		}
		else
		{
			// Check if UPS is installed and active
			if (isset($this->config['points_enable']) && $this->config['points_enable'])
			{
				$this->template->assign_vars([
					'S_UPS_INSTALLED'				=> true,
					'CHART_UPS_POINTS'				=> $this->config['breizhcharts_ups_points'],
					'POINTS_NAME'					=> $this->config['points_name'],
					'UPS'							=> $this->language->lang('BC_UPS', $this->config['points_name']),
					'UPS_EXPLAIN'					=> $this->language->lang('BC_UPS_EXPLAIN', $this->config['points_name']),
					'POINTS'						=> $this->language->lang('BC_RANKING', $this->config['points_name']),
					'POINTS_EXPLAIN'				=> $this->language->lang('BC_RANKING_EXPLAIN', $this->config['points_name']),
					'FIRST'							=> $this->language->lang('BC_PLACE_FIRST', $this->config['points_name']),
					'SECOND'						=> $this->language->lang('BC_PLACE_SECOND', $this->config['points_name']),
					'THIRD'							=> $this->language->lang('BC_PLACE_THIRD', $this->config['points_name']),
					'POINTS_PER_VOTE_DESC'			=> $this->language->lang('BC_POINTS_PER_VOTE', $this->config['points_name']),
					'POINTS_PER_VOTE_DESC_EXPLAIN'	=> $this->language->lang('BC_POINTS_PER_VOTE_EXPLAIN', $this->config['points_name']),
					'POINTS_VOTERS_BONUS'			=> $this->language->lang('BC_VOTERS_POINTS', $this->config['points_name']),
					'POINTS_VOTERS_BONUS_EXPLAIN'	=> $this->language->lang('BC_VOTERS_POINTS_EXPLAIN', $this->config['points_name']),
				]);
			}

			// Check last bonus winner
			$bonus_winner = $this->language->lang('BC_NO_BONUS_WINNER');
			if ($this->config['breizhcharts_winner_id'] > 0)
			{
				$sql = 'SELECT user_id, username, user_colour
					FROM ' . USERS_TABLE . '
						WHERE user_id = ' . $this->config['breizhcharts_winner_id'];
				$result = $this->db->sql_query_limit($sql, 1);
				if ($row = $this->db->sql_fetchrow($result))
				{
					$bonus_winner = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
				}
				$this->db->sql_freeresult($result);
			}

			$lang_period = ((int) $this->config['breizhcharts_period_val'] === 86400) ? 'BC_DAY' : 'BC_WEEK';
			// Send all values to the template
			$this->template->assign_vars([
				'BONUS_WINNER_NAME'				=> $bonus_winner,
				'SELECT_PERIOD'					=> $this->language->lang($lang_period, $this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']),
				'CHART_MAX_ENTRIES'				=> $this->config['breizhcharts_max_entries'],
				'BC_CHARTS_VERSION'				=> $this->config['breizhcharts_version'],
				'CHART_ACP_PAGE'				=> $this->config['breizhcharts_acp_page'],
				'CHART_USER_PAGE'				=> $this->config['breizhcharts_user_page'],
				'CHART_START_TIME'				=> $this->config['breizhcharts_start_time'],
				'CHART_START_TIME_READABLE'		=> $this->user->format_date($this->config['breizhcharts_start_time'], $this->language->lang('BC_DATE')),
				'FORMAT_START_TIME'				=> $this->config['breizhcharts_start_time_bis'],
				'CHART_PERIOD'					=> $this->period_select($this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']),
				'CHART_PERIOD_VAL'				=> $this->config['breizhcharts_period_val'],
				'CHART_NEXT_RESET'				=> $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE')),
				'CHART_CHECK_TIME'				=> $this->days_select($this->config['breizhcharts_check_time']),
				'CHART_CHECK_1'					=> $this->config['breizhcharts_check_1'],
				'CHART_CHECK_2'					=> $this->config['breizhcharts_check_2'],
				'CHART_1ST_PLACE'				=> $this->config['breizhcharts_place_1'],
				'CHART_2ND_PLACE'				=> $this->config['breizhcharts_place_2'],
				'CHART_3RD_PLACE'				=> $this->config['breizhcharts_place_3'],
				'REQUIRED_1'					=> $this->config['breizhcharts_required_1'],
				'REQUIRED_2'					=> $this->config['breizhcharts_required_2'],
				'PM_USER'						=> $this->config['breizhcharts_pm_user'],
				'PM_ENABLE'						=> $this->config['breizhcharts_pm_enable'],
				'PM_USER_NAME'					=> ($this->config['breizhcharts_pm_user']) ? $this->get_pm_user() : '',
				'ANNOUNCE_FORUM_LIST'			=> make_forum_select((int) $this->config['breizhcharts_song_forum'], false, true, true),
				'ANNOUNCE_ENABLE'				=> $this->config['breizhcharts_announce_enable'],
				'POINTS_PER_VOTE'				=> $this->config['breizhcharts_points_per_vote'],
				'VOTERS_POINTS'					=> $this->config['breizhcharts_voters_points'],
				'LAST_VOTERS_WINNER_ID'			=> $this->config['breizhcharts_winner_id'],
				'WINNERS_PER_PAGE'				=> $this->config['breizhcharts_winners_per_page'],
				'USER_LANG'						=> $this->user->lang_name,
				'U_ACTION'						=> $this->u_action,
				'S_BREIZHCHARTS_CONFIG'			=> true,
			]);
		}
	}

	private function update_config($data)
	{
		foreach ($data as $key => $value)
		{
			$this->config->set($key, $value);
		}
	}

	private function period_select($value)
	{
		$select = '';
		for ($i = 1; $i < 32; $i++)
		{
			$selected = ($i == $value) ? ' selected="selected"' : '';
			$select .= '<option value="' . $i . '"' . $selected . '>' . $i . "</option>\n";
		}

		return $select;
	}

	private function days_select($value)
	{
		$select = '';
		$content = 24;
		for ($i = 1; $i < 8; $i++)
		{
			$selected = ($content == $value) ? ' selected="selected"' : '';
			$select .= '<option value="' . $content . '"' . $selected . '>' . $i . "</option>\n";
			$content = $content + 24;
		}

		return $select;
	}

	private function get_pm_user()
	{
		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . '
				WHERE user_id = ' . $this->config['breizhcharts_pm_user'];
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);
		$username = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
		$this->db->sql_freeresult($result);

		return $username;
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return null
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
