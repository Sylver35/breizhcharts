<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\migrations;

use phpbb\db\migration\migration;

class breizhcharts_1_1_0 extends migration
{
	public function effectively_installed()
	{
		return (bool) phpbb_version_compare($this->config['breizhcharts_version'], '1.1.0', '>=');
	}

	static public function depends_on()
	{
		return ['\sylver35\breizhcharts\migrations\breizhcharts_install'];
	}

	public function update_data()
	{
		return [
			// Version of extension
			['config.update', ['breizhcharts_version', '1.1.0']],

			['config.add', ['breizhcharts_last_result', 0]],
		];
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'breizhcharts_result' => [
					'COLUMNS'		=> [
						'result_id'				=> ['UINT:10', NULL, 'auto_increment'],
						'result_song_id'		=> ['UINT:10', 0],
						'result_nb'				=> ['INT:11', 0],
						'result_time'			=> ['INT:11', 0],
						'result_pos'			=> ['UINT:4', 0],
						'result_song_name'		=> ['VCHAR', ''],
						'result_artist'			=> ['VCHAR', ''],
						'result_video'			=> ['TEXT_UNI', ''],
						'result_poster_id'		=> ['UINT:10', 0],
						'result_song_note'		=> ['DECIMAL', 0.00],
						'result_nb_note'		=> ['UINT:4', 0],
						'result_add_time'		=> ['INT:11', 0],
					],
					'PRIMARY_KEY'	=> 'result_id',
					'KEYS'	=> [
						'result_song_id'		=> ['INDEX', 'result_song_id'],
						'result_nb'				=> ['INDEX', 'result_nb'],
					],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'breizhcharts_result',
			],
		];
	}
}
