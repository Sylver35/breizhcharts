<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use phpbb\json_response;
use phpbb\exception\http_exception;
use sylver35\breizhcharts\core\charts;
use sylver35\breizhcharts\core\work;
use sylver35\breizhcharts\core\verify;
use sylver35\breizhcharts\core\points;
use sylver35\breizhcharts\core\result;
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
use phpbb\event\dispatcher_interface as phpbb_dispatcher;

class breizhcharts
{
	/** @var \sylver35\breizhcharts\core\charts */
	protected $charts;

	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \sylver35\breizhcharts\core\verify */
	protected $verify;

	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

	/** @var \sylver35\breizhcharts\core\result */
	protected $result;

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
	protected $breizhcharts_voters_table;

	/**
	 * Constructor
	 */
	public function __construct(charts $charts, work $work, verify $verify, points $points, result $result, template $template, language $language, user $user, auth $auth, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, phpbb_dispatcher $phpbb_dispatcher, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_voters_table)
	{
		$this->charts = $charts;
		$this->work = $work;
		$this->verify = $verify;
		$this->points = $points;
		$this->result = $result;
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
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function handle($mode, $userid = 0, $id = 0, $result_id = 0, $start = 0, $cat = 0, $name = '', $song_name = '')
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_view', 'a_breizhcharts_manage', 'm_breizhcharts_manage']))
		{
			throw new http_exception(403, 'BC_NOT_AUTHORISED');
		}

		$data = [
			'rules'			=> true,
			'mode'			=> (string) $mode,
			'name'			=> (string) $name,
			'song_id'		=> (int) $id,
			'userid'		=> (int) $userid,
			'result_id'		=> (int) $result_id,
			'song_name'		=> (string) $song_name,
			'cat'			=> (int) ($cat ? $cat : 0),
			'mobile'		=> $this->verify->session_mobile(),
			'is_user'		=> ($this->user->data['is_registered'] && !$this->user->data['is_bot']),
			'start'			=> $start ? $start : (int) $this->request->variable('start', 0),
			'moderate'		=> $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']),
			'title_mode'	=> $this->language->lang('BC_NEWEST'),
			'url'			=> 'sylver35_breizhcharts_page_music',
			'url_array'		=> ['mode' => 'list_newest', 'cat' => 0],
			'order_by'		=> 'c.song_id DESC',
			'body'			=> 'breizhcharts.html',
			'title_cat'		=> '',
		];

		/**
		 * You can use this event before all modes listed here
		 *
		 * @event breizhcharts.list_mode_before
		 * @var	array
		 * @since 1.1.0
		 */
		$vars = ['data'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.list_mode_before', compact($vars)));

		// Load the data in mode
		$data = $this->initialyse_data($data);
		// Create the navigation
		$this->verify->create_phpbb_navigation($data);
		// Add random songs
		$this->verify->get_random_songs(true);

		/**
		 * You can use this event after all modes listed here
		 *
		 * @event breizhcharts.list_mode_after
		 * @var	array
		 * @since 1.1.0
		 */
		$vars = ['data'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.list_mode_after', compact($vars)));

		// Output the page
		page_header($this->language->lang('BC_CHARTS') . ' - ' . $data['title_mode']);

		// Load charts template
		$this->template->set_filenames([
			'body'	=> $data['body'],
		]);

		page_footer();
	}

	private function initialyse_data($data)
	{
		// Switch the mode
		switch ($data['mode'])
		{
			case 'list_newest':
				$data = array_merge($data, [
					'url_sel'		=> ['mode' => 'list_newest'],
					'url_array'		=> ['mode' => 'list_newest', 'cat' => $data['cat']],
					'title_mode'	=> $this->language->lang('BC_NEWEST'),
				]);
				$data['title_mode'] = $this->charts->get_list_charts($data);
			break;
			case 'list':
				$data = array_merge($data, [
					'url_sel'		=> ['mode' => 'list'],
					'url_array'		=> ['mode' => 'list', 'cat' => $data['cat']],
					'title_mode'	=> $this->language->lang('BC_BEST_RATED'),
					'order_by'		=> 'c.song_note DESC, c.nb_note DESC, c.last_pos ASC, c.best_pos ASC',
				]);
				$data['title_mode'] = $this->charts->get_list_charts($data);
			break;
			case 'own':
				$data = array_merge($data, [
					'url_sel'		=> ['mode' => 'own'],
					'url_array'		=> ['mode' => 'own', 'cat' => $data['cat']],
					'title_mode'	=> $this->language->lang('BC_OWN'),
				]);
				$data['title_mode'] = $this->charts->get_list_charts($data);
			break;
			case 'other':
				$data = array_merge($data, [
					'url'			=> 'sylver35_breizhcharts_page_user',
					'url_sel'		=> ['userid' => $data['userid'], 'name' => $this->work->display_url($data['name'])],
					'url_array'		=> ['userid' => $data['userid'], 'name' => $this->work->display_url($data['name']), 'cat' => $data['cat']],
				]);
				$data['title_mode'] = $this->charts->get_list_charts($data);
			break;
			case 'video':
				$data = $this->charts->display_video($data);
			break;
			case 'result':
				$data = $this->charts->get_winners_charts($data);
			break;
			case 'add_video':
				$data = $this->add_video($data);
			break;
			case 'edit':
				$data = $this->edit_song($data);
			break;
		}

		return $data;
	}

	public function handle_user($name, $userid, $cat)
	{
		$start = $this->request->variable('start', 0);
		$this->handle('other', $userid, 0, 0, $start, $cat, $name, '');
	}

	public function handle_add()
	{
		$this->handle('add_video');
	}

	public function handle_video($id, $song_name)
	{
		$this->handle('video', 0, $id, 0, 0, 0, '', $song_name);
	}

	public function handle_edit($id, $start, $cat)
	{
		$this->handle('edit', 0, $id, 0, $start, $cat, '', '');
	}

	public function get_result()
	{
		$result_id = $this->request->variable('result_id', 0);
		$this->handle('result', 0, 0, $result_id);
	}

	public function display_popup($id)
	{
		$sql = 'SELECT song_id, song_name, artist, video, song_view, reported
			FROM ' . $this->breizhcharts_table . '
				WHERE song_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'S_IN_VIDEO'		=> true,
			'S_REPORTED'		=> $row['reported'],
			'SONG_VIEW'			=> $row['song_view'],
			'TITLE_SONG_VIEW'	=> $this->language->lang('BC_SONG_VIEW', (int) $row['song_view']),
			'VIDEO_TITLE' 		=> $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']),
			'YOUTUBE_ID'		=> $this->work->get_youtube_id($row['video']),
			'U_VIEW_SONG'		=> $this->helper->route('sylver35_breizhcharts_song_view', ['id' => $row['song_id'], 'song_view' => $row['song_view']]),
			'U_REPORT_AUTO'		=> $this->helper->route('sylver35_breizhcharts_report_video_auto', ['id' => $row['song_id']]),
		]);

		return $this->helper->render('breizhcharts_video_popup.html', $this->language->lang('BC_CHARTS') . ' - ' . $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']));
	}

	private function add_video($data)
	{
		if (!$this->auth->acl_gets(['u_breizhcharts_add', 'a_breizhcharts_manage', 'm_breizhcharts_manage']))
		{
			throw new http_exception(403, 'BC_SONG_ADD_NO');
		}

		$action = $this->request->variable('action', '');
		$data_video = [
			'song_name'		=> $this->request->variable('song_name', '', true),
			'artist'		=> $this->request->variable('artist', '', true),
			'album'			=> $this->request->variable('album', '', true),
			'year'			=> $this->request->variable('year', ''),
			'cat'			=> $this->request->variable('cat', 0),
			'video'			=> $this->request->variable('video', '', true),
			'comment'		=> $this->request->variable('comment', '', true),
			'poster_id'		=> $this->user->data['user_id'],
			'add_time'		=> time(),
			'topic_id'		=> 0,
			'reason'		=> '',
			'reported_text'	=> '',
		];

		if ($action === 'validate_add')
		{
			$error = $this->verify->verify_chart_before_send($data_video, 0);
			if (!count($error))
			{
				$this->validate_add_video($data_video);
			}
		}

		$error = isset($error) ? $error : [];
		$error = $this->verify->verify_max_entries($error);
		$this->charts->get_template_charts(false);

		$this->template->assign_vars([
			'NAV_ID'			=> 'add_video',
			'S_ADD_SONG'		=> true,
			'BC_ERROR'			=> (count($error)) ? implode('<br>', $error) : '',
			'TITLE_PAGE'		=> $this->language->lang('BC_ADD_SONG'),
			'CHART_SONG_NAME'	=> $data_video['song_name'],
			'CHART_ARTIST'		=> $data_video['artist'],
			'CHART_ALBUM'		=> $data_video['album'],
			'CHART_YEAR'		=> $data_video['year'],
			'CHART_VIDEO'		=> $data_video['video'],
			'SELECT_CATS'		=> $this->work->get_cats_select($data_video['cat']),
			'CHART_COMMENT'		=> !$this->config['breizhcharts_announce_enable'] ?: $data_video['comment'],
			'S_REQ_1'			=> $this->config['breizhcharts_required_1'],
			'S_REQ_2'			=> $this->config['breizhcharts_required_2'],
			'S_COMMENT'			=> $this->config['breizhcharts_announce_enable'] && $this->config['breizhcharts_song_forum'],
			'U_EXT_PATH'		=> $this->ext_path_web,
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'U_CHECK_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_check_video', ['check' => 1, 'song_id' => 0]),
			'S_POST_ACTION'		=> $this->helper->route('sylver35_breizhcharts_add_video') . '?action=validate_add',
		]);

		$data = array_merge($data, [
			'rules'			=> false,
			'url'			=> 'sylver35_breizhcharts_add_video',
			'body'			=> 'breizhcharts_add.html',
			'title_mode'	=> $this->language->lang('BC_ADD_SONG'),
		]);

		return $data;
	}

