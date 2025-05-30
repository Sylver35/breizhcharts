<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use phpbb\json_response;
use sylver35\breizhcharts\core\points;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\controller\helper;
use phpbb\cache\service as cache;
use phpbb\db\driver\driver_interface as db;
use phpbb\log\log;
use phpbb\request\request;
use phpbb\config\config;

class admin_config
{
	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\cache\service */
	protected $cache;	

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 */
	public function __construct(points $points, template $template, language $language, user $user, helper $helper, cache $cache, db $db, log $log, request $request, config $config)
	{
		$this->points = $points;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->helper = $helper;
		$this->cache = $cache;
		$this->db = $db;
		$this->log = $log;
		$this->request = $request;
		$this->config = $config;
	}

	public function acp_breizhcharts_config()
	{
		add_form_key('acp_breizhcharts');
		// Check if UPS is installed and active
		$points_activ = $this->points->points_active();

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_breizhcharts'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$data = $this->update_all_values();
			$data = $this->update_all_values_activ($data);
			$data = $this->update_all_values_points($data, $points_activ);

			$this->cache->destroy('_breizhcharts_cats');

			$this->update_config($data);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_BC_UPDATED');
			trigger_error($this->language->lang('BC_CONFIG_UPDATED') . adm_back_link($this->u_action));
		}
		else
		{
			if ($points_activ)
			{
				$this->points->points_config();
			}

			// Send all values to the template
			$this->all_values();
		}
	}

	private function all_values()
	{
		$this->template->assign_vars([
			'CHART_PERIOD_ACTIV'			=> $this->config['breizhcharts_period_activ'],
			'CHART_PERIOD_CSS'				=> $this->config['breizhcharts_period_activ'] ? 'block' : 'none',
			'SELECT_PERIOD'					=> $this->language->lang(((int) $this->config['breizhcharts_period_val'] === 86400) ? 'BC_DAY' : 'BC_WEEK', $this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']),
			'CHART_MAX_ENTRIES'				=> $this->config['breizhcharts_max_entries'],
			'BC_CHARTS_VERSION'				=> $this->config['breizhcharts_version'],
			'CHART_ACP_PAGE'				=> $this->config['breizhcharts_acp_page'],
			'CHART_USER_PAGE'				=> $this->config['breizhcharts_user_page'],
			'CHART_VIDEO_WIDTH'				=> $this->config['breizhcharts_video_width'],
			'CHART_VIDEO_HEIGHT'			=> $this->config['breizhcharts_video_height'],
			'CHART_SELECT_ORDER'			=> $this->config['breizhcharts_select_order'],
			'CHART_START_TIME'				=> $this->config['breizhcharts_start_time'],
			'CHART_START_TIME_READABLE'		=> $this->config['breizhcharts_start_time'] ? $this->user->format_date($this->config['breizhcharts_start_time'], $this->language->lang('BC_DATE')) : '',
			'FORMAT_START_TIME'				=> $this->config['breizhcharts_start_time_bis'],
			'CHART_PERIOD'					=> $this->period_select($this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']),
			'CHART_PERIOD_VAL'				=> $this->config['breizhcharts_period_val'],
			'CHART_NEXT_RESET'				=> $this->config['breizhcharts_start_time'] ? $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE')) : 0,
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
			'PM_USER_NAME'					=> !$this->config['breizhcharts_pm_user'] ?: $this->get_pm_user(),
			'RANDOM_ENABLE'					=> $this->config['breizhcharts_random'],
			'RANDOM_INDEX_SELECT'			=> $this->random_select($this->config['breizhcharts_random_index']),
			'ANNOUNCE_FORUM_LIST'			=> make_forum_select((int) $this->config['breizhcharts_song_forum'], false, true, true),
			'ANNOUNCE_ENABLE'				=> $this->config['breizhcharts_announce_enable'],
			'POINTS_PER_VOTE'				=> $this->config['breizhcharts_points_per_vote'],
			'VOTERS_POINTS'					=> $this->config['breizhcharts_voters_points'],
			'LAST_VOTERS_WINNER_ID'			=> $this->config['breizhcharts_winner_id'],
			'USER_LANG'						=> $this->user->lang_name,
			'U_CHANGE_PERIOD'				=> $this->helper->route('sylver35_breizhcharts_change_period'),
			'U_ACTION'						=> $this->u_action,
			'S_BREIZHCHARTS_CONFIG'			=> true,
		]);
	}

	private function update_all_values()
	{
		$data = [
			'breizhcharts_acp_page'				=> $this->request->variable('breizhcharts_acp_page', 10),
			'breizhcharts_user_page'			=> $this->request->variable('breizhcharts_user_page', 10),
			'breizhcharts_max_entries'			=> $this->request->variable('breizhcharts_max_entries', 100),
			'breizhcharts_announce_enable'		=> $this->request->variable('breizhcharts_announce_enable', 0),
			'breizhcharts_song_forum'			=> $this->request->variable('breizhcharts_song_forum', 0),
			'breizhcharts_video_width'			=> $this->request->variable('breizhcharts_video_width', 900),
			'breizhcharts_video_height'			=> $this->request->variable('breizhcharts_video_height', 600),
			'breizhcharts_select_order'			=> $this->request->variable('breizhcharts_select_order', 0),
			'breizhcharts_period_activ'			=> $this->request->variable('breizhcharts_period_activ', 0),
			'breizhcharts_pm_user'				=> $this->request->variable('breizhcharts_pm_user', 0),
			'breizhcharts_random'				=> $this->request->variable('breizhcharts_random', 1),
			'breizhcharts_random_index'			=> $this->request->variable('breizhcharts_random_index', 1),
		];
		return $data;
	}

	private function update_all_values_activ($data)
	{
		if ($data['breizhcharts_period_activ'])
		{
			$data = array_merge($data, [
				'breizhcharts_start_time'			=> strtotime($this->request->variable('breizhcharts_start_time_bis', '')),
				'breizhcharts_start_time_bis'		=> $this->request->variable('breizhcharts_start_time_bis', ''),
				'breizhcharts_period'				=> $this->request->variable('breizhcharts_period', 1) * $this->request->variable('breizhcharts_period_val', 604800),
				'breizhcharts_period_val'			=> $this->request->variable('breizhcharts_period_val', 604800),
				'breizhcharts_check_1'				=> $this->request->variable('breizhcharts_check_1', 0),
				'breizhcharts_check_2'				=> $this->request->variable('breizhcharts_check_2', 0),
				'breizhcharts_check_time'			=> $this->request->variable('breizhcharts_check_time', 1),
				'breizhcharts_required_1'			=> $this->request->variable('breizhcharts_required_1', 0),
				'breizhcharts_required_2'			=> $this->request->variable('breizhcharts_required_2', 0),
				'breizhcharts_pm_enable'			=> $this->request->variable('breizhcharts_pm_enable', 0),
			]);
		}
		return $data;
	}

	private function update_all_values_points($data, $points_activ)
	{
		if ($points_activ)
		{
			$data = array_merge($data, [
				'breizhcharts_ups_points'			=> $this->request->variable('breizhcharts_ups_points', 0),
				'breizhcharts_points_per_vote'		=> $this->request->variable('breizhcharts_points_per_vote', 0),
			]);
			if ($data['breizhcharts_period_activ'])
			{
				$data = array_merge($data, [
					'breizhcharts_place_1'				=> $this->request->variable('breizhcharts_place_1', 0),
					'breizhcharts_place_2'				=> $this->request->variable('breizhcharts_place_2', 0),
					'breizhcharts_place_3'				=> $this->request->variable('breizhcharts_place_3', 0),
					'breizhcharts_voters_points'		=> $this->request->variable('breizhcharts_voters_points', 0),
					'breizhcharts_winner_id'			=> $this->request->variable('breizhcharts_winner_id', 0),
				]);
			}
		}
		return $data;
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

	private function random_select($value)
	{
		$select = '';
		for ($i = 0; $i < 5; $i++)
		{
			$selected = ((int) $value === $i) ? ' selected="selected"' : '';
			$select .= '<option value="' . $i . '"' . $selected . '>' . $this->language->lang('BC_CHOICE_INDEX_' . $i) . "</option>\n";
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

	public function change_period()
	{
		$value = (string) $this->request->variable('value', '');
		$period = (int) $this->request->variable('period', 0);
		$period_val = (int) $this->request->variable('period_val', 0);
		
		$json_response = new json_response;
		$json_response->send([
			'breizhcharts_start_time'			=> (int) strtotime($value),
			'breizhcharts_start_time_bis'		=> (string) $value,
			'breizhcharts_period'				=> (int) $period * $period_val,
			'breizhcharts_period_val'			=> (int) $period_val,
			'day_start'							=> (string) $this->user->format_date(strtotime($value), $this->language->lang('BC_DATE')),
			'day_finish'						=> (string) $this->user->format_date(strtotime($value) + ($period * $period_val), $this->language->lang('BC_DATE')),
		]);
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
