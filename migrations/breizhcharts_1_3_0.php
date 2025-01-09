<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2025 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\migrations;

use phpbb\db\migration\migration;

class breizhcharts_1_3_0 extends migration
{
	public function effectively_installed()
	{
		return (bool) phpbb_version_compare($this->config['breizhcharts_version'], '1.3.0', '>=');
	}

	static public function depends_on()
	{
		return ['\sylver35\breizhcharts\migrations\breizhcharts_1_2_0'];
	}

	public function update_data()
	{
		return [
			// Version of extension
			['config.update', ['breizhcharts_version', '1.3.0']],

			['config.add', ['breizhcharts_period_activ', 0]],
			['config.add', ['breizhcharts_video_width', 900]],
			['config.add', ['breizhcharts_video_height', 600]],		
		];
	}
}
