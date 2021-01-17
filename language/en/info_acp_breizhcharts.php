<?php

/**
* info_acp_breizhcharts [English]
*
* @package language
* @version $Id: info_acp_breizhcharts.php 154 2011-12-21 14:16:58Z femu $
* @copyright (c) 2010 femu - http://die-muellers.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
	'BC_LAST_RANK'					=> 'last position',
	'BC_LAST_VOTERS_WINNER_ID'		=> 'User-ID of the last bonus winner',
	'BC_LAST_VOTERS_WINNER_ID_EX'	=> 'Here you can see the user ID of the last bonus winner',
	'BC_MANAGE'						=> 'Manage Charts',
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
	'BC_PM_USER_EXPLAIN'			=> 'Enter here the ID of the user, from which the PM is sent to the winners.',
	'BC_POINTS_PER_VOTE'			=> '%s per vote',
	'BC_POINTS_PER_VOTE_EXPLAIN'	=> 'Enter here how much %s the user will receive for each vote they give (0 = deactivate this ooption)',
	'BC_RANKING'					=> '%1$s for the three Top songs',
	'BC_RANKING_EXPLAIN'			=> 'Here you can give %1$s for the three best song within a period. The will be given to the users, who posted the songs.',
	'BC_REALLY_DELETE'				=> 'Are you sure you want delete the selected entry?',
	'BC_REQUIRED'					=> 'Required Fields',
	'BC_REQUIRED_ALBUM'				=> 'Album to the song',
	'BC_REQUIRED_ALBUMCOVER'		=> 'Album Cover',
	'BC_REQUIRED_EXPLAIN'			=> 'Here you can set, if you want additional fileds marked as required. These fields will then be marked with [*] in the adding page and they also will be proofed, if they are filled.',
	'BC_REQUIRED_VIDEO'				=> 'Video Clip',
	'BC_REQUIRED_WEBSITE'			=> 'Link to the website of the artist',
	'BC_REQUIRED_YEAR'				=> 'Publishes year',
	'BC_PLACE_SECOND'				=> '%1$s for the 2nd place',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Enter here the title of the album, which the song is extracted from.',
	'BC_SONG_ARTIST'				=> 'Artist',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Enter the name of artist or the band',
	'BC_SONG_PICTURE'				=> 'Album Cover Image',
	'BC_SONG_PICTURE_EXPLAIN'		=> 'Enter here the link to the album cover image, if available (use the complete link incl. http://. Don\'t use backlinks and keep the copyrights in mind!)',
	'BC_SONG_TITLE'					=> 'Title',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Enter here the title of the song',
	'BC_SONG_URL'					=> 'Web Site',
	'BC_SONG_URL_EXPLAIN'			=> 'Enter here the complete link to the web site of the artist.',
	'BC_SONG_VIDEO'					=> 'Video',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Enter here the embedded code, which can receive ie. with YouTube. Take care, that the dimesion of the video don\'t exceed <strong>640x505</strong> (you can see the dimension inside the embedded code)!<br /><br /><strong>Please take care, that you don\'t violate any copyrights!</strong>',
	'BC_SONG_VIDEO_ID'				=> 'DM Video ID',
	'BC_SONG_VIDEO_ID_EXPLAIN'		=> 'The DM Video Mod is installed over here. If the video you might like to add, already exists over here, you can simply enter the video ID over here. To get the ID, please check the link for the video in your browser. Take the number after the v=. If you use this option, you don\'t have to enter the embedded code.',
	'BC_SONG_YEAR'					=> 'Year',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Enter here the year, where the song or the album was recorded.',
	'BC_STARTING_TIME'				=> 'Start Time',
	'BC_STARTING_TIME_EXPLAIN'		=> 'Enter here the start time (based on the time of your board timezone), which will be the basis for the end period calculation. If your personal timezone differs, you will see another time in the brackets than you entered! <strong>Please use the UNIX time format!</strong> To find out the correct value, go to <a href="http://unixtime.de" onclick="window.open(this.href); return false">Unixtime.de</a>. Here you can convert the time vice versa. The calculation will then use this value and the period value to calculate the ending time for the current voting period. A manual entry is only needed when you start the charts. Later on, the values are set automatically!',
	'BC_PLACE_THIRD'				=> '%1$s for the 3rd place',
	'BC_TITLE'						=> 'Music Charts',
	'BC_UPDATED'					=> 'The song was successfully edited',
	'BC_UPS'						=> '%1$s for adding a new song',
	'BC_UPS_EXPLAIN'				=> 'Since the Ultimate Points  is installed, you may set how much %1$s the user will receive, when he/she add a new song. Set 0 to disable this feature.',
	'BC_UPS_TITLE'					=> 'Ultimate Points',
	'BC_UPS_TITLE_EXPLAIN'			=> 'Since you have the Ultimate Points (UPS) installed, you may configure some additional settings.',
	'BC_VOTERS_POINTS'				=> '%s for the winner of the voters',
	'BC_VOTERS_POINTS_EXPLAIN'		=> 'Here you can set an amount of %s, which the winner of the users, which voted, will receive (0 = deactivates this function)',
	'BC_WEEK'						=> [
		1	=> '%d week',
		2	=> '%d weeks',
	],
	'BC_DAY'						=> [
		1	=> '%d day',
		2	=> '%d days',
	],
	'BC_WINNERS_PER_PAGE'			=> 'Winners per page',
	'BC_WINNERS_PER_PAGE_EXPLAIN'	=> 'Enter here, how much of the winners should be shown on the winners page',

	'LOG_ADMIN_BC_UPDATED'			=> 'Updated the Music Charts configuration',
	'LOG_ADMIN_CHART_DELETED'		=> 'Deleted the Music Chart song <strong>%1$s</strong>',
	'LOG_ADMIN_CHART_RESET'			=> '<strong>Started the automatic reset of the Music Charts</strong>',
	'LOG_ADMIN_CHART_UPDATED'		=> 'Updated the Music Chart entry <strong>%1$s</strong>',
	'LOG_USER_ADDED_SONG'			=> 'Added the song <strong>%1$s</strong>.',
	'LOG_USER_EDITED_SONG'			=> 'Edited the song <strong>%1$s</strong>.',
));
