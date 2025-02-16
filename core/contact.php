<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\points;
use phpbb\language\language;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\request\request;
use phpbb\config\config;

class contact
{
	/** @var \sylver35\breizhcharts\core\points */
	protected $points;

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

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php_ext */
	protected $php_ext;

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
	public function __construct(points $points, language $language, user $user, auth $auth, helper $helper, db $db, request $request, config $config, $root_path, $php_ext, $breizhcharts_table, $breizhcharts_cats_table, $breizhcharts_voters_table)
	{
		$this->points = $points;
		$this->language = $language;
		$this->user = $user;
		$this->auth = $auth;
		$this->helper = $helper;
		$this->db = $db;
		$this->request = $request;
		$this->config = $config;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->breizhcharts_voters_table = $breizhcharts_voters_table;
	}

	public function run_random_winner()
	{
		if (!$this->config['breizhcharts_voters_points'])
		{
			return;
		}
		if (!function_exists('submit_pm'))
		{
			include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
		}

		// Select a random voter to get a bonus, if UPS is enabled and active
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'v.*, u.user_id, u.username, u.user_colour, u.user_lang',
			'FROM'		=> [$this->breizhcharts_voters_table => 'v'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = v.vote_user_id',
				],
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
				// Switch language if needed
				$switch_lang = $this->language_switch($row['user_lang'], false);

				$options = 0;
				$uid = $bitfield = '';
				$text = $this->language->lang('BC_PM_VOTERS_MESSAGE',
					$row['username'],
					$this->colorize($row['user_colour']),
					$this->config['breizhcharts_voters_points'],
					$this->config['points_name'],
				);

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

				// Switch language if needed
				$this->language_switch($row['user_lang'], $switch_lang);
			}
		}
		$this->db->sql_freeresult($result);
	}

	public function send_pm_to_winners($points_active)
	{
		if (!function_exists('submit_pm'))
		{
			include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
		}

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
				$this->colorize($row['user_colour']),
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

	public function send_pm_view_report($id)
	{
		if (!function_exists('submit_pm'))
		{
			include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
		}
		$message_user = $this->request->variable('message-box', '', true);

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'b.*, a.*, u.user_id, u.username, u.user_colour, u.user_lang, v.user_id as id, v.username as name, v.user_colour as colour',
			'FROM'		=> [$this->breizhcharts_table => 'b'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [USERS_TABLE => 'u'],
					'ON'	=> 'u.user_id = b.poster_id',
				],
				[
					'FROM'	=> [USERS_TABLE => 'v'],
					'ON'	=> 'v.user_id = b.reported',
				],
				[
					'FROM'	=> [$this->breizhcharts_cats_table => 'a'],
					'ON'	=> 'a.cat_id = b.cat',
				],
			],
			'WHERE'		=> 'b.song_id = ' . (int) $id,
		]);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Switch language if needed
		$switch_lang = $this->language_switch($row['user_lang'], false);

		$options = 0;
		$uid = $bitfield = '';
		$subject = $this->language->lang('BC_REPORT_SEND_SUBJECT', $row['song_name']);
		$message = $this->language->lang('BC_REPORT_SEND_MESSAGE',
			$row['username'],
			$this->colorize($row['user_colour']),
			$this->user->data['username'],
			$this->colorize($this->user->data['user_colour']),
			$this->language->lang('BC_REPORTED_ON', $row['song_name'], $row['artist']),
			$row['name'],
			$this->colorize($row['colour']),
			$this->user->format_date($row['reported_time'], $this->language->lang('BC_FORMAT_DATE_PM')),
			$this->user->lang['bc_report_reasons']['TITLE'][$row['reason']],
			$this->user->lang['bc_report_reasons']['DESCRIPTION'][$row['reason']],
			$row['reported_text'] ? $row['reported_text'] : '',
			$message_user,
		);

		generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);

		// Switch language if needed
		$this->language_switch($row['user_lang'], $switch_lang);

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
		submit_pm('post', utf8_encode_ucr($subject), $data, false);

		$redirect_url = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? $this->helper->route('sylver35_breizhcharts_list_report') : $this->helper->route('sylver35_breizhcharts_page_music', ['mode' => 'own', 'cat' => 0]) . '#nav';
		$return_msg = $this->auth->acl_gets(['a_breizhcharts_manage', 'm_breizhcharts_manage']) ? 'BC_REPORT_BACKLINK' : 'BC_REPORT_BACKLINK_OWN';
		meta_refresh(3, $redirect_url);
		trigger_error($this->language->lang('BC_REPORT_SEND_FINISH', get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'])) . '<br><br>' . $this->language->lang($return_msg, '<a href="' . $redirect_url . '">', '</a>'));
	}

	public function send_pms_report($id, $song_name, $artist, $poster_id, $poster_name, $poster_colour, $reason, $auto = 0)
	{
		if (!function_exists('submit_pm'))
		{
			include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
		}

		$switch_lang = false;
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $poster_id . ' OR ' . $this->db->sql_in_set('user_id', $this->list_manage());
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			// Switch language if needed
			$switch_lang = $this->language_switch($row['user_lang'], $switch_lang);

			$options = 0;
			$uid = $bitfield = '';
			// Form the complete reason description with auto error if needed
			$description = $this->user->lang['bc_report_reasons']['DESCRIPTION'][$reason];
			$description .= ($auto) ? $this->language->lang('BC_AUTO_RETURN', $auto) . $this->language->lang('BC_AUTO_' . $auto) : '';
			$description .= ($auto == 101) ? $this->language->lang('BC_101_RETURN') . $this->language->lang('BC_AUTO_101') : '';

			$subject = $this->language->lang('BC_PM_REPORT_SUBJECT', $this->user->lang['bc_report_reasons']['TITLE'][$reason]);
			$message = $this->language->lang('BC_PM_REPORT_MESSAGE',
				$row['username'],
				$this->colorize($row['user_colour']),
				(!$auto) ? $this->user->data['username'] : $this->language->lang('BC_AUTO_NAME'),
				(!$auto) ? $this->colorize($this->user->data['user_colour']) : $this->colorize(''),
				$this->user->format_date(time(), $this->language->lang('BC_FORMAT_DATE_PM')),
				$this->language->lang('BC_REPORTED_ON', $song_name, $artist),
				$poster_name,
				$this->colorize($poster_colour),
				$this->user->lang['bc_report_reasons']['DESCRIPTION'][$reason],
				$this->helper->route('sylver35_breizhcharts_reported_video', ['id' => $id]),
			);

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
			submit_pm('post', utf8_encode_ucr($subject), $data, false);

			// Switch language if needed
			$switch_lang = $this->language_switch($row['user_lang'], $switch_lang);
		}
		$this->db->sql_freeresult($result);
	}

	public function send_pm_close($action, $lang, $song, $artist, $reason, $poster_id, $poster_name, $poster_colour, $report_id, $report_name, $report_colour)
	{
		if (!function_exists('submit_pm'))
		{
			include($this->root_path . 'includes/functions_privmsgs.' . $this->php_ext);
		}

		// Switch language if needed
		$switch_lang = $this->language_switch($lang, false);

		$options = 0;
		$uid = $bitfield = '';
		$sort_message = ($action == 'poster') ? 'BC_PM_REPORT_CLOSE' : 'BC_PM_REPORT_CLOSE_TO';
		$reason = $this->user->lang['bc_report_reasons']['TITLE'][$reason];
		$subject = $this->language->lang('BC_PM_REPORT_SUBJECT', $reason);
		$message = $this->language->lang($sort_message,
			$poster_name,
			$this->colorize($poster_colour),
			$this->language->lang('BC_REPORTED_ON', $song, $artist),
			$reason,
			$report_name,
			$this->colorize($report_colour),
			$this->user->data['username'],
			$this->colorize($this->user->data['user_colour']),
			$this->user->format_date(time(), $this->language->lang('BC_FORMAT_DATE_PM')),
		);

		generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);

		$data = [
			'address_list'		=> ['u' => [(($action == 'poster') ? $poster_id : $report_id) => 'to']],
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
		submit_pm('post', utf8_encode_ucr($subject), $data, false);

		// Switch language if needed
		$this->language_switch($lang, $switch_lang);
	}

	private function language_switch($lang_user, $switch_lang)
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

	public function colorize($colour, $name = '')
	{
		$data = $colour;
		if (!$colour && !$name)
		{
			$data = '435B8A';
		}
		else if (!$colour && $name)
		{
			$data = '<span style="color: #435B8A;font-weight: bold;">' . $name . '</span>';
		}

		return $data;
	}

	private function list_manage()
	{
		// Grab an array of user_id's with admin permission
		$admin_ary = $this->auth->acl_get_list(false, 'a_breizhcharts_manage');
		$admin_ary = (!empty($admin_ary[0]['a_breizhcharts_manage'])) ? $admin_ary[0]['a_breizhcharts_manage'] : [];

		// Grab an array of user_id's with moderator permission
		$modo_ary = $this->auth->acl_get_list(false, 'm_breizhcharts_manage');
		$modo_ary = (!empty($modo_ary[0]['m_breizhcharts_manage'])) ? $modo_ary[0]['m_breizhcharts_manage'] : [];

		// Merge the two arrays
		$list_ary = array_unique(array_merge($admin_ary, $modo_ary));

		return $list_ary;
	}
}
