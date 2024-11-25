<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\points;
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
use Symfony\Component\DependencyInjection\Container as phpbb_container;
use phpbb\event\dispatcher_interface as phpbb_dispatcher;

class work
{
	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

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

	/** @var \Symfony\Component\DependencyInjection\Container */
	protected $phpbb_container;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

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
	protected $breizhcharts_result_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(points $points, template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_container $phpbb_container, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_result_table, $breizhcharts_voters_table)
	{
		$this->points = $points;
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
		$this->phpbb_container = $phpbb_container;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_result_table = $breizhcharts_result_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function get_username_song($user_id, $username, $user_colour)
	{
		$url = $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $user_id, 'name' => $username]);
		return '<span title="' . $this->language->lang('BC_OF_USER_TITLE', $username) . '">' . get_username_string('full', $user_id, $username, $user_colour, '', $url) . '</span>';
	}

	public function get_like($var)
	{
		return strtolower("'%" . $this->db->sql_escape($var) . "%'");
	}

	public function get_youtube_id($url)
	{
		$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
		preg_match($pattern, $url, $matches);

		return isset($matches[1]) ? $matches[1] : false;
	}

	public function get_youtube_img($youtube_id, $get_id = false)
	{
		if ($get_id !== false)
		{
			if ($id = $this->get_youtube_id($youtube_id))
			{
				$youtube_id = $id;
			}
		}

		return 'https://img.youtube.com/vi/' . $youtube_id . '/hqdefault.jpg';
	}

	public function get_voters()
	{
		$i = 0;
		$usernames = [];
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_voters_table => 'v'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = v.vote_user_id',
				],
			],
			'GROUP_BY'	=> 'v.vote_user_id',
		]);
		$result = $this->db->sql_query($sql, 800);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$usernames[$i] = $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']);
			$i++;
		}
		$this->db->sql_freeresult($result);

		if ($i > 0)
		{
			$this->template->assign_vars([
				'S_USERS_VOTED'		=> true,
				'VOTED_USERS'		=> $this->language->lang('BC_VOTED_USERS', $i),
				'LIST_USER_VOTED'	=> implode(', ', $usernames),
			]);  
		}
	}

	public function get_total_charts($select)
	{
		if ($select !== '')
		{
			$sql = 'SELECT COUNT(song_id) AS total_charts
				FROM ' . $this->breizhcharts_table . $select;
			$result = $this->db->sql_query($sql, 800);
			$total_charts = (int) $this->db->sql_fetchfield('total_charts');
			$this->db->sql_freeresult($result);
		}
		else
		{
			$total_charts = (int) $this->config['breizhcharts_songs_nb'];
		}

		return $total_charts;
	}

	public function get_tendency_image($pos, $last_pos)
	{
		if ($last_pos === 0)
		{
			$img = 'new.png';
			$title = 'BC_NEW_PLACED';
		}
		else if ($last_pos === $pos)
		{
			$img = 'equal.png';
			$title = 'BC_POSITION_EQUAL';
		}
		else if ($pos < $last_pos)
		{
			$img = 'up.png';
			$title = 'BC_POSITION_UP';
		}
		else
		{
			$img = 'down.png';
			$title = 'BC_POSITION_DOWN';
		}

		return '<img src="' . $this->ext_path . 'images/' . $img . '" alt="' . $img . '" title="' . $this->language->lang($title, $pos) . '">';
	}

	public function create_navigation($data)
	{
		$url = 'sylver35_breizhcharts_page_music';
		$url_array = [];

		if ($data['mode'] == 'add')
		{
			$url = 'sylver35_breizhcharts_add_music';
		}
		else if ($data['mode'] == 'edit')
		{
			$url = 'sylver35_breizhcharts_edit_music';
		}
		else
		{
			$url_array = [
				'mode'	=> $data['mode'],
			];
		}

		if ($data['song_id'])
		{
			$url_array['id'] = $data['song_id'];
		}
		else if ($data['userid'] && $data['name'])
		{
			$url_array = [
				'user'	=> $data['userid'],
				'name'	=> $data['name'],
			];
		}

		// Main template variables for the navigation
		$this->add_navlinks([
			$this->language->lang('BC_CHARTS')	=> $this->helper->route('sylver35_breizhcharts_page_music'),
			$data['title_mode']					=> $this->helper->route($url, $url_array),
		]);
	}

	private function add_navlinks($links)
	{
		foreach ($links as $name => $link)
		{
			$this->template->assign_block_vars('navlinks', [
				'FORUM_NAME'	=> $name,
				'U_VIEW_FORUM'	=> $link,
			]);
		}
	}

	public function build_tendency()
	{
		$i = 1;
		$tendency = [];
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'song_id, last_pos, best_pos, song_note, nb_note',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'ORDER_BY'	=> 'song_note DESC, nb_note DESC, last_pos ASC, best_pos ASC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$tendency[$row['song_id']] = [
				'position'	=> $this->language->lang('BC_ACTUAL', $i),
				'image'		=> $this->get_tendency_image($i, (int) $row['last_pos']),
				'last'		=> ((int) $row['last_pos'] === 0) ? $this->language->lang('BC_ENTER') : $this->language->lang('BC_LATEST', $row['last_pos']),
			];
			$i++;
		}
		$this->db->sql_freeresult($result);

		return $tendency;
	}

	public function build_data_in_mode($data)
	{
		if ($data['mode'] === 'own')
		{
			$data = array_merge($data, [
				'rules'		=> false,
				'where'		=> 'c.poster_id = ' . (int) $this->user->data['user_id'],
				'select'	=> ' WHERE poster_id = ' . (int) $this->user->data['user_id'],
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $data['mode']]),
			]);
		}
		else if ($data['mode'] === 'user')
		{
			$data = array_merge($data, [
				'rules'		=> false,
				'where'		=> 'c.poster_id = ' . $data['userid'],
				'select'	=> ' WHERE poster_id = ' . $data['userid'],
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $data['mode'], 'user' => $data['userid'], 'name' => $data['name']]),
			]);
		}
		else
		{
			$data = array_merge($data, [
				'rules'		=> true,
				'where'		=> '',
				'select'	=> '',
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $data['mode']]),
			]);
		}

		return $data;
	}

	public function stars_vote($song_id, $vote, $user_vote, $song_note)
	{
		$class = (!$vote) ? '-not' : '';
		$content = sprintf($this->config['breizhcharts_li_rating'], $song_id, $class, number_format($song_note * 10, 2) . '%');

		for ($i = 1, $nb = 11; $i < $nb; $i++)
		{
			$onclick = (!$vote) ? sprintf($this->config['breizhcharts_li_onclick'], $song_id, $i) : '';
			$title = (!$vote) ? $this->language->lang('BC_AJAX_STARS', $i) : strip_tags($user_vote);
			$content .= sprintf($this->config['breizhcharts_li_stars'], $onclick, $title, $i);
		}

		return $content;
	}

	public function get_all_wins($nb_win)
	{
		$options = '';
		$sql = 'SELECT result_nb, result_time
			FROM ' . $this->breizhcharts_result_table . '
				GROUP BY result_nb
				ORDER BY result_nb DESC';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$select = ($nb_win === (int) $row['result_nb']) ? ' selected="selected"' : '';
			$options .= '<option value="' . $row['result_nb'] . '"' . $select . '>' . $this->user->format_date($row['result_time'], $this->language->lang('BC_LAST_WINNERS_FORMAT')) . '</option>';
		}
		$this->db->sql_freeresult($result);

		return $options;
	}

	public function get_template_charts($rules)
	{
		$lang_period = ((int) $this->config['breizhcharts_period_val'] === 86400) ? 'BC_DAY' : 'BC_WEEK';
		$period = $this->language->lang($lang_period, $this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']);
		$date_finish = $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE'));

		$this->template->assign_vars([
			'S_LIST_NAV'		=> true,
			'S_RULES'			=> $rules,
			'U_EXT_PATH'		=> $this->ext_path_web,
			'MC_TITLE_EXPLAIN'	=> $this->language->lang('BC_HEADER_EXPLAIN', $period, $date_finish),
			'MC_TOP_XX'			=> $this->language->lang('BC_TOP_TEN', $this->config['breizhcharts_num_top']),
			'U_ADD_SONG'		=> $this->auth->acl_get('u_breizhcharts_add') ? $this->helper->route('sylver35_breizhcharts_add_music') . '#start' : '',
			'U_LIST_TOP'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list']) . '#start',
			'U_LIST_NEWEST'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']) . '#start',
			'U_LIST_OWN'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own']) . '#start',
			'U_LAST_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'winners']) . '#start',
			'U_SELECT_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'winners']),
			'U_VOTE_MUSIC'		=> $this->helper->route('sylver35_breizhcharts_vote'),
		]);
	}

	public function run_random_winner()
	{
		if (!$this->config['breizhcharts_voters_points'])
		{
			return;
		}
		include_once($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);

		// Select a random voter to get a bonus, if UPS is enabled and active
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'v.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_voters_table => 'v'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = v.vote_user_id',
				]
			],
			'ORDER_BY'	=> 'RAND()',
		]);
		$result = $this->db->sql_query_limit($sql, 1);
		if ($row = $this->db->sql_fetchrow($result))
		{
			// Add the points
			$this->points->add_user_points((int) $row['user_id'], (int) $this->config['breizhcharts_voters_points']);

			// Update last winner id
			$this->config->set('breizhcharts_winner_id', $row['user_id']);

			// Inform the lucky winner by PM, if PM is enabled
			if ($this->config['breizhcharts_pm_enable'])
			{
				$options = 0;
				$uid = $bitfield = '';
				$text = $this->language->lang('BC_PM_VOTERS_MESSAGE', $row['username'], $this->config['breizhcharts_voters_points'], $this->config['points_name']);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

				$data = [
					'address_list'		=> ['u' => [$row['user_id'] => 'to']],
					'from_user_id'		=> (int) $this->config['breizhcharts_pm_user'],
					'from_username'		=> 'Admin',
					'from_user_ip'		=> '',
					'icon_id'			=> 0,
					'enable_bbcode'		=> true,
					'enable_smilies'	=> true,
					'enable_urls'		=> true,
					'enable_sig'		=> true,
					'message'			=> $text,
					'bbcode_bitfield'	=> $bitfield,
					'bbcode_uid'		=> $uid,
				];
				submit_pm('post', utf8_encode_ucr($this->language->lang('BC_PM_VOTERS_SUBJECT')), $data, false);
			}
		}
	}

	public function send_pm_to_winners($points_active)
	{
		include_once($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				]
			],
			'WHERE'		=> 'c.last_pos > 0',
			'ORDER_BY'	=> 'c.last_pos ASC',
		]);
		$result = $this->db->sql_query_limit($sql, 3);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$options = 0;
			$uid = $bitfield = '';
			$message = $this->language->lang('BC_PM_MESSAGE', $row['username'], $this->language->lang('BC_PLACE_LIST_' . (int) $row['last_pos']), $row['song_name'], $row['artist']);
			if ($points_active)
			{
				$message .= $this->language->lang('BC_PM_MESSAGE_UPS', $this->config['breizhcharts_place_' . $row['last_pos']], $this->config['points_name']);
			}
			generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);

			$data = [
				'address_list'		=> ['u' => [$row['user_id'] => 'to']],
				'from_user_id'		=> (int) $this->config['breizhcharts_pm_user'],
				'from_username'		=> 'Admin',
				'from_user_ip'		=> '',
				'icon_id'			=> 0,
				'enable_bbcode'		=> true,
				'enable_smilies'	=> true,
				'enable_urls'		=> true,
				'enable_sig'		=> true,
				'message'			=> $message,
				'bbcode_bitfield'	=> $bitfield,
				'bbcode_uid'		=> $uid,
			];
			submit_pm('post', utf8_encode_ucr($this->language->lang('BC_PM_SUBJECT_' . $row['last_pos'])), $data, false);
		}
		$this->db->sql_freeresult($result);
	}
}