	private function validate_add_video($data_video)
	{
		// Announce new songs, if enabled, create topic
		$url = '';
		if ($this->config['breizhcharts_announce_enable'])
		{
			$comment = isset($data_video['comment']) ? $data_video['comment'] : '';
			$url = str_replace('./../', './', $this->result->create_topic($data_video['song_name'], $data_video['artist'], $data_video['video'], $data_video['cat'], $comment));
			$data_video['topic_id'] = (int) $this->charts->find_string($url . '#', 't=', '#');
		}
		unset($data_video['comment']);

		$this->db->sql_query('UPDATE ' . USERS_TABLE . ' SET breizhchart_last = ' . time() . ' WHERE user_id = ' . (int) $this->user->data['user_id']);
		$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_table . $this->db->sql_build_array('INSERT', $data_video));
		$id = $this->db->sql_last_inserted_id();
		$data_video['song_id'] = $id;

		$this->config->increment('breizhcharts_songs_nb', 1, true);
		$this->config->set('breizhcharts_last_song', time(), true);
		$this->verify->update_song_cat('add', $data_video['cat']);
		$this->cache->destroy('_breizhcharts_positions');
		$this->cache->destroy('_breizhcharts_uploaders');

		/**
		 * You can use this event when a song is added
		 *
		 * @event breizhcharts.add_song_after
		 * @var	array
		 * @since 1.1.0
		 */
		$vars = ['data_video', 'id', 'url'];
		extract($this->phpbb_dispatcher->trigger_event('breizhcharts.add_song_after', compact($vars)));

