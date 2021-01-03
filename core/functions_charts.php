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
		phpbb_container $phpbb_container,
		$root_path,
		$php_ext,
		$breizhcharts_table,
		$breizhcharts_voters_table
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

		return [
			'version'	=> $meta['version'],
			'homepage1'	=> $homepages[0],
		];
	}

	/**
	 * test if the extension relaxarcade is running
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

	public function get_like($var)
	{
		return strtolower("'%" . $this->db->sql_escape($var) . "%'");
	}

	public function create_announcement($song_name, $artist, $picture)
	{
		if (!function_exists('submit_post'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}

		$url = '[url=' . $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']) . ']' . $this->language->lang('BC_GO_CHARTS') . '[/url]';
		$picture = (!empty($picture)) ? $picture : $this->ext_path_web . 'images/breizhcharts.png';
		$song_title = $this->language->lang('BC_ANNOUNCE_TITLE', $song_name, $artist);
		$song_msg = $this->language->lang('BC_ANNOUNCE_MSG', $song_name, $artist, $url, $picture);
		// variables to hold the parameters for submit_post
		$options = 0;
		$poll = $uid = $bitfield = '';
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
		submit_post('post', (string) $song_title, '', POST_NORMAL, $poll, $data);
	}

	public function dm_addpoints($user_id, $amount)
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

		if ($this->config['breizhcharts_check_1'] && !$this->user->data['breizhchart_check_1'])
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
			if ($this->user->data['breizhchart_check_1'] == 0)
			{
				$sql = 'UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 1, breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id'];
				$this->db->sql_query($sql);
				$modified = true;
			}
			else if ($this->user->data['breizhchart_check_2'] == 0)
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
		$sql = 'SELECT u.user_id, u.username, u.user_colour
			FROM ' . $this->breizhcharts_voters_table . ' v
			LEFT JOIN ' . USERS_TABLE . ' u
				on v.vote_user_id = u.user_id
			GROUP BY v.vote_user_id';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$usernames[$i] = '<span title="' . $this->language->lang('BC_OF_USER_TITLE', $row['username']) . '">' . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])) . '</span>';
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
			$result = $this->db->sql_query($sql);
			$total_charts = (int) $this->db->sql_fetchfield('total_charts');
			$this->db->sql_freeresult($result);
		}
		else
		{
			$total_charts = (int) $this->config['breizhcharts_songs_nb'];
		}

		return $total_charts;
	}

	private function get_images_vote($voter, $song, $artist)
	{
		if (!$this->auth->acl_get('u_breizhcharts_vote'))
		{
			return [
				'hot'	=> '<img src="' . $this->ext_path . 'images/hot_voted.png" alt="" title="' . $this->language->lang('BC_NOT_LOGGED_IN') . '" height="25" />',
				'not'	=> '<img src="' . $this->ext_path . 'images/not_voted.png" alt="" title="' . $this->language->lang('BC_NOT_LOGGED_IN') . '" height="25" />',
			];
		}
		else if (!$voter)
		{
			return [
				'hot'	=> '<a onclick="breizhcharts.voteMusic(' . $song . ', 1);" style="cursor:pointer;"><img src="' . $this->ext_path . 'images/hot.png" alt="hot" title="' . $this->language->lang('BC_PICTURE_HOT_TITLE', $artist) . '" height="25" /></a>',
				'not'	=> '<a onclick="breizhcharts.voteMusic(' . $song . ', 2);" style="cursor:pointer;"><img src="' . $this->ext_path . 'images/not.png" alt="not" title="' . $this->language->lang('BC_PICTURE_NOT_TITLE', $artist) . '" height="25" /></a>',
			];
		}
		else
		{
			return [
				'hot'	=> '<img src="' . $this->ext_path . 'images/hot_voted.png" alt="" title="' . $this->language->lang('BC_ALREADY_VOTED') . '" height="25" />',
				'not'	=> '<img src="' . $this->ext_path . 'images/not_voted.png" alt="" title="' . $this->language->lang('BC_ALREADY_VOTED') . '" height="25" />',
			];
		}
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
			'SELECT'	=> 'song_id, last_pos, best_pos, song_hot, song_not, average',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'ORDER_BY'	=> 'average DESC, song_hot DESC, song_not ASC, last_pos DESC, best_pos DESC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$tendency[$row['song_id']] = [
				'position'	=> $i,
				'image'		=> $this->get_tendency_image($i, (int) $row['last_pos']),
				'last'		=> ((int) $row['last_pos'] === 0) ? $this->language->lang('DM_ENTER') : $this->language->lang('BC_LATEST') . $row['last_pos'],
			];
			$i++;
		}
		$this->db->sql_freeresult($result);

		return $tendency;
	}

	private function build_data_in_mode($mode, $user, $name)
	{
		$data = [
			'rules'		=> true,
			'where'		=> '',
			'select'	=> '',
			'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $mode]),
		];

		if ($mode === 'own')
		{
			$data = [
				'rules'		=> false,
				'where'		=> 'c.poster_id = ' . $this->user->data['user_id'],
				'select'	=> ' WHERE poster_id = ' . $this->user->data['user_id'],
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $mode]),
			];
		}
		else if ($mode === 'user')
		{
			$data = [
				'rules'		=> false,
				'where'		=> 'c.poster_id = ' . $user,
				'select'	=> ' WHERE poster_id = ' . $user,
				'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $mode, 'user' => $user, 'name' => $name]),
			];
		}

		return $data;
	}

	public function get_list_mode_charts($mode, $order_by, $start, $title_mode, $nav, $userid = 0, $name = '')
	{
		$i = 0;
		$this->update_breizhcharts_check();
		$data = $this->build_data_in_mode($mode, $userid, $name);
		$tendency = $this->build_tendency();
		$total_charts = $this->get_total_charts($data['select']);
		$number = (int) $this->config['breizhcharts_user_page'];

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, v.*, u.user_id, u.username, u.user_colour',
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
			$vote = $this->get_images_vote($row['vote_user_id'], (int) $row['song_id'], $row['artist']);
			$is_user = $row['poster_id'] == $this->user->data['user_id'];
			$can_edit = ($this->auth->acl_get('u_breizhcharts_edit') && $is_user || $this->auth->acl_get('a_breizhcharts_manage'));

			$this->template->assign_block_vars('charts', [
				'TENDENCY_IMG'	=> $tendency[$row['song_id']]['image'],
				'ACTUAL'		=> $tendency[$row['song_id']]['position'],
				'LAST'			=> $tendency[$row['song_id']]['last'],
				'POSITION'		=> $i + 1 + $start,
				'SONG_ID'		=> $row['song_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])),
				'USER_TITLE'	=> $this->language->lang('BC_OF_USER_TITLE', $row['username']),
				'TITLE'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'ALBUM'			=> $row['album'],
				'ALBUM_IMG'		=> $row['picture'] ? '<img src="' . $row['picture'] . '" height="50px" alt="" title="' . $this->language->lang('BC_PICTURE_TITLE', $row['artist']) . '" />' : '',
				'YEAR'			=> $row['year'],
				'WEBSITE'		=> $row['website'],
				'GO_TO_WEBSITE'	=> $row['website'] ? $this->language->lang('BC_GOTO_WEB', $row['artist']) : '',
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'VOTE_HOT'		=> $vote['hot'],
				'VOTE_NOT'		=> $vote['not'],
				'AVERAGE'		=> $this->language->lang('BC_AVERAGE', $row['average']),
				'SONG_HOT' 		=> $this->language->lang('BC_HOT', $row['song_hot']),
				'SONG_NOT' 		=> $this->language->lang('BC_NOT', $row['song_not']),
				'BEST'			=> $row['best_pos'],
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
			'TITLE_PAGE'		=> $title_mode,
			'NAV_ID'			=> $nav,
			'U_USER_NAV'		=> $userid ? $data['pagin'] : '',
			'USER_NAV_TITLE'	=> $userid ? $title_mode : '',
			'TOTAL_CHARTS'		=> $this->language->lang('BC_SONG_NB', $total_charts),
			'S_ON_PAGE'			=> $total_charts > $number,
			'PAGE_NUMBER' 		=> $this->pagination->validate_start($total_charts, $number, $start),
		]);
		$this->pagination->generate_template_pagination($data['pagin'], 'pagination', 'start', $total_charts, $number, $start);
	}

	public function get_winners_charts($title_mode)
	{
		$i = 0;
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
			$data = $this->get_win_charts($row['last_pos']);
			$this->template->assign_block_vars('winners', [
				'NB'			=> $i,
				'RANK' 			=> $data['img'],
				'WIN'			=> $data['win'],
				'USER' 			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])),
				'USER_TITLE'	=> $this->language->lang('BC_OF_USER_TITLE', $row['username']),
				'VIDEO'			=> $this->language->lang('BC_SHOW_VIDEO', $row['song_name']),
				'SONG' 			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'U_SHOW_VIDEO'	=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		// Last bonus winner
		if ($this->points_active() && $this->config['breizhcharts_last_voters_winner'] > 0)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_last_voters_winner'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'S_UPS'				=> true,
				'S_BONUS_WINNER'	=> ($this->config['breizhcharts_last_voters_winner'] > 0) ? true : false,
				'BONUS_WINNER'		=> $this->language->lang('BC_BONUS_WINNER', get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])), $this->config['breizhcharts_voters_points'], $this->config['points_name']),
				'USER_TITLE'		=> $this->language->lang('BC_OF_USER_TITLE', $row['username']),
			]);
		}

		$this->get_template_charts(false);
		$this->template->assign_vars([
			'S_LAST_WINNERS'	=> true,
			'NAV_ID'			=> 'win',
			'TITLE_PAGE'		=> $title_mode,
		]);
	}

	private function get_win_charts($last_pos)
	{
		switch ($last_pos)
		{
			case 1:
				$data = [
					'img'	=> '<img src="' . $this->ext_path . 'images/1st.gif" alt="' . $this->language->lang('BC_FIRST') . '" title="' . $this->language->lang('BC_FIRST') . '" />',
					'win'	=> ($this->config['points_enable']) ? $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_1st_place'], $this->config['points_name']) : '',
				];
			break;
			case 2:
				$data = [
					'img'	=> '<img src="' . $this->ext_path . 'images/2nd.gif" alt="' . $this->language->lang('BC_SECOND') . '" title="' . $this->language->lang('BC_SECOND') . '" />',
					'win'	=> ($this->config['points_enable']) ? $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_2nd_place'], $this->config['points_name']) : '',
				];
			break;
			case 3:
				$data = [
					'img'	=> '<img src="' . $this->ext_path . 'images/3rd.gif" alt="' . $this->language->lang('BC_THIRD') . '" title="' . $this->language->lang('BC_THIRD') . '" />',
					'win'	=> ($this->config['points_enable']) ? $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_3rd_place'], $this->config['points_name']) : '',
				];
			break;
			default:
				$data = [
					'img'	=> '',
					'win'	=> '',
				];
		}

		return $data;
	}

	public function get_template_charts($rules)
	{
		$this->template->assign_vars([
			'S_RULES'			=> $rules,
			'S_LIST_NAV'		=> true,
			'U_EXT_PATH'		=> $this->ext_path_web,
			'MC_TITLE_EXPLAIN'	=> $this->language->lang('BC_HEADER_EXPLAIN', $this->user->format_date($this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period'], $this->language->lang('BC_DATE'))),
			'MC_TOP_XX'			=> $this->language->lang('BC_TOP_TEN', $this->config['breizhcharts_num_top']),
			'U_ADD_SONG' 		=> $this->auth->acl_get('u_breizhcharts_add') ? $this->helper->route('sylver35_breizhcharts_add_music') : '',
			'U_LIST_TOP'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list']),
			'U_LIST_NEWEST'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest']),
			'U_LAST_WINNERS'	=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'winners']),
			'U_LIST_OWN'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own']),
			'U_VOTE_MUSIC' 		=> $this->helper->route('sylver35_breizhcharts_vote'),
		]);
	}

	private function first_check_charts()
	{
		// Last winners
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
			if ($row['last_pos'] == 1)
			{
				$rank_img = '<img src="' . $this->ext_path . 'images/1st.gif" alt="" title="' . $this->language->lang('BC_FIRST') . '" />';
				$win = $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_1st_place'], $this->config['points_name']);
			}
			else if ($row['last_pos'] == 2)
			{
				$rank_img = '<img src="' . $this->ext_path . 'images/2nd.gif" alt="" title="' . $this->language->lang('BC_SECOND') . '" />';
				$win = $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_2nd_place'], $this->config['points_name']);
			}
			else
			{
				$rank_img = '<img src="' . $this->ext_path . 'images/3rd.gif" alt="" title="' . $this->language->lang('BC_THIRD') . '" />';
				$win = $this->language->lang('BC_WON_VALUE', $this->config['breizhcharts_3rd_place'], $this->config['points_name']);
			}

			$this->template->assign_block_vars('winners', [
				'RANK'			=> $rank_img,
				'USER'			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])),
				'USER_TITLE'	=> $this->language->lang('BC_OF_USER_TITLE', $row['username']),
				'SONG'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'VIDEO'			=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'IMG'			=> (!empty($row['picture'])) ? $row['picture'] : $this->ext_path . 'images/breizhcharts.png',
				'WIN'			=> $win,
			]);
		}

		// Last bonus winner
		$bonus_winner = $bonus_title = '';
		if ($this->config['breizhcharts_last_voters_winner'] > 0)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $this->config['breizhcharts_last_voters_winner'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			$bonus_winner = $this->language->lang('BC_BONUS_WINNER', get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])), $this->config['breizhcharts_voters_points'], $this->config['points_name']);
			$bonus_title = $this->language->lang('BC_OF_USER_TITLE', $row['username']);
		}

		$this->template->assign_vars(array(
			'S_CHECK_FIRST' => true,
			'BONUS_WINNER'	=> $bonus_winner,
			'USER_TITLE'	=> $bonus_title,
			'PERIOD'		=> $this->user->format_date($this->config['breizhcharts_start_time']),         
			'VOTE'			=> $this->language->lang('BC_VOTE_CHECK_FIRST', $this->user->data['username']) . $this->language->lang('BC_VOTE_CHECK_LINK', '<br /><br /><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'),
		));
	}

	private function second_check_charts()
	{
		// List xx newest chart songs
		$j = 0;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				]
			],
			'WHERE'		=> 'c.add_time > ' . $this->config['breizhcharts_start_time'],
			'ORDER_BY'	=> 'c.song_id DESC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$line = false;
			if ($j == 4)
			{
				$line = true;
				$j = 0;
			}
			$this->template->assign_block_vars('newests', array(
				'USER'			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], '', $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'user', 'user' => $row['user_id'], 'name' => $row['username']])),
				'USER_TITLE'	=> $this->language->lang('BC_OF_USER_TITLE', $row['username']),
				'SONG'			=> $row['song_name'],
				'ARTIST'		=> $row['artist'],
				'VIDEO'			=> $this->helper->route('sylver35_breizhcharts_page_popup', ['id' => $row['song_id']]),
				'IMG'			=> (!empty($row['picture'])) ? $row['picture'] : $this->ext_path . 'images/breizhcharts.png',
				'LINE'			=> $line,
			));
			$j++;
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
			$this->dm_addpoints((int) $row['user_id'], (int) $this->config['breizhcharts_voters_points']);

			// Update last winner id
			$this->config->set('breizhcharts_last_voters_winner', $row['user_id']);

			// Inform the lucky winner by PM, if PM is enabled
			if ($this->config['breizhcharts_pm_enable'])
			{
				if (!function_exists('submit_pm'))
				{
					include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
				}

				$subject = $this->language->lang('BC_PM_VOTERS_SUBJECT');
				$text = $this->language->lang('BC_PM_VOTERS_MESSAGE', $row['username'], $this->config['breizhcharts_voters_points'], $this->config['points_name']);

				$options = 0;
				$uid = $bitfield = '';
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
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
			if ((int) $row['last_pos'] === 1)
			{
				if ($this->config['breizhcharts_1st_place'] > 0)
				{
					$this->dm_addpoints((int) $row['poster_id'], (int) $this->config['breizhcharts_1st_place']);
				}
			}
			else if ((int) $row['last_pos'] === 2)
			{
				if ($this->config['breizhcharts_2nd_place'] > 0)
				{
					$this->dm_addpoints((int) $row['poster_id'], (int) $this->config['breizhcharts_2nd_place']);
				}
			}
			else if ((int) $row['last_pos'] === 3)
			{
				if ($this->config['breizhcharts_3rd_place'] > 0)
				{
					$this->dm_addpoints((int) $row['poster_id'], (int) $this->config['breizhcharts_3rd_place']);
				}
			}
		}
		$this->db->sql_freeresult($result);
	}

	private function send_pm_to_winners()
	{
		if (!function_exists('submit_pm'))
		{
			include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
		}

		// Find the three winners
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
			$price = 0;
			if (!$this->points_active())
			{
				$my_text = $this->language->lang('BC_PM_MESSAGE', $row['username'], $row['last_pos'], $row['song_name'], $row['artist']);
			}
			else
			{
				if ($this->config['breizhcharts_1st_place'] > 0 && $row['last_pos'] == 1)
				{
					$price = $this->config['breizhcharts_1st_place'];
				}
				else if ($this->config['breizhcharts_2nd_place'] > 0 && $row['last_pos'] == 2)
				{
					$price = $this->config['breizhcharts_2nd_place'];
				}
				else if ($this->config['breizhcharts_3rd_place'] > 0 && $row['last_pos'] == 3)
				{
					$price = $this->config['breizhcharts_3rd_place'];
				}

				$my_text = $this->language->lang('BC_PM_MESSAGE_UPS', $row['username'], $row['last_pos'], $row['song_name'], $row['artist'], $price, $this->config['points_name']);
			}

			$my_subject = $this->language->lang('BC_PM_SUBJECT', $row['last_pos']);

			$options = 0;
			$uid = $bitfield = '';
			generate_text_for_storage($my_subject, $uid, $bitfield, $options, false, false, false);
			generate_text_for_storage($my_text, $uid, $bitfield, $options, true, true, true);

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
				'message' 			=> $my_text,
				'bbcode_bitfield' 	=> $bitfield,
				'bbcode_uid' 		=> $uid,
			];
			submit_pm('post', $my_subject, $data, false);
		}
		$this->db->sql_freeresult($result);
	}

	public function run_vote_charts_period()
	{
		// Check if current time is higher than the reset time
		$this->config->set('breizhcharts_start_time', $this->config['breizhcharts_start_time'] + $this->config['breizhcharts_period']);

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

		// Select the current rating and users
		$i = 1;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> '*',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'ORDER_BY'	=> 'average DESC, song_hot DESC, song_not ASC, last_pos DESC, best_pos DESC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$new_best = ($i < $row['best_pos'] || $row['best_pos'] == 0) ? $i : $row['best_pos'];
			$sql_ary = [
				'last_pos'	=> $i,
				'best_pos'	=> $new_best,
			];
			$sql = 'UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE song_id = ' . $row['song_id'];
			$this->db->sql_query($sql);
		}
		$this->db->sql_freeresult($result);

		if ($this->points_active())
		{
			$this->run_random_winner();
			$this->points_to_winners();
		}

		// Send PM to the winners, if enabled
		if ($this->config['breizhcharts_pm_enable'])
		{
			$this->send_pm_to_winners();
		}

		// Reset the votes
		$sql = 'UPDATE ' . $this->breizhcharts_table . ' SET song_hot = 0, song_not = 0, average = 0 WHERE song_id > 0';
		$this->db->sql_query($sql);

		// Empty the voters table
		$sql = 'TRUNCATE ' . $this->breizhcharts_voters_table;
		$this->db->sql_query($sql);

		// Reset the checks value
		$sql = 'UPDATE ' . USERS_TABLE . ' SET breizhchart_check_1 = 0, breizhchart_check_2 = 0 WHERE breizhchart_check_1 <> 0';
		$this->db->sql_query($sql);

		// Add a log entry, when the job is done
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_RESET', time());
	}

	public function verify_chart_before_send($data, $id)
	{
		$error = [];
		if (empty($data['song_name']))
		{
			$error[] = $this->language->lang('BC_TITLE_ERROR');
		}

		if (empty($data['artist']))
		{
			$error[] = $this->language->lang('BC_ARTIST_ERROR');
		}

		if ($this->config['breizhcharts_required_1'] && empty($data['album']))
		{
			$error[] = $this->language->lang('BC_REQUIRED_ALBUM_ERROR');
		}

		// Check, if the link to the album cover starts with http://
		if (!empty($data['picture']))
		{
			if (stripos($data['picture'], 'http://') === false && stripos($data['picture'], 'https://') === false)
			{
				$error[] = $this->language->lang('BC_COVER_FORMAT_ERROR');
			}
		}
		else if ($this->config['breizhcharts_required_2'] && empty($data['picture']))
		{
			$error[] = $this->language->lang('BC_REQUIRED_COVER_ERROR');
		}

		// Check, if the publishing year is between 1900
		if (!empty($data['year']))
		{
			if ($data['year'] < 1900 || $data['year'] > 3000)
			{
				$error[] = $this->language->lang('BC_YEAR_FORMAT_ERROR');
			}
		}
		else if ($this->config['breizhcharts_required_3'] && empty($data['year']))
		{
			$error[] = $this->language->lang('BC_REQUIRED_YEAR_ERROR');
		}

		// Check, if the link to the website starts with http://
		if (!empty($data['website']))
		{
			if (stripos($data['website'], 'http://') === false && stripos($data['website'], 'https://') === false)
			{
				$error[] = $this->language->lang('BC_WEBSITE_FORMAT_ERROR');
			}
		}
		else if ($this->config['breizhcharts_required_4'] && empty($data['website']))
		{
			$error[] = $this->language->lang('BC_REQUIRED_WEBSITE_ERROR');
		}

		if (!empty($data['video']))
		{
			if (stristr($data['video'], 'object') === false && stristr($data['video'], 'iframe') === false)
			{
				$error[] = $this->language->lang('BC_EMBED_FORMAT_ERROR');
			}
		}
		else if (empty($data['video']))
		{
			$error[] = $this->language->lang('BC_REQUIRED_VIDEO_ERROR');
		}

		// Check if new song probably already exist
		$sql = [
			'SELECT'	=> 'song_name',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> "LOWER(song_name) LIKE '%" . $this->db->sql_escape(strtolower($data['song_name'])) . "%' AND LOWER(artist) LIKE '%" . $this->db->sql_escape(strtolower($data['artist'])) . "%' AND song_id <> $id",
		];
		$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $sql));
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		
		if (isset($row['song_name']))
		{
			$error[] = $this->language->lang('BC_ALREADY_EXISTS_ERROR', $data['song_name'], $data['artist']);
		}

		return $error;
	}

	public function verify_max_entries()
	{
		if ($this->config['breizhcharts_max_entries'] > 0)
		{
			// Check if maximum songs is reached
			$sql = 'SELECT COUNT(song_id) AS counter
				FROM ' . $this->breizhcharts_table;
			$result = $this->db->sql_query($sql);
			$counter = (int) $this->db->sql_fetchfield('counter');
			$this->db->sql_freeresult($result);

			if ($counter > $this->config['breizhcharts_max_entries'])
			{
				return true;
			}
		}
		return false;
	}
}
