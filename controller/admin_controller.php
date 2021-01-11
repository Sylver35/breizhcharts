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

class admin_controller
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\controller\helper */
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

	/**
	 * Constructor
	 */
	public function __construct(
		template $template,
		language $language,
		user $user,
		auth $auth,
		helper $helper,
		db $db,
		pagination $pagination,
		log $log,
		cache $cache,
		request $request,
		config $config,
		ext_manager $ext_manager,
		path_helper $path_helper,
		$root_path,
		$php_ext,
		$breizhcharts_table
	)
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
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function acp_breizhcharts()
	{
		$action = (string) $this->request->variable('action', '');
		$action = ($this->request->is_set_post('submit') && !$this->request->is_set_post('id')) ? 'add' : $action;
		$id = (int) $this->request->variable('id', 0);
		$start = (int) $this->request->variable('start', 0);
		$sort_days = (int) $this->request->variable('st', 0);
		$sort_key = (string) $this->request->variable('sk', 'song_name');
		$sort_dir = (string) $this->request->variable('sd', 'a');
		add_form_key('acp_breizhcharts');

		$limit_days = [0 => $this->language->lang('BC_ALL_CHARTS'), 1 => $this->language->lang('1_DAY'), 7 => $this->language->lang('7_DAYS'), 14 => $this->language->lang('2_WEEKS'), 30 => $this->language->lang('1_MONTH'), 90 => $this->language->lang('3_MONTHS'), 180 => $this->language->lang('6_MONTHS'), 365 => $this->language->lang('1_YEAR')];
		$sort_by_text = ['n' => $this->language->lang('BC_SONG_TITLE'), 'a' => $this->language->lang('BC_SONG_ARTIST'), 'f' => $this->language->lang('BC_FROM_NAME'), 'p' => $this->language->lang('BC_LAST_RANK'), 't' => $this->language->lang('BC_LAST_DATE')];
		$sort_by_sql = ['n' => 'song_name', 'a' => 'artist', 'f' => 'poster_id', 'p' => 'last_pos', 't' => 'add_time'];

		$s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
		$sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		if ($action)
		{
			switch ($action)
			{
				case 'edit':
					$sql = 'SELECT *
						FROM ' . $this->breizhcharts_table . ' 
							WHERE song_id = ' . $id;
					$result = $this->db->sql_query_limit($sql, 1);
					$row = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);

					$this->template->assign_vars(array(
						'S_BREIZHCHARTS_EDIT'	=> true,
						'ID'					=> $id,
						'SK'					=> $sort_key,
						'SD'					=> $sort_dir,
						'TITLE'					=> $row['song_name'],
						'ARTIST'				=> $row['artist'],
						'ALBUM'					=> $row['album'],
						'PICTURE'				=> $row['picture'],
						'YEAR'					=> $row['year'],
						'URL'					=> $row['website'],
						'VIDEO'					=> $row['video'],
						'U_ACTION'				=> $this->u_action . '&amp;action=update',
						'U_BACK'				=> $this->u_action,
						'U_CHECK_SONG'			=> $this->helper->route('sylver35_breizhcharts_check_song'),
						'U_CHECK_VIDEO'			=> $this->helper->route('sylver35_breizhcharts_check_video'),
						'U_EXT_PATH'			=> $this->ext_path_web,
					));
				break;

				case 'update':
					if (!check_form_key('acp_breizhcharts'))
					{
						trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$data = [
						'song_name'		=> $this->request->variable('song_name', '', true),
						'artist'		=> $this->request->variable('artist', '', true),
						'album'			=> $this->request->variable('album', '', true),
						'year'			=> $this->request->variable('year', ''),
						'picture'		=> $this->request->variable('picture', '', true),
						'website'		=> $this->request->variable('website', '', true),
						'video'			=> $this->request->variable('video', '', true),
					];

					if ($data['song_name'] === '' || $data['artist'] === '')
					{
						if ($data['song_name'] === '' && $data['artist'] === '')
						{
							trigger_error($this->language->lang('BC_NEED_DATA') . adm_back_link($this->u_action));	
						}
						else if ($data['song_name'] === '')
						{
							trigger_error($this->language->lang('BC_NEED_TITLE') . adm_back_link($this->u_action));	
						}
						else if ($data['artist'] === '')
						{
							trigger_error($this->language->lang('BC_NEED_ARTIST') . adm_back_link($this->u_action));	
						}
					}
					else
					{
						$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE song_id = ' . $id);

						$message = $this->language->lang('LOG_ADMIN_CHART_UPDATED', $data['song_name']);
						$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $message, time());
						trigger_error($this->language->lang('BC_UPDATED') . adm_back_link($this->u_action . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir));
					}
				break;

				case 'delete':
					if (confirm_box(true))
					{
						$sql = 'SELECT song_name
							FROM ' . $this->breizhcharts_table . '
								WHERE song_id = ' . $id;
						$result = $this->db->sql_query_limit($sql, 1);
						$title = $this->db->sql_fetchfield('song_name');
						$this->db->sql_freeresult($result);

						$sql = 'DELETE FROM ' . $this->breizhcharts_table . ' WHERE song_id = ' . $id;
						$this->db->sql_query($sql);

						$this->config->increment('breizhcharts_songs_nb', -1, true);
						$message = $this->language->lang('LOG_ADMIN_CHART_DELETED', $title);
						$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $message, time());
						trigger_error($this->language->lang('BC_DELETED') . adm_back_link($this->u_action . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir));
					}
					else
					{
						confirm_box(false, $this->language->lang('BC_REALLY_DELETE'), build_hidden_fields([
							'song_id'	=> $id,
							'action'	=> 'delete',
						]));
					}
				break;
			}
		}
		else
		{
			// Count number of charts
			$sql = 'SELECT COUNT(song_id) AS total_charts
				FROM ' . $this->breizhcharts_table;
			$result = $this->db->sql_query($sql);
			$total_charts = (int) $this->db->sql_fetchfield('total_charts');
			$this->db->sql_freeresult($result);

			// List all charts
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
				'FROM'		=> [$this->breizhcharts_table => 'c'],
				'LEFT_JOIN'	=> [
					[
						'FROM'	=> [USERS_TABLE => 'u'],
						'ON'	=> 'u.user_id = c.poster_id',
					]
				],
				'ORDER_BY'	=> $sql_sort_order,
			]);
			$result = $this->db->sql_query_limit($sql, (int) $this->config['breizhcharts_acp_page'], $start);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->template->assign_block_vars('charts', [
					'TITLE'			=> $row['song_name'],
					'ARTIST'		=> $row['artist'],
					'ALBUM'			=> $row['album'],
					'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
					'PICTURE'		=> $row['picture'] ? $row['picture'] : $this->get_youtube_img($row['video']),
					'TITLE_PIC'		=> $this->language->lang('BC_PICTURE_TITLE', $row['artist']),
					'YEAR'			=> $row['year'],
					'ADDED_TIME'	=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'])),
					'LAST_RANK'		=> $row['last_pos'],
					'URL'			=> $row['website'],
					'VIDEO'			=> $row['video'],
					'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['song_id'] . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir,
					'U_DEL'			=> $this->u_action . '&amp;action=delete&amp;id=' . $row['song_id'] . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir,
				]);
			}
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'S_ON_PAGE'			=> $total_charts > (int) $this->config['breizhcharts_acp_page'],
				'PAGE_NUMBER' 		=> $this->pagination->validate_start($total_charts, (int) $this->config['breizhcharts_acp_page'], $start),
			]);
			$this->pagination->generate_template_pagination($this->u_action . '&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir, 'pagination', 'start', $total_charts, (int) $this->config['breizhcharts_acp_page'], $start);
		}

		$this->template->assign_vars([
			'S_MANAGE'			=> true,
			'DM_VERSION'		=> $this->config['breizhcharts_version'],
			'S_SELECT_SORT_DIR'	=> $s_sort_dir,
			'S_SELECT_SORT_KEY'	=> $s_sort_key,
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
		return '';
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
