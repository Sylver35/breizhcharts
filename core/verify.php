<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\work;
use phpbb\user;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\controller\helper;
use phpbb\cache\service as cache;
use phpbb\db\driver\driver_interface as db;
use phpbb\config\config;

class verify
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

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
	public function __construct(work $work, user $user, language $language, template $template, helper $helper, cache $cache, db $db, config $config, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_voters_table)
	{
		$this->work = $work;
		$this->user = $user;
		$this->language = $language;
		$this->template = $template;
		$this->helper = $helper;
		$this->cache = $cache;
		$this->db = $db;
		$this->config = $config;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
	}

	public function verify_chart_before_send($data, $id)
	{
		$i = 1;
		$error = [];
		$list = [
			'album'		=> 'BC_REQUIRED_ALBUM_ERROR',
			'year'		=> 'BC_REQUIRED_YEAR_ERROR',
			'song_name'	=> 'BC_TITLE_ERROR',
			'artist'	=> 'BC_ARTIST_ERROR',
			'cat'		=> 'BC_REQUIRED_CAT_ERROR',
			'video'		=> 'BC_REQUIRED_VIDEO_ERROR',
		];

		// Check if new song probably already exist
		if ($this->verify_song_name($id, $data['song_name'], $data['artist']) !== false)
		{
			$error['song'] = $this->language->lang('BC_ALREADY_EXISTS_ERROR', $data['song_name'], $data['artist']);
		}

		// Check if video probably already exist
		$video = $this->verify_url_in_db($data['video'], $id);
		if (isset($video['name']))
		{
			$error['video'] = $this->language->lang('BC_ALREADY_EXISTS_SIMPLE', $video['name']);
		}

		foreach ($list as $key => $lang)
		{
			if ($i < 3)
			{
				if (empty($data[$key]) && $this->config['breizhcharts_required_' . $i])
				{
					$error[$data[$key]] = $this->language->lang($lang);
				}
			}
			else if (empty($data[$key]))
			{
				$error[$data[$key]] = $this->language->lang($lang);
			}
			$i++;
		}

		return $error;
	}

	public function verify_max_entries($error)
	{
		if ($this->config['breizhcharts_max_entries'] > 0)
		{
			if ($this->config['breizhcharts_songs_nb'] > $this->config['breizhcharts_max_entries'])
			{
				$error['entries'] = $this->language->lang('BC_COUNT_ERROR', $this->config['breizhcharts_max_entries']);
			}
		}

		return $error;
	}

	public function verify_song_name($id, $song, $artist)
	{
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'song_id',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> $this->db->sql_lower_text('song_name') . ' LIKE ' . $this->work->get_like($song) . ' AND ' . $this->db->sql_lower_text('artist') . ' LIKE ' . $this->work->get_like($artist) . ' AND song_id <> ' . (int) $id,
		]);
		$result = $this->db->sql_query($sql);
		$song_exist = (bool) $this->db->sql_fetchfield('song_id');
		$this->db->sql_freeresult($result);

		return $song_exist;
	}

	public function build_data_in_mode($data)
	{
		$sql_where = $data['cat'] ? ' AND c.cat = ' . (int) $data['cat'] : '';
		$sql_where_2 = $data['cat'] ? ' AND cat = ' . (int) $data['cat'] : '';
		switch ($data['mode'])
		{
			case 'own':
				$data = array_merge($data, [
					'where'		=> 'c.poster_id = ' . (int) $this->user->data['user_id'] . $sql_where,
					'select'	=> ' WHERE poster_id = ' . (int) $this->user->data['user_id'] . $sql_where_2,
					'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $data['mode'], 'cat' => $data['cat']]),
				]);
			break;
			case 'other':
				$data = array_merge($data, [
					'where'		=> 'c.poster_id = ' . $data['userid'] . $sql_where,
					'select'	=> ' WHERE poster_id = ' . $data['userid'] . $sql_where_2,
					'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_user', ['userid' => $data['userid'], 'cat' => $data['cat'], 'name' => $this->work->display_url($data['name'])]),
				]);
			break;
			default:
				$data = array_merge($data, [
					'where'		=> $data['cat'] ? 'c.cat = ' . (int) $data['cat'] : '',
					'select'	=> $data['cat'] ? ' WHERE cat = ' . (int) $data['cat'] : '',
					'pagin'		=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => $data['mode'], 'cat' => $data['cat']]),
				]);
		}

		return $data;
	}

	public function get_voters()
	{
		$list = $ids = [];
		if ($voters = $this->get_voters_cache())
		{
			for ($i = 0, $nb = count($voters); $i < $nb; $i++)
			{
				$ids[] = $voters[$i]['user_id'];
				$list[] = $this->work->get_username_song($voters[$i]['user_id'], $voters[$i]['username'], $voters[$i]['user_colour']);
			}

			$this->template->assign_vars([
				'S_USERS_VOTED'		=> true,
				'VOTED_USERS'		=> $this->language->lang('BC_VOTED_USERS', $i),
				'LIST_USER_VOTED'	=> implode(', ', $list),
				'LIST_IDS'			=> implode(', ', $ids),
			]);
		}
	}

	private function get_voters_cache()
	{
		if (($voters = $this->cache->get('_breizhcharts_voters')) === false)
		{
			$i = 0;
			$voters = [];
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'u.user_id, u.username, u.user_colour',
				'FROM'		=> [$this->breizhcharts_voters_table => 'v'],
				'LEFT_JOIN'	=> [
					[
						'FROM'	=> [USERS_TABLE => 'u'],
						'ON'	=> 'u.user_id = v.vote_user_id',
					],
				],
				'GROUP_BY'	=> 'v.vote_user_id, u.user_id, u.username, u.user_colour',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$voters[$i] = [
					'user_id'		=> $row['user_id'],
					'username'		=> $row['username'],
					'user_colour'	=> $row['user_colour'],
				];
				$i++;
			}
			$this->db->sql_freeresult($result);

			// cache for 7 days
			$this->cache->put('_breizhcharts_voters', $voters, 604800);
		}

		return $voters;
	}

	public function get_uploaders()
	{
		$list = $ids = [];
		if ($uploaders = $this->get_uploaders_cache())
		{
			for ($i = 0, $nb = count($uploaders); $i < $nb; $i++)
			{
				$ids[] = $uploaders[$i]['user_id'];
				$list[] = $this->work->get_username_song($uploaders[$i]['user_id'], $uploaders[$i]['username'], $uploaders[$i]['user_colour']);
			}

			$this->template->assign_vars([
				'S_UPLOADERS'		=> true,
				'UPLOADERS'			=> $this->language->lang('BC_UPLOADERS', $i),
				'LIST_UPLOADERS'	=> implode(', ', $list),
				'LIST_UPS'			=> implode(', ', $ids),
			]);
		}
	}

	private function get_uploaders_cache()
	{
		if (($uploaders = $this->cache->get('_breizhcharts_uploaders')) === false)
		{
			$i = 0;
			$uploaders = [];
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'b.poster_id, u.user_id, u.username, u.user_colour',
				'FROM'		=> [$this->breizhcharts_table => 'b'],
				'LEFT_JOIN'	=> [
					[
						'FROM'	=> [USERS_TABLE => 'u'],
						'ON'	=> 'u.user_id = b.poster_id',
					],
				],
				'GROUP_BY'	=> 'b.poster_id, u.user_id, u.username, u.user_colour',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$uploaders[$i] = [
					'user_id'		=> $row['user_id'],
					'username'		=> $row['username'],
					'user_colour'	=> $row['user_colour'],
				];
				$i++;
			}
			$this->db->sql_freeresult($result);

			// cache for 7 days
			$this->cache->put('_breizhcharts_uploaders', $uploaders, 604800);
		}

		return $uploaders;
	}

	public function create_phpbb_navigation($data)
	{
		$nav_name = $this->language->lang('BC_CHARTS_SIMPLE');
		$param = isset($data['url_param']) ? $data['url_param'] : '';
		$param2 = isset($data['url_param2']) ? $data['url_param2'] : '';

		$links = [
			$nav_name			=> $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'list_newest', 'cat' => 0]),
			$data['title_mode']	=> $this->helper->route($data['url'], isset($data['url_array']) ? $data['url_array'] : []) . $param,
		];

		if (isset($data['title_mode2']))
		{
			$links = array_merge($links, [
				$data['title_mode2']	=> $this->helper->route($data['url2'], isset($data['url_array2']) ? $data['url_array2'] : []) . $param2,
			]);
		}

		foreach ($links as $name => $link)
		{
			$this->template->assign_block_vars('navlinks', [
				'BREADCRUMB_NAME'	=> $name,
				'U_BREADCRUMB'		=> $link,
			]);
		}
	}

	public function update_song_cat($action, $cat, $ex_cat = 0, $ex_cat_nb = 0)
	{
		switch ($action)
		{
			case 'delete':
				if ($cat)
				{
					$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . ' SET cat_nb = cat_nb - 1 WHERE cat_id = ' . (int) $cat);
				}
			break;

			case 'add':
				$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . ' SET cat_nb = cat_nb + 1 WHERE cat_id = ' . (int) $cat);
			break;

			case 'update':
				if ((int) $ex_cat !== (int) $cat)
				{
					if ($ex_cat && ((int) $ex_cat_nb !== 0))
					{
						$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . ' SET cat_nb = cat_nb - 1 WHERE cat_id = ' . (int) $ex_cat);
					}
					$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . ' SET cat_nb = cat_nb + 1 WHERE cat_id = ' . (int) $cat);
				}
			break;
		}
		$this->cache->destroy('_breizhcharts_cats');
	}

	public function verify_url_in_db($url, $song_id)
	{
		$video = [0];
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> '*',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> 'song_id <> ' . (int) $song_id . ' AND ' . $this->db->sql_lower_text('video') . ' LIKE ' . $this->work->get_like($url),
		]);
		$result = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($result))
		{
			$video = [
				'name'		=> $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']),
				'time'		=> $this->language->lang('BC_ADDED_TIME', $this->user->format_date($row['add_time'])),
				'image'		=> $this->work->get_youtube_img($row['video'], true),
				'url'		=> $this->helper->route('sylver35_breizhcharts_video', ['id' => (int) $row['song_id'], 'song_name' => $this->work->display_url($row['song_name'])]) . '#nav',
			];
		}
		$this->db->sql_freeresult($result);

		return $video;
	}

	public function get_random_song($in_chart = false)
	{
		if ($in_chart && !$this->config['breizhcharts_random'])
		{
			return;
		}

		$i = 1;
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'c.*, u.user_id, u.username, u.user_colour',
			'FROM'		=> [$this->breizhcharts_table => 'c'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = c.poster_id',
				],
			],
			'WHERE'		=> 'c.reported = 0',
			'ORDER_BY'	=> 'RAND()',
		]);
		$result = $this->db->sql_query_limit($sql, 2);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('randoms', [
				'RAND_NB'			=> $i,
				'RAND_SONG_ID'		=> $row['song_id'],
				'RAND_NAME'			=> $row['song_name'],
				'RAND_ARTIST'		=> $row['artist'],
				'RAND_ALBUM'		=> $row['album'],
				'RAND_YEAR'			=> $row['year'],
				'RAND_CAT'			=> $this->work->get_cat_name($row['cat']),
				'RAND_THUMBNAIL'	=> $this->work->get_youtube_img($row['video'], true),
				'RAND_TITLE' 		=> $this->language->lang('BC_FROM_OF', $row['song_name'], $row['artist']),
				'RAND_VIEW'			=> $this->language->lang('BC_SONG_VIEW', (int) $row['song_view']),
				'RAND_USERNAME'		=> $this->work->get_username_song($row['user_id'], $row['username'], $row['user_colour']),
				'TOTAL_RATE'		=> $this->language->lang('BC_AJAX_NOTE_TOTAL', number_format($row['song_note'], 2)),
				'U_RAND_VIDEO'		=> $this->helper->route('sylver35_breizhcharts_video', ['id' => (int) $row['song_id'], 'song_name' => $this->work->display_url($row['song_name'])]) . '#nav',
			]);
			$i++;
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			'S_RANDOM_SONG'		=> $i > 1,
			'RANDOM_INDEX'		=> $this->config['breizhcharts_random_index'],
		]);
	}

	public function session_mobile()
	{
		$browser = strtolower($this->user->browser);

		if (!empty($browser))
		{
			if (preg_match("#ipad|tablet#i", $browser))
			{
				return false;
			}
			else if (preg_match("#mobile|android|iphone|mobi|ipod|fennec|webos|j2me|midp|cdc|cdlc|bada#i", $browser))
			{
				return true;
			}
		}

		return false;
	}
}
