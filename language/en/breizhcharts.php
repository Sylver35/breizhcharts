<?php

/**
* breizhcharts [ENGLISH]
*
* @package language
* @copyright (c) 2010 femu - http://die-muellers.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
	'BC_CHARTS_NEW'					=> 'New songs in the Music Charts',
	'BC_ACTUAL'						=> 'Current: <span>%s</span>',
	'BC_LATEST'						=> 'Latest: <span>%s</span>',
	'BC_BEST_POS'					=> 'Better: <span>%s</span>',
	'BC_AJAX_NOTE_TOTAL'			=> 'Average rating: <span>%s</span>',
	'BC_AJAX_NO_VOTE'				=> 'You are not allowed to rate the images…',
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
	'BC_ADDED_TIME'					=> '<i>Added on: <strong>%1$s</strong></i>',
	'BC_ALL_TITLE'					=> 'The Charts - All Songs',
	'BC_ALREADY_EXISTS_ERROR'		=> 'The song <strong>%1$s</strong> from <strong>%2$s</strong> already exists. Please select another song to add.',
	'BC_ALREADY_VOTED'				=> 'You already voted for this song',
	'BC_ENTER'						=> 'Entry',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_ANNOUNCE_MSG'				=> 'Hello everybody,' . "\n\n" . 'There was a new song added to the Music Charts!' . "\n" . '[img]%4$s[/img]' . "\n\n" . '🎶 Title: [b]%1$s[/b]' . "\n" . '🎸 Artist: [b]%2$s [/b]' . "\n\n" . 'Click [b]%3$s[/b] to go the list of the newest songs.' . "\n\n" . 'Have fun watching and listening the new song and don’t forget to vote!',
	'BC_ANNOUNCE_TITLE'				=> '🎼 %1$s of %2$s',
	'BC_ARTIST_ERROR'				=> 'You have to enter an artist!',
	'BC_BACKLINK'					=> '%sBack to the Chart Overview%s',
	'BC_BACKLINK_ADD'				=> '<br /><br />%sBack to the adding page%s',
	'BC_BACKLINK_EDIT'				=> '<br /><br />%sReturn to the song’s edit page%s',
	'BC_BEST_POS'					=> 'Best',
	'BC_BEST_RATED'					=> 'Top ranked songs',
	'BC_BONUS_WINNER'				=> 'The random winner of the bonus price from those users, who voted, is: %1$s<br /><br />Congratulations for the bonus of <strong>%2$s %3$s</strong>!',
	'BC_BONUS_WINNER_HEADER'		=> 'Congratulations',
	'BC_CLICK_LINK'					=> 'Click %shere%s to return to the Charts',
	'BC_CLICK_VIDEO'				=> 'Click here to watch the video!',
	'BC_COPYRIGHT'					=> 'Breizh Chart Extension V%1$s by %2$s',
	'BC_COUNT_ERROR'				=> 'Sorry. We have reached our max. allowed number of entries.<br />Please try again later or ask the Admin to remove some older entries in order to give you the possibility to enter new songs.',
	'BC_COVER_FORMAT_ERROR'			=> 'Album cover is not a valid image',
	'BC_CURR_POS'					=> 'Current',
	'BC_DELETE_SONG'				=> 'Delete Song',
	'BC_DELETE_SONG_EXPLAIN'		=> 'Are you sure you want to delete this song?',
	'BC_DELETE_SUCCESS'				=> 'The song <strong>%1$s</strong> was successfully deleted.',
	'BC_EDIT_SONG'					=> 'Edit your entry',
	'BC_FIELDS_ERROR'				=> 'You have to enter at least a song title and an artist!',
	'BC_FROM'						=> ' from ',
	'BC_FROM_OF'					=> '%1$s from %2$s',
	'BC_GOTO_WEB'					=> 'Goto the web site of %1$s',
	'BC_GO_CHARTS'					=> 'here',
	'BC_HEADER'						=> 'Music Charts - Your Charts over here',
	'BC_HEADER_EXPLAIN'				=> 'Here you can create your own charts and rate them. Every registered user can add songs and every registered user can vote for them. Within the running voting period you only can vote once per song. As soon as the current period ends, you may vote again for your favourite songs.<br /><br />The current voting period ends on: <strong>%1$s</strong>',
	'BC_INDEX_WINNER'				=> 'The last Music-Charts winners from',
	'BC_LAST_POS'					=> 'Ranking',
	'BC_LAST_WINNERS'				=> 'Results of the last vote',
	'BC_LAST_WINNERS_PORTAL'		=> 'The winners from the last Music Charts voting period',
	'BC_MULTI_VOTERS'				=> '<strong>Currently %1$s users voted in our Charts</strong>.',
	'BC_NEEDED'						=> 'Fields marked with [*] have to be filled. All other fields are optional.',
	'BC_NEWEST_XX'					=> 'The newest songs',
	'BC_NEWEST_PERIOD'				=> 'The newest songs, which were added during the current period',
	'BC_NEW_PLACED'					=> 'This song was added new : %s',
	'BC_NEW_SONG'					=> 'New Song',
	'BC_NO_EXISTS'					=> 'The song <strong>%1$s</strong> from <strong>%2$s</strong> is accepted.',
	'BC_NOT_LOGGED_IN'				=> 'You need to be logged in, in order to vote',
	'BC_NO_CHARTS'					=> 'Sorry, currently there are no charts to show',
	'BC_NO_SONGS'					=> 'No entries available',
	'BC_NO_VOTES'					=> 'Nobody voted during the last period. Therefore we have no lucky winners.',
	'BC_NO_VOTERS'					=> '<strong>Currently no user voted in our Charts.</strong>',
	'BC_NO_WINNER'					=> 'No winners yet',
	'BC_OK'							=> 'Compliant',
	'BC_OF_USER'					=> 'Songs from %s',
	'BC_OF_USER_TITLE'				=> 'See all Songs from %s',
	'BC_OWN'						=> 'My Songs',
	'BC_OWN_CHARTS'					=> [
		0				=> 'Didn’t add any song up to now',
		1				=> 'Added <strong>1</strong> song up to now',
		2				=> 'Added <strong>%s</strong> songs up to now',
	],
	'BC_PERIOD'						=> 'Voting Period: <strong>%1$s %2$s</strong>',
	'BC_PICTURE_TITLE'				=> 'The album cover for the song %1$s',
	'BC_PLACE_LIST_1'				=> 'first place',
	'BC_PLACE_LIST_2'				=> 'second place',
	'BC_PLACE_LIST_3'				=> 'third place',
	'BC_PM_MESSAGE'					=> 'Hello %1$s,' . "\n\n" . 'congratulations for your [b]%2$s[/b] in the Music Charts!' . "\n" . 'The song [b]%3$s[/b] from [b]%4$s[/b] you posted in the charts, was voted to the %2$s from the users of our board during the last voting period!',
	'BC_PM_MESSAGE_UPS'				=> "\n\n" . 'As a little gift, we are happy to say, that you earned [b]%1$s %2$s[/b] for this.',
	'BC_PM_SUBJECT_1'				=> '🥇 Congratulations to the first place',
	'BC_PM_SUBJECT_2'				=> '🥈 Congratulations to the second place',
	'BC_PM_SUBJECT_3'				=> '🥉 Congratulations to the third place',
	'BC_PM_VOTERS_SUBJECT'			=> '🙌 Congratualations to the bonus winner',
	'BC_PM_VOTERS_MESSAGE'			=> 'Hello %1$s,' . "\n\n" . 'Out of all users, which take part in the Music Charts, you are the lucky winner of the bonus of [b]%2$s %3$s[/b] 🤩' . "\n\n" . 'Have fun and stay with us in the next election!',
	'BC_POSITION_DOWN'				=> 'Falling : %s',
	'BC_POSITION_EQUAL'				=> 'Equal : %s',
	'BC_POSITION_UP'				=> 'Rising : %s',
	'BC_RANK'						=> 'Rank',
	'BC_RATE'						=> 'Rating',
	'BC_REQUIRED'					=> 'Fields marked with a <strong>[*]</strong> have to be filled.',
	'BC_REQUIRED_COVER_ERROR'		=> 'You have to enter a <strong>link to a cover</strong> for the album.',
	'BC_REQUIRED_ALBUM_ERROR'		=> 'You have to enter an <strong>album</strong> to the song.',
	'BC_REQUIRED_VIDEO_ERROR'		=> 'You have to enter an <strong>embedded code</strong> to a video clip of the song.',
	'BC_REQUIRED_WEBSITE_ERROR'		=> 'You have to enter a <strong>link to the website</strong> of the artist.',
	'BC_REQUIRED_YEAR_ERROR'		=> 'You have to enter the <strong>publishing year</strong> for the song.',
	'BC_SHOW_VIDEO'					=> 'Watch the video for the song %1$s',
	'BC_SONG_NB'					=> [
		1				=> '%d song',
		2				=> '%d songs',
	],
	'BC_WEEK'						=> [
		1				=> '%d week',
		2				=> '%d weeks',
	],
	'BC_DAY'						=> [
		1				=> '%d day',
		2				=> '%d days',
	],
	'BC_SINGLE_VOTER'				=> '<strong>Currently only one user voted in our Charts.</strong>',
	'BC_SONG'						=> 'Title',
	'BC_SONG_ADDED'					=> 'Your song was successfully added.<br />',
	'BC_SONG_ADDED_UPS'				=> 'Your song was successfully added and you earned <strong>%1$s %2$s</strong> for it.<br />',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Name of the album from which the song was extracted',
	'BC_SONG_PICTURE'				=> 'Cover',
	'BC_SONG_ALBUM_PICTURE'			=> 'Album Cover',
	'BC_SONG_PICTURE_EXPLAIN'		=> 'Enter here the complete link to the album cover image (ie. http://www.yourdomain.com/images/cover.jpg)',
	'BC_SONG_ARTIST'				=> 'Name of the artist or the band',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Enter here the name of the artist or the band name',
	'BC_SONG_EDIT_SUCCESS'			=> 'The song <strong>%1$s</strong> was successfully edited.',
	'BC_SONG_TITLE'					=> 'Title',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Enter here the title of the song',
	'BC_SONG_VIDEO'					=> 'Video Clip',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Enter here the url of the YouTube video.<br />To retrieve it, right click in the desired video and:<br /><em>Copy video url</em><br /><br /><strong>Please take care, that you don’t violate any copyrights!</strong>',
	'BC_SONG_WEBSITE'				=> 'Web Site',
	'BC_SONG_WEBSITE_EXPLAIN'		=> 'Enter here the link to the homepage of the artist or the band (complete incl. https://)<br />(ie: https://www.yourdomain.com)',
	'BC_SONG_YEAR'					=> 'Year',
	'BC_SONG_YEAR_EXPLAIN'			=> 'The year where the song or the album was published',
	'BC_TITLE_ERROR'				=> 'You have to enter a song title!',
	'BC_TOP_TEN'					=> 'Show Top %s',
	'BC_TOP_XX'						=> 'The Top %1$s',
	'BC_USER'						=> 'User',
	'BC_VIEWONLINE'					=> 'Is watching the Music Charts',
	'BC_VOTE_CHECK_FIRST'			=> '<br /><br />Hello %1$s,<br /><br />The new rating period for the Music Charts has just started! Take the opportunity and go directly to the charts and rate your favourites. If there’s nothing you like, just add your own song.<br /><br /><strong>Thanks a lot!</strong>',
	'BC_VOTE_CHECK_LINK'			=> '<strong>%sClick here to go to the Charts!%s</strong><br /><br />',
	'BC_VOTE_CHECK_SECOND'			=> '<br /><br />Hello %1$s,<br /><br />The current rating period for the Music Charts is running out shortly! Please take a short moment to visit the charts and check, if there are new songs, which you haven’t rated yet.<br /><br /><strong>Thanks a lot!</strong>',
	'BC_VOTE_SUCCESS'				=> '<strong>Your voting was successfully set!</strong><br /><br />Thanks a lot for your voting for the song <strong>%1$s</strong> from <strong>%2$s</strong>.',
	'BC_VOTE_SUCCESS_UPS'			=> '<br />You received <strong>%1$s %2$s</strong> for your voting.',
	'BC_VOTED_USERS'				=> [
		1		=> 'Following user already voted: ',
		2		=> 'Following users already voted: ',
	],
	'BC_WEBSITE_FORMAT_ERROR'		=> 'The artist or group page link is not a valid url',
	'BC_WON'						=> 'Earnings',
	'BC_WON_VALUE'					=> '%1$s %2$s',
	'BC_YEAR_FORMAT_ERROR'			=> 'The year of the song should really be somewhere between the <strong>20. or 21. century</strong>!',
	'ACL_A_BC_MANAGE'				=> 'Can manage Music Charts',
	'ACL_U_BC_VIEW'					=> 'Can view Music Charts',
	'ACL_U_BC_VOTE'					=> 'Can rate in Music Charts',
	'ACL_U_BC_ADD'					=> 'Can add songs to Music Charts', 
	'ACL_U_BC_EDIT'					=> 'Can edit own songs in Music Charts',
	'GO_TO_YOUTUBE'					=> '<a href="https://www.youtube.com/" onclick="window.open(this.href, \'youtube.com/\', \'height=800, width=900\'); return false;" title="ouvrir youtube en pop up"><img src="https://super-game.be/images/youtube-icone.png" alt="youtube" /></a> ouvrir youtube en pop up<br /> <span style="color: blue;font-weight: bold;">Vous pouvez trouver le manuel d’utilisation ici:</span> <a href="https://super-game.be/viewtopic.php?f=6&t=244">Tuto</a>',
]);
