<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\migrations;

use phpbb\db\migration\migration;

class breizhcharts_install extends migration
{
	public function effectively_installed()
	{
		return phpbb_version_compare($this->config['breizhcharts_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v33x\v331'];
	}

	public function update_data()
	{
		return [
			['config.add', ['breizhcharts_version', '1.0.0']],

			['config.add', ['breizhcharts_acp_page', 15]],
			['config.add', ['breizhcharts_announce_enable', 0]],
			['config.add', ['breizhcharts_check_1', 1]],
			['config.add', ['breizhcharts_check_2', 1]],
			['config.add', ['breizhcharts_check_time', 72]],
			['config.add', ['breizhcharts_last_song', 0, true]],
			['config.add', ['breizhcharts_max_entries', 150]],
			['config.add', ['breizhcharts_period', 1209600]],
			['config.add', ['breizhcharts_period_val', 604800]],
			['config.add', ['breizhcharts_place_1', 200]],
			['config.add', ['breizhcharts_place_2', 150]],
			['config.add', ['breizhcharts_place_3', 100]],
			['config.add', ['breizhcharts_pm_enable', 1]],
			['config.add', ['breizhcharts_pm_user', 2]],
			['config.add', ['breizhcharts_points_per_vote', 3]],
			['config.add', ['breizhcharts_required_1', 0]],
			['config.add', ['breizhcharts_required_2', 0]],
			['config.add', ['breizhcharts_required_3', 0]],
			['config.add', ['breizhcharts_required_4', 0]],
			['config.add', ['breizhcharts_song_forum', 0]],
			['config.add', ['breizhcharts_songs_nb', 0, true]],
			['config.add', ['breizhcharts_start_time', time()]],
			['config.add', ['breizhcharts_start_time_bis', date('m-d-Y H:i', time())]],
			['config.add', ['breizhcharts_ups_points', 10]],
			['config.add', ['breizhcharts_user_page', 15]],
			['config.add', ['breizhcharts_voters_points', 100]],
			['config.add', ['breizhcharts_winner', 0]],
			['config.add', ['breizhcharts_winners_per_page', 10]],

			['permission.add', ['a_breizhcharts_manage', true]],
			['permission.add', ['u_breizhcharts_view', true]],
			['permission.add', ['u_breizhcharts_vote', true]],
			['permission.add', ['u_breizhcharts_add', true]],
			['permission.add', ['u_breizhcharts_edit', true]],

			['permission.permission_set', ['ROLE_ADMIN_FULL', ['a_breizhcharts_manage'], 'role']],
			['permission.permission_set', ['ROLE_ADMIN_STANDARD', ['a_breizhcharts_manage'], 'role']],
			['permission.permission_set', ['ADMINISTRATORS', ['a_breizhcharts_manage', 'u_breizhcharts_view', 'u_breizhcharts_vote', 'u_breizhcharts_add', 'u_breizhcharts_edit'], 'group']],
			['permission.permission_set', ['ROLE_USER_FULL', ['u_breizhcharts_view', 'u_breizhcharts_vote', 'u_breizhcharts_add', 'u_breizhcharts_edit'], 'role']],
			['permission.permission_set', ['ROLE_USER_STANDARD', ['u_breizhcharts_view', 'u_breizhcharts_vote', 'u_breizhcharts_add', 'u_breizhcharts_edit'], 'role']],
			['permission.permission_set', ['REGISTERED', ['u_breizhcharts_view', 'u_breizhcharts_vote', 'u_breizhcharts_add', 'u_breizhcharts_edit'], 'group']],
			['permission.permission_set', ['ROLE_USER_LIMITED', ['u_breizhcharts_view', 'u_breizhcharts_vote', 'u_breizhcharts_add'], 'role']],
			['permission.permission_set', ['ROLE_USER_NEW_MEMBER', ['u_breizhcharts_view', 'u_breizhcharts_vote', 'u_breizhcharts_add'], 'role']],
			['permission.permission_set', ['GUESTS', ['u_breizhcharts_view'], 'group']],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'BC_TITLE',
			]],

			['module.add', [
				'acp',
				'BC_TITLE',
				[
					'module_basename'	=> '\sylver35\breizhcharts\acp\acp_breizhcharts_module',
					'modes'			=> [
						'config',
						'manage_charts',
					],
				],
			]],
		];
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'breizhcharts' => [
					'COLUMNS'		=> [
						'song_id'			=> ['UINT:10', NULL, 'auto_increment'],
						'song_name'			=> ['VCHAR', ''],
						'artist'			=> ['VCHAR', ''],
						'album'				=> ['VCHAR', ''],
						'picture'			=> ['TEXT_UNI', ''],
						'year'				=> ['VCHAR:4', ''],
						'website'			=> ['TEXT_UNI', ''],
						'video'				=> ['TEXT_UNI', ''],
						'poster_id'			=> ['UINT:10', 0],
						'song_note'			=> ['DECIMAL', 0.00],
						'nb_note'			=> ['UINT:4', 0],
						'last_pos'			=> ['UINT:4', 0],
						'best_pos'			=> ['UINT:4', 0],
						'add_time'			=> ['INT:11', 0],
						'topic_id'			=> ['UINT:8', 0],
					],
					'PRIMARY_KEY'	=> 'song_id',
				],

				$this->table_prefix . 'breizhcharts_voters' => [
					'COLUMNS'		=> [
						'vote_id'		=> ['UINT:10', NULL, 'auto_increment'],
						'vote_user_id'	=> ['UINT:4', 0],
						'vote_song_id'	=> ['UINT:4', 0],
						'vote_rate'		=> ['UINT:11', 0],
					],
					'PRIMARY_KEY'	=> ['vote_id'],
				],
			],

			'add_columns' => [
				$this->table_prefix . 'users' => [
					'breizhchart_check_1'		=> ['TINT:1', 0],
					'breizhchart_check_2'		=> ['TINT:1', 0],
					'breizhchart_last'			=> ['UINT:11', 0],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'breizhcharts',
				$this->table_prefix . 'breizhcharts_voters',
			],
			'drop_columns' => [
				$this->table_prefix . 'users' => [
					'breizhchart_check_1',
					'breizhchart_check_2',
					'breizhchart_last',
				],
			],
		];
	}
}
