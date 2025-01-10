<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
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
	protected $breizhcharts_cats_table;
	protected $breizhcharts_result_table;
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(points $points, template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_container $phpbb_container, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_result_table, $breizhcharts_voters_table)
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
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->breizhcharts_result_table = $breizhcharts_result_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function language_switch($lang_user, $switch_lang)
	{
		if (!$switch_lang && $lang_user !== $this->user->data['user_lang'])
		{
			$this->language->set_user_language($lang_user, true);
			return true;
		}
		else if ($switch_lang)
		{
			$this->language->set_user_language($this->user->data['user_lang'], true);
			return false;
		}
		return false;
	}

	public function get_username_song($user_id, $username, $user_colour)
	{
		$url = $this->helper->route('sylver35_breizhcharts_page_user', ['userid' => $user_id, 'cat' => 0, 'name' => $this->display_url($username)]);
		$name = str_replace(['&amp;u=' . $user_id, '&'], '', get_username_string('full', $user_id, $username, $user_colour, '', $url));
		return '<span title="' . $this->language->lang('BC_OF_USER_TITLE', $username) . '">' . $name . '</span>';
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

		return '<img src="' . $this->ext_path_web . 'images/' . $img . '" alt="' . $img . '" title="' . $this->language->lang($title, $pos) . '">';
	}

	public function stars_vote($song_id, $song_note, $user_vote, $can_vote)
	{
		$class = (!$user_vote && $can_vote) ? '-not' : '';
		$lang_note = $can_vote ? $this->language->lang('BC_AJAX_NOTE', (int) $user_vote) : $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($song_note, 2));
		$content = sprintf($this->config['breizhcharts_li_rating'], $song_id, $class, number_format($song_note * 10, 2) . '%');
		$sort = $can_vote ? 'true' : 'false';

		for ($i = 1, $nb = 11; $i < $nb; $i++)
		{
			$onclick = !$user_vote ? sprintf($this->config['breizhcharts_li_onclick'], $song_id, $i, $sort) : '';
			$title = (!$user_vote && $can_vote) ? $this->language->lang('BC_AJAX_STARS', $i) : strip_tags($lang_note);
			$content .= sprintf($this->config['breizhcharts_li_stars'], $onclick, $title, $i);
		}

		return $content;
	}

	public function display_url($value)
	{
		return str_replace([' ', '&nbsp;', '?', "'"], ['_', '_', '', '’'], $value);
	}

	public function get_all_wins($result_id)
	{
		$options = '';
		$sql = 'SELECT result_nb, result_time
			FROM ' . $this->breizhcharts_result_table . '
				GROUP BY result_nb, result_time
				ORDER BY result_nb DESC';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$value = $this->helper->route('sylver35_breizhcharts_result') . '?result_id=' . $row['result_nb'];
			$select = ((int) $result_id === (int) $row['result_nb']) ? ' selected="selected"' : '';
			$options .= '<option value="' . $value . '"' . $select . '>' . $this->user->format_date($row['result_time'], $this->language->lang('BC_LAST_WINNERS_FORMAT')) . '</option>';
		}
		$this->db->sql_freeresult($result);

		return $options;
	}

	public function get_reported_videos()
	{
		if (($reports = $this->cache->get('_breizhcharts_reported')) === false)
		{
			$i = 0;
			$reports = [];
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'b.*, u.user_id, u.username, u.user_colour, v.user_id as id, v.username as name, v.user_colour as colour',
				'FROM'		=> [$this->breizhcharts_table => 'b'],
				'LEFT_JOIN'	=> [
					[
						'FROM'	=> [USERS_TABLE => 'u'],
						'ON'	=> 'u.user_id = b.reported',
					],
					[
						'FROM'	=> [USERS_TABLE => 'v'],
						'ON'	=> 'v.user_id = b.poster_id',
					],
				],
				'WHERE'		=> 'b.reported > 0',
				'ORDER_BY'	=> 'b.song_id ASC',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$reports[$i] = [
					'song_id'			=> $row['song_id'],
					'song_name'			=> $row['song_name'],
					'artist'			=> $row['artist'],
					'video'				=> $row['video'],
					'add_time'			=> $row['add_time'],
					'poster_id'			=> $row['poster_id'],
					'name'				=> $row['name'],
					'colour'			=> $row['colour'],
					'user_id'			=> $row['user_id'],
					'username'			=> ($row['user_id'] == 3) ? 'auto' : $row['username'],
					'user_colour'		=> ($row['user_id'] == 3) ? '435B8A' : $row['user_colour'],
					'reason'			=> $row['reason'],
					'reported_time'		=> $row['reported_time'],
				];
				$i++;
			}
			$this->db->sql_freeresult($result);

			// cache for 7 days
			$this->cache->put('_breizhcharts_reported', $reports, 604800);
		}
		else
		{
			$i = sizeof($reports);
		}

		return ['reports' => $reports, 'nb' => $i];
	}

	public function get_positions()
	{
		if (($positions = $this->cache->get('_breizhcharts_positions')) === false)
		{
			$i = 0;
			$positions = [];
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'song_id, song_name, artist, poster_id, song_note, last_pos, best_pos',
				'FROM'		=> [$this->breizhcharts_table => 'b'],
				'ORDER_BY'	=> 'song_note DESC, nb_note DESC, last_pos ASC, best_pos ASC',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$positions[$row['song_id']] = [
					'position'		=> $i + 1,
					'song_id'		=> $row['song_id'],
					'song_name'		=> $row['song_name'],
					'artist'		=> $row['artist'],
					'poster_id'		=> $row['poster_id'],
					'song_note'		=> $row['song_note'],
					'last_pos'		=> $row['last_pos'],
					'best_pos'		=> $row['best_pos'],
				];
				$i++;
			}
			$this->db->sql_freeresult($result);

			// cache for 7 days
			$this->cache->put('_breizhcharts_positions', $positions, 604800);
		}

		return $positions;
	}

	public function get_cat_id($cat = 0)
	{
		if (($list = $this->cache->get('_breizhcharts_get_id')) === false)
		{
			$i = 0;
			$list = [];
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'cat_id, cat_name, cat_nb',
				'FROM'		=> [$this->breizhcharts_cats_table => 'c'],
				'ORDER_BY'	=> 'cat_id ASC',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$list[$row['cat_id']] = [
					$row['cat_id']	=> $row['cat_name'],
				];
				$i++;
			}
			$this->db->sql_freeresult($result);

			// cache for 7 days
			$this->cache->put('_breizhcharts_get_id', $list, 604800);
		}

		if ($cat)
		{
			return array_search((int) $cat, $list, true);
		}

		return $list;
	}

	public function get_cats()
	{
		if (($cats = $this->cache->get('_breizhcharts_cats')) === false)
		{
			$i = 0;
			$cats = [];
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'c.*',
				'FROM'		=> [$this->breizhcharts_cats_table => 'c'],
				'ORDER_BY'	=> $this->config['breizhcharts_select_order'] ? 'cat_order ASC' : 'cat_name ASC',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$cats[$i] = [
					'cat_id'		=> $row['cat_id'],
					'cat_order'		=> $row['cat_order'],
					'cat_name'		=> $row['cat_name'],
					'cat_nb'		=> $row['cat_nb'],
				];
				$i++;
			}
			$this->db->sql_freeresult($result);

			// cache for 7 days
			$this->cache->put('_breizhcharts_cats', $cats, 604800);
		}

		return $cats;
	}

	public function get_cats_select($cat, $url = '', $url_array = '')
	{
		// Get all categories from cache
		$cats = $this->get_cats();

		$value = $url ? $this->helper->route($url, array_merge($url_array, ['cat' => 0])) . '#nav' : '';
		$select = (!$cat) ? ' selected="selected"' : '';
		$options = '<option id="cat-0"' . $select . ' value="' . $value . '">' . ($url ? $this->language->lang('BC_SONG_CAT_ALL') : $this->language->lang('BC_SONG_CAT_CHOICE')) . '</option>';
		$count = count($cats);
		for ($i = 0; $i < $count; $i++)
		{
			$value = $url ? $this->helper->route($url, array_merge($url_array, ['cat' => $cats[$i]['cat_id']])) . '#nav' : $cats[$i]['cat_id'];
			$select = ((int) $cats[$i]['cat_id'] === (int) $cat) ? ' selected="selected"' : '';
			$options .= '<option id="cat-' . $cats[$i]['cat_id'] . '"' . $select . ' value="' . $value . '">' . $cats[$i]['cat_name'] . '</option>';
		}

		return $options;
	}

	public function send_pm_to_winners($points_active)
	{
		include_once($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);

		$switch_lang = false;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour, u.user_lang',
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
			// Switch language if needed
			$switch_lang = $this->language_switch($row['user_lang'], $switch_lang);

			$options = 0;
			$uid = $bitfield = '';
			$message = $this->language->lang('BC_PM_MESSAGE',
				$row['username'],
				$row['user_colour'] ? $row['user_colour'] : '435B8A',
				$this->language->lang('BC_PLACE_LIST_' . (int) $row['last_pos']),
				$row['song_name'],
				$row['artist'],
			);
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

			// Switch language if needed
			$switch_lang = $this->language_switch($row['user_lang'], $switch_lang);
		}
		$this->db->sql_freeresult($result);
	}

	public function get_cat_name($cat)
	{
		if ($cat)
		{
			$cats = $this->get_cats();
			$count = count($cats);
			for ($i = 0; $i < $count; $i++)
			{
				if ((int) $cats[$i]['cat_id'] === (int) $cat)
				{
					return $cats[$i]['cat_name'];
				}
			}
		}
		return '';
	}
}
