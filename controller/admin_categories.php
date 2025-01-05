<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\controller;

use phpbb\template\template;
use phpbb\language\language;
use phpbb\user;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\pagination;
use phpbb\log\log;
use phpbb\cache\service as cache;
use phpbb\request\request;
use phpbb\config\config;
use phpbb\extension\manager as ext_manager;
use phpbb\path_helper;

class admin_categories
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

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
	public function __construct(template $template, language $language, user $user, helper $helper, db $db, pagination $pagination, log $log, cache $cache, request $request, config $config, ext_manager $ext_manager, path_helper $path_helper, $breizhcharts_table, $breizhcharts_cats_table)
	{
		$this->template = $template;
		$this->language = $language;
		$this->user = $user;
		$this->helper = $helper;
		$this->db = $db;
		$this->pagination = $pagination;
		$this->log = $log;
		$this->cache = $cache;
		$this->request = $request;
		$this->config = $config;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->breizhcharts_table = $breizhcharts_table;
		$this->breizhcharts_cats_table = $breizhcharts_cats_table;
		$this->ext_path = $this->ext_manager->get_extension_path('sylver35/breizhcharts', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function acp_categories()
	{
		add_form_key('acp_categories');
		$action = $this->request->variable('action', '');

		if ($action)
		{
			$cat_id	= $this->request->variable('cat_id', 0);
			$update	= $this->request->variable('update', 0);

			switch ($action)
			{
				case 'edit':
					$this->action_edit($cat_id, $update);
				break;

				case 'add':
					$this->action_add($update);
				break;

				case 'delete':
					$this->action_delete($cat_id);
				break;
			}
		}
		else
		{
			$i = 0;
			$order_max = (int) $this->get_max_order();
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'cat_id, cat_order, cat_name, cat_nb',
				'FROM'		=> [$this->breizhcharts_cats_table => 'c'],
				'ORDER_BY'	=> 'cat_order ASC',
			]);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->template->assign_block_vars('categories', [
					'CAT_ORDER'		=> $i == 0,
					'S_ROW_COUNT'	=> $i,
					'CAT_ID'		=> $row['cat_id'],
					'POSITION'		=> $row['cat_order'],
					'CAT_NAME'		=> $row['cat_name'],
					'CAT_NB'		=> $row['cat_nb'],
					'ROW_MAX'		=> $row['cat_order'] == $order_max,
					'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;cat_id=' . $row['cat_id'],
					'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;cat_id=' . $row['cat_id'],
				]);
				$i++;
			}
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'S_MANAGE_CATS'			=> true,
				'CAT_TITLE'				=> $this->language->lang('BC_CAT_TITLE'),
				'CAT_TITLE_EXPLAIN'		=> $this->language->lang('BC_CATEGORIES_EXPLAIN') . $this->language->lang('COLON') . ' ' . $this->language->lang('BC_SELECT_ORDER'),
				'TOTAL_CATS'			=> $this->language->lang('BC_CAT_TOTAL', $i),
				'BC_CHARTS_VERSION'		=> $this->config['breizhcharts_version'],
				'U_MOVE_CATS'			=> $this->helper->route('sylver35_breizhcharts_ajax_cats'),
				'U_ACTION_CONFIG'		=> $this->u_action . '&amp;action=config_cat',
				'U_ADD'					=> $this->u_action . '&amp;action=add',
				'U_ACTION'				=> $this->u_action,
			]);
		}
	}

	private function action_edit($cat_id, $update)
	{
		if ($update)
		{
			if (!check_form_key('acp_categories'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$data = [
				'cat_name'	=> $this->request->variable('cat_name', '', true),
				'cat_nb'	=> $this->request->variable('cat_nb', 0),
			];

			$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE cat_id = ' . (int) $cat_id);
			$this->cache->destroy('_breizhcharts_cats');
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CAT_UPDATED', time(), [$data['cat_name']]);
			trigger_error($this->language->lang('BC_CAT_EDIT_OK', $data['cat_name']) . adm_back_link($this->u_action));
		}
		else
		{
			$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'c.*',
				'FROM'		=> [$this->breizhcharts_cats_table => 'c'],
				'WHERE'		=> 'cat_id = ' . (int) $cat_id,
			]);
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);

			$this->template->assign_vars([
				'S_EDIT_CAT'			=> true,
				'CAT_TITLE'				=> $this->language->lang('BC_CAT_TITLE'),
				'CAT_TITLE_EXPLAIN'		=> $this->language->lang('BC_CAT_EDIT', $row['cat_name']),
				'CAT_ID'				=> $row['cat_id'],
				'CAT_NAME'				=> $row['cat_name'],
				'CAT_NB'				=> $row['cat_nb'],
				'BC_CHARTS_VERSION'		=> $this->config['breizhcharts_version'],
				'U_ACTION_FORM'			=> $this->u_action . '&amp;action=edit&amp;update=1&amp;cat_id=' . $cat_id,
				'U_BACK'				=> $this->u_action,
			]);
			$this->db->sql_freeresult($result);
		}
	}

	private function action_add($update)
	{
		if ($update)
		{
			if (!check_form_key('acp_categories'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$max_order = $this->get_max_order();
			$data = [
				'cat_order'	=> $max_order + 1,
				'cat_name'	=> $this->request->variable('cat_name', '', true),
				'cat_nb'	=> 0,
			];

			$this->db->sql_query('INSERT INTO ' . $this->breizhcharts_cats_table . $this->db->sql_build_array('INSERT', $data));
			$this->cache->destroy('_breizhcharts_cats');
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CAT_ADDED', time(), [$data['cat_name']]);
			trigger_error($this->language->lang('BC_CAT_ADD_OK', $data['cat_name']) . adm_back_link($this->u_action));
		}
		else
		{
			$this->template->assign_vars([
				'S_ADD_CAT'				=> true,
				'CAT_TITLE'				=> $this->language->lang('BC_CAT_TITLE'),
				'CAT_TITLE_EXPLAIN'		=> $this->language->lang('BC_CAT_ADD'),
				'CAT_NAME'				=> $this->request->variable('cat_name', '', true),
				'BC_CHARTS_VERSION'		=> $this->config['breizhcharts_version'],
				'U_ACTION_FORM'			=> $this->u_action . '&amp;action=add&amp;update=1',
				'U_BACK'				=> $this->u_action,
			]);
		}
	}

	private function action_delete($cat_id)
	{
		if (confirm_box(true))
		{
			$sql = 'SELECT cat_id, cat_order, cat_name, cat_nb
				FROM ' . $this->breizhcharts_cats_table . '
					WHERE cat_id = ' . (int) $cat_id;
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$cat_name = (string) $row['cat_name'];
			$cat_order = (int) $row['cat_order'];
			$cat_nb = (int) $row['cat_nb'];
			$this->db->sql_freeresult($result);

			$this->db->sql_query('DELETE FROM ' . $this->breizhcharts_cats_table . ' WHERE cat_id = ' . (int) $cat_id);

			if ($cat_order !== $this->get_max_order())
			{
				$i = 0;
				$list = [];
				$sql = 'SELECT cat_id, cat_order
					FROM ' . $this->breizhcharts_cats_table . '
						WHERE cat_order > ' . (int) $cat_order;
				$result = $this->db->sql_query($sql);
				while ($row = $this->db->sql_fetchrow($result))
				{
					$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . ' SET cat_order = cat_order - 1 WHERE cat_id = ' . (int) $row['cat_id']);
				}
				$i++;
			}

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_CAT_DELETED', time(), [$cat_name]);
			$this->cache->destroy('_breizhcharts_cats');
			$message = $this->language->lang('BC_CAT_DELETE_OK', $cat_name);
			$message .= $cat_nb ? $this->language->lang('BC_CAT_DELETE_NB', $cat_nb) : '';
			trigger_error($message . adm_back_link($this->u_action));
		}
		else
		{
			confirm_box(false, $this->language->lang('BC_REALLY_DELETE'), build_hidden_fields([
				'cat_id'		=> $cat_id,
				'action'		=> 'delete',
			]));
		}
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function ajax_cats()
	{
		$action = (string) $this->request->variable('action', '');
		$cat_id = (int) $this->request->variable('cat_id', 0);

		$i = 0;
		$list_cat = [];
		$this->move_cat($action, $cat_id);
		$max_order = (int) $this->get_max_order();

		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'cat_id, cat_order, cat_name, cat_nb',
			'FROM'		=> [$this->breizhcharts_cats_table => 'c'],
			'ORDER_BY'	=> 'cat_order ASC',
		]);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$list_cat[$i] = [
				'firstOrder'	=> $i == 0,
				'rowCount'		=> ($i % 2 == 0) ? 'row1' : 'row2',
				'catId'			=> $row['cat_id'],
				'catOrder'		=> $row['cat_order'],
				'catName'		=> $row['cat_name'],
				'catNb'			=> $row['cat_nb'],
				'rowMax'		=> (int) $row['cat_order'] === $max_order,
				'uEdit'			=> '&amp;action=edit&amp;id=' . $row['cat_id'],
				'uDelete'		=> '&amp;action=delete&amp;id=' . $row['cat_id'],
			];
			$i++;
		}
		$this->db->sql_freeresult($result);

		$json_response = new \phpbb\json_response;
		$json_response->send([
			'total'	=> $i,
			'datas'	=> $list_cat,
		]);
	}

	private function move_cat($action, $cat_id)
	{
		// Get current order id and title...
		$sql = 'SELECT cat_order, cat_name
			FROM ' . $this->breizhcharts_cats_table . '
				WHERE cat_id = ' . (int) $cat_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$current_order = (int) $row['cat_order'];
		$cat_name = $row['cat_name'];
		$this->db->sql_freeresult($result);

		$switch_order_id = (int) $this->set_order($action, $current_order);
		if ($switch_order_id === 0)
		{
			return;
		}

		$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . " SET cat_order = $current_order WHERE cat_order = $switch_order_id AND cat_id <> $cat_id");
		$move_executed = (bool) $this->db->sql_affectedrows();

		// Only update the other entry too if the previous entry got updated
		if ($move_executed)
		{
			$this->db->sql_query('UPDATE ' . $this->breizhcharts_cats_table . " SET cat_order = $switch_order_id WHERE cat_order = $current_order AND cat_id = $cat_id");
		}

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_SC_' . strtoupper($action) . '_CAT', time(), [$cat_name]);
	}

	private function set_order($action, $current_order)
	{
		// Never move up the first
		if ($current_order === 1 && $action === 'move_up')
		{
			return 0;
		}

		// Never move down the last
		$max_order = $this->get_max_order();
		if (($current_order === $max_order) && ($action === 'move_down'))
		{
			return 0;
		}

		// on move_down, switch cat_order with next order_id...
		// on move_up, switch cat_order with previous order_id...
		$switch_order_id = ($action === 'move_down') ? $current_order + 1 : $current_order - 1;

		// Return the new cat_order
		return $switch_order_id;
	}

	private function get_max_order()
	{
		// Get max order id...
		$sql = 'SELECT MAX(cat_order) AS maxi
			FROM ' . $this->breizhcharts_cats_table;
		$result = $this->db->sql_query($sql);
		$max = (int) $this->db->sql_fetchfield('maxi');
		$this->db->sql_freeresult($result);

		return $max;
	}

	private function get_max_id()
	{
		// Get max id...
		$sql = 'SELECT MAX(cat_id) AS id_max
			FROM ' . $this->breizhcharts_cats_table;
		$result = $this->db->sql_query($sql);
		$id_max = (int) $this->db->sql_fetchfield('id_max');
		$this->db->sql_freeresult($result);

		return $id_max;
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
