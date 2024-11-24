<?php
/**
*
* @package phpBB Extension -  breizhcharts
* @copyright (c) 2021-2024 Sylver35  https://breizhcode.com
* @license https://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
namespace sylver35\breizhcharts\acp;

class acp_breizhcharts_module
{
	/** @var string */
	public $u_action;

	/** @var string */
	public $tpl_name;

	/** @var string */
	public $page_title;

	/**
	 * @param int		$id
	 * @param string	$mode
	 *
	 * @return void
	 * @access public
	 */
	public function main(/** @scrutinizer ignore-unused */$id, $mode)
	{
		global $phpbb_container;

		/** @type \phpbb\language\language $language Language object */
		$language = $phpbb_container->get('language');
		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('sylver35.breizhcharts.admin.controller');
		// Get an instance of the admin config
		$admin_config = $phpbb_container->get('sylver35.breizhcharts.admin.config');

		// Make the $u_action url available
		$admin_config->set_page_url($this->u_action);
		$admin_controller->set_page_url($this->u_action);

		switch ($mode)
		{
			case 'config':
				// Load a template from adm/style for our ACP page
				$this->tpl_name = 'breizhchart_config';
				// Set the page title for our ACP page
				$this->page_title = $language->lang('BC_CONFIG');
				// Load the display points in the admin controller
				$admin_config->acp_breizhcharts_config();
			break;

			case 'manage_charts':
				// Load a template from adm/style for our ACP page
				$this->tpl_name = 'breizhchart_manage';
				// Set the page title for our ACP page
				$this->page_title = $language->lang('BC_MANAGE');
				// Load the display lottery in the admin controller
				$admin_controller->acp_breizhcharts();
			break;
		}
	}	
}