		$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_ADDED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $data_video['song_name'], $data_video['artist'])]);
		$return = $this->points->get_message_return($id, $this->work->display_url($data_video['song_name']));
		meta_refresh(3, $return['url']);
		trigger_error($return['message']);
	}

	private function edit_song($data)
	{
		if (!$this->auth->acl_get('u_breizhcharts_edit') && !$data['moderate'])
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		$action = $this->request->variable('action', '');
		//if ($this->request->is_set_post('post'))
		if ($action === 'validate_edit')
		{
			$error = $this->validate_edit_song($data['song_id'], $data['start'], $data['cat']);
		}

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'b.*, c.cat_nb',
			'FROM'		=> [$this->breizhcharts_table => 'b'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [$this->breizhcharts_cats_table => 'c'],
					'ON'	=> 'c.cat_id = b.cat',
				],
			],
			'WHERE'		=> 'b.song_id = ' . (int) $data['song_id'],
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (((int) $this->user->data['user_id'] !== (int) $row['poster_id']))
		{
			if (!$data['moderate'])
			{
				throw new http_exception(403, 'NOT_AUTHORISED');
			}
		}

		$this->charts->get_template_charts(false);
		$this->template->assign_vars([
			'S_EDIT_SONG'		=> true,
			'ERROR'				=> isset($error) ? implode('<br>', $error) : '',
			'TITLE_PAGE'		=> $this->language->lang('BC_EDIT_SONG') . ' : ' . $row['song_name'],
			'CHART_ID'			=> $row['song_id'],
			'CHART_SONG_NAME'	=> $row['song_name'],
			'CHART_ARTIST'		=> $row['artist'],
			'CHART_ALBUM'		=> $row['album'],
			'CHART_YEAR'		=> $row['year'],
			'CHART_VIDEO'		=> $row['video'],
			'CHART_CAT'			=> $row['cat'],
			'CAT_NB'			=> $row['cat_nb'] ? $row['cat_nb'] : 0,
			'SELECT_CATS'		=> $this->work->get_cats_select($row['cat']),
			'S_REQ_1'			=> $this->config['breizhcharts_required_1'],
			'S_REQ_2'			=> $this->config['breizhcharts_required_2'],
			'U_EXT_PATH'		=> $this->ext_path_web,
			'U_CHECK_SONG'		=> $this->helper->route('sylver35_breizhcharts_check_song'),
			'U_CHECK_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_check_video', ['check' => 0, 'song_id' => $row['song_id']]),
			'S_POST_ACTION'		=> $this->helper->route('sylver35_breizhcharts_edit_video', ['id' => $row['song_id'], 'start' => $data['start'], 'cat' => $data['cat']]) . '?action=validate_edit',
		]);

		$data = array_merge($data, [
			'rules'			=> false,
			'url'			=> 'sylver35_breizhcharts_edit_video',
			'url_array'		=> ['id' => $data['song_id'], 'start' => $data['start'], 'cat' => $data['cat']],
			'body'			=> 'breizhcharts_add.html',
			'title_mode'	=> $this->language->lang('BC_EDIT_SONG'),
		]);

		return $data;
	}

	private function validate_edit_song($id, $start, $cat)
	{
		$data_video = [
			'song_name'		=> $this->request->variable('song_name', '', true),
			'artist'		=> $this->request->variable('artist', '', true),
			'album'			=> $this->request->variable('album', '', true),
			'year'			=> $this->request->variable('year', ''),
			'cat'			=> $this->request->variable('cat', 0),
			'video'			=> $this->request->variable('video', '', true),
		];
		$ex_cat = $this->request->variable('ex_cat', 0);
		$ex_cat_nb = $this->request->variable('ex_cat_nb', 0);

		$error = $this->verify->verify_chart_before_send($data_video, $id);
		if (count($error))
		{
			return $error;
		}
		else
		{
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data_video) . ' WHERE song_id = ' . (int) $id);

			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_EDITED_SONG', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $data_video['song_name'], $data_video['artist'])]);
			$this->cache->destroy('sql', $this->breizhcharts_table);
			$this->verify->update_song_cat('update', $data_video['cat'], $ex_cat, $ex_cat_nb);

			$redirect_url = $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => $cat, 'start' => $start]);
			meta_refresh(3, $redirect_url);
			trigger_error($this->language->lang('BC_SONG_EDIT_SUCCESS', $data_video['song_name']) . '<br><br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $redirect_url . '">', '</a>'));
		}
	}

	public function delete_song($id)
	{
		if (confirm_box(true))
		{
			$sql = 'SELECT poster_id, song_name, artist, topic_id, cat, reported
				FROM ' . $this->breizhcharts_table . '
					WHERE song_id = ' . (int) $id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$poster = (int) $row['poster_id'];
			$title = (string) $row['song_name'];
			$artist = (string) $row['artist'];
			$cat = (int) $row['cat'];
			$topic_id = (int) $row['topic_id'];
			$reported = (int) $row['reported'];
			$this->db->sql_freeresult($result);

			if (((int) $this->user->data['user_id'] !== $poster))
			{
				if (!$this->auth->acl_gets(['u_breizhcharts_delete', 'a_breizhcharts_manage', 'm_breizhcharts_manage']))
				{
					throw new http_exception(403, 'NOT_AUTHORISED');
				}
			}

			$this->db->sql_query('DELETE FROM ' . $this->breizhcharts_table . ' WHERE song_id = ' . (int) $id);
			$this->db->sql_query('DELETE FROM ' . $this->breizhcharts_voters_table . ' WHERE vote_song_id = ' . (int) $id);
			// Update the cat table
			$this->verify->update_song_cat('delete', $cat);
			// Refresh the cache positions
			$this->cache->destroy('_breizhcharts_positions');
			if ($reported)
			{
				// Refresh the reports if needed
				$this->cache->destroy('_breizhcharts_reported');
			}
			$this->cache->destroy('sql', $this->breizhcharts_table);
			$this->config->increment('breizhcharts_songs_nb', -1, true);

			if ($topic_id)
			{
				include_once($this->root_path . 'includes/functions_admin.' . $this->php_ext);
				delete_topics('topic_id', [$topic_id], false);
				// Resync topics_posted table
				$topic_ids[] = (int) $topic_id;
				update_posted_info($topic_ids);
			}

			$this->log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CHART_DELETED', time(), ['reportee_id' => $this->user->data['user_id'], $this->language->lang('BC_FROM_OF', $title, $artist)]);
			meta_refresh(3, $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => 0]));
			trigger_error($this->language->lang('BC_DELETE_SUCCESS', $title) . '<br><br>' . $this->language->lang('BC_BACKLINK', '<a href="' . $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => 0]) . '">', '</a>'));
		}
		else
		{
			if ($this->request->is_set_post('cancel'))
			{
				redirect($this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => 0]));
			}
			else
			{
				confirm_box(false, $this->language->lang('BC_DELETE_SONG_EXPLAIN'), build_hidden_fields([
					'id'		=> $id,
					'action'	=> 'delete',
				]));
			}
		}
	}
}
