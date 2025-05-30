<?php

/**
* breizhcharts [German]
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
	'BC_CHARTS'						=> '🎼 Musik Charts',
	'BC_CHARTS_SIMPLE'				=> 'Musik Charts',
	'BC_CHARTS_NEW'					=> 'Neue Songs in den Musiù Charts',
	'BC_ACTUAL'						=> 'Aktuell: <span>%s</span>',
	'BC_LATEST'						=> 'Neueste: <span>%s</span>',
	'BC_BEST_POS'					=> 'Besser: <span>%s</span>',
	'BC_AJAX_NOTE_TOTAL'			=> 'Durchschnittsnote: <span class="total">%s</span>',
	'BC_AJAX_NO_VOTE'				=> 'Du darfst keine Lieder bemerkt…',
	'BC_AJAX_NOTE_NO'				=> 'Sie können nicht bemerkt',
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
	'BC_ADDED_TIME'					=> 'Eingestellt am:<br><strong>%1$s</strong>',
	'BC_ADDED_TIME_SHORT'			=> 'Von : %1$s',
	'BC_ALL_TITLE'					=> 'Die Chart Platzierungen - Alle Songs',
	'BC_ALREADY_EXISTS_ERROR'		=> 'Der song <strong>%1$s</strong> von <strong>%2$s</strong> existiert bereits. Wähle einen neuen Song zum Einstellen.',
	'BC_ALREADY_EXISTS_SIMPLE'		=> 'Der song %1$s existiert bereits. Wähle einen neuen Song zum Einstellen.',
	'BC_ALREADY_VOTED'				=> 'Du hast diesen Song bereits bewertet',
	'BC_ENTER'						=> 'Eintrag',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_COMMENT'					=> 'Kommentar',
	'BC_COMMENT_EXPLAIN'			=> 'Im Forum wird automatisch ein Thema erstellt.<br>Hier können Sie einen Kommentar hinzufügen.',
	'BC_ANNOUNCE_MSG'				=> 'Hallo Zusammen,' . "\n\n" . 'Es wurde ein neuer Song zu den Music Charts hinzugefügt!' . "\n" . '[img]%1$s[/img]' . "\n\n" . '🎶 Titel: [b]%2$s[/b]' . "\n" . '🎸 Künstler: [b]%3$s[/b]' . "\n" . '🎵 Musikgenre: [b]%4$s[/b]' . "\n\n" . '[b]%5$s[/b][b][url=%6$s]%7$s[/url][/b]' . "\n\n" . 'Viel Spaß beim Anschauen und Anhören und nicht vergessen abzustimmen!',
	'BC_ANNOUNCE_TITLE'				=> '🎼 %1$s von %2$s',
	'BC_ANNOUNCE_USER'				=> '👉 Anmerkung des Autors: [quote]%1$s[/quote]' . "\n",
	'BC_ANNOUNCE_SEPARATE'			=> "\n\n &emsp&emsp&emsp&emsp&emsp&emsp----------------------------------------------------- \n",// Separation in topic when needed
	'BC_ARTIST_ERROR'				=> 'Du musst schon einen Song Künstler eingeben!',
	'BC_BACKLINK'					=> '%sZurück zur Chartübersicht%s',
	'BC_BACKLINK_ADD'				=> '<br><br>%sZurück zur Eingabeseite%s',
	'BC_BACKLINK_EDIT'				=> '<br><br>%sKehren Sie zur Bearbeitungsseite des Songs zurück%s',
	'BC_BACKLINK_VIDEO'				=> '<br>%sKehren Sie zur Seite mit der Videoansicht zurück%s',
	'BC_BEST_RATED'					=> 'Bestplatzierten',
	'BC_BONUS_WINNER'				=> 'Der zufällige Bonus-Gewinner unter den Benutzern, die abgestimmt haben, ist: %1$s<br><br>Herzlichen Glückwunsch zum Bonus von <strong>%2$s %3$s</strong>!',
	'BC_BONUS_WINNER_HEADER'		=> 'Herzlichen Glückwunsch',
	'BC_CLICK_LINK'					=> 'Hier %sklicken%s, um zu den Charts zurückzukehren',
	'BC_CLICK_VIDEO'				=> '🎬 Klicke hier, um dir das Video anzusehen!',
	'BC_COPYRIGHT'					=> 'Breizh Chart Erweiterung V%1$s durch %2$s',
	'BC_COUNT_ERROR'				=> 'Sorry. Aber wir haben die max. Anzahl von <b>%1$s zugelassenen Einträgen</b> für unsere Charts erreicht.<br>Bitte versuche es später wieder oder bitte den Admin ältere oder nie bewertete Einträge zu entfernen, damit neue Einträge erstellt werden können.',
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
	'BC_HEADER_EXPLAIN'				=> 'Hier könnt Ihr Eure persönlichen Charts erstellen und bewerten. Jeder registrierte Benutzer kann Titel hinzufügen und jeder registrierter Benutzer kann abstimmen.',
	'BC_HEADER_BIS_EXPLAIN'			=> 'Innerhalb der aktuellen Abstimmungsperiode könnt ihr aber nur einmal pro Song abstimmen.<br>Erst wenn die aktuelle Abstimmungsperiode beendet ist, könnt ihr erneut für eure Favoriten abstimmen.',
	'BC_HEADER_TER_EXPLAIN'			=> '<br><br>Die Abstimmungsperiode von <strong>%1$s</strong> endet am: <strong>%2$s</strong>',
	'BC_HEADER_QUATER_EXPLAIN'		=> '<br><br><strong>! Tipp:</strong> Klicken Sie auf den Spitznamen eines Mitglieds, um die Liste seiner Songs anzuzeigen.',
	'BC_INDEX_WINNER'				=> 'Die letzten Music Charts Gewinner vom %s',
	'BC_LAST_POS'					=> 'Platzierung',
	'BC_LAST_WINNERS'				=> 'Ergebnisse der Abstimmung',
	'BC_LAST_WINNERS_FORMAT'		=> 'l j F Y',
	'BC_LAST_WINNERS_DATE'			=> 'Ergebnisse der Abstimmung vom %s',
	'BC_LAST_WINNERS_PORTAL'		=> 'Die Gewinner aus der letzten Abtimmungsperiode der Music Charts',
	'BC_LAST_WINNERS_SELECT'		=> 'Wählen Sie ein Ergebnis :',
	'BC_MULTI_VOTERS'				=> '<strong>Bis jetzt haben %1$s Benutzer ihre Stimmen abgegeben.</strong>',
	'BC_NEEDED'						=> 'Felder mit [*] sind Pflichtfelder. Alle anderen Angaben sind freiwillig.<br><strong>Funktioniert NUR mit <a href="https://www.youtube.com" onclick="window.open(this.href);return false;">YouTube-Videos</a></strong>',
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
	'BC_NOT_AUTHORISED'				=> 'Sie sind nicht berechtigt, auf das Hitparade-Modul zuzugreifen.',
	'BC_OK'							=> 'Konforme',
	'BC_OF_USER'					=> 'Songs aus %s',
	'BC_OF_USER_TITLE'				=> '🎶 Alle Songs von anzeigen %s',
	'BC_OWN'						=> 'Meine Songs',
	'BC_OWN_CHARTS'					=> [
		0	=> 'Hat bisher noch keinen Song eingestellt',
		1	=> 'Hat bisher <strong>1</strong> Song eingestellt',
		2	=> 'Hat bisher <strong>%s</strong> Songs eingestellt',
	],
	'BC_PERIOD'						=> 'Periode für die Abstimmung: <strong>%1$s %2$s</strong>',
	'BC_PICTURE_TITLE'				=> 'Das Albumcover zum Song von %1$s',
	'BC_PLACE_LIST_1'				=> '🥇 ersten place',
	'BC_PLACE_LIST_2'				=> '🥈 zweiten place',
	'BC_PLACE_LIST_3'				=> '🥉 dritten place',
	'BC_PM_MESSAGE'					=> 'Hallo [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . 'Herzlichen Glückwunsch zu deinem [b]%3$s[/b] bei den Music Charts!' . "\n" . 'Der von dir eingestellte Song [b]%4$s[/b] von [b]%5$s[/b] wurde in der letzten Abstimmungsperiode von den Benutzer unseres Boards auf den [b]%3$s[/b] gewählt!',
	'BC_PM_MESSAGE_UPS'				=> "\n\n" . 'Als Belohnung dafür, wurden deinem Account [b]%1$s %2$s[/b] gutgeschrieben.',
	'BC_PM_SUBJECT_1'				=> '🥇 Herzlichen Glückwunsch zum ersten platz',
	'BC_PM_SUBJECT_2'				=> '🥈 Herzlichen Glückwunsch zum zweiten platz',
	'BC_PM_SUBJECT_3'				=> '🥉 Herzlichen Glückwunsch zum dritten platz',
	'BC_PM_VOTERS_SUBJECT'			=> '🙌 Herzlichen Glückwunsch zum Bonus-Gewinn',
	'BC_PM_VOTERS_MESSAGE'			=> 'Hallo [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . 'Unter all den Benutzern, die an der Abstimmung in den Music Charts teilgenommen haben, wurdest du zum Gewinner des Zusatzgewinns von [b]%3$s %4$s[/b] 🤩' . "\n\n" . 'Viel Spaß damit und bleibe uns treu beim Abstimmen!',
	'BC_POSITION_DOWN'				=> '↘ Fallen : %s',
	'BC_POSITION_EQUAL'				=> '➡ Gleich : %s',
	'BC_POSITION_UP'				=> '↗ Steigend : %s',
	'BC_RANK'						=> 'Platz',
	'BC_RATE'						=> 'Wertung',
	'BC_REQUIRED'					=> 'Felder mit einem <strong>*</strong> sind Pflichtfelder und müssen ausgefüllt werden.',
	'BC_REQUIRED_ALBUM_ERROR'		=> 'Du musst ein <strong>Albumnamen</strong> zum Song eingeben.',
	'BC_REQUIRED_VIDEO_ERROR'		=> 'Du musst einen <strong>eingebetteten Code</strong> zu einem Video Clip zum Song eingeben.',
	'BC_REQUIRED_YEAR_ERROR'		=> 'Du musst das <strong>Erscheinungsjahr</strong> eingeben.',
	'BC_REQUIRED_CAT_ERROR'			=> 'Vous devez définir <strong>le genre musical</strong> de la chanson.',
	'BC_SHOW_VIDEO'					=> 'Das Video zum Song %1$s ansehen',
	'BC_SHOW_VIDEO_AJAX'			=> 'Sehen Sie sich das Video an: ',
	'BC_SHOW_VIDEO_POPUP'			=> 'Sehen Sie sich das Video im Popup an',
	'BC_SEE_TOPIC'					=> 'Thema der Titelpräsentation',
	'BC_SONG_NB'					=> [
		1	=> '%d song',
		2	=> '%d songs',
	],
	'BC_WEEK'						=> [
		1	=> '%d woche',
		2	=> '%d wochen',
	],
	'BC_DAY'						=> [
		1	=> '%d tag',
		2	=> '%d tage',
	],
	'BC_SINGLE_VOTER'				=> '<strong>Bis jetzt hat erst 1 Benutzer seine Stimme(n) abgegeben.</strong>',
	'BC_SONG_RANDOM'				=> 'Zufällige Lieder',
	'BC_SONG_VIEW'					=> 'Anzahl der aufrufe: %d',
	'BC_SONG_VIEW_SHORT'			=> 'Aufrufe: %d',
	'BC_SONG_ADD_NO'				=> 'Du bist nicht berechtigt, Songs hinzuzufügen.',
	'BC_SONG_ADDED'					=> 'Der Song wurde erfolgreich hinzugefügt.<br>',
	'BC_SONG_ADDED_UPS'				=> 'Der Song wurde erfolgreich hinzugefügt und du hast dafür <strong>%1$s %2$s</strong> erhalten.<br>',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Der Name des Albums aus dem der Song stammt',
	'BC_SONG_ARTIST'				=> 'Künstler/in oder Bandname',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Gib hier den Namen des Künstlers/in oder der Band ein',
	'BC_SONG_CAT'					=> 'Musikgenre',
	'BC_SONG_CAT_ALL'				=> 'Alle Musikrichtungen',
	'BC_SONG_CAT_EXPLAIN'			=> 'Wählen Sie das Musikgenre aus, zu dem das vorgeschlagene Lied gehört',
	'BC_SONG_CAT_CHOICE'			=> 'Wählen Sie ein Musikgenre aus',
	'BC_SONG_CAT_SELECT'			=> 'Sortieren Sie nach Musikgenre',
	'BC_SONG_EDIT_SUCCESS'			=> 'Der Song <strong>%1$s</strong> wurde erfolgreich bearbeitet.',
	'BC_SONG_TITLE'					=> 'Titel',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Gib hier den Titel des Songs ein',
	'BC_SONG_VIDEO'					=> 'Video Clip',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Geben Sie hier die URL des YouTube-Videos ein.<br>Um es abzurufen, klicken Sie mit der rechten Maustaste in das gewünschte Video und:<br><em>Kopiere die Video Adresse</em>',
	'BC_SONG_YEAR'					=> 'Jahr',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Das Erscheinungsjahr des Songs oder Albums',
	'BC_TITLE_ERROR'				=> 'Du musst schon einen Song Titel eingeben!',
	'BC_PRESENT_ERROR'				=> 'Video bereits vorhanden',
	'BC_TOP_TEN'					=> 'Zeige Top %s',
	'BC_TOP_XX'						=> 'Die Top %1$s',
	'BC_USER'						=> 'Benutzer',
	'BC_VIEWONLINE'					=> 'Sieht sich die Music Charts an',
	'BC_VOTE_CHECK_FIRST'			=> '<br><br>Hallo %1$s,<br><br>Die neue Periode für die aktuellen Music Charts hat begonnen! Nutze am besten gleich die Gelegenheit und bewerte deine Favoriten. Wenn du nichts Spannendes findest, dann stelle doch einfach selbst einen Song ein.<br><br><strong>Vielen Dank!</strong>',
	'BC_VOTE_CHECK_LINK'			=> '<strong>%sHier geht’s zu den Music Charts!%s</strong><br><br>',
	'BC_VOTE_CHECK_SECOND'			=> '<br><br>Hallo %1$s,<br><br>In Kürze läuft die aktuelle Bewertungsperiode für die Music Charts ab! Schau am besten nochmal kurz rein, ob vielleicht noch neue Songs dazugekommen sind, die du noch nicht bewertet hast.<br><br><strong>Vielen Dank!</strong>',
	'BC_VOTE_SUCCESS'				=> '<strong>Deine Stimme wurde erfolgreich gewertet!</strong><br><br>Vielen Dank für Deine Bewertung für den Song <strong>%1$s</strong> von <strong>%2$s</strong>.',
	'BC_VOTE_SUCCESS_UPS'			=> '<br>Dir wurden dafür <strong>%1$s %2$s</strong> gutgeschrieben.',
	'BC_VOTED_USERS'				=> [
		1	=> '%1$s benutzer hat bereits abgestimmt: ',
		2	=> '%1$s benutzer haben bereits abgestimmt: ',
	],
	'BC_UPLOADERS'				=> [
		1	=> '%1$s benutzer hat Lieder gepostet: ',
		2	=> '%1$s benutzer haben Lieder gepostet: ',
	],
	'BC_WON'						=> 'Gewinn',
	'BC_WON_VALUE'					=> '%1$s %2$s',
	'ACL_A_BC_MANAGE'				=> 'Kann Musik Charts verwalten', 
	'ACL_M_BC_MANAGE'				=> 'Kann die Musikcharts moderieren',
	'ACL_U_BC_VIEW'					=> 'Kann Musik Charts anzeigen',
	'ACL_U_BC_VOTE'					=> 'Kann in Musik Charts bewerten',
	'ACL_U_BC_ADD'					=> 'Kann Songs zu Musik Charts hinzufügen',
	'ACL_U_BC_EDIT'					=> 'Kann eigene Songs in Musik Charts bearbeiten',
	'ACL_U_BC_DELETE'				=> 'Kann Ihre Songs in der Musik Charts löschen',
	'ACL_U_BC_REPORT'				=> 'Kann Songs in Musik charts melden',
	'BC_INVALID_URL'				=> 'Die URL ist keine gültige URL',
	'REPORT_VIDEO'					=> 'Ein Video melden',
	'REPORT_VIDEO_EXPLAIN'			=> 'Mit diesem Bericht können Sie etwaige Probleme mit einem Video melden.<br>Defekter Link, Video entfernt, anstößig oder kann nicht mehr außerhalb von YouTube ausgestrahlt werden.',
	'MORE_INFO'						=> 'Weitere Informationen',
	'CAN_LEAVE_BLANK'				=> 'Dies kann leer gelassen werden.',
	'BC_ERROR_SELECT'				=> 'Sie müssen einen gültigen Grund auswählen',
	'BC_ERROR_SELECT_NO'			=> 'Bitte geben Sie zusätzliche Informationen ein',
	'BC_FORMAT_DATE_PM'				=> 'l, d. F o G:i',// Format date used in pms, to avoid "less than a minute ago" Mittwoch, 25. Dezember 2024, 22:10 Uhr
	'BC_TOOLS'						=> 'Werkzeuge',
	'BC_TOOLS_PAGE'					=> 'Musik Charts Werkzeuge',
	'BC_TOOLS_TITLE'				=> 'Greifen Sie auf Musik Charts Werkzeuge zu',
	'BC_REPORT'						=> 'Video melden',
	'BC_REPORT_INFO'				=> 'Wird zur Signalisierung eines Liedes verwendet',
	'BC_REPORT_TO'					=> 'Video melden <strong>%1$s</strong> von <strong>%2$s</strong>',
	'BC_REPORT_GO'					=> 'Bericht ansehen',
	'BC_REPORT_TITLE'				=> 'Songbericht : %1$s',
	'BC_REPORTED'					=> 'Video gemeldet',
	'BC_REPORTED_BY'				=> 'Berichtet von ',
	'BC_REPORTED_TIME'				=> 'Berichtet am:' . "\n" . ' %1$s',
	'BC_REPORTED_EDIT'				=> 'Gemeldetes Video bearbeiten',
	'BC_REPORT_FROM'				=> 'Dieses Video wurde gemeldet von %1$s<br>» Für die Sache : %2$s<br>» Die %3$s.',
	'BC_REPORT_NEANT'				=> 'Keiner',
	'BC_REPORTED_LIST'				=> 'Liste der Berichte',
	'BC_REPORTED_LIST_TITLE'		=> 'Sehen Sie sich die Liste der offenen Berichte an',
	'BC_REPORT_ACTIONS'				=> 'Maßnahmen zur Behebung dieses Berichts',
	'BC_REPORT_EDIT_CLOSE'			=> 'Schließen Sie das Panel',
	'BC_REPORT_INFORM'				=> 'Informieren Sie den Benutzer %1$s',
	'BC_REPORT_NO_REPORT'			=> 'Für dieses Video gibt es keinen offenen Bericht',
	'BC_REPORT_CLOSE'				=> 'Bericht schließen',
	'BC_REPORT_CLOSE_END'			=> 'Der angeforderte Bericht ist dauerhaft geschlossen',
	'BC_REPORT_CLOSE_CONTACT'		=> 'Schließen Sie den Bericht und benachrichtigen Sie die Benutzer',
	'BC_REPORT_CLOSE_NO_REASON'		=> 'Schließen Sie den Bericht, da der Grund nicht gut ist…<br>%1$s und %2$s wird über die Entscheidung informiert',
	'BC_REPORT_CLOSE_NO_REASON_OWN'	=> 'Schließen Sie den Bericht, da der Grund nicht gut ist…<br>%1$s wird über die Entscheidung informiert',
	'BC_REPORT_CLOSE_FINISH'		=> 'Der Bericht ist nun geschlossen und %1$s wird informiert',
	'BC_REPORT_CLOSE_FINISH_TO'		=> 'Der Bericht ist nun geschlossen, %1$s und %2$s wird informiert',
	'BC_REPORT_BACKLINK'			=> '%sZurück zur Liste der Berichte%s',
	'BC_REPORT_BACKLINK_OWN'		=> '%sKehren Sie zu Ihrer Songliste zurück%s',
	'BC_REPORT_INFOS'				=> 'Informationen zu diesem Bericht finden Sie am Anfang der Nachricht.',
	'BC_REPORTED_THANKS'			=> 'Vielen Dank, dass Sie sich die Zeit genommen haben, darüber zu berichten : %1$s.<br> Das Mitglied, das es gepostet hat, und die Moderatoren wird informiert.',
	'BC_REPORTED_ON'				=> 'Video gemeldet: [b]%1$s[/b] von [b]%2$s[/b]',
	'BC_REPORT_SEND_SUBJECT'		=> 'Berichten Sie über Ihr Lied : %1$s',
	'BC_REPORT_SEND_MESSAGE'		=> 'Hallo [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . '[b][color=#%4$s]%3$s[/color][/b] kontaktiert Sie für einen offenen Bericht bzgl : %5$s.' . "\n" . 'Berichtet von [b][color=#%7$s]%6$s[/color][/b] der %8$s.' . "\n\n" . '» Aus dem Grund : %9$s' . "\n" . '» %10$s' . "\n" . '%11$s' . "\n\n" . '» %12$s',
	'BC_REPORT_SEND_FINISH'			=> 'Die private Nachricht wird an gesendet %1$s',
	'BC_PM_REPORT_SUBJECT'			=> 'Gemeldete Videomusik-Charts: %1$s',
	'BC_PM_REPORT_MESSAGE'			=> 'Hallo [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . ' Sie erhalten diese private Nachricht, weil gerade ein Video von gemeldet wurde [b][color=#%4$s]%3$s[/color][/b] gerade ein Video gemeldet hat' . "\n" . '» auf %5$s.' . "\n\n" . '» %6$s' . "\n" . '» Veröffentlicht von [b][color=#%8$s]%7$s[/color][/b]' . "\n\n" . '» Grund %9$s' . "\n" . '» Link melden: [url=%10$s]Sehen Sie sich den Bericht an[/url]',
	'BC_PM_REPORT_CLOSE'			=> 'Hallo [b][color=#%2$s]%1$s[/color][/b],' . "\n\n" . ' Sie erhalten diese private Nachricht, weil der Bericht über Sie vorliegt %3$s, berichtet von [b][color=#%6$s]%5$s[/color][/b], aus dem Grund : %4$s, wurde gerade geschlossen [b][color=#%8$s]%7$s[/color][/b] von %9$s',
	'BC_PM_REPORT_CLOSE_TO'			=> 'Hallo [b][color=#%6$s]%5$s[/color][/b],' . "\n\n" . ' Sie erhalten diese private Nachricht aus dem Grund, weil Sie den Bericht geöffnet haben : %4$s, bezüglich der %3$s, gepostet von [b][color=#%2$s]%1$s[/color][/b], wurde gerade geschlossen [b][color=#%8$s]%7$s[/color][/b] von %9$s',
	'BC_REPORT_REASON'				=> 'Grund angegeben',
	'bc_report_reasons'				=> [
		'TITLE'	=> [
			'NOT'		=> 'Wählen Sie einen Grund',
			'DEAD'		=> 'Toter Link',
			'OUT'		=> 'Nicht verfügbar',
			'AGE'		=> 'Altersbeschränkung',
			'BAD'		=> 'Schlechte Qualität',
			'OFF_TOPIC'	=> 'Nicht zum Thema gehörend',
			'SHOCKING'	=> 'Schockierendes Video',
			'BAD_CAT'	=> 'Schlechte Art',
			'DOUBLE'	=> 'Doppelbeschäftigung',
			'OTHER'		=> 'Andere',
			'AUTO'		=> 'Automatisch',
		],
		'DESCRIPTION' => [
			'NOT'		=> 'Wählen Sie einen Grund aus der Dropdown-Liste',
			'DEAD'		=> 'Dieses Video existiert nicht mehr, der Link ist tot',
			'OUT'		=> 'Dieses Video ist nicht verfügbar',
			'AGE'		=> 'Dieses Video ist altersbeschränkt und nur auf YouTube verfügbar',
			'BAD'		=> 'Dieses Video ist von schlechter Qualität und sollte durch ein Video mit besserer Qualität ersetzt werden',
			'OFF_TOPIC'	=> 'Dieses Video ist nicht thematisch, es entspricht überhaupt nicht dem, was erwartet wurde',
			'SHOCKING'	=> 'Dieses Video ist schockierend, es sollte in diesem Hit nicht seinen Platz finden',
			'BAD_CAT'	=> 'Das gewählte Musikgenre ist nicht das richtige',
			'DOUBLE'	=> 'Doppelte Verwendung, dieses Video war bereits vorher vorhanden',
			'OTHER'		=> 'Vernunft passt in keine andere Kategorie, Verwenden Sie das Feld für zusätzliche Informationen',
			'AUTO'		=> 'Das automatische meldesystem hat dieses video aufgrund eines zurückgegebenen fehlers gekennzeichnet',
		],
	],
	'BC_AUTO_2'			=> 'Die Anfrage enthält einen ungültigen Parameterwert. Dieser Fehler tritt beispielsweise auf, wenn du eine Video-ID angibst, die nicht aus elf Zeichen besteht, oder wenn die Video-ID ungültige Zeichen wie z. B. Ausrufezeichen oder Sternchen enthält.',
	'BC_AUTO_5'			=> 'Der angeforderte Inhalt kann nicht mit einem HTML5-Player wiedergegeben werden oder es ist ein anderer Fehler im Zusammenhang mit dem HTML5-Player aufgetreten.',
	'BC_AUTO_100'		=> 'Der angeforderte Video wurde nicht gefunden. Dieser Fehler tritt auf, wenn ein Video aus irgendeinem Grund entfernt oder als privat markiert wurde.',
	'BC_AUTO_101'		=> 'Der Rechteinhaber des angeforderten Videos untersagt die Wiedergabe des Videos in eingebetteten Playern.',
	'BC_AUTO_150'		=> 'Dieser Fehler ist mit 101 identisch. Es handelt sich eigentlich um einen 101-Fehler.',
	'BC_AUTO_NAME'		=> 'automatisches meldesystem',
	'BC_AUTO_RETURN'	=> "\n" . 'Fehler zurückgegeben (%1$s): ',
	'BC_101_RETURN'		=> "\n" . '101-Fehler: ',
]);
