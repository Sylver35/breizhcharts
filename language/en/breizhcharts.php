<?php

/**
* breizhcharts [ENGLISH]
*
* @package language
* @copyright (c) 2021-2025 Sylver35  https://breizhcode.com
* @license https://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
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

$lang = array_merge($lang, [
	'BC_CHARTS'						=> '🎼 Music Charts',
	'BC_CHARTS_SIMPLE'				=> 'Music Charts',
	'BC_CHARTS_NEW'					=> 'New songs in the Music Charts',
	'BC_ACTUAL'						=> 'Current: <span>%s</span>',
	'BC_LATEST'						=> 'Latest: <span>%s</span>',
	'BC_BEST_POS'					=> 'Better: <span>%s</span>',
	'BC_AJAX_NOTE_TOTAL'			=> 'Average rating: <span class="total">%s</span>',
	'BC_AJAX_NO_VOTE'				=> 'You are not allowed to rate songs…',
	'BC_AJAX_NOTE_NO'				=> 'You can’t rate',
	'BC_AJAX_NOTE'					=> [
		0	=> 'You did not rate…',
		1	=> 'You have already rated: <span>%s</span>',
	],
	'BC_AJAX_NOTE_NB'				=> [
		0	=> '<span>%s</span> vote',
		1	=> '<span>%s</span> vote',
		2	=> '<span>%s</span> votes',
	],
	'BC_AJAX_STARS'					=> [
		1	=> 'Rate out %s star of 10',
		2	=> 'Rate out %s stars of 10',
	],
	'BC_AJAX_THANKS'				=> 'Thanks for rating!',
	'BC_AJAX_UPDATING'				=> 'Note update…',
	'BC_AJAX_VIDEO'					=> 'The video does exist…',
	'BC_AJAX_VIDEO_NO'				=> 'The video does not exist…',
	'BC_ADD_SONG'					=> 'Add a new Song',
	'BC_ADDED_BY'					=> 'Added by',
	'BC_ADDED_TIME'					=> 'Added on:<br><strong>%1$s</strong>',
	'BC_ADDED_TIME_SHORT'			=> 'On : %1$s',
	'BC_ALL_TITLE'					=> 'The Charts - All Songs',
	'BC_ALREADY_EXISTS_ERROR'		=> 'The song <strong>%1$s</strong> from <strong>%2$s</strong> already exists. Please select another song to add.',
	'BC_ALREADY_VOTED'				=> 'You already voted for this song',
	'BC_ENTER'						=> 'Entry',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_COMMENT'					=> 'Comment',
	'BC_COMMENT_EXPLAIN'			=> 'A topic will be automatically created in the forum.<br>You can add a comment here.',
	'BC_ANNOUNCE_MSG'				=> 'Hello everybody,' . "\n\n" . 'There was a new song added to the Music Charts!' . "\n" . ' [img]%1$s[/img] ' . "\n\n" . '🎶 Title: [b]%2$s[/b] ' . "\n" . '🎸 Artist: [b]%3$s[/b] ' . "\n" . '🎵 Musical genre: [b]%4$s[/b]' . "\n\n" . '[b]%5$s[/b][b][url=%6$s]%7$s[/url][/b] ' . "\n\n" . 'Have fun watching and listening the new song and don’t forget to vote!',
	'BC_ANNOUNCE_TITLE'				=> '🎼 %1$s of %2$s',
	'BC_ANNOUNCE_USER'				=> '👉 Author’s Note: [quote]%1$s[/quote]' . "\n",
	'BC_ANNOUNCE_SEPARATE'			=> "\n\n" . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-----------------------------------------------------' . "\n",// Separation in topic when needed
	'BC_ARTIST_ERROR'				=> 'You have to enter an artist!',
	'BC_BACKLINK'					=> '%sBack to the Chart Overview%s',
	'BC_BACKLINK_ADD'				=> '<br><br>%sBack to the adding page%s',
	'BC_BACKLINK_EDIT'				=> '<br><br>%sReturn to the song’s edit page%s',
	'BC_BACKLINK_VIDEO'				=> '<br>%sBack to video view page%s',
	'BC_BEST_RATED'					=> 'Top ranked',
	'BC_BONUS_WINNER'				=> 'The random winner of the bonus price from those users, who voted, is: %1$s<br><br>Congratulations for the bonus of <strong>%2$s %3$s</strong>!',
	'BC_BONUS_WINNER_HEADER'		=> 'Congratulations',
	'BC_CLICK_LINK'					=> 'Click %shere%s to return to the Charts',
	'BC_CLICK_VIDEO'				=> '🎬 Click here to watch the video!',
	'BC_COPYRIGHT'					=> 'Breizh Chart Extension V%1$s by %2$s',
	'BC_COUNT_ERROR'				=> 'Sorry. We have reached our max. allowed number of entries (%s)<br>Please try again later or ask the Admin to remove some older entries in order to give you the possibility to enter new songs.',
	'BC_CURR_POS'					=> 'Current',
	'BC_DELETE_SONG'				=> 'Delete Song',
	'BC_DELETE_SONG_EXPLAIN'		=> 'Are you sure you want to delete this song?',
	'BC_DELETE_SUCCESS'				=> 'The song <strong>%1$s</strong> was successfully deleted.',
	'BC_EDIT_SONG'					=> 'Edit your entry',
	'BC_FIELDS_ERROR'				=> 'You have to enter at least a song title and an artist!',
	'BC_FROM'						=> ' from ',
	'BC_FROM_OF'					=> '%1$s from %2$s',
	'BC_GO_CHARTS'					=> 'here',
	'BC_HEADER'						=> 'Music Charts - Your Charts over here',
	'BC_HEADER_EXPLAIN'				=> 'Here you can create your own charts and rate them. Every registered user can add songs and every registered user can vote for them.',
	'BC_HEADER_BIS_EXPLAIN'			=> '<br>Within the running voting period you only can vote once per song.<br>As soon as the current period ends, you may vote again for your favourite songs.',
	'BC_HEADER_TER_EXPLAIN'			=> '<br><br>the voting period of <strong>%1$s</strong> ends on: <strong>%2$s</strong>',
	'BC_HEADER_QUATER_EXPLAIN'		=> '<br><br><strong>! Tip:</strong> Click on a member’s nickname to see their song list',
	'BC_INDEX_WINNER'				=> 'The last Music-Charts winners from %s',
	'BC_LAST_POS'					=> 'Ranking',
	'BC_LAST_WINNERS'				=> 'Voting results',
	'BC_LAST_WINNERS_FORMAT'		=> 'l j F Y',
	'BC_LAST_WINNERS_DATE'			=> 'Results of the vote from %s',
	'BC_LAST_WINNERS_PORTAL'		=> 'The winners from the last Music Charts voting period',
	'BC_LAST_WINNERS_SELECT'		=> 'Select a result :',
	'BC_MULTI_VOTERS'				=> '<strong>Currently %1$s users voted in our Charts</strong>.',
	'BC_NEEDED'						=> 'Fields marked with [*] have to be filled. All other fields are optional.<br><strong>Works ONLY with <a href="https://www.youtube.com" onclick="window.open(this.href);return false;">YouTube videos</a></strong>',
	'BC_NEWEST'						=> 'The newest songs',
	'BC_NEWEST_PERIOD'				=> 'The newest songs, which were added during the current period',
	'BC_NEW_PLACED'					=> 'This song has been added to the new releases : %s',
	'BC_NEW_SONG'					=> 'New Song',
	'BC_NO_EXISTS'					=> 'The song <strong>%1$s</strong> from <strong>%2$s</strong> is accepted.',
	'BC_NOT_LOGGED_IN'				=> 'You need to be logged in, in order to vote',
	'BC_NO_CHARTS'					=> 'Sorry, currently there are no charts to show',
	'BC_NO_SONGS'					=> 'No entries available',
	'BC_NO_VOTES'					=> 'Nobody voted during the last period. Therefore we have no lucky winners.',
	'BC_NO_VOTERS'					=> '<strong>Currently no user voted in our Charts.</strong>',
	'BC_NO_WINNER'					=> 'No winners yet',
	'BC_NOT_AUTHORISED'				=> 'You are not authorized to access the Hit Parade module.',
	'BC_OK'							=> 'Compliant',
	'BC_OF_USER'					=> 'Songs from %s',
	'BC_OF_USER_TITLE'				=> '🎶 See all Songs from %s',
	'BC_OWN'						=> 'My Songs',
	'BC_OWN_CHARTS'					=> [
		0	=> 'Didn’t add any song up to now',
		1	=> 'Added <strong>1</strong> song up to now',
		2	=> 'Added <strong>%s</strong> songs up to now',
	],
	'BC_PERIOD'						=> 'Voting Period: <strong>%1$s %2$s</strong>',
	'BC_PICTURE_TITLE'				=> 'The album cover for the song %1$s',
	'BC_PLACE_LIST_1'				=> '🥇 first place',
	'BC_PLACE_LIST_2'				=> '🥈 second place',
	'BC_PLACE_LIST_3'				=> '🥉 third place',
	'BC_PM_MESSAGE'					=> 'Hello [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . 'Congratulations for your [b]%3$s[/b] in the Music Charts!' . "\n" . 'The song [b]%4$s[/b] from [b]%5$s[/b] you posted in the charts, was voted to the [b]%3$s[/b] from the users of our board during the last voting period!',
	'BC_PM_MESSAGE_UPS'				=> "\n\n" . 'As a little gift, we are happy to say, that you earned [b]%1$s %2$s[/b] for this.',
	'BC_PM_SUBJECT_1'				=> '🥇 Congratulations to the first place',
	'BC_PM_SUBJECT_2'				=> '🥈 Congratulations to the second place',
	'BC_PM_SUBJECT_3'				=> '🥉 Congratulations to the third place',
	'BC_PM_VOTERS_SUBJECT'			=> '🙌 Congratualations to the bonus winner',
	'BC_PM_VOTERS_MESSAGE'			=> 'Hello [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . 'Out of all users, which take part in the Music Charts, you are the lucky winner of the bonus of [b]%3$s %4$s[/b] 🤩 ' . "\n\n" . 'Have fun and stay with us in the next election!',
	'BC_POSITION_DOWN'				=> '↘ Falling : %s',
	'BC_POSITION_EQUAL'				=> '➡ Equal : %s',
	'BC_POSITION_UP'				=> '↗ Rising : %s',
	'BC_RANK'						=> 'Rank',
	'BC_RATE'						=> 'Rating',
	'BC_REQUIRED'					=> 'Fields marked with a <strong>[*]</strong> have to be filled.',
	'BC_REQUIRED_ALBUM_ERROR'		=> 'You have to enter an <strong>album</strong> to the song.',
	'BC_REQUIRED_VIDEO_ERROR'		=> 'You have to enter an <strong>embedded code</strong> to a video clip of the song.',
	'BC_REQUIRED_YEAR_ERROR'		=> 'You have to enter the <strong>publishing year</strong> for the song.',
	'BC_REQUIRED_CAT_ERROR'			=> 'Vous devez définir <strong>le genre musical</strong> de la chanson.',
	'BC_SHOW_VIDEO'					=> 'Watch the video for the song %1$s',
	'BC_SHOW_VIDEO_AJAX'			=> 'Watch the video: ',
	'BC_SHOW_VIDEO_POPUP'			=> 'Watch the video in popup',
	'BC_SEE_TOPIC'					=> 'Title presentation topic',
	'BC_SONG_NB'					=> [
		1	=> '%d song',
		2	=> '%d songs',
	],
	'BC_WEEK'						=> [
		1	=> '%d week',
		2	=> '%d weeks',
	],
	'BC_DAY'						=> [
		1	=> '%d day',
		2	=> '%d days',
	],
	'BC_SINGLE_VOTER'				=> '<strong>Currently only one user voted in our Charts.</strong>',
	'BC_SONG_RANDOM'				=> 'Random songs',
	'BC_SONG_VIEW'					=> 'Number of views: %d',
	'BC_SONG_ADD_NO'				=> 'You don’t have permission to add songs .',
	'BC_SONG_ADDED'					=> 'Your song was successfully added.<br>',
	'BC_SONG_ADDED_UPS'				=> 'Your song was successfully added and you earned <strong>%1$s %2$s</strong> for it.<br>',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Name of the album from which the song was extracted',
	'BC_SONG_ARTIST'				=> 'Name of the artist or the band',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Enter here the name of the artist or the band name',
	'BC_SONG_CAT'					=> 'Musical genre',
	'BC_SONG_CAT_ALL'				=> 'All musical genres',
	'BC_SONG_CAT_EXPLAIN'			=> 'Select the musical genre to which the proposed song belongs',
	'BC_SONG_CAT_CHOICE'			=> 'Select a musical genre',
	'BC_SONG_CAT_SELECT'			=> 'Sort by musical genre',
	'BC_SONG_EDIT_SUCCESS'			=> 'The song <strong>%1$s</strong> was successfully edited.',
	'BC_SONG_TITLE'					=> 'Title',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Enter here the title of the song',
	'BC_SONG_VIDEO'					=> 'Video Clip',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Enter here the url of the YouTube video.<br>To retrieve it, right click in the desired video and:<br><em>Copy video url</em>',
	'BC_SONG_YEAR'					=> 'Year',
	'BC_SONG_YEAR_EXPLAIN'			=> 'The year where the song or the album was published',
	'BC_TITLE_ERROR'				=> 'You have to enter a song title!',
	'BC_PRESENT_ERROR'				=> 'Video already present',
	'BC_TOP_TEN'					=> 'Show Top %s',
	'BC_TOP_XX'						=> 'The Top %1$s',
	'BC_USER'						=> 'User',
	'BC_VIEWONLINE'					=> 'Is watching the Music Charts',
	'BC_VOTE_CHECK_FIRST'			=> '<br><br>Hello %1$s,<br><br>The new rating period for the Music Charts has just started! Take the opportunity and go directly to the charts and rate your favourites. If there’s nothing you like, just add your own song.<br><br><strong>Thanks a lot!</strong>',
	'BC_VOTE_CHECK_LINK'			=> '<strong>%sClick here to go to the Charts!%s</strong><br><br>',
	'BC_VOTE_CHECK_SECOND'			=> '<br><br>Hello %1$s,<br><br>The current rating period for the Music Charts is running out shortly! Please take a short moment to visit the charts and check, if there are new songs, which you haven’t rated yet.<br><br><strong>Thanks a lot!</strong>',
	'BC_VOTE_SUCCESS'				=> '<strong>Your voting was successfully set!</strong><br><br>Thanks a lot for your voting for the song <strong>%1$s</strong> from <strong>%2$s</strong>.',
	'BC_VOTE_SUCCESS_UPS'			=> '<br>You received <strong>%1$s %2$s</strong> for your voting.',
	'BC_VOTED_USERS'				=> [
		1	=> '%1$s user has already voted: ',
		2	=> '%1$s users have already voted: ',
	],
	'BC_UPLOADERS'				=> [
		1	=> '%1$s user having posted songs: ',
		2	=> '%1$s users having posted songs: ',
	],
	'BC_WON'						=> 'Earnings',
	'BC_WON_VALUE'					=> '%1$s %2$s',
	'ACL_A_BC_MANAGE'				=> 'Can manage Music Charts',
	'ACL_M_BC_MANAGE'				=> 'Can moderate the Music Charts',
	'ACL_U_BC_VIEW'					=> 'Can view Music Charts',
	'ACL_U_BC_VOTE'					=> 'Can rate in Music Charts',
	'ACL_U_BC_ADD'					=> 'Can add songs to Music Charts', 
	'ACL_U_BC_EDIT'					=> 'Can edit own songs in Music Charts',
	'ACL_U_BC_DELETE'				=> 'Can remove his songs from the Music Charts',
	'ACL_U_BC_REPORT'				=> 'Can report songs in Music Charts',
	'GO_TO_YOUTUBE'					=> '<a href="https://www.youtube.com" onclick="window.open(this.href);return false;" title="Go to YouTube">Visit the YouTube site</a>',
	'BC_INVALID_URL'				=> 'The url is not a valid url',
	'REPORT_VIDEO'					=> 'Report a video',
	'REPORT_VIDEO_EXPLAIN'			=> 'This report allows you to report any problems with a video.<br>Broken link, deleted video, offensive or no longer able to be broadcast outside of YouTube.',
	'MORE_INFO'						=> 'Additional informations',
	'CAN_LEAVE_BLANK'				=> 'This can be left blank.',
	'BC_ERROR_SELECT'				=> 'You must choose a valid reason',
	'BC_ERROR_SELECT_NO'			=> 'Please enter additional information',
	'BC_FORMAT_DATE_PM'				=> 'l F d, Y g:i a',// Format date used in pms, to avoid "less than a minute ago" Wednesday, December 25, 2024 10:10 p.m.
	'BC_TOOLS'						=> 'Tools',
	'BC_TOOLS_TITLE'				=> 'Access the Hit Parade tools',
	'BC_REPORT'						=> 'Report video',
	'BC_REPORT_INFO'				=> 'Used to signal a song',
	'BC_REPORT_TO'					=> 'Report video <strong>%1$s</strong> of <strong>%2$s</strong>',
	'BC_REPORT_GO'					=> 'View the report',
	'BC_REPORT_TITLE'				=> 'Song Report : %1$s',
	'BC_REPORTED'					=> 'Video reported',
	'BC_REPORTED_BY'				=> 'Reported by ',
	'BC_REPORTED_TIME'				=> 'Reported on:' . "\n" . ' %1$s',
	'BC_REPORTED_EDIT'				=> 'Edit the reported video',
	'BC_REPORT_FROM'				=> 'This video was reported by %1$s<br>» For cause : %2$s<br>» The %3$s.',
	'BC_REPORT_NEANT'				=> 'Nothing',
	'BC_REPORTED_LIST'				=> 'List of reports',
	'BC_REPORTED_LIST_TITLE'		=> 'View list of open reports',
	'BC_REPORT_ACTIONS'				=> 'Actions to take to resolve this report',
	'BC_REPORT_EDIT_CLOSE'			=> 'Close the panel',
	'BC_REPORT_INFORM'				=> 'Inform the user %1$s',
	'BC_REPORT_CLOSE'				=> 'Close report',
	'BC_REPORT_CLOSE_CONTACT'		=> 'Close the report and notify users',
	'BC_REPORT_CLOSE_NO_REASON'		=> 'Close the report because the reason is not good…<br>%1$s and %2$s will be informed of the decision',
	'BC_REPORT_CLOSE_NO_REASON_OWN'	=> 'Close the report because the reason is not good…<br>%1$s will be informed of the decision',
	'BC_REPORT_CLOSE_FINISH'		=> 'The report is now closed and %1$s will be informed',
	'BC_REPORT_CLOSE_FINISH_TO'		=> 'The report is now closed, %1$s and %2$s will be informed',
	'BC_REPORT_BACKLINK'			=> '%sBack to list of reports%s',
	'BC_REPORT_BACKLINK_OWN'		=> '%sBack to your song list%s',
	'BC_REPORT_INFOS'				=> 'Information regarding this report is attached at the beginning of the message.',
	'BC_REPORTED_THANKS'			=> 'Thank you for taking the time to make this report regarding : %1$s.<br> The member who posted it and the moderators will be informed.',
	'BC_REPORTED_ON'				=> 'Video reported: [b]%1$s[/b] of [b]%2$s[/b]',
	'BC_REPORT_SEND_SUBJECT'		=> 'Report about your song : %1$s',
	'BC_REPORT_SEND_MESSAGE'		=> 'Hello [b][color=#%2$s]%1$s[/color][/b], ' . "\n\n" . '[b][color=#%4$s]%3$s[/color][/b] contact you for the open report regarding : %5$s.' . "\n" . 'Reported by [b][color=#%7$s]%6$s[/color][/b] on %8$s.' . "\n\n" . '» For the reason : %9$s' . "\n" . '» %10$s' . "\n" . '%11$s' . "\n\n" . '» %12$s',
	'BC_REPORT_SEND_FINISH'			=> 'The private message is sent to %1$s',
	'BC_PM_REPORT_SUBJECT'			=> 'Video Music Charts Reported: %1$s',
	'BC_PM_REPORT_MESSAGE'			=> 'Hello [b][color=#%2$s]%1$s[/color][/b], ' . "\n\n" . ' You are receiving this private message because a video has just been reported by [b][color=#%4$s]%3$s[/color][/b] ' . "\n" . ' » on %5$s. ' . "\n\n" . '» %6$s ' . "\n" . '» Posted by [b][color=#%8$s]%7$s[/color][/b] ' . "\n\n" . '» Reason: %9$s ' . "\n" . '» Report link: [url=%10$s]View the report[/url]',
	'BC_PM_REPORT_CLOSE'			=> 'Hello [b][color=#%2$s]%1$s[/color][/b], ' . "\n\n" . ' You are receiving this private message because the report regarding your %3$s, reported by [b][color=#%6$s]%5$s[/color][/b], for the reason : %4$s, has just been closed by [b][color=#%8$s]%7$s[/color][/b] on %9$s',
	'BC_PM_REPORT_CLOSE_TO'			=> 'Hello [b][color=#%6$s]%5$s[/color][/b], ' . "\n\n" . ' You are receiving this private message because the report you opened, for the reason : %4$s, regarding the %3$s, posted by [b][color=#%2$s]%1$s[/color][/b], has just been closed by [b][color=#%8$s]%7$s[/color][/b] on %9$s',
	'BC_REPORT_REASON'				=> 'Reason given',
	'bc_report_reasons'				=> [
		'TITLE'	=> [
			'NOT'		=> 'Choose a reason',
			'DEAD'		=> 'Dead link',
			'OUT'		=> 'Not available',
			'AGE'		=> 'Age restriction',
			'BAD'		=> 'Poor quality',
			'OFF_TOPIC'	=> 'Off subject',
			'SHOCKING'	=> 'Shocking video',
			'BAD_CAT'	=> 'Wrong kind',
			'DOUBLE'	=> 'Double employment',
			'OTHER'		=> 'Other',
		],
		'DESCRIPTION' => [
			'NOT'		=> 'Choose a reason from the drop-down list','Choose a reason from the drop-down list',
			'DEAD'		=> 'This video no longer exists, the link is dead',
			'OUT'		=> 'This video is not available',
			'AGE'		=> 'This video is age restricted and only available on YouTube.',
			'BAD'		=> 'This video is of poor quality and should be replaced with a better quality one',
			'OFF_TOPIC'	=> 'This video is off topic, it does not correspond at all to what was expected',
			'SHOCKING'	=> 'This video is shocking, it should not find its place in this hit',
			'BAD_CAT'	=> 'The chosen musical genre is not the right one',
			'DOUBLE'	=> 'Duplicate, this video was already present previously',
			'OTHER'		=> 'The reason does not fit into any other category, use the additional information field',
		],
	],
]);
