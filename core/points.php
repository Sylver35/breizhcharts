<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use phpbb\language\language;
use phpbb\template\template;
use phpbb\user;
use phpbb\db\driver\driver_interface as db;
use phpbb\config\config;
use phpbb\controller\helper;
use Symfony\Component\DependencyInjection\Container as phpbb_container;

class points
{
	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \Symfony\Component\DependencyInjection\Container */
	protected $phpbb_container;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;

	/**
	 * Constructor
	 */
	public function __construct(language $language, template $template, user $user, db $db, config $config, helper $helper, phpbb_container $phpbb_container, $breizhcharts_table)
	{
		$this->language = $language;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->config = $config;
		$this->helper = $helper;
		$this->phpbb_container = $phpbb_container;
		$this->breizhcharts_table = $breizhcharts_table;
	}

	/**
	 * test if the extension ultimatepoints is running and active
	 * @return bool
	 */
	public function points_active()
	{
		if ($this->phpbb_container->has('dmzx.ultimatepoints.listener'))
		{
			if ($this->config['points_enable'])
			{
				$this->template->assign_vars([
					'S_UPS'		=> true,
				]);
				return true;
			}
		}
		return false;
	}

	public function add_user_points($user_id, $amount)
	{
		$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_points = user_points + ' . (int) $amount . ' WHERE user_id = ' . (int) $user_id);
	}

	public function points_to_winners()
	{
		// Find the three winners
		$sql = 'SELECT poster_id, last_pos
			FROM ' . $this->breizhcharts_table . '
				ORDER BY last_pos ASC';
		$result = $this->db->sql_query_limit($sql, 3);
		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($this->config['breizhcharts_place_' . $row['last_pos']] > 0)
			{
				$this->add_user_points((int) $row['poster_id'], (int) $this->config['breizhcharts_place_' . $row['last_pos']]);
			}
		}
		$this->db->sql_freeresult($result);
	}

	public function points_config()
	{
		$this->template->assign_vars([
			'S_UPS_INSTALLED'				=> true,
			'BONUS_WINNER_NAME'				=> $this->bonus_winner(),
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

	public function Last_bonus_winner()
	{
		if ($this->points_active() && $this->config['breizhcharts_winner_id'] > 0)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_winner_id'];
			$result = $this->db->sql_query($sql, 6800);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'S_BONUS_WINNER'	=> true,
				'BONUS_WINNER'		=> $this->language->lang('BC_BONUS_WINNER', get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']), $this->config['breizhcharts_voters_points'], $this->config['points_name']),
			]);
		}
	}

	public function points_in_vote()
	{
		$message = '';
		if ($this->points_active() && $this->config['breizhcharts_points_per_vote'])
		{
			// Giving points for voting, if UPS is installed and active
			$this->add_user_points($this->user->data['user_id'], $this->config['breizhcharts_points_per_vote']);
			$message = $this->language->lang('BC_VOTE_SUCCESS_UPS', $this->config['breizhcharts_points_per_vote'], $this->config['points_name']);
		}

		return $message;
	}

	public function get_message_return($id)
	{
		$message = $this->language->lang('BC_SONG_ADDED') . '<br>';
		if ($this->points_active() && $this->config['breizhcharts_ups_points'])
		{
			$this->add_user_points($this->user->data['user_id'], $this->config['breizhcharts_ups_points']);
			$message = $this->language->lang('BC_SONG_ADDED_UPS', $this->config['breizhcharts_ups_points'], $this->config['points_name']) . '<br>';
		}
		$message .= $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>') . '<br>';
		$message .= $this->language->lang('BC_BACKLINK_VIDEO', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'video', 'id' => $id]) . '#start">', '</a>');

		 return $message;
	}

	private function bonus_winner()
	{
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

		return $bonus_winner;
	}
}
