<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\points;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\config\config;
use phpbb\extension\manager as ext_manager;

class check
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\controller\helper */
	protected $helper;	

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;
	protected $breizhcharts_result_table;

	/**
	 * Constructor
	 */
	public function __construct(work $work, points $points, template $template, language $language, user $user, helper $helper, db $db, config $config, ext_manager $ext_manager, $breizhcharts_table, $breizhcharts_result_table)
	{
		$this->work = $work;
		$this->points = $points;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->helper = $helper;
		$this->db = $db;
		$this->config = $config;
		$this->ext_manager = $ext_manager;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_result_table = $breizhcharts_result_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
	}

	public function get_version()
	{
		$md_manager = $this->ext_manager->create_extension_metadata_manager('sylver35/breizhcharts');
		$meta = $md_manager->get_metadata();
		$i = 0;
		$homepages = [];

		foreach (array_slice($meta['authors'], 0, 1) as $author)
		{
			$homepages[$i] = sprintf('<a href="%1$s" title="%1$s">%2$s</a>', $author['homepage'], $author['name']);
			$i++;
		}

		$this->template->assign_vars([
			'BC_COPYRIGHT'	=> $this->language->lang('BC_COPYRIGHT', $meta['version'], $homepages[0]),
		]);
	}

	public function check_charts_voted()
	{
		if ($this->user->data['breizhchart_check_2'])
		{
			return;
		}
		else if ($this->config['breizhcharts_check_1'] && !$this->user->data['breizhchart_check_1'])
		{
			$this->first_check_charts();
		}
		else if ($this->config['breizhcharts_check_1'] && $this->config['breizhcharts_check_2'] && $this->user->data['breizhchart_check_1'] && !$this->user->data['breizhchart_check_2'])
		{
			if (time() > ($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'] - ($this->config['breizhcharts_check_time'] * 3600)))
			{
				$this->second_check_charts();
			}
		}
	}

	public function update_breizhcharts_check()
	{
		$modified = false;
		if ($this->user->data['is_registered'] && !$this->user->data['is_bot'])
		{
			if ($this->user->data['breizhchart_check_1'] == false)
			{
				$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 1, breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id']);
				$modified = true;
			}
			else if ($this->user->data['breizhchart_check_2'] == false)
			{
				if (time() > ($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'] - ($this->config['breizhcharts_check_time'] * 3600)))
				{
					$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET breizhchart_check_2 = 1, breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id']);
					$modified = true;
				}
			}
			if (!$modified)
			{
				$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id']);
			}
		}
	}

	private function first_check_charts()
	{
		// Last winners
		$points_active = $this->points->points_active();
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_result_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.result_poster_id',
				]
			],
			'ORDER_BY'	=> 'c.result_nb DESC, c.result_pos ASC',
		]);
		$result = $this->db->sql_query_limit($sql, 3);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$data = $this->get_win_charts((int) $row['result_pos'], $points_active);
			$this->template->assign_block_vars('winners', [
				'RANK' 			=> $data['img'],
				'WIN'			=> $data['win'],
				'USER'			=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'SONG'			=> $row['result_song_name'],
				'ARTIST'		=> $row['result_artist'],
				'VIDEO'			=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['result_song_id']]),
				'IMG'			=> $this->work->get_youtube_img($row['result_video'], true),
				'RESULT'		=> number_format($row['result_song_note'] * 10, 2),
				'TITLE_RATE'	=> strip_tags($this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['result_song_note'], 2))),
				'TOTAL_RATE'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['result_song_note'], 2)),
				'SONG_RATED'	=> $this->language->lang('BC_AJAX_NOTE_NB', (int) $row['result_nb_note']),
			]);
		}

		// Last bonus winner
		$bonus_winner = '';
		if (($this->config['breizhcharts_winner_id'] > 0) && isset($this->config['points_name']))
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_winner_id'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			$bonus_winner = $this->language->lang('BC_BONUS_WINNER', $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']), $this->config['breizhcharts_voters_points'], $this->config['points_name']);
		}

		$this->template->assign_vars([
			'S_CHECK_FIRST'		=> true,
			'BONUS_WINNER'		=> $bonus_winner,
			'RESULT_PERIOD'		=> ($this->config['breizhcharts_last_result']) ? $this->language->lang('BC_INDEX_WINNER', $this->user->format_date($this->config['breizhcharts_last_result'])) : '',
			'VOTE'				=> $this->language->lang('BC_VOTE_CHECK_FIRST', $this->user->data['username']) . $this->language->lang('BC_VOTE_CHECK_LINK', '<br><br><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'),
		]);
	}

	private function second_check_charts()
	{
		// List newest songs
		$i = 0;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				]
			],
			'ORDER_BY'	=> 'c.song_id DESC',
		]);
		$result = $this->db->sql_query_limit($sql, 8);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('newests', [
				'LINE'			=> ($i === 4) ? true : false,
				'USER'			=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'SONG'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'VIDEO'			=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'IMG'			=> $this->work->get_youtube_img($row['video'], true),
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'S_CHECK_SECOND'	=> true,
			'REMINDER'			=> $this->language->lang('BC_VOTE_CHECK_SECOND', $this->user->data['username']) . $this->language->lang('BC_VOTE_CHECK_LINK', '<br><br><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'),
		]);
	}

	private function get_win_charts($last_pos, $points_active)
	{
		if ($last_pos > 3)
		{
			return [
				'img'	=> $last_pos,
				'win'	=> '',
			];
		}
		else
		{
			return [
				'img'	=> '<img src="' . $this->ext_path . 'images/place_' . $last_pos . '.gif" alt="' . $this->language->lang('BC_PLACE_LIST_' . $last_pos) . '" title="' . $this->language->lang('BC_PLACE_LIST_' . $last_pos) . '">',
				'win'	=> ($points_active) ? $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_place_' . $last_pos], $this->config['points_name']) : '',
			];
		}
	}
}
