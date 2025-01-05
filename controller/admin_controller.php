<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use sylver35\breizhcharts\core\work;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\cache\service as cache;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\pagination;
use phpbb\log\log;
use phpbb\request\request;
use phpbb\config\config;
use phpbb\extension\manager as ext_manager;
use phpbb\path_helper;

class admin_controller
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\cache\service */
	protected $cache;

	/* @var \phpbb\controller\helper */
	protected $helper;	

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \phpbb\path_helper */
	protected $path_helper;

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
	protected $breizhcharts_cats_table;

	/**
	 * Constructor
	 */
	public function __construct(work $work, template $template, language $language, user $user, cache $cache, helper $helper, db $db, pagination $pagination, log $log, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, $breizhcharts_table, $breizhcharts_cats_table)
	{
		$this->work = $work;
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->cache = $cache;
		$this->helper = $helper;
		$this->db = $db;
		$this->pagination = $pagination;
		$this->log = $log;
		$this->request = $request;
		$this->config = $config;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function acp_breizhcharts()
	{
		$data = $this->get_start_variables();
		add_form_key('acp_breizhcharts');

		if ($data['action'])
		{
			switch ($data['action'])
			{
				case 'edit':
					$this->action_edit($data['id'], $data['sort_days'], $data['sort_key'], $data['sort_dir']);
				break;

				case 'update':
					$this->action_update($data['id'], $data['sort_days'], $data['sort_key'], $data['sort_dir']);
				break;

				case 'delete':
					$this->action_delete($data['id'], $data['sort_days'], $data['sort_key'], $data['sort_dir']);
				break;
				
				case 'view_report':
					$this->view_report($data['id'], $data['sort_days'], $data['sort_key'], $data['sort_dir']);
				break;
			}
		}
		else
		{
			$this->display_row_songs($data);
		}

		$this->template->assign_vars([
			'S_MANAGE'				=> true,
			'BC_CHARTS_VERSION'		=> $this->config['breizhcharts_version'],
			'S_SELECT_SORT_DIR'		=> $data['s_sort_dir'],
			'S_SELECT_SORT_KEY'		=> $data['s_sort_key'],
			'S_LIMIT_DAYS'			=> $data['s_limit_days'],
		]);
	}

	private function get_youtube_id($url)
	{
		$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
		preg_match($pattern, $url, $matches);

		return isset($matches[1]) ? $matches[1] : false;
	}

	private function get_youtube_img($youtube_id)
	{
		if ($youtube_id = $this->get_youtube_id($youtube_id))
		{
			return 'https://img.youtube.com/vi/' . $youtube_id . '/hqdefault.jpg';
		}
		else
		{
			return '';
		}
	}

	private function action_edit($id, $sort_days, $sort_key, $sort_dir)
	{
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'b.*, c.cat_nb',
			'FROM'		=> [$this->breizhcharts_table => 'b'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [$this->breizhcharts_cats_table => 'c'],
					'ON'	=> 'c.cat_id = b.cat',
				],
			],
			'WHERE'		=> 'b.song_id = ' . (int) $id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		$this->template->assign_vars(array(
			'S_BREIZHCHARTS_EDIT'	=> true,
			'ID'					=> $id,
			'ST'					=> $sort_days,
			'SK'					=> $sort_key,
			'SD'					=> $sort_dir,
			'TITLE'					=> $row['song_name'],
			'ARTIST'				=> $row['artist'],
			'ALBUM'					=> $row['album'],
			'YEAR'					=> $row['year'],
			'CAT'					=> $row['cat'],
			'VIDEO'					=> $row['video'],
			'REPORTED'				=> $row['reported'],
			'CAT_NB'				=> $row['cat_nb'] ? $row['cat_nb'] : 0,
			'SELECT_CATS'			=> $this->work->get_cats_select($row['cat']),
			'U_ACTION'				=> $this->u_action . '&amp;action=update&amp;st=' . $sort_days . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir,
			'U_BACK'				=> $this->u_action . '&amp;st=' . $sort_days . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir,
			'U_CHECK_SONG'			=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'U_CHECK_VIDEO'			=> $this->helper->route('sylver35_breizhcharts_check_video', ['check' => 1, 'song_id' => $row['song_id']]),
			'U_EXT_PATH'			=> $this->ext_path_web,
		));
		$this->db->sql_freeresult($result);
	}

	private function action_update($id, $sort_days, $sort_key, $sort_dir)
	{
		if (!check_form_key('acp_breizhcharts'))
		{
			trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
		}

		$data = [
			'song_name'		=> $this->request->variable('song_name', '', true),
			'artist'		=> $this->request->variable('artist', '', true),
			'album'			=> $this->request->variable('album', '', true),
			'year'			=> $this->request->variable('year', ''),
			'cat'			=> $this->request->variable('cat', 0),
			'video'			=> $this->request->variable('video', '', true),
		];

		$ex_cat		= $this->request->variable('ex_cat', 0);
		$ex_cat_nb	= $this->request->variable('ex_cat_nb', 0);

		if (!$data['song_name'])
		{
			trigger_error($this->language->lang('BC_NEED_TITLE') . adm_back_link($this->u_action));	
		}
		else if (!$data['artist'])
		{
			trigger_error($this->language->lang('BC_NEED_ARTIST') . adm_back_link($this->u_action));	
		}
		else if (!$data['cat'])
		{
			trigger_error($this->language->lang('BC_CAT_NEED') . adm_back_link($this->u_action));	
		}
		else
		{
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE song_id = ' . (int) $id);
			$this->verify->update_song_cat('update', $data['cat'], $ex_cat, $ex_cat_nb);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_EDITED_SONG', time(), [$this->language->lang('BC_FROM_OF', $data['song_name'], $data['artist'])]);
			trigger_error($this->language->lang('BC_UPDATED') . adm_back_link($this->u_action . '&amp;st=' . $sort_days . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir));
		}
	}

	private function action_delete($id, $sort_days, $sort_key, $sort_dir)
	{
		if (confirm_box(true))
		{
			$sql = 'SELECT song_name, artist, topic_id, cat
				FROM ' . $this->breizhcharts_table . '
					WHERE song_id = ' . (int) $id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$title = $row['song_name'];
			$artist = $row['artist'];
			$topic_id = (int) $row['topic_id'];
			$cat = (int) $row['cat'];
			$this->db->sql_freeresult($result);

			$this->db->sql_query('DELETE FROM ' . $this->breizhcharts_table . ' WHERE song_id = ' . (int) $id);
			if ($topic_id)
			{
				delete_topics('topic_id', [$topic_id], false);
			}
			$this->verify->update_song_cat('delete', $cat);
			$this->config->increment('breizhcharts_songs_nb', -1, true);
			// Refresh the cache positions
			$this->cache->destroy('_breizhcharts_positions');

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_DELETED', time(), [$this->language->lang('BC_FROM_OF', $title, $artist)]);
			trigger_error($this->language->lang('BC_DELETE_OK') . adm_back_link($this->u_action));
		}
		else
		{
			confirm_box(false, $this->language->lang('BC_REALLY_DELETE'), build_hidden_fields([
				'song_id'	=> $id,
				'st'		=> $sort_days,
				'sk'		=> $sort_key,
				'sd'		=> $sort_dir,
				'action'	=> 'delete',
			]));
		}
	}

	private function get_start_variables()
	{
		$action = (string) $this->request->variable('action', '');
		$action = ($this->request->is_set_post('submit') && !$this->request->is_set_post('id')) ? 'add' : $action;
		$data = [
			'action'		=> $action,
			'id'			=> (int) $this->request->variable('id', 0),
			'start'			=> (int) $this->request->variable('start', 0),
			'sort_days'		=> (int) $this->request->variable('st', 0),
			'sort_key'		=> (string) $this->request->variable('sk', 'add_time'),
			'sort_dir'		=> (string) $this->request->variable('sd', 'd'),
			'limit_days'	=> [0 => $this->language->lang('BC_ALL_CHARTS'), 1 => $this->language->lang('1_DAY'), 7 => $this->language->lang('7_DAYS'), 14 => $this->language->lang('2_WEEKS'), 30 => $this->language->lang('1_MONTH'), 90 => $this->language->lang('3_MONTHS'), 180 => $this->language->lang('6_MONTHS'), 365 => $this->language->lang('1_YEAR')],
			'sort_by_text'	=> ['t' => $this->language->lang('BC_LAST_DATE'), 's' => $this->language->lang('BC_ACTUAL_NOTE'), 'n' => $this->language->lang('BC_SONG_TITLE'), 'a' => $this->language->lang('BC_SONG_ARTIST'), 'c' => $this->language->lang('BC_CAT_SORT'), 'f' => $this->language->lang('BC_FROM_NAME'), 'p' => $this->language->lang('BC_LAST_RANK')],
			'sort_by_sql'	=> ['t' => 'add_time', 's' => 'song_note', 'n' => 'song_name', 'a' => 'artist', 'c' => 'cat', 'f' => 'poster_id', 'p' => 'last_pos'],
		];

		gen_sort_selects($data['limit_days'], $data['sort_by_text'], $data['sort_days'], $data['sort_key'], $data['sort_dir'], $data['s_limit_days'], $data['s_sort_key'], $data['s_sort_dir'], $data['u_sort_param']);
		$data['sql_sort_order'] = $data['sort_by_sql'][$data['sort_key']] . ' ' . (($data['sort_dir'] == 'd') ? 'DESC' : 'ASC');

		return $data;
	}

	private function stars_vote($song_id, $song_note)
	{
		$content = sprintf($this->config['breizhcharts_li_rating'], $song_id, '', number_format($song_note * 10, 2) . '%');

		for ($i = 1, $nb = 11; $i < $nb; $i++)
		{
			$content .= sprintf($this->config['breizhcharts_li_stars'], '', number_format($song_note, 2) . ' /10', $i);
		}

		return $content;
	}

	private function view_report($data)
	{
		
	}

	private function display_row_songs($data)
	{
		// Count number of charts
		$sql = 'SELECT COUNT(song_id) AS total_charts
			FROM ' . $this->breizhcharts_table;
		$result = $this->db->sql_query($sql);
		$total_charts = (int) $this->db->sql_fetchfield('total_charts');
		$this->db->sql_freeresult($result);

		$position = $this->work->get_positions();
		// List all charts
		$i = 0;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, a.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				],
				[
					'FROM'	=> [$this->breizhcharts_cats_table => 'a'],
					'ON'	=> 'a.cat_id = c.cat',
				],
			],
			'ORDER_BY'	=> $data['sql_sort_order'],
			'WHERE'		=> 'c.add_time > ' . ($data['sort_days'] ? (time() - ($data['sort_days'] * 86400)) : 0),
		]);
		$result = $this->db->sql_query_limit($sql, (int) $this->config['breizhcharts_acp_page'], (int) $data['start']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('charts', [
				'SONG_ID'		=> $row['song_id'],
				'TITLE'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'ALBUM'			=> $row['album'],
				'YEAR'			=> $row['year'],
				'CAT'			=> isset($row['cat_name']) ? $row['cat_name'] : '',
				'POSITION'		=> $position[$row['song_id']]['position'],
				'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'PICTURE'		=> $this->get_youtube_img($row['video']),
				'TITLE_PIC'		=> $this->language->lang('BC_PICTURE_TITLE', $row['artist']),
				'ADDED_TIME'	=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'], $this->language->lang('BC_DATE_ADDED'))),
				'LAST_RANK'		=> $row['last_pos'],
				'STARS_VOTE'	=> $this->stars_vote($row['song_id'], $row['song_note']),
				'TOTAL_RATE'	=> number_format($row['song_note'], 2),
				'VIDEO'			=> $row['video'],
				'U_REPORTED'	=> $row['reported'] ? $this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $row['song_id']]) : '',
				'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['song_id'] . '&amp;st=' . $data['sort_days'] . '&amp;sk=' . $data['sort_key'] . '&amp;sd=' . $data['sort_dir'],
				'U_DEL'			=> $this->u_action . '&amp;action=delete&amp;id=' . $row['song_id'],
				'U_VIEW_REPORT'	=> $this->u_action . '&amp;action=view_report&amp;id=' . $row['song_id'],
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'TOTAL'			=> $this->language->lang('BC_SONG_NB', $total_charts),
			'S_ON_PAGE'		=> ($total_charts > (int) $this->config['breizhcharts_acp_page']) ? true : false,
			'PAGE_NUMBER'	=> $this->pagination->validate_start($total_charts, (int) $this->config['breizhcharts_acp_page'], $data['start']),
		]);
		$this->pagination->generate_template_pagination($this->u_action . '&amp;sk=' . $data['sort_key'] . '&amp;sd=' . $data['sort_dir'], 'pagination', 'start', $total_charts, (int) $this->config['breizhcharts_acp_page'], $data['start']);
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
