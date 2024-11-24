<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\migrations;

use phpbb\db\migration\migration;

class breizhcharts_1_2_0 extends migration
{
	public function effectively_installed()
	{
		return (bool) phpbb_version_compare($this->config['breizhcharts_version'], '1.2.0', '>=');
	}

	static public function depends_on()
	{
		return ['\sylver35\breizhcharts\migrations\breizhcharts_1_1_0'];
	}

	public function update_data()
	{
		return [
			// Version of extension
			['config.update', ['breizhcharts_version', '1.2.0']],

			['config.add', ['breizhcharts_li_onclick', ' onclick="breizhcharts.voteMusic(%1$s, %2$s);"']],
			['config.add', ['breizhcharts_li_rating', '<li id="rating-%1$s" class="current%2$s-rating" style="width: %3$s;"></li>']],
			['config.add', ['breizhcharts_li_stars', '<li><a%1$s title="%2$s" class="star-%3$s">%3$s</a></li>']],
		];
	}
}
