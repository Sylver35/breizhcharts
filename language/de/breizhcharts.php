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
	'BC_CHARTS'						=> 'Musik Charts',
	'BC_ACTUAL'						=> 'Aktuell: ',
	'BC_LATEST'						=> 'Neueste: ',
	'BC_AVERAGE'					=> 'Durchschnitt: %s',
	'BC_ADD_SONG'					=> 'Einen neuen Song hinzufügen',
	'BC_ALL_TITLE'					=> 'Die Chart Platzierungen - Alle Songs',
	'BC_ADDED_BY'					=> 'Hinzugefügt von',
	'BC_ADDED_TIME'					=> '<i>Eingestellt am: <strong>%1$s</strong></i>',
	'BC_ALREADY_EXISTS_ERROR'		=> 'Der Song <strong>%1$s</strong> von <strong>%2$s</strong> existiert bereits. Wähle einen neuen Song zum Einstellen.',
	'BC_ALREADY_VOTED'				=> 'Du hast diesen Song bereits bewertet',
	'DM_ENTER'						=> 'Eintrag',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_ANNOUNCE_MSG'				=> 'Hallo Zusammen,~~es wurde ein neuer Song zu den Music Charts hinzugefügt!~[img]%4$s[/img]~Titel: [b]%1$s[/b]~Künstler: [b]%2$s[/b]~~Klicke [b]%3$s[/b], um zur Liste der neusten Songs zu gelangen!~~Viel Spaß beim Anschauen und Anhören und nicht vergessen abzustimmen!',
	'BC_ANNOUNCE_TITLE'				=> '[NEU] %1$s von %2$s',
	'BC_ARTIST_ERROR'				=> 'Du musst schon einen Song Künstler eingeben!',
	'BC_BACKLINK'					=> '%sZurück zur Chartübersicht%s',
	'BC_BACKLINK_ADD'				=> '<br /><br />%sZurück zur Eingabeseite%s',
	'BC_BACKLINK_EDIT'				=> '<br /><br />%sKehren Sie zur Bearbeitungsseite des Songs zurück%s',
	'BC_BEST_POS'					=> 'Beste Platzierung',
	'BC_BEST_RATED'					=> 'Bestplatzierten Songs',
	'ACL_U_BC_VIEW'					=> 'Darf Music Charts ansehen',
	'ACL_U_BC_VOTE'					=> 'Darf Music Charts bewerten',
	'ACL_U_BC_ADD'					=> 'Darf Music Charts Songs hinzufügen',
	'ACL_U_BC_EDIT'					=> 'Darf eigene Music Charts Songs bearbeiten', 
	'ACL_A_BC_MANAGE'				=> 'Darf  Music Charts verwalten', 
	'BC_BONUS_WINNER'				=> 'Der zufällige Bonus-Gewinner unter den Benutzern, die abgestimmt haben, ist: %1$s<br /><br />Herzlichen Glückwunsch zum Bonus von <strong>%2$s %3$s</strong>!',
	'BC_BONUS_WINNER_HEADER'		=> 'Herzlichen Glückwunsch',
	'BC_CLICK_LINK'					=> 'Hier %sklicken%s, um zu den Charts zurückzukehren',
	'BC_CLICK_VIDEO'				=> 'Klicke hier, um dir das Video anzusehen!',
	'BC_COPYRIGHT'					=> 'Breizh Chart Erweiterung V%1$s durch %2$s',
	'BC_COUNT_ERROR'				=> 'Sorry. Aber wir haben die max. Anzahl von <b>%1$s zugelassenen Einträgen</b> für unsere Charts erreicht.<br />Bitte versuche es später wieder oder bitte den Admin ältere oder nie bewertete Einträge zu entfernen, damit neue Einträge erstellt werden können.',
	'BC_COVER_FORMAT_ERROR'			=> 'Das Albumcover ist kein gültiges Bild',
	'BC_CURR_POS'					=> 'Aktuell',
	'BC_DELETE_SONG'				=> 'Diesen Song löschen',
	'BC_DELETE_SONG_REGULAR'		=> 'Willst du den Song wirklich löschen?',
	'BC_DELETE_SONG_UPS'			=> 'Willst du den Song wirklich löschen und vom Benutzer die %1$s wieder entfernen, die er dafür bekommen hat?',
	'BC_DELETE_SUCCESS'				=> 'Der Song <strong>%1$s</strong> wurde erfolgreich gelöscht.',
	'BC_EDIT_SONG'					=> 'Deinen Eintrag bearbeiten',
	'BC_EMBED_FORMAT_ERROR'			=> 'Der eingebettete Code muß mit so was wie <strong>object</strong> oder <strong>iframe</strong> beginnen!',
	'BC_FIELDS_ERROR'				=> 'Du musst zumindest einen Song Titel und einen Künstler/in eingeben!',
	'BC_FIRST'						=> '1. Platz',
	'BC_FROM'						=> 'von',
	'BC_GOTO_WEB'					=> 'Gehe zur Webseite von %1$s',
	'BC_GO_CHARTS'					=> 'hier',
	'BC_HEADER'						=> ' Music Charts - Eure Charts hier bei uns',
	'BC_HEADER_EXPLAIN'				=> 'Hier könnt Ihr Eure persönlichen Charts erstellen und bewerten. Jeder registrierte Benutzer kann Titel hinzufügen und jeder registrierter Benutzer kann abstimmen. Innerhalb der aktuellen Abstimmungsperiode könnt ihr aber nur einmal pro Song abstimmen. Erst wenn die aktuelle Abstimmungsperiode beendet ist, könnt ihr erneut für eure Favoriten abstimmen. <strong>Ihr könnt natürlich auch mehreren Songs eure Stimme geben!</strong><br /><br />Die aktuelle Abstimmungsperiode endet am: <strong>%1$s</strong><br /><br /><strong>Viel Spaß beim Abstimmen!</strong>',
	'BC_HOT'						=> 'Top: %s',
	'BC_INDEX_WINNER'				=> 'Die letzten Music Charts Gewinner vom',
	'BC_LAST'						=> 'Die letzten Gewinner',
	'BC_LAST_POS'					=> 'Platzierung',
	'BC_LAST_WINNERS'				=> 'Ergebnisse der letzten Abstimmung',
	'BC_LAST_WINNERS_PORTAL'		=> 'Die Gewinner aus der letzten Abtimmungsperiode der Music Charts',
	'BC_MULTI_VOTERS'				=> '<strong>Bis jetzt haben %1$s Benutzer ihre Stimmen abgegeben.</strong>',
	'BC_NEEDED'						=> 'Felder mit [*] sind Pflichtfelder. Alle anderen Angaben sind freiwillig',
	'BC_NEWEST_XX'					=> 'Die neuesten Songs',
	'BC_NEWEST_PERIOD'				=> 'Die neuesten Songs, die während der aktuellen Periode hinzugefügt wurden',
	'BC_NEW_PLACED'					=> 'Dieser Song wurde neu platziert : %s',
	'BC_NEW_SONG'					=> 'Neuer Song',
	'BC_NO_EXISTS'					=> 'Der Song <strong>%1$s</strong> von <strong>%2$s</strong> ist akzeptiert.',
	'BC_NOT'						=> 'Flop: %s',
	'BC_NOT_LOGGED_IN'				=> 'Du musst eingeloggt sein, um abstimmen zu können',
	'BC_NO_CHARTS'					=> 'Sorry, es gibt keine Charts zum Anzeigen',
	'BC_NO_SONGS'					=> 'Keine Einträge vorhanden',
	'BC_NO_VOTES'					=> 'In der letzten Periode hat niemand abgestimmt. Daher gibt es auch keine Gewinner.',
	'BC_NO_VOTERS'					=> '<strong>Bis jetzt hat noch kein Benutzer seine Stimme(n) abgegeben.</strong>',
	'BC_NO_WINNER'					=> 'Noch keine Gewinner vorhanden',
	'BC_OK'							=> 'Konforme',
	'BC_OF_USER'					=> 'Songs aus %s',
	'BC_OF_USER_TITLE'				=> 'Alle Songs von anzeigen %s',
	'BC_OWN'						=> 'Meine Songs',
	'BC_OWN_CHARTS'					=> [
		0				=> 'Hat bisher noch keinen Song eingestellt',
		1				=> 'Hat bisher <strong>1</strong> Song eingestellt',
		2				=> 'Hat bisher <strong>%s</strong> Songs eingestellt',
	],
	'BC_PERIOD'						=> 'Periode für die Abstimmung: <strong>%1$s %2$s</strong>',
	'BC_PICTURE_HOT_TITLE'			=> 'Hier klicken, wenn Dir der Song von %1$s gefällt',
	'BC_PICTURE_NOT_TITLE'			=> 'Hier klicken, wenn Dir der Song von %1$s nicht gefällt',
	'BC_PICTURE_TITLE'				=> 'Das Albumcover zum Song von %1$s',
	'BC_PM_MESSAGE'					=> 'Hallo %1$s, herzlichen Glückwunsch zu deinem [b]%2$s. Platz[/b] bei den  Music Charts! Der von dir eingestellte Song [b]%3$s[/b] von [b]%4$s[/b] wurde in der letzten Abstimmungsperiode von den Benutzer unseres Boards auf den %2$s. Platz gewählt!',
	'BC_PM_MESSAGE_UPS'				=> 'Hallo %1$s, herzlichen Glückwunsch zu deinem [b]%2$s. Platz[/b] bei den  Music Charts! Der von dir eingestellte Song [b]%3$s[/b] von [b]%4$s[/b] wurde in der letzten Abstimmungsperiode von den Benutzer unseres Boards auf den %2$s. Platz gewählt! Als Belohnung dafür, wurden deinem Account [b]%5$s %6$s[/b] gutgeschrieben.',
	'BC_PM_SUBJECT'					=> 'Herzlichen Glückwunsch zum %s. Platz!',
	'BC_PM_VOTERS_SUBJECT'			=> 'Herzlichen Glückwunsch zum Bonus-Gewinn!',
	'BC_PM_VOTERS_MESSAGE'			=> 'Hallo %1$s,<br /><br />unter all den Benutzern, die an der Abstimmung in den Music Charts teilgenommen haben, wurdest du zum Gewinner des Zusatzgewinns von <strong>%2$s %3$s</strong> ausgelost.<br /><br />Viel Spaß damit und bleibe uns treu beim Abstimmen!',
	'BC_PORTAL_ARTIST'				=> 'Künstler',
	'BC_PORTAL_GOTO'				=> 'Hier geht’s zu den Charts',
	'BC_PORTAL_HEADER'				=> 'Die aktuellen Music Charts',
	'BC_PORTAL_POSTER'				=> 'eingestellt von',
	'BC_PORTAL_TITLE'				=> 'Titel',
	'BC_POSITION_DOWN'				=> 'Fallen : %s',
	'BC_POSITION_EQUAL'				=> 'Gleich : %s',
	'BC_POSITION_UP'				=> 'Steigend : %s',
	'BC_RANK'						=> 'Platz',
	'BC_RATE'						=> 'Wertung',
	'BC_REQUIRED'					=> 'Felder mit einem <strong>*</strong> sind Pflichtfelder und müssen ausgefüllt werden.',
	'BC_REQUIRED_COVER_ERROR'		=> 'Du musst einen <strong>Link zu einem Album Cover</strong> eingeben.',
	'BC_REQUIRED_ALBUM_ERROR'		=> 'Du musst ein <strong>Albumnamen</strong> zum Song eingeben.',
	'BC_REQUIRED_VIDEO_ERROR'		=> 'Du musst einen <strong>eingebetteten Code</strong> zu einem Video Clip zum Song eingeben.',
	'BC_REQUIRED_WEBSITE_ERROR'		=> 'Du musst einen <strong>Link zur Website</strong> des Künstlers eingeben.',
	'BC_REQUIRED_YEAR_ERROR'		=> 'Du musst das <strong>Erscheinungsjahr</strong> eingeben.',
	'BC_SECOND'						=> '2. Platz',
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
	'BC_SONG'						=> 'Titel',
	'BC_SONG_ADDED'					=> 'Der Song wurde erfolgreich hinzugefügt.<br />',
	'BC_SONG_ADDED_UPS'				=> 'Der Song wurde erfolgreich hinzugefügt und du hast dafür <strong>%1$s %2$s</strong> erhalten.<br />',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Der Name des Albums aus dem der Song stammt',
	'BC_SONG_ALBUM_PICTURE'			=> 'Albumcover',
	'BC_SONG_PICTURE_EXPLAIN'		=> 'Gib hier den Link zu einem Album Cover Bild ein (z.B. http://www.yourdomain.com/images/cover.jpg)',
	'BC_SONG_ARTIST'				=> 'Künstler/in oder Bandname',
	'BC_SONG_ARTIST_ADD'			=> '[*] Künstler/in oder Bandname',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Gib hier den Namen des Künstlers/in oder der Band ein',
	'BC_SONG_EDIT_SUCCESS'			=> 'Der Song <strong>%1$s</strong> wurde erfolgreich bearbeitet.',
	'BC_SONG_TITLE'					=> 'Titel',
	'BC_SONG_TITLE_ADD'				=> '[*] Titel',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Gib hier den Titel des Songs ein',
	'BC_SONG_VIDEO'					=> 'Video Clip',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Gib hier den Code zum Einbetten eines Videos ein, den du z.B. von YouTube, MyVideo oder anderen Providern erhälst. Achte darauf, daß die Videogröße von <strong>640 x 505</strong> nicht überschritten wird (siehst du innerhalb des Codes)!<br /><br />Um das Video füllend einzurichten, kannst du die Größe im Code entsprechend anpassen. Wenn z.B. 400 x 291 als Größe angegeben wurde, kannst du die neue Größe wie folgt errechnen: 640 x (640 mal 291 durch 400). Daraus ergibt sich die neue Größe von 640 x 466. So bleibt auch das Seitenverhältnis erhalten!<br /><br /><strong>Bitte stelle auch sicher, daß Du nur auf Videos verlinkst, die keine Urheberrechte verletzen!</strong>',
	'BC_SONG_WEBSITE'				=> 'Webseite',
	'BC_SONG_WEBSITE_EXPLAIN'		=> 'Der Link zum Künstler, zur Künstlerin oder der Band (komplett mit http://)',
	'BC_SONG_YEAR'					=> 'Jahr',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Das Erscheinungsjahr des Songs oder Albums',
	'BC_THIRD'						=> '3. Platz',
	'BC_TITLE_ERROR'				=> 'Du musst schon einen Song Titel eingeben!',
	'BC_TOP_TEN'					=> 'Zeige Top %s',
	'BC_TOP_XX'						=> 'Die Top %1$s',
	'BC_USER'						=> 'Benutzer',
	'BC_VIDEO_EXIST_ERROR'			=> 'Die eingegebene Video ID aus der  Video Mod existiert nicht. Bitte prüfe deine Eingabe!',
	'BC_VIDEO_NO'					=> ' Video ID',
	'BC_VIDEO_NO_EXPLAIN'			=> 'Die  Video Mod ist hier installiert. Wenn das Video, das du hier einsetzen möchtest, dort bereits existiert, kannst du einfach die ID aus der  Video Mod übernehmen. Du findest die ID, indem du dir im  Video das gewünschte Video anschaust. In der Browserzeile nimmst du die Zahl, die hinter dem v= steht. Diese trägst du dann hier ein. Bei Verwendung der ID brauchst du keinen Code für die Einbettung eingeben.',
	'BC_VIEWONLINE'					=> 'Sieht sich die Music Charts an',
	'BC_VOTE_CHECK_FIRST'			=> '<br /><br />Hallo %1$s,<br /><br />Die neue Periode für die aktuellen Music Charts hat begonnen! Nutze am besten gleich die Gelegenheit und bewerte deine Favoriten. Wenn du nichts Spannendes findest, dann stelle doch einfach selbst einen Song ein.<br /><br /><strong>Vielen Dank!</strong>',
	'BC_VOTE_CHECK_LINK'			=> '<strong>%sHier geht’s zu den Music Charts!%s</strong><br /><br />',
	'BC_VOTE_CHECK_SECOND'			=> '<br /><br />Hallo %1$s,<br /><br />In Kürze läuft die aktuelle Bewertungsperiode für die Music Charts ab! Schau am besten nochmal kurz rein, ob vielleicht noch neue Songs dazugekommen sind, die du noch nicht bewertet hast.<br /><br /><strong>Vielen Dank!</strong>',
	'BC_VOTE_SUCCESS'				=> '<strong>Deine Stimme wurde erfolgreich gewertet!</strong><br /><br />Vielen Dank für Deine Bewertung für den Song <strong>%1$s</strong> von <strong>%2$s</strong>.<br />Du kannst Deine Stimme erneut abgeben, sobald die aktuelle Periode zurückgesetzt wurde.',
	'BC_VOTE_SUCCESS_UPS'			=> '<strong>Deine Stimme wurde erfolgreich gewertet!</strong><br /><br />Vielen Dank für Deine Bewertung für den Song <strong>%1$s</strong> von <strong>%2$s</strong>.<br />Dir wurden dafür <strong>%3$s %4$s</strong> gutgeschrieben!<br /><br />Du kannst Deine Stimme erneut abgeben, sobald die aktuelle Periode zurückgesetzt wurde.',
	'BC_VOTED_USERS'				=> [
		1		=> 'Folgender Benutzer hat schon abgestimmt: ',
		2		=> 'Folgende Benutzer haben schon abgestimmt: ',
	],
	'BC_WEBSITE_FORMAT_ERROR'		=> 'Der Link zur Künstler- oder Gruppenseite ist keine gültige URL',
	'BC_WON'						=> 'Gewinn',
	'BC_WON_VALUE'					=> '%1$s %2$s',
	'BC_YEAR_FORMAT_ERROR'			=> 'Das Jahr muß schon aus dem <strong>20. oder 21. Jahrhundert</strong> sein!',
	'GO_TO_YOUTUBE'					=> '<a href="https://www.youtube.com/" onclick="window.open(this.href, \'youtube.com/\', \'height=800, width=900\'); return false;" title="ouvrir youtube en pop up"><img src="https://super-game.be/images/youtube-icone.png" alt="youtube" /></a> ouvrir youtube en pop up<br /> <span style="color: blue;font-weight: bold;">Vous pouvez trouver le manuel d’utilisation ici:</span> <a href="https://super-game.be/viewtopic.php?f=6&t=244">Tuto</a>',
]);
