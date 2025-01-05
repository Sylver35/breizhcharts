<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\migrations;

use phpbb\db\migration\migration;

class breizhcharts_1_4_0 extends migration
{
	public function effectively_installed()
	{
		return (bool) phpbb_version_compare($this->config['breizhcharts_version'], '1.4.0', '>=');
	}

	static public function depends_on()
	{
		return ['\sylver35\breizhcharts\migrations\breizhcharts_1_3_0'];
	}

	public function update_data()
	{
		return [
			// Version of extension
			['config.update', ['breizhcharts_version', '1.4.0']],
			['config.update', ['breizhcharts_li_onclick', ' onclick="breizhcharts.voteMusic(%1$s, %2$s, %3$s);"']],

			['config.add', ['breizhcharts_last_nb', 0]],
			['config.add', ['breizhcharts_select_order', 0]],
			['config.add', ['breizhcharts_random', 1]],
			['config.add', ['breizhcharts_random_index', 2]],

			['config.remove', ['breizhcharts_winners_per_page']],

			['module.add', ['acp', 'BC_TITLE', [
				'module_basename'	=> '\sylver35\breizhcharts\acp\acp_breizhcharts_module',
				'module_langname'	=> 'BC_CATEGORIES',
				'module_mode'		=> 'categories',
				'module_auth'		=> 'ext_sylver35/breizhcharts && acl_a_breizhcharts_manage',
			]]],

			['permission.add', ['m_breizhcharts_manage', true]],
			['permission.add', ['u_breizhcharts_delete', true]],
			['permission.add', ['u_breizhcharts_report', true]],

			// Set permissions administration
			['permission.permission_set', ['ADMINISTRATORS', ['m_breizhcharts_manage', 'u_breizhcharts_delete', 'u_breizhcharts_report'], 'group']],

			// Set permissions moderation
			['permission.permission_set', ['GLOBAL_MODERATORS', ['m_breizhcharts_manage', 'u_breizhcharts_delete', 'u_breizhcharts_report'], 'group']],

			['if', [
				['permission.role_exists', ['ROLE_MOD_FULL']],
				['permission.permission_set', ['ROLE_MOD_FULL', 'm_breizhcharts_manage', 'role']],
			]],
			['if', [
				['permission.role_exists', ['ROLE_MOD_STANDARD']],
				['permission.permission_set', ['ROLE_MOD_STANDARD', 'm_breizhcharts_manage', 'role']],
			]],
			['if', [
				['permission.role_exists', ['ROLE_USER_FULL']],
				['permission.permission_set', ['ROLE_USER_FULL', ['u_breizhcharts_delete', 'u_breizhcharts_report'], 'role']],
			]],
			['if', [
				['permission.role_exists', ['ROLE_USER_STANDARD']],
				['permission.permission_set', ['ROLE_USER_STANDARD', ['u_breizhcharts_delete', 'u_breizhcharts_report'], 'role']],
			]],
			['permission.permission_set', ['REGISTERED', ['u_breizhcharts_delete', 'u_breizhcharts_report'], 'group']],

			['custom', [[&$this, 'install_musical_genres'], [&$this, 'get_last_nb']]],
		];
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'breizhcharts_cats' => [
					'COLUMNS'	=> [
						'cat_id'	=> ['UINT:10', NULL, 'auto_increment'],
						'cat_order'	=> ['UINT:10', 0],
						'cat_name'	=> ['VCHAR:60', ''],
						'cat_nb'	=> ['UINT:4', 0],
					],
					'PRIMARY_KEY'	=> ['cat_id'],
				],
			],

			'add_columns' => [
				$this->table_prefix . 'breizhcharts' => [
					'song_view'		=> ['UINT:10', 0],
					'cat'			=> ['UINT:10', 0],
					'reported'		=> ['UINT:10', 0],
					'reason'		=> ['VCHAR:10', ''],
					'reported_time'	=> ['UINT:10', 0],
					'reported_text'	=> ['VCHAR:255', ''],
				],
			],
			'add_index' => [
				$this->table_prefix . 'breizhcharts' => [
					'poster_id'	=> ['poster_id'],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'breizhcharts_cats',
			],

			'drop_columns' => [
				$this->table_prefix . 'breizhcharts' => [
					'song_view',
					'cat',
					'reported',
					'reason',
					'reported_time',
					'reported_text',
				],
			],
		];
	}

	public function get_last_nb()
	{
		$sql = 'SELECT result_nb
			FROM ' . $this->table_prefix . ' breizhcharts_result 
			ORDER BY result_nb DESC';
		$result = $this->db->sql_query_limit($sql, 1);
		$last_nb = (int) $this->db->sql_fetchfield('result_nb');
		$this->db->sql_freeresult($result);

		if ($last_nb)
		{
			$this->config->set('breizhcharts_last_nb', $last_nb);
		}
	}

	public function install_musical_genres()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'breizhcharts_cats'))
		{
			$sql_ary = [
				[
					'cat_order'	=> '2',
					'cat_name'	=> 'Hard Rock',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '18',
					'cat_name'	=> 'Disco',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '13',
					'cat_name'	=> 'New Wave',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '4',
					'cat_name'	=> 'Métal',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '6',
					'cat_name'	=> 'Hip-hop',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '7',
					'cat_name'	=> 'Electro',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '8',
					'cat_name'	=> 'Jazz',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '20',
					'cat_name'	=> 'Classique',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '9',
					'cat_name'	=> 'Reggae',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '5',
					'cat_name'	=> 'Country',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '10',
					'cat_name'	=> 'Blues',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '12',
					'cat_name'	=> 'Punk',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '17',
					'cat_name'	=> 'Folk',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '14',
					'cat_name'	=> 'Grunge',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '1',
					'cat_name'	=> 'Rock\'n Roll',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '11',
					'cat_name'	=> 'Funk',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '15',
					'cat_name'	=> 'Techno',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '19',
					'cat_name'	=> 'Variété',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '16',
					'cat_name'	=> 'Pop',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '3',
					'cat_name'	=> 'Rock',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '21',
					'cat_name'	=> 'Latino',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '22',
					'cat_name'	=> 'Rap',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '23',
					'cat_name'	=> 'Soul',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '25',
					'cat_name'	=> 'Dance',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '24',
					'cat_name'	=> 'Rockabilly',
					'cat_nb'	=> '0',
				],
				[
					'cat_order'	=> '26',
					'cat_name'	=> 'World Music',
					'cat_nb'	=> '0',
				],
			];

			$this->db->sql_multi_insert($this->table_prefix . 'breizhcharts_cats', $sql_ary);
		}
	}
}
