<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\acp;

class acp_breizhcharts_info
{
	public function module()
	{
		return [
			'filename'		=> '\sylver35\breizhcharts\acp\acp_breizhcharts_module',
			'title'			=> 'BC_TITLE',
			'modes'			=> [
				'config'		=> [
					'title'	=> 'BC_CONFIG',
					'auth'	=> 'ext_sylver35/breizhcharts && acl_a_breizhcharts_manage',
					'cat'	=> ['ACP_BC'],
				],
				'manage_charts'	=> [
					'title'	=> 'BC_MANAGE',
					'auth'	=> 'ext_sylver35/breizhcharts && acl_a_breizhcharts_manage',
					'cat'	=> ['ACP_BC'],
				],
			],
		];
	}
}
