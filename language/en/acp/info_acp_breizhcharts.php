<?php

/**
* info_acp_breizhcharts [English]
*
* @package language
* @copyright (c) 2021-2025 Sylver35  https://breizhcode.com
* @license https://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » „ “ — …
//

$lang = array_merge($lang, array(
	'BC_ACP_PAGE'					=> 'Number of entires per page (im ACP)',
	'BC_ACP_PAGE_EXPLAIN'			=> 'Enter here how much entries you like to see per page in the ACP.',
	'BC_ADD'						=> 'Add',
	'BC_ALL_CHARTS'					=> 'All entries',
	'BC_ANNOUNCE_SETTINGS'			=> 'Announcement Settings',
	'BC_CALENDAR'					=> 'Calendar',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_DATE_ADDED'					=> 'l j F Y',
	'BC_CATEGORIES'					=> 'Genre Management',
	'BC_CATEGORIES_EXPLAIN'			=> 'You can add new music genres, delete them, edit them.<br>it is possible to change the display order with the up and down arrows.<br>Changing the order of drop-down lists in the configuration page',
	'BC_CAT_TITLE'					=> '🎼 Music genre management',
	'BC_CAT_SORT'					=> 'Musical genre',
	'BC_CAT_NEED'					=> 'You must define a musical genre',
	'BC_CAT_NB_SONGS'				=> 'Number of songs',
	'BC_CAT_TOTAL'					=> '%1$s musical genres',
	'BC_CAT_EDIT'					=> 'Edit music genre : <strong>%1$s</strong>',
	'BC_CAT_NAME'					=> 'Genre name',
	'BC_CAT_NAME_EXPLAIN'			=> 'Choose a name for the music genre',
	'BC_CAT_NB_EDIT'				=> 'Number of songs',
	'BC_CAT_NB_EDIT_EXPLAIN'		=> 'Allows you to see the number of songs with this musical style.<br>Only change it if you found an account error.',
	'BC_CAT_ADD'					=> 'Add a music genre',
	'BC_CAT_ADD_EXPLAIN'			=> '',
	'BC_CAT_EDIT_OK'				=> 'The musical genre <strong>%1$s</strong> has been successfully edited.',
	'BC_CAT_ADD_OK'					=> 'The musical genre <strong>%1$s</strong> has been added successfully.',
	'BC_CAT_DELETE_OK'				=> 'The musical genre <strong>%1$s</strong> has been successfully deleted.',
	'BC_CAT_DELETE_NB'				=> [
		1	=> '<br>But %d song no longer has a musical genre assigned',
		2	=> '<br>But %d songs no longer have a musical genre assigned',
	],
	'BC_CHOICE_WEEKS'				=> 'Weeks',
	'BC_CHOICE_DAYS'				=> 'Days',
	'BC_CHOICE_CHART'				=> 'Random Songs Block',
	'BC_CHOICE_CHART_EXPLAIN'		=> 'Choice for displaying random songs in Music Charts pages',
	'BC_CHOICE_INDEX'				=> 'Block random songs on index',
	'BC_CHOICE_INDEX_EXPLAIN'		=> 'Choice for displaying random songs on the forum index',
	'BC_CHOICE_INDEX_0'				=> 'Not activated',
	'BC_CHOICE_INDEX_1'				=> 'At the top of the page',
	'BC_CHOICE_INDEX_2'				=> 'Before the list of forums',
	'BC_CHOICE_INDEX_3'				=> 'After the list of forums',
	'BC_CHOICE_INDEX_4'				=> 'At the bottom of the page',
	'BC_CHECK_1_ENABLE'				=> 'Inform user about new period?',
	'BC_CHECK_1_ENABLE_EXPLAIN'		=> 'Check, if the users should be informed that a new rating period started. This message will be shown only once to the users.',
	'BC_CHECK_2_ENABLE'				=> 'Inform the users short before the end of the period?',
	'BC_CHECK_2_ENABLE_EXPLAIN'		=> 'Check, if the users should see a reminder message, if the current period is short before ending. You will need the below time. This message will be shown only once to the users. <strong>Above option needs to be enabled too!</strong>',
	'BC_CHECK_TIME'					=> 'Time frame',
	'BC_CHECK_TIME_EXPLAIN'			=> 'Enter here how many hours before the end of the period the users should be informed (if above option is enabled). Enter the time in hours (ie. 24)!',
	'BC_CLICK_RETURN'				=> 'Click %shere%s to return to the Charts Management',
	'BC_CONFIG'						=> 'Configuration',
	'BC_CONFIG_ANNOUNCE_ENABLE'		=> 'Announce new Charts Songs',
	'BC_CONFIG_ANNOUNCE_ENABLE_EX'	=> 'Set to Yes, if you like to announce new songs in below selected forum',
	'BC_CONFIG_ANNOUNCE_FORUM'		=> 'Select a forum',
	'BC_CONFIG_ANNOUNCE_FORUM_EX'	=> 'Select her the forum, in which new songs should be announced',
	'BC_CONFIG_EXPLAIN'				=> 'Here you can set or edit different values. Please read every explanation very carefully!',
	'BC_CONFIG_MAIN_SETTINGS'		=> 'Settings',
	'BC_CONFIG_OK'					=> 'The Chart Configuration was successfully updated.',
	'BC_CONFIG_TITLE'				=> 'Music Charts Configuration',
	'BC_CONFIG_UPDATED'				=> 'The configuration was usccessfully updated.',
	'BC_CONF_CLICK_RETURN'			=> 'Click %shere%s to return to the Charts Configuration.',
	'BC_DELETE_OK'					=> 'Song was deleted successfully.',
	'BC_EDIT'						=> 'Edit song',
	'BC_PLACE_FIRST'				=> '%1$s for the 1st place',
	'BC_FROM_NAME'					=> 'posted by',
	'BC_FROM_OF'					=> '%1$s of %2$s',
	'BC_HOURS'						=> 'hour(s)',
	'BC_LAST_DATE'					=> 'Added date',
	'BC_LAST_RANK'					=> 'last position',
	'BC_LAST_VOTERS_WINNER_ID'		=> 'User-ID of the last bonus winner',
	'BC_LAST_VOTERS_WINNER_ID_EX'	=> 'Here you can see the user ID of the last bonus winner',
	'BC_MANAGE'						=> 'Manage Charts',
	'BC_CATEGORIES'					=> 'Gestion des Catégories',
	'BC_MAX_ENTRIES'				=> 'Max. allowed entries',
	'BC_MAX_ENTRIES_EXPLAIN'		=> 'Enter here the max. number of entries, which are allowed in the Charts. The same value is used for the option <strong>Show All</strong>. This value needs to be higher than the value for the Top XX! 0 means unlimited.',
	'BC_MAX_PER_PAGE'				=> 'Number of entries per page (Users)',
	'BC_MAX_PER_PAGE_EXPLAIN'		=> 'Enter here the number of entries, which should be shown per page, if the user clicks <strong>Show all songs</strong>.',
	'BC_NEED_ARTIST'				=> 'You need to enter an artist',
	'BC_NEED_DATA'					=> 'There are missing needed datas',
	'BC_NEED_TITLE'					=> 'You need to enter a title',
	'BC_NEXT_RESET'					=> 'Next reset date',
	'BC_NEXT_RESET_EXPLAIN'			=> 'Here you can see, when the Charts will be reset the next time. If you change the start time above, you might need to save the configuration twice, until you see the correct reset date!',
	'BC_NO_BONUS_WINNER'			=> 'Es gibt keinen Eintrag',
	'BC_NO_ENTRY'					=> 'No entries',
	'BC_OK'							=> 'Compliant',
	'BC_PAGE_DESC'					=> 'Here you can edit or delete the entries the users made on the Charts page.',
	'BC_PAGE_TITLE'					=> 'Music Charts Management',
	'BC_PERIOD'						=> 'Period in weeks',
	'BC_PERIOD_EXPLAIN'				=> 'Enter here the weekly time in seconds (<strong>on a per week basis</strong>). So one week is 604800 seconds.',
	'BC_PM'							=> 'Private Message',
	'BC_PM_ENABLE'					=> 'PM for the winners?',
	'BC_PM_ENABLE_EXPLAIN'			=> 'Set here, if you would like to send a PM to the lucky winners of the election and also the random of the bonus price (if enabled).',
	'BC_PM_EXPLAIN'					=> 'Here you can do some settings for sending private messages.',
	'BC_PM_USER'					=> 'Charts Manager',
	'BC_PM_USER_EXPLAIN'			=> 'Enter here the ID of the user, from which the PM is sent to the winners.<br>As well as private messages from the reporting system',
	'BC_POINTS_PER_VOTE'			=> '%s per vote',
	'BC_POINTS_PER_VOTE_EXPLAIN'	=> 'Enter here how much %s the user will receive for each vote they give (0 = deactivate this ooption)',
	'BC_RANKING'					=> '%1$s for the three Top songs',
	'BC_RANKING_EXPLAIN'			=> 'Here you can give %1$s for the three best song within a period. The will be given to the users, who posted the songs.',
	'BC_REALLY_DELETE'				=> 'Are you sure you want delete the selected entry?',
	'BC_REQUIRED'					=> 'Required Fields',
	'BC_REQUIRED_ALBUM'				=> 'Album to the song',
	'BC_REQUIRED_EXPLAIN'			=> 'Here you can set, if you want additional fileds marked as required. These fields will then be marked with [*] in the adding page and they also will be proofed, if they are filled.',
	'BC_REQUIRED_VIDEO'				=> 'Video Clip',
	'BC_REQUIRED_YEAR'				=> 'Publishes year',
	'BC_PLACE_SECOND'				=> '%1$s for the 2nd place',
	'BC_SELECT_ORDER'				=> 'Choice of sorting musical genres',
	'BC_SELECT_ORDER_EXPLAIN'		=> 'Determine how you want to sort music genres in the selection drop-down lists',
	'BC_SELECT_ORDER_POSITION'		=> 'Position',
	'BC_SELECT_ORDER_NATURAL'		=> 'Alphabetical order',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Enter here the title of the album, which the song is extracted from.',
	'BC_SONG_ARTIST'				=> 'Artist',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Enter the name of artist or the band',
	'BC_SONG_TITLE'					=> 'Title',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Enter here the title of the song',
	'BC_SONG_VIDEO'					=> 'Video',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Enter here the embedded code, which can receive ie. with YouTube. Take care, that the dimesion of the video don’t exceed <strong>640x505</strong> (you can see the dimension inside the embedded code)!<br><br><strong>Please take care, that you don’t violate any copyrights!</strong>',
	'BC_SONG_YEAR'					=> 'Year',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Enter here the year where the song or the album was recorded.',
	'BC_STARTING_TIME'				=> 'Start Time',
	'BC_STARTING_TIME_EXPLAIN'		=> 'Enter here the start time (based on the time of your board timezone), which will be the basis for the end period calculation. If your personal timezone differs, you will see another time in the brackets than you entered! <strong>Please use the UNIX time format!</strong> To find out the correct value, go to <a href="http://unixtime.de" onclick="window.open(this.href); return false">Unixtime.de</a>. Here you can convert the time vice versa. The calculation will then use this value and the period value to calculate the ending time for the current voting period. A manual entry is only needed when you start the charts. Later on, the values are set automatically!',
	'BC_PLACE_THIRD'				=> '%1$s for the 3rd place',
	'BC_TITLE'						=> 'Music Charts',
	'BC_UPDATED'					=> 'The song was successfully edited',
	'BC_UPS'						=> '%1$s for adding a new song',
	'BC_UPS_EXPLAIN'				=> 'Since the Ultimate Points  is installed, you may set how much %1$s the user will receive, when he/she add a new song. Set 0 to disable this feature.',
	'BC_UPS_TITLE'					=> 'Ultimate Points',
	'BC_UPS_TITLE_EXPLAIN'			=> 'Since you have the Ultimate Points (UPS) installed, you may configure some additional settings.',
	'BC_VOTES_PERIOD'				=> 'Activation of voting periods',
	'BC_VOTES_PERIOD_EXPLAIN'		=> 'Activate the voting period system here or not',
	'BC_VOTERS_POINTS'				=> '%s for the winner of the voters',
	'BC_VOTERS_POINTS_EXPLAIN'		=> 'Here you can set an amount of %s, which the winner of the users, which voted, will receive (0 = deactivates this function)',
	'BC_VIDEO_WIDTH'				=> 'Video width',
	'BC_VIDEO_WIDTH_EXPLAIN'		=> 'Enter here the width of the video on the video view page',
	'BC_VIDEO_HEIGHT'				=> 'Video Height',
	'BC_VIDEO_HEIGHT_EXPLAIN'		=> 'Enter the height of the video on the video view page here',
	'BC_WEEK'						=> [
		1	=> '%d week',
		2	=> '%d weeks',
	],
	'BC_DAY'						=> [
		1	=> '%d day',
		2	=> '%d days',
	],

	'LOG_ADMIN_BC_UPDATED'			=> 'Updated the Music Charts configuration',
	'LOG_ADMIN_CHART_RESET'			=> '<strong>Music Charts Auto Reset Start</strong>',
	'LOG_ADMIN_CHART_DELETED'		=> 'Deleted the Music Chart song <strong>%1$s</strong>',
	'LOG_ADMIN_CHART_UPDATED'		=> 'Updated the Music Chart entry <strong>%1$s</strong>',
	'LOG_ADMIN_CAT_UPDATED'			=> '<strong>Edited musical genre</strong> » %1$s',
	'LOG_ADMIN_CAT_ADDED'			=> '<strong>New music genre added</strong> » %1$s',
	'LOG_ADMIN_CAT_DELETED'			=> '<strong>Music genre deleted</strong> » %1$s',
	'LOG_USER_ADDED_SONG'			=> 'Added the song <strong>%1$s</strong>.',
	'LOG_USER_EDITED_SONG'			=> 'Edited the song <strong>%1$s</strong>.',
	'LOG_USER_REPORT_SONG'			=> '<strong>Music Charts song reported by %1$s</strong><br>» %2$s',
	'LOG_USER_REPORT_CLOSE'			=> '<strong>Music Charts Report Closed</strong><br>» %1$s',
	'LOG_USER_REPORT_SONG_AUTO'		=> '<strong>Music Chart song automatically reported</strong><br>» %1$s',
));
