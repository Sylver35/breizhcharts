<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use phpbb\json_response;
use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\functions_charts;
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

class breizhcharts
{
	/** @var \sylver35\breizhcharts\core\functions_charts */
	protected $functions_charts;

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
		functions_charts $functions_charts,	
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
		$breizhcharts_table,
		$breizhcharts_voters_table
	)
	{
		$this->functions_charts = $functions_charts;
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

	public function handle()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_view', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		$mode = (string) $this->request->variable('mode', '');
		$start = (int) $this->request->variable('start', 0);
		$song_id = (int) $this->request->variable('id', 0);
		$userid = (int) $this->request->variable('user', 0);
		$name = (string) $this->request->variable('name', '', true);
		$body = 'breizhcharts.html';
		$title_mode = '';

		// Select users, which already voted
		$this->functions_charts->get_voters();

		// Switch the mode
		switch ($mode)
		{
			default :
			case 'list_newest':
				$title_mode = $this->language->lang('BC_NEWEST_XX');
				$order_by = 'c.song_id DESC';
				$this->functions_charts->get_list_mode_charts($mode, $order_by, $start, $title_mode, 'new');
			break;

			case 'list':
				$title_mode = $this->language->lang('BC_BEST_RATED');
				$order_by = 'c.average DESC, c.song_hot DESC, c.song_not ASC, c.last_pos DESC, c.best_pos DESC';
				$this->functions_charts->get_list_mode_charts($mode, $order_by, $start, $title_mode, 'top');
			break;

			case 'own':
				$title_mode = $this->language->lang('BC_OWN');
				$order_by = 'c.song_id DESC';
				$this->functions_charts->get_list_mode_charts($mode, $order_by, $start, $title_mode, 'own');
			break;

			case 'user':
				$title_mode = $this->language->lang('BC_OF_USER', $name);
				$order_by = 'c.song_id DESC';
				$this->functions_charts->get_list_mode_charts($mode, $order_by, $start, $title_mode, 'user', $userid, $name);
			break;

			case 'winners':
				$body = 'breizhcharts_winners.html';
				$title_mode = $this->language->lang('BC_LAST_WINNERS', $this->config['breizhcharts_winners_per_page']);
				$this->functions_charts->get_winners_charts($title_mode);
			break;
		}

		$this->functions_charts->create_navigation($mode, $title_mode, $song_id, $userid, $name);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ($title_mode ? ' - ' . $title_mode : ''));

		// Load charts template
		$this->template->set_filenames([
			'body' => $body,
		]);

		page_footer();
	}

	public function handle_popup()
	{
		$video_id = (int) $this->request->variable('id', 0);

		$sql = 'SELECT song_id, song_name, artist, video
			FROM ' . $this->breizhcharts_table . '
				WHERE song_id = ' . $video_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$title = $row['song_name'] . ' ' . $this->language->lang('BC_FROM') . ' ' . $row['artist'];
		$this->template->assign_vars([
			'S_IN_VIDEO'	=> true,
			'VIDEO_TITLE' 	=> $title,
			'VIDEO_URL'		=> htmlspecialchars_decode($row['video']),
		]);

		return $this->helper->render('breizhcharts_video_popup.html', $title);
	}

	public function handle_vote()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_vote', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		$song_id = (int) $this->request->variable('id', 0);
		$rate = (int) $this->request->variable('rate', 0);
		$json_response = new json_response;

		// Check if user already voted
		$sql = 'SELECT COUNT(vote_song_id) AS counter
			FROM ' . $this->breizhcharts_voters_table . '
			WHERE vote_song_id = ' . $song_id . '
				AND vote_user_id = ' . $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$counter = (int) $this->db->sql_fetchfield('counter');
		$this->db->sql_freeresult($result);

		if (!$counter)
		{
			// Grab some data from the charts table
			$sql = 'SELECT song_name, artist, song_hot, song_not, average
				FROM ' . $this->breizhcharts_table . '
					WHERE song_id = ' . $song_id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			$song = $row['song_name'];
			$artist = $row['artist'];
			$song_hot = (int) $row['song_hot'];
			$song_not = (int) $row['song_not'];

			// Create array for the voting
			$sql_ary = [
				'vote_user_id'	=> $this->user->data['user_id'],
				'vote_song_id'	=> $song_id,
				'vote_rate'		=> $rate,
			];
			$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_voters_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));

			// Update the charts table
			if ($rate === 1)
			{
				$new_hot = $song_hot + 1;
				$new_not = $song_not;
				$average = $new_hot - $new_not;
				$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . " SET song_hot = $new_hot, average = $average WHERE song_id = $song_id");
			}
			else
			{
				$new_hot = $song_hot;
				$new_not = $song_not + 1;
				$average = $new_hot - $new_not;
				$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . " SET song_not = $new_not, average = $average WHERE song_id = $song_id");
			}

			// Giving points for voting, if UPS is installed and active
			if ($this->functions_charts->points_active() && $this->config['breizhcharts_points_per_vote'] > 0)
			{
				$this->functions_charts->dm_addpoints($this->user->data['user_id'], $this->config['breizhcharts_points_per_vote']);
				$message = $this->language->lang('BC_VOTE_SUCCESS_UPS', $song, $artist, $this->config['breizhcharts_points_per_vote'], $this->config['points_name']);
			}
			else
			{
				$message = $this->language->lang('BC_VOTE_SUCCESS', $song, $artist);
			}

			// Send the response to the browser now
			$json_response->send([
				'sort'		=> 1,
				'id'		=> $song_id,
				'hot'		=> '<img src="' . $this->ext_path . 'images/hot_voted.png" alt="' . $this->language->lang('BC_ALREADY_VOTED') . '" title="' . $this->language->lang('BC_ALREADY_VOTED') . '" />',
				'not'		=> '<img src="' . $this->ext_path . 'images/not_voted.png" alt="' . $this->language->lang('BC_ALREADY_VOTED') . '" title="' . $this->language->lang('BC_ALREADY_VOTED') . '" />',
				'new_hot'	=> $this->language->lang('BC_HOT', $new_hot),
				'new_not'	=> $this->language->lang('BC_NOT', $new_not),
				'average'	=> $this->language->lang('BC_AVERAGE', $average),
				'message'	=> $message,
			]);
		}
		else
		{
			// Send the response to the browser now
			$json_response->send([
				'sort'		=> 0,
				'id'		=> $song_id,
				'message'	=> $this->language->lang('BC_ALREADY_VOTED'),
			]);
		}
	}

	// Check if new song probably already exist
	public function check_song()
	{
		$id = (int) $this->request->variable('id', 0);
		$song = (string) $this->request->variable('song', '', true);
		$artist = (string) $this->request->variable('artist', '', true);

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'song_id',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> $this->db->sql_lower_text('song_name') . ' LIKE ' . $this->functions_charts->get_like($song) . ' AND ' . $this->db->sql_lower_text('artist') . ' LIKE ' . $this->functions_charts->get_like($artist) . ' AND song_id <> ' . $id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Send the response to the browser now
		$json_response = new json_response;
		if (isset($row['song_id']))
		{
			$json_response->send([
				'sort'		=> 2,
				'message'	=> $this->language->lang('BC_ALREADY_EXISTS_ERROR', $song, $artist),
			]);
		}
		else
		{
			$json_response->send([
				'sort'		=> 1,
				'message'	=> $this->language->lang('BC_NO_EXISTS', $song, $artist),
			]);
		}
	}

	public function add_song()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_add', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		if ($this->functions_charts->verify_max_entries())
		{
			trigger_error($this->language->lang('BC_COUNT_ERROR', $this->config['breizhcharts_max_entries']) . $this->language->lang('BC_BACKLINK', '<br/><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
		}
		
		if ($this->request->is_set_post('post'))
		{
			$this->validate_add_song();
		}

		$title_mode = $this->language->lang('BC_ADD_SONG');
		$this->functions_charts->get_template_charts(false);

		$this->template->assign_vars([
			'NAV_ID'			=> 'add',
			'S_ADD_SONG'		=> true,
			'U_EXT_PATH'		=> $this->ext_path_web,
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'S_REQ_1'			=> ($this->config['breizhcharts_required_1']) ? '[*] ' : '',
			'S_REQ_2'			=> ($this->config['breizhcharts_required_2']) ? '[*] ' : '',
			'S_REQ_3'			=> ($this->config['breizhcharts_required_3']) ? '[*] ' : '',
			'S_REQ_4'			=> ($this->config['breizhcharts_required_4']) ? '[*] ' : '',
		]);
		
		$this->functions_charts->create_navigation('add', $title_mode);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $title_mode);

		// Load template
		$this->template->set_filenames([
			'body' => 'breizhcharts_add.html',
		]);

		page_footer();
	}

	private function validate_add_song()
	{
		$data = [
			'song_name'	=> $this->request->variable('song_name', '', true),
			'artist'	=> $this->request->variable('artist', '', true),
			'album'		=> $this->request->variable('album', '', true),
			'year'		=> $this->request->variable('year', ''),
			'picture'	=> $this->request->variable('picture', '', true),
			'website'	=> $this->request->variable('website', '', true),
			'video'		=> $this->request->variable('video', '', true),
		];

		if ($error = $this->functions_charts->verify_chart_before_send($data, 0))
		{
			$message = implode('<br />', $error);
			$message .= '<br />' . $this->language->lang('BC_BACKLINK_ADD', '<br /><a href="#" onclick="history.go(-1);return false;">', '</a>');
			trigger_error($message);
		}
		else
		{
			$data = array_merge($data, [						
				'poster_id'		=> $this->user->data['user_id'],
				'user_points'	=> ($this->functions_charts->points_active()) ? $this->config['breizhcharts_ups_points'] : 0,
				'add_time'		=> time(),
			]);

			$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_table . ' ' . $this->db->sql_build_array('INSERT', $data));
			$this->config->increment('breizhcharts_songs_nb', 1, true);
			$this->config->set('breizhcharts_last_song', time(), true);

			// Announce new songs, if enabled
			if ($this->config['breizhcharts_announce_enable'])
			{
				$this->functions_charts->create_announcement($data['song_name'], $data['artist'], $data['picture']);
			}

			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_ADDED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $data['song_name'], $data['artist']]);
			meta_refresh(3, $this->helper->route('sylver35_breizhcharts_page_music'));
			if ($this->functions_charts->points_active() && $this->config['breizhcharts_ups_points'] > 0)
			{
				$this->functions_charts->dm_addpoints($this->user->data['user_id'], $this->config['breizhcharts_ups_points']);
				trigger_error($this->language->lang('BC_SONG_ADDED_UPS', $this->config['breizhcharts_ups_points'], $this->config['points_name']) . '<br />' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
			}
			else
			{
				trigger_error($this->language->lang('BC_SONG_ADDED') . '<br />' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
			}
		}
	}

	public function edit_song()
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_edit', 'a_breizhcharts_manage']))
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}
		
		$song_id = (int) $this->request->variable('id', 0);
		$start = (int) $this->request->variable('start', 0);
		if ($this->request->is_set_post('post'))
		{
			$this->validate_edit_song($song_id, $start);
		}
		$this->functions_charts->get_template_charts(false);

		$sql = 'SELECT *
			FROM ' . $this->breizhcharts_table . '
				WHERE song_id = ' . (int) $song_id;
		$result = $this->db->sql_query($sql);
		$data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$title_mode = $this->language->lang('BC_EDIT_SONG');
		$this->template->assign_vars([
			'S_EDIT_SONG'		=> true,
			'TITLE_PAGE'		=> $title_mode,
			'CHART_ID'			=> $data['song_id'],
			'CHART_SONG_NAME'	=> $data['song_name'],
			'CHART_ARTIST'		=> $data['artist'],
			'CHART_ALBUM'		=> $data['album'],
			'CHART_YEAR'		=> $data['year'],
			'CHART_PICTURE'		=> $data['picture'],
			'CHART_WEBSITE'		=> $data['website'],
			'CHART_VIDEO'		=> $data['video'],
			'U_EXT_PATH'		=> $this->ext_path_web,
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'S_REQ_1'			=> ($this->config['breizhcharts_required_1']) ? '[*] ' : '',
			'S_REQ_2'			=> ($this->config['breizhcharts_required_3']) ? '[*] ' : '',
			'S_REQ_3'			=> ($this->config['breizhcharts_required_3']) ? '[*] ' : '',
			'S_REQ_4'			=> ($this->config['breizhcharts_required_4']) ? '[*] ' : '',
		]);

		$this->functions_charts->create_navigation('edit', $title_mode, $song_id);

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $title_mode);

		// Load template
		$this->template->set_filenames([
			'body' => 'breizhcharts_add.html',
		]);

		page_footer();
	}

	private function validate_edit_song($song_id, $start)
	{
		$data = [
			'song_name'		=> $this->request->variable('song_name', '', true),
			'artist'		=> $this->request->variable('artist', '', true),
			'album'			=> $this->request->variable('album', '', true),
			'year'			=> $this->request->variable('year', ''),
			'picture'		=> $this->request->variable('picture', '', true),
			'website'		=> $this->request->variable('website', '', true),
			'video'			=> $this->request->variable('video', '', true),
		];

		if ($error = $this->functions_charts->verify_chart_before_send($data, $song_id))
		{
			$message = implode('<br />', $error);
			$message .= '<br />' . $this->language->lang('BC_BACKLINK_EDIT', '<a href="' . $this->helper->route('sylver35_breizhcharts_edit_music', ['id' => $song_id, 'start' => $start]) . '">', '</a>');
			trigger_error($message);
		}
		else
		{
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE song_id = ' . $song_id);

			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_EDITED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $data['song_name'], $data['artist']]);
			$this->cache->destroy('sql', $this->breizhcharts_table);

			$redirect_url = $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list', 'start' => $start]);
			meta_refresh(3, $redirect_url);
			trigger_error($this->language->lang('BC_SONG_EDIT_SUCCESS', $data['song_name']) . '<br /><br />' . $this->language->lang('BC_BACKLINK', '<a href="' . $redirect_url . '">', '</a>'));
		}
	}

	public function delete_song()
	{
		$delete_id = (int) $this->request->variable('id', 0);
		if (confirm_box(true))
		{
			$sql = 'SELECT poster_id, song_name, artist, user_points
				FROM ' . $this->breizhcharts_table . '
					WHERE song_id = ' . $delete_id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$title = $row['song_name'];
			$artist = $row['artist'];
			$poster_id = (int) $row['poster_id'];
			$delete_points = (int) $row['user_points'];
			$this->db->sql_freeresult($result);
			// Delete points, if UPS exists
			if ($this->functions_charts->points_active())
			{
				// Substract the points from the user account
				$sql = 'UPDATE ' . USERS_TABLE . ' SET user_points = user_points - ' . $delete_points . ' WHERE user_id = ' . $poster_id;
				$this->db->sql_query($sql);
			}

			$sql = 'DELETE FROM ' . $this->breizhcharts_table . ' WHERE song_id = ' . $delete_id;
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM ' . $this->breizhcharts_voters_table . ' WHERE vote_song_id = ' . $delete_id;
			$this->db->sql_query($sql);

			$this->config->increment('breizhcharts_songs_nb', -1, true);
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_DELETED', time(), ['reportee_id' => $this->user->data['user_id'], $title, $artist]);

			meta_refresh(3, $this->helper->route('sylver35_breizhcharts_page_music'));
			trigger_error($this->language->lang('BC_DELETE_SUCCESS', $title) . '<br /><br />' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
		}
		else
		{
			if ($this->request->is_set_post('cancel'))
			{
				redirect($this->helper->route('sylver35_breizhcharts_page_music'));
			}
			else
			{
				if ($this->functions_charts->points_active())
				{
					confirm_box(false, $this->language->lang('BC_DELETE_SONG_UPS', $this->config['points_name']), build_hidden_fields([
						'id'		=> $delete_id,
						'action'	=> 'delete',
					]));
				}
				else
				{
					confirm_box(false, $this->language->lang('BC_DELETE_SONG_REGULAR'), build_hidden_fields([
						'id'		=> $delete_id,
						'action'	=> 'delete',
					]));
				}
			}
		}
	}
}
