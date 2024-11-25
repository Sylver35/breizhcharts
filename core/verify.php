<?php
/**
 * @author		Sylver35 <webmaster@breizhcode.com>
 * @package		Breizh Charts Extension
 * @copyright	(c) 2021-2024 Sylver35  https://breizhcode.com
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sylver35\breizhcharts\core;

use sylver35\breizhcharts\core\work;
use phpbb\language\language;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface as db;
use phpbb\config\config;

class verify
{
	/** @var \sylver35\breizhcharts\core\work */
	protected $work;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\controller\helper */
	protected $helper;	

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/**
	 * The database tables
	 * @var string
	 */
	protected $breizhcharts_table;

	/**
	 * Constructor
	 */
	public function __construct(work $work, language $language, helper $helper, db $db, config $config, $breizhcharts_table)
	{
		$this->work = $work;
		$this->language = $language;
		$this->helper = $helper;
		$this->db = $db;
		$this->config = $config;
		$this->breizhcharts_table = $breizhcharts_table;
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
			'video'		=> 'BC_REQUIRED_VIDEO_ERROR',
		];

		// Check if new song probably already exist
		if ($this->verify_song_name($id, $data['song_name'], $data['artist']) !== false)
		{
			$error[] = $this->language->lang('BC_ALREADY_EXISTS_ERROR', $data['song_name'], $data['artist']);
		}

		foreach ($list as $key => $lang)
		{
			if ($i < 3)
			{
				if (empty($data[$key]) && $this->config['breizhcharts_required_' . $i])
				{
					$error[] = $this->language->lang($lang);
				}
			}
			else if (empty($data[$key]))
			{
				$error[] = $this->language->lang($lang);
			}
			$i++;
		}

		return $error;
	}

	public function verify_song_name($id, $song, $artist)
	{
		$sql = $this->db->sql_build_query('SELECT', [
			'SELECT'	=> 'song_id',
			'FROM'		=> [$this->breizhcharts_table => ''],
			'WHERE'		=> $this->db->sql_lower_text('song_name') . ' LIKE ' . $this->work->get_like($song) . ' AND ' . $this->db->sql_lower_text('artist') . ' LIKE ' . $this->work->get_like($artist) . ' AND song_id <> ' . $id,
		]);
		$result = $this->db->sql_query($sql);
		$song_exist = (bool) $this->db->sql_fetchfield('song_id');
		$this->db->sql_freeresult($result);

		return $song_exist;
	}

	public function verify_max_entries()
	{
		if ($this->config['breizhcharts_max_entries'] > 0)
		{
			if ($this->config['breizhcharts_songs_nb'] > $this->config['breizhcharts_max_entries'])
			{
				trigger_error($this->language->lang('BC_COUNT_ERROR', $this->config['breizhcharts_max_entries']) . $this->language->lang('BC_BACKLINK', '<br/><br/><a href="' . $this->helper->route('sylver35_breizhcharts_page_music') . '">', '</a>'));
			}
		}
	}
}
