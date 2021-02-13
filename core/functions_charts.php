<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

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

class functions_charts
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

	/** @var \Symfony\Component\DependencyInjection\Container */
	protected $phpbb_container;

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
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_container $phpbb_container, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_voters_table)
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
		$this->phpbb_container = $phpbb_container;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function get_version()
	{
		$md_manager = $this->ext_manager->create_extension_metadata_manager('sylver35/breizhcharts');
		$meta = $md_manager->get_metadata();
		$i = 0;
		$homepages = [];

		foreach (array_slice($meta['authors'], 0, 1) as $author)
		{
			$homepages[$i] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', $author['homepage'], $author['name']);
			$i++;
		}

		$this->template->assign_vars([
			'BC_COPYRIGHT'	=> $this->language->lang('BC_COPYRIGHT', $meta['version'], $homepages[0]),
		]);
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
				return true;
			}
		}
		return false;
	}

	private function get_username_song($user_id, $username, $user_colour)
	{
		$url = $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $user_id, 'name' => $username]);
		return '<span title="' . $this->language->lang('BC_OF_USER_TITLE', $username) . '">' . get_username_string('full', $user_id, $username, $user_colour, '', $url) . '</span>';
	}

	public function get_like($var)
	{
		return strtolower("'%" . $this->db->sql_escape($var) . "%'");
	}

	public function find_string($string, $start, $end)
	{
		$ini = strpos($string, $start);
		if ($ini == 0)
		{
			return $ini;
		}
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		$value = substr($string, $ini, $len);

		return $value;
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

	public function create_topic($song, $artist, $video)
	{
		if (!function_exists('submit_post'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}

		$options = 0;
		$poll = $uid = $bitfield = '';
		$url = '[url=' . $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']) . ']' . $this->language->lang('BC_GO_CHARTS') . '[/url]';
		$song_title = utf8_encode_ucr($this->language->lang('BC_ANNOUNCE_TITLE', $song, $artist));
		$song_msg = $this->language->lang('BC_ANNOUNCE_MSG', $song, $artist, $url, $this->get_youtube_img($video, true));
		generate_text_for_storage($song_msg, $uid, $bitfield, $options, true, true, true);

		$data = [
			'forum_id'			=> (int) $this->config['breizhcharts_song_forum'],
			'icon_id'			=> false,
			'enable_bbcode'		=> true,
			'enable_smilies'	=> true,
			'enable_urls'		=> true,
			'enable_sig'		=> true,
			'message'			=> (string) $song_msg,
			'message_md5'		=> (string) md5($song_msg),
			'bbcode_bitfield'	=> (string) $bitfield,
			'bbcode_uid'		=> (string) $uid,
			'post_edit_locked'	=> 0,
			'topic_title'		=> (string) $song_title,
			'notify_set'		=> false,
			'notify'			=> false,
			'post_time'			=> 0,
			'poster_id'			=> $this->user->data['user_id'],
			'forum_name'		=> '',
			'enable_indexing'	=> true,
		];
		$url = submit_post('post', $song_title, '', POST_NORMAL, $poll, $data);

		return $url;
	}

	public function add_user_points($user_id, $amount)
	{
		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_points = user_points + ' . (int) $amount . ' WHERE user_id = ' . (int) $user_id;
		$this->db->sql_query($sql);
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

	private function update_breizhcharts_check()
	{
		$modified = false;
		if ($this->user->data['is_registered'] && !$this->user->data['is_bot'])
		{
			if ($this->user->data['breizhchart_check_1'] == false)
			{
				$sql = 'UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 1, breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id'];
				$this->db->sql_query($sql);
				$modified = true;
			}
			else if ($this->user->data['breizhchart_check_2'] == false)
			{
				if (time() > ($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'] - ($this->config['breizhcharts_check_time'] * 3600)))
				{
					$sql = 'UPDATE ' . USERS_TABLE . ' SET breizhchart_check_2 = 1, breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id'];
					$this->db->sql_query($sql);
					$modified = true;
				}
			}
			if (!$modified)
			{
				$sql = 'UPDATE ' . USERS_TABLE . ' SET breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id'];
				$this->db->sql_query($sql);
			}
		}
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

	private function get_tendency_image($pos, $last_pos)
	{
		if ($last_pos === 0)
		{
			$img = 'new.png';
			$title = 'BC_NEW_PLACED';
		}
		else if ($pos === $last_pos)
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

		return '<img src="' . $this->ext_path . 'images/' . $img . '" alt="" title="' . $this->language->lang($title, $pos) . '" />';
	}

	public function create_navigation($mode, $title_mode, $song = 0, $userid = 0, $name = '')
	{
		$url_array = [];
		$url = 'sylver35_breizhcharts_page_music';
		if ($mode == 'add')
		{
			$url = 'sylver35_breizhcharts_add_music';
		}
		else if ($mode == 'edit')
		{
			$url = 'sylver35_breizhcharts_edit_music';
		}
		else
		{
			$url_array = [
				'mode'	=> $mode,
			];
		}
		if ($song)
		{
			$url_array['id'] = $song;
		}
		else if ($userid)
		{
			$url_array['user'] = $userid;
			$url_array['name'] = $name;
		}

		// Main template variables for the navigation
		$this->template->assign_block_vars('navlinks', [
			'FORUM_NAME'	=> $this->language->lang('BC_CHARTS'),
			'U_VIEW_FORUM'	=> $this->helper->route('sylver35_breizhcharts_page_music'),
		]);

		$this->template->assign_block_vars('navlinks', [
			'FORUM_NAME'	=> $title_mode,
			'U_VIEW_FORUM'	=> $this->helper->route($url, $url_array),
		]);
	}

	private function build_tendency()
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

	private function build_data_in_mode($mode, $user, $name)
	{
		if ($mode === 'own')
		{
			return [
				'rules'		=> false,
				'where'		=> 'c.poster_id = ' . (int) $this->user->data['user_id'],
				'select'	=> ' WHERE poster_id = ' . (int) $this->user->data['user_id'],
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $mode]),
			];
		}
		else if ($mode === 'user')
		{
			return [
				'rules'		=> false,
				'where'		=> 'c.poster_id = ' . $user,
				'select'	=> ' WHERE poster_id = ' . $user,
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $mode, 'user' => $user, 'name' => $name]),
			];
		}
		
		return [
			'rules'		=> true,
			'where'		=> '',
			'select'	=> '',
			'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $mode]),
		];
	}

	public function get_list_mode_charts($mode, $order_by, $start, $title_mode, $userid = 0, $name = '')
	{
		$i = 1;
		$this->update_breizhcharts_check();
		$data = $this->build_data_in_mode($mode, $userid, $name);
		$tendency = $this->build_tendency();
		$total_charts = $this->get_total_charts($data['select']);
		$number = (int) $this->config['breizhcharts_user_page'];

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, v.vote_rate as user_vote, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				],
				[
					'FROM'	=> [$this->breizhcharts_voters_table => 'v'],
					'ON'	=> 'v.vote_song_id = c.song_id AND v.vote_user_id = ' . $this->user->data['user_id'],
				],
			],
			'WHERE'		=> $data['where'],
			'ORDER_BY'	=> $order_by,
		]);
		$result = $this->db->sql_query_limit($sql, $number, $start);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$is_user = $row['poster_id'] == $this->user->data['user_id'];
			$can_edit = ($this->auth->acl_get('u_breizhcharts_edit') && $is_user || $this->auth->acl_get('a_breizhcharts_manage'));
			$row['user_vote'] = isset($row['user_vote']) ? $row['user_vote'] : 0;
			$user_vote = $this->language->lang('BC_AJAX_NOTE', $row['user_vote']);

			$this->template->assign_block_vars('charts', [
				'POSITION'		=> $i + $start,
				'SONG_ID'		=> $row['song_id'],
				'USERNAME'		=> $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'TITLE'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'ALBUM'			=> $row['album'],
				'YEAR'			=> $row['year'],
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'THUMBNAIL'		=> $this->get_youtube_img($row['video'], true),
				'RESULT'		=> number_format($row['song_note'] * 10, 2),
				'VOTE_USER'		=> $row['user_vote'] ? true : false,
				'TOTAL_RATE'	=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['song_note'], 2)),
				'SONG_RATED'	=> $this->language->lang('BC_AJAX_NOTE_NB', (int) $row['nb_note']),
				'USER_VOTE'		=> $user_vote,
				'RESULT_VOTE'	=> str_replace(['<span>', '</span>'], '', $user_vote),
				'TENDENCY_IMG'	=> $tendency[$row['song_id']]['image'],
				'ACTUAL'		=> $tendency[$row['song_id']]['position'],
				'LAST'			=> $tendency[$row['song_id']]['last'],
				'BEST'			=> $this->language->lang('BC_BEST_POS', $row['best_pos']),
				'ADDED_TIME'	=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'])),
				'U_SHOW_VIDEO'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'U_DELETE_SONG'	=> $this->auth->acl_get('a_breizhcharts_manage') ? $this->helper->route('sylver35_breizhcharts_delete_music', ['id' => $row['song_id']]) : '',
				'U_EDIT_SONG'	=> $can_edit ? $this->helper->route('sylver35_breizhcharts_edit_music', ['id' => $row['song_id'], 'start' => $start]) : '',
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->get_template_charts($data['rules']);
		$this->template->assign_vars([
			'S_LIST'			=> true,
			'NAV_ID'			=> $mode,
			'TITLE_PAGE'		=> $title_mode,
			'U_USER_NAV'		=> $userid ? $data['pagin'] : '',
			'USER_NAV_TITLE'	=> $userid ? $title_mode : '',
			'TOTAL_CHARTS'		=> $this->language->lang('BC_SONG_NB', $total_charts),
			'S_ON_PAGE'			=> $total_charts > $number,
			'PAGE_NUMBER' 		=> $this->pagination->validate_start($total_charts, $number, $start),
		]);
		$this->pagination->generate_template_pagination($data['pagin'], 'pagination', 'start', $total_charts, $number, $start);
	}

	public function get_winners_charts()
	{
		$i = 0;
		$points_active = $this->points_active();
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				],
			],
			'WHERE'		=> 'last_pos > 0',
			'ORDER_BY'	=> 'last_pos ASC',
		]);
		$result = $this->db->sql_query_limit($sql, (int) $this->config['breizhcharts_winners_per_page']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$data = $this->get_win_charts((int) $row['last_pos'], $points_active);
			$this->template->assign_block_vars('winners', [
				'NB'			=> $i,
				'RANK' 			=> $data['img'],
				'WIN'			=> $data['win'],
				'SONG' 			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'USER' 			=> $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'THUMBNAIL'		=> $this->get_youtube_img($row['video'], true),
				'U_SHOW_VIDEO'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		// Last bonus winner
		if ($points_active && $this->config['breizhcharts_winner_id'] > 0)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_winner_id'];
			$result = $this->db->sql_query($sql, 6800);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'S_UPS'				=> true,
				'S_BONUS_WINNER'	=> ($this->config['breizhcharts_winner_id'] > 0) ? true : false,
				'BONUS_WINNER'		=> $this->language->lang('BC_BONUS_WINNER', $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']), $this->config['breizhcharts_voters_points'], $this->config['points_name']),
			]);
		}

		$this->get_template_charts(false);
		$this->template->assign_vars([
			'S_LAST_WINNERS'	=> true,
			'NAV_ID'			=> 'winners',
			'TITLE_PAGE'		=> $this->language->lang('BC_LAST_WINNERS'),
		]);
	}

	private function get_win_charts($last_pos, $points_active)
	{
		if ($last_pos > 3)
		{
			return [
				'img'	=> '',
				'win'	=> '',
			];
		}

		return [
			'img'	=> '<img src="' . $this->ext_path . 'images/place_' . $last_pos . '.gif" alt="' . $this->language->lang('BC_PLACE_LIST_' . $last_pos) . '" title="' . $this->language->lang('BC_PLACE_LIST_' . $last_pos) . '" />',
			'win'	=> ($points_active) ? $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_place_' . $last_pos], $this->config['points_name']) : '',
		];
	}

	public function get_template_charts($rules)
	{
		$lang_period = ($this->config['breizhcharts_period_val'] == 86400) ? 'BC_DAY' : 'BC_WEEK';
		$period = $this->language->lang($lang_period, $this->config['breizhcharts_period'] / $this->config['breizhcharts_period_val']);
		$date_finish = $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE'));

		$this->template->assign_vars([
			'S_LIST_NAV'		=> true,
			'S_RULES'			=> $rules,
			'U_EXT_PATH'		=> $this->ext_path_web,
			'MC_TITLE_EXPLAIN'	=> $this->language->lang('BC_HEADER_EXPLAIN', $period, $date_finish),
			'MC_TOP_XX'			=> $this->language->lang('BC_TOP_TEN', $this->config['breizhcharts_num_top']),
			'U_ADD_SONG' 		=> $this->auth->acl_get('u_breizhcharts_add') ? $this->helper->route('sylver35_breizhcharts_add_music') . '#start' : '',
			'U_LIST_TOP'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list']) . '#start',
			'U_LIST_NEWEST'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']) . '#start',
			'U_LAST_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'winners']) . '#start',
			'U_LIST_OWN'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own']) . '#start',
			'U_VOTE_MUSIC' 		=> $this->helper->route('sylver35_breizhcharts_vote'),
		]);
	}

	private function first_check_charts()
	{
		// Last winners
		$points_active = $this->points_active();
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
			$data = $this->get_win_charts((int) $row['last_pos'], $points_active);
			$this->template->assign_block_vars('winners', [
				'RANK' 			=> $data['img'],
				'WIN'			=> $data['win'],
				'USER'			=> $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'SONG'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'VIDEO'			=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'IMG'			=> (empty($row['picture'])) ? $this->get_youtube_img($row['video'], true) : $row['picture'],
			]);
		}

		// Last bonus winner
		$bonus_winner = '';
		if ($this->config['breizhcharts_winner_id'] > 0)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_winner_id'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			$bonus_winner = $this->language->lang('BC_BONUS_WINNER', $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']), $this->config['breizhcharts_voters_points'], $this->config['points_name']);
		}

		$this->template->assign_vars(array(
			'S_CHECK_FIRST' => true,
			'BONUS_WINNER'	=> $bonus_winner,
			'PERIOD'		=> $this->user->format_date($this->config['breizhcharts_start_time']),         
			'VOTE'			=> $this->language->lang('BC_VOTE_CHECK_FIRST', $this->user->data['username']) . $this->language->lang('BC_VOTE_CHECK_LINK', '<br /><br /><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'),
		));
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
			$line = false;
			if ($i == 4)
			{
				$line = true;
				$i = 0;
			}
			$this->template->assign_block_vars('newests', array(
				'LINE'			=> $line,
				'USER'			=> $this->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'SONG'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'VIDEO'			=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'IMG'			=> (empty($row['picture'])) ? $this->get_youtube_img($row['video'], true) : $row['picture'],
			));
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'S_CHECK_SECOND'	=> true,
			'REMINDER'			=> $this->language->lang('BC_VOTE_CHECK_SECOND', $this->user->data['username']) . $this->language->lang('BC_VOTE_CHECK_LINK', '<br /><br /><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'),
		));
	}

	private function run_random_winner()
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
			$this->add_user_points((int) $row['user_id'], (int) $this->config['breizhcharts_voters_points']);

			// Update last winner id
			$this->config->set('breizhcharts_winner_id', $row['user_id']);

			// Inform the lucky winner by PM, if PM is enabled
			if ($this->config['breizhcharts_pm_enable'])
			{
				$subject = utf8_encode_ucr($this->language->lang('BC_PM_VOTERS_SUBJECT'));
				$text = $this->language->lang('BC_PM_VOTERS_MESSAGE', $row['username'], $this->config['breizhcharts_voters_points'], $this->config['points_name']);

				$options = 0;
				$uid = $bitfield = '';
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

				$data = [
					'address_list'		=> ['u' => [$row['user_id'] => 'to']],
					'from_user_id' 		=> (int) $this->config['breizhcharts_pm_user'],
					'from_username' 	=> 'Administration',
					'icon_id'			=> 0,
					'from_user_ip'		=> '',
					'enable_bbcode' 	=> true,
					'enable_smilies' 	=> true,
					'enable_urls' 		=> true,
					'enable_sig' 		=> true,
					'message' 			=> $text,
					'bbcode_bitfield' 	=> $bitfield,
					'bbcode_uid' 		=> $uid,
				];
				submit_pm('post', $subject, $data, false);
			}
		}
	}

	private function points_to_winners()
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

	private function send_pm_to_winners($points_active)
	{
		if (!$this->config['breizhcharts_pm_enable'])
		{
			return;
		}
		include_once($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);

		$i = 1;
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
			$last_pos = (int) $row['last_pos'];
			$message = $this->language->lang('BC_PM_MESSAGE', $row['username'], $this->language->lang('BC_PLACE_LIST_' . $last_pos), $row['song_name'], $row['artist']);
			if ($points_active)
			{
				$message .= $this->language->lang('BC_PM_MESSAGE_UPS', $this->config['breizhcharts_place_' . $last_pos], $this->config['points_name']);
			}

			$subject = utf8_encode_ucr($this->language->lang('BC_PM_SUBJECT_' . $last_pos));
			generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);

			$data = [
				'address_list'		=> ['u' => [$row['user_id'] => 'to']],
				'from_user_id' 		=> (int) $this->config['breizhcharts_pm_user'],
				'from_username' 	=> 'Administration',
				'icon_id'			=> 0,
				'from_user_ip'		=> '',
				'enable_bbcode' 	=> true,
				'enable_smilies' 	=> true,
				'enable_urls' 		=> true,
				'enable_sig' 		=> true,
				'message' 			=> $message,
				'bbcode_bitfield' 	=> $bitfield,
				'bbcode_uid' 		=> $uid,
			];
			submit_pm('post', $subject, $data, false);
			$i++;
		}
		$this->db->sql_freeresult($result);
	}

	public function run_vote_charts_period()
	{
		// Reset time
		$new_period = $this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'];
		$this->config->set('breizhcharts_start_time', $new_period);
		$this->config->set('breizhcharts_start_time_bis', date('d-m-Y H:i', $new_period));

		// Check if there are any voters - needed to decide, if we have winners in this period
		$sql = 'SELECT COUNT(vote_id) AS total_votes
			FROM ' . $this->breizhcharts_voters_table;
		$result = $this->db->sql_query($sql);
		$total_votes = (int) $this->db->sql_fetchfield('total_votes');
		$this->db->sql_freeresult($result);
		if (empty($total_votes))
		{
			return;
		}

		// Reset all notes
		$points_active = $this->points_active();
		$this->reset_all_notes();
		if ($points_active)
		{
			$this->run_random_winner();
			$this->points_to_winners();
		}

		// Send PM to the winners
		$this->send_pm_to_winners($points_active);

		// Empty the voters table
		$sql = 'TRUNCATE ' . $this->breizhcharts_voters_table;
		$this->db->sql_query($sql);

		// Reset the checks value
		$sql = 'UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 0, breizhchart_check_2 = 0 WHERE breizhchart_check_1 <> 0';
		$this->db->sql_query($sql);

		// Add a log entry, when the job is done
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_RESET', time());
	}

	private function reset_all_notes()
	{
		$i = 1;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'song_id, best_pos, last_pos',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> 'nb_note > 0',
			'ORDER_BY'	=> 'song_note DESC, nb_note DESC, last_pos ASC, best_pos ASC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$sql_ary = [
				'song_note'	=> 0,
				'nb_note'	=> 0,
				'last_pos'	=> $i,
				'best_pos'	=> ($i < (int) $row['best_pos'] || (int) $row['best_pos'] === 0) ? $i : $row['best_pos'],
			];
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE song_id = ' . $row['song_id']);
			$i++;
		}
		$this->db->sql_freeresult($result);
	}

	public function verify_chart_before_send($data, $id)
	{
		$i = 1;
		$error = [];
		// Check if new song probably already exist
		if ($this->verify_song_name($id, $data['song_name'], $data['artist']) !== false)
		{
			$error[] = $this->language->lang('BC_ALREADY_EXISTS_ERROR', $data['song_name'], $data['artist']);
		}

		$list = [
			'album'		=> 'BC_REQUIRED_ALBUM_ERROR',
			'year'		=> 'BC_REQUIRED_YEAR_ERROR',
			'song_name'	=> 'BC_TITLE_ERROR',
			'artist'	=> 'BC_ARTIST_ERROR',
			'video'		=> 'BC_REQUIRED_VIDEO_ERROR',
		];

		foreach ($list as $key => $lang)
		{
			if ($i < 3)
			{
				if (empty($data[$key]) && $this->config['breizhcharts_required_' . $i])
				{
					$error[] = $this->language->lang($lang);
				}
			}
			else if (empty($data[$key]))
			{
				$error[] = $this->language->lang($lang);
			}
			$i++;
		}

		return $error;
	}

	public function verify_song_name($id, $song, $artist)
	{
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'song_id',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> $this->db->sql_lower_text('song_name') . ' LIKE ' . $this->get_like($song) . ' AND ' . $this->db->sql_lower_text('artist') . ' LIKE ' . $this->get_like($artist) . ' AND song_id <> ' . $id,
		]);
		$result = $this->db->sql_query($sql);
		$song_id = $this->db->sql_fetchfield('song_id');
		$this->db->sql_freeresult($result);

		return $song_id;
	}

	public function verify_max_entries()
	{
		if ($this->config['breizhcharts_max_entries'] > 0)
		{
			if ($this->config['breizhcharts_songs_nb'] > $this->config['breizhcharts_max_entries'])
			{
				trigger_error($this->language->lang('BC_COUNT_ERROR', $this->config['breizhcharts_max_entries']) . $this->language->lang('BC_BACKLINK', '<br/><br/><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
			}
		}
	}
}
