<?php

/**
* breizhcharts [German]
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
	'BC_CHARTS'						=> '🎼 Musik Charts',
	'BC_CHARTS_NEW'					=> 'Neue Songs in den Musiù Charts',
	'BC_ACTUAL'						=> 'Aktuell: <span>%s</span>',
	'BC_LATEST'						=> 'Neueste: <span>%s</span>',
	'BC_BEST_POS'					=> 'Besser: <span>%s</span>',
	'BC_AJAX_NOTE_TOTAL'			=> 'Durchschnittsnote: <span class="total">%s</span>',
	'BC_AJAX_NO_VOTE'				=> 'Du darfst keine Lieder bewerten …',
	'BC_AJAX_NOTE'					=> [
		0	=> 'Du hast es nicht bemerkt…',
		1	=> 'Sie haben bereits bemerkt: <span>%s</span>',
	],
	'BC_AJAX_NOTE_NB'				=> [
		0	=> '<span>%s</span> note',
		1	=> '<span>%s</span> note',
		2	=> '<span>%s</span> notes',
	],
	'BC_AJAX_STARS'					=> [
		1	=> 'Beachten Sie %s von 10 Sternen',
		2	=> 'Bewerten Sie %s von 10 Sternen',
	],
	'BC_AJAX_THANKS'				=> 'Danke für die Bewertung!',
	'BC_AJAX_UPDATING'				=> 'Update beachten…',
	'BC_AJAX_VIDEO'					=> 'Das Video existiert…',
	'BC_AJAX_VIDEO_NO'				=> 'Das Video existiert nicht…',
	'BC_ADD_SONG'					=> 'Einen neuen Song hinzufügen',
	'BC_ADDED_BY'					=> 'Hinzugefügt von',
	'BC_ADDED_TIME'					=> 'Eingestellt am:<br /><strong>%1$s</strong>',
	'BC_ALL_TITLE'					=> 'Die Chart Platzierungen - Alle Songs',
	'BC_ALREADY_EXISTS_ERROR'		=> 'Der Song <strong>%1$s</strong> von <strong>%2$s</strong> existiert bereits. Wähle einen neuen Song zum Einstellen.',
	'BC_ALREADY_VOTED'				=> 'Du hast diesen Song bereits bewertet',
	'BC_ENTER'						=> 'Eintrag',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_ANNOUNCE_MSG'				=> 'Hallo Zusammen,' . "\n\n" . 'es wurde ein neuer Song zu den Music Charts hinzugefügt!' . "\n" . '[img]%4$s[/img]' . "\n\n" . '🎶 Titel: [b]%1$s[/b]' . "\n" . '🎸 Künstler: [b]%2$s[/b]' . "\n\n" . 'Klicke [b]%3$s[/b], um zur Liste der neusten Songs zu gelangen.' . "\n\n" . 'Viel Spaß beim Anschauen und Anhören und nicht vergessen abzustimmen!',
	'BC_ANNOUNCE_TITLE'				=> '🎼 %1$s von %2$s',
	'BC_ARTIST_ERROR'				=> 'Du musst schon einen Song Künstler eingeben!',
	'BC_BACKLINK'					=> '%sZurück zur Chartübersicht%s',
	'BC_BACKLINK_ADD'				=> '<br /><br />%sZurück zur Eingabeseite%s',
	'BC_BACKLINK_EDIT'				=> '<br /><br />%sKehren Sie zur Bearbeitungsseite des Songs zurück%s',
	'BC_BEST_RATED'					=> 'Bestplatzierten Songs',
	'BC_BONUS_WINNER'				=> 'Der zufällige Bonus-Gewinner unter den Benutzern, die abgestimmt haben, ist: %1$s<br /><br />Herzlichen Glückwunsch zum Bonus von <strong>%2$s %3$s</strong>!',
	'BC_BONUS_WINNER_HEADER'		=> 'Herzlichen Glückwunsch',
	'BC_CLICK_LINK'					=> 'Hier %sklicken%s, um zu den Charts zurückzukehren',
	'BC_CLICK_VIDEO'				=> '🎬 Klicke hier, um dir das Video anzusehen!',
	'BC_COPYRIGHT'					=> 'Breizh Chart Erweiterung V%1$s durch %2$s',
	'BC_COUNT_ERROR'				=> 'Sorry. Aber wir haben die max. Anzahl von <b>%1$s zugelassenen Einträgen</b> für unsere Charts erreicht.<br />Bitte versuche es später wieder oder bitte den Admin ältere oder nie bewertete Einträge zu entfernen, damit neue Einträge erstellt werden können.',
	'BC_CURR_POS'					=> 'Aktuell',
	'BC_DELETE_SONG'				=> 'Diesen Song löschen',
	'BC_DELETE_SONG_EXPLAIN'		=> 'Willst du den Song wirklich löschen?',
	'BC_DELETE_SUCCESS'				=> 'Der Song <strong>%1$s</strong> wurde erfolgreich gelöscht.',
	'BC_EDIT_SONG'					=> 'Deinen Eintrag bearbeiten',
	'BC_FIELDS_ERROR'				=> 'Du musst zumindest einen Song Titel und einen Künstler/in eingeben!',
	'BC_FROM'						=> ' von ',
	'BC_FROM_OF'					=> '%1$s von %2$s',
	'BC_GO_CHARTS'					=> 'hier',
	'BC_HEADER'						=> 'Music Charts - Eure Charts hier bei uns',
	'BC_HEADER_EXPLAIN'				=> 'Hier könnt Ihr Eure persönlichen Charts erstellen und bewerten. Jeder registrierte Benutzer kann Titel hinzufügen und jeder registrierter Benutzer kann abstimmen. Innerhalb der aktuellen Abstimmungsperiode könnt ihr aber nur einmal pro Song abstimmen. Erst wenn die aktuelle Abstimmungsperiode beendet ist, könnt ihr erneut für eure Favoriten abstimmen.<br /><br />Die Abstimmungsperiode von <strong>%1$s</strong> endet am: <strong>%2$s</strong>',
	'BC_INDEX_WINNER'				=> 'Die letzten Music Charts Gewinner vom %s',
	'BC_LAST_POS'					=> 'Platzierung',
	'BC_LAST_WINNERS'				=> 'Ergebnisse der letzten Abstimmung',
	'BC_LAST_WINNERS_FORMAT'		=> 'l j F Y',
	'BC_LAST_WINNERS_DATE'			=> 'Ergebnisse der Abstimmung vom %s',
	'BC_LAST_WINNERS_PORTAL'		=> 'Die Gewinner aus der letzten Abtimmungsperiode der Music Charts',
	'BC_LAST_WINNERS_SELECT'		=> 'Wählen Sie ein Ergebnis :',
	'BC_MULTI_VOTERS'				=> '<strong>Bis jetzt haben %1$s Benutzer ihre Stimmen abgegeben.</strong>',
	'BC_NEEDED'						=> 'Felder mit [*] sind Pflichtfelder. Alle anderen Angaben sind freiwillig',
	'BC_NEWEST'						=> 'Die neuesten Songs',
	'BC_NEWEST_PERIOD'				=> 'Die neuesten Songs, die während der aktuellen Periode hinzugefügt wurden',
	'BC_NEW_PLACED'					=> 'Dieser Song wurde neu platziert : %s',
	'BC_NEW_SONG'					=> 'Neuer Song',
	'BC_NO_EXISTS'					=> 'Der Song <strong>%1$s</strong> von <strong>%2$s</strong> ist akzeptiert.',
	'BC_NOT_LOGGED_IN'				=> 'Du musst eingeloggt sein, um abstimmen zu können',
	'BC_NO_CHARTS'					=> 'Sorry, es gibt keine Charts zum Anzeigen',
	'BC_NO_SONGS'					=> 'Keine Einträge vorhanden',
	'BC_NO_VOTES'					=> 'In der letzten Periode hat niemand abgestimmt. Daher gibt es auch keine Gewinner.',
	'BC_NO_VOTERS'					=> '<strong>Bis jetzt hat noch kein Benutzer seine Stimme(n) abgegeben.</strong>',
	'BC_NO_WINNER'					=> 'Noch keine Gewinner vorhanden',
	'BC_OK'							=> 'Konforme',
	'BC_OF_USER'					=> 'Songs aus %s',
	'BC_OF_USER_TITLE'				=> '🎶 Alle Songs von anzeigen %s',
	'BC_OWN'						=> 'Meine Songs',
	'BC_OWN_CHARTS'					=> [
		0				=> 'Hat bisher noch keinen Song eingestellt',
		1				=> 'Hat bisher <strong>1</strong> Song eingestellt',
		2				=> 'Hat bisher <strong>%s</strong> Songs eingestellt',
	],
	'BC_PERIOD'						=> 'Periode für die Abstimmung: <strong>%1$s %2$s</strong>',
	'BC_PICTURE_TITLE'				=> 'Das Albumcover zum Song von %1$s',
	'BC_PLACE_LIST_1'				=> 'ersten place',
	'BC_PLACE_LIST_2'				=> 'zweiten place',
	'BC_PLACE_LIST_3'				=> 'dritten place',
	'BC_PM_MESSAGE'					=> 'Hallo %1$s,' . "\n\n" . 'herzlichen Glückwunsch zu deinem [b]%2$s[/b] bei den Music Charts!' . "\n" . 'Der von dir eingestellte Song [b]%3$s[/b] von [b]%4$s[/b] wurde in der letzten Abstimmungsperiode von den Benutzer unseres Boards auf den %2$s gewählt!',
	'BC_PM_MESSAGE_UPS'				=> "\n\n" . 'Als Belohnung dafür, wurden deinem Account [b]%1$s %2$s[/b] gutgeschrieben.',
	'BC_PM_SUBJECT_1'				=> '🥇 Herzlichen Glückwunsch zum ersten platz',
	'BC_PM_SUBJECT_2'				=> '🥈 Herzlichen Glückwunsch zum zweiten platz',
	'BC_PM_SUBJECT_3'				=> '🥉 Herzlichen Glückwunsch zum dritten platz',
	'BC_PM_VOTERS_SUBJECT'			=> '🙌 Herzlichen Glückwunsch zum Bonus-Gewinn',
	'BC_PM_VOTERS_MESSAGE'			=> 'Hallo %1$s,' . "\n\n" . 'Unter all den Benutzern, die an der Abstimmung in den Music Charts teilgenommen haben, wurdest du zum Gewinner des Zusatzgewinns von [b]%2$s %3$s[/b] ausgelost 🤩' . "\n\n" . 'Viel Spaß damit und bleibe uns treu beim Abstimmen!',
	'BC_POSITION_DOWN'				=> 'Fallen : %s',
	'BC_POSITION_EQUAL'				=> 'Gleich : %s',
	'BC_POSITION_UP'				=> 'Steigend : %s',
	'BC_RANK'						=> 'Platz',
	'BC_RATE'						=> 'Wertung',
	'BC_REQUIRED'					=> 'Felder mit einem <strong>*</strong> sind Pflichtfelder und müssen ausgefüllt werden.',
	'BC_REQUIRED_ALBUM_ERROR'		=> 'Du musst ein <strong>Albumnamen</strong> zum Song eingeben.',
	'BC_REQUIRED_VIDEO_ERROR'		=> 'Du musst einen <strong>eingebetteten Code</strong> zu einem Video Clip zum Song eingeben.',
	'BC_REQUIRED_YEAR_ERROR'		=> 'Du musst das <strong>Erscheinungsjahr</strong> eingeben.',
	'BC_SHOW_VIDEO'					=> 'Das Video zum Song %1$s ansehen',
	'BC_SONG_NB'					=> [
		1				=> '%d song',
		2				=> '%d songs',
	],
	'BC_WEEK'						=> [
		1				=> '%d woche',
		2				=> '%d wochen',
	],
	'BC_DAY'						=> [
		1				=> '%d tag',
		2				=> '%d tage',
	],
	'BC_SINGLE_VOTER'				=> '<strong>Bis jetzt hat erst 1 Benutzer seine Stimme(n) abgegeben.</strong>',
	'BC_SONG_ADD_NO'				=> 'Du bist nicht berechtigt, Songs hinzuzufügen.',
	'BC_SONG_ADDED'					=> 'Der Song wurde erfolgreich hinzugefügt.<br />',
	'BC_SONG_ADDED_UPS'				=> 'Der Song wurde erfolgreich hinzugefügt und du hast dafür <strong>%1$s %2$s</strong> erhalten.<br />',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Der Name des Albums aus dem der Song stammt',
	'BC_SONG_ARTIST'				=> 'Künstler/in oder Bandname',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Gib hier den Namen des Künstlers/in oder der Band ein',
	'BC_SONG_EDIT_SUCCESS'			=> 'Der Song <strong>%1$s</strong> wurde erfolgreich bearbeitet.',
	'BC_SONG_TITLE'					=> 'Titel',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Gib hier den Titel des Songs ein',
	'BC_SONG_VIDEO'					=> 'Video Clip',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Geben Sie hier die URL des YouTube-Videos ein.<br />Um es abzurufen, klicken Sie mit der rechten Maustaste in das gewünschte Video und:<br /><em>Kopiere die Video Adresse</em>',
	'BC_SONG_YEAR'					=> 'Jahr',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Das Erscheinungsjahr des Songs oder Albums',
	'BC_TITLE_ERROR'				=> 'Du musst schon einen Song Titel eingeben!',
	'BC_TOP_TEN'					=> 'Zeige Top %s',
	'BC_TOP_XX'						=> 'Die Top %1$s',
	'BC_USER'						=> 'Benutzer',
	'BC_VIEWONLINE'					=> 'Sieht sich die Music Charts an',
	'BC_VOTE_CHECK_FIRST'			=> '<br /><br />Hallo %1$s,<br /><br />Die neue Periode für die aktuellen Music Charts hat begonnen! Nutze am besten gleich die Gelegenheit und bewerte deine Favoriten. Wenn du nichts Spannendes findest, dann stelle doch einfach selbst einen Song ein.<br /><br /><strong>Vielen Dank!</strong>',
	'BC_VOTE_CHECK_LINK'			=> '<strong>%sHier geht’s zu den Music Charts!%s</strong><br /><br />',
	'BC_VOTE_CHECK_SECOND'			=> '<br /><br />Hallo %1$s,<br /><br />In Kürze läuft die aktuelle Bewertungsperiode für die Music Charts ab! Schau am besten nochmal kurz rein, ob vielleicht noch neue Songs dazugekommen sind, die du noch nicht bewertet hast.<br /><br /><strong>Vielen Dank!</strong>',
	'BC_VOTE_SUCCESS'				=> '<strong>Deine Stimme wurde erfolgreich gewertet!</strong><br /><br />Vielen Dank für Deine Bewertung für den Song <strong>%1$s</strong> von <strong>%2$s</strong>.',
	'BC_VOTE_SUCCESS_UPS'			=> '<br />Dir wurden dafür <strong>%1$s %2$s</strong> gutgeschrieben.',
	'BC_VOTED_USERS'				=> [
		1		=> 'Folgender Benutzer hat schon abgestimmt: ',
		2		=> 'Folgende Benutzer haben schon abgestimmt: ',
	],
	'BC_WON'						=> 'Gewinn',
	'BC_WON_VALUE'					=> '%1$s %2$s',
	'ACL_A_BC_MANAGE'				=> 'Kann Musik Charts verwalten', 
	'ACL_U_BC_VIEW'					=> 'Kann Musik Charts anzeigen',
	'ACL_U_BC_VOTE'					=> 'Kann in Musik Charts bewerten',
	'ACL_U_BC_ADD'					=> 'Kann Songs zu Musik Charts hinzufügen',
	'ACL_U_BC_EDIT'					=> 'Kann eigene Songs in Music Charts bearbeiten',
	'GO_TO_YOUTUBE'					=> '<a href="https://www.youtube.com" onclick="window.open(this.href);return false;" title="Gehe zu YouTube">YouTube-Seite</a>',
]);
