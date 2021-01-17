<?php

/**
* info_acp_breizhcharts [German]
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
	'BC_ACP_PAGE'					=> 'Anzahl Einträge pro Seite (im ACP)',
	'BC_ACP_PAGE_EXPLAIN'			=> 'Gib hier an, wieviele Charteintrage im ACP pro Seite sichtbar sein sollen',
	'BC_ADD'						=> 'Hinzufügen',
	'BC_ALL_CHARTS'					=> 'Alle Einträge',
	'BC_ANNOUNCE_SETTINGS'			=> 'Ankündigungseinstellungen',
	'BC_CALENDAR'					=> 'Kalender',
	'BC_DATE'						=> 'l j F Y, H:i',
	'BC_CHECK_1_ENABLE'				=> 'Benutzer über neue Periode informieren?',
	'BC_CHECK_1_ENABLE_EXPLAIN'		=> 'Wähle aus, ob die Benutzer darüber informiert werden sollen, wenn eine neue Abstimmungsperiode beginnt. Die Information wird einmalig angezeigt.',
	'BC_CHECK_2_ENABLE'				=> 'Die Benutzer vor Ende der Periode informieren?',
	'BC_CHECK_2_ENABLE_EXPLAIN'		=> 'Wähle aus, ob die Benutzer darüber informiert werden sollen, wenn sich die aktuelle Abstimmungsperiode dem Ende neigt. Dazu benötigst du die u.s. Zeitangabe. Die Information wird einmalig angezeigt. <strong>Obige Option muß ebenfalls aktiviert sein!</strong>',
	'BC_CHECK_TIME'					=> 'Zeitraum',
	'BC_CHECK_TIME_EXPLAIN'			=> 'Gib hier an, wieviel Stunden vor dem Ende der aktuellen Abstimmungsperiode die Benutzer darüber informiert werden sollen. Gib die Zeit in Stunden an (z.B. 24)!',
	'BC_CLICK_RETURN'				=> 'Hier %sklicken%s, um zur Chart Verwaltung zurückzukehren',
	'BC_CONFIG'						=> 'Konfiguration',
	'BC_CONFIG_ANNOUNCE_ENABLE'		=> 'Neue Charts Songs ankündigen',
	'BC_CONFIG_ANNOUNCE_ENABLE_EX'	=> 'Setze hier Ja, wenn neue Chart Songs im unten gewählten Forum angekündigt werden sollen',
	'BC_CONFIG_ANNOUNCE_FORUM'		=> 'Wähle ein Forum',
	'BC_CONFIG_ANNOUNCE_FORUM_EX'	=> 'Wähle hier das Forum aus, in dem neue Chart Songs angekündigt werden',
	'BC_CONFIG_EXPLAIN'				=> 'Hier kannst Du einzelne Vorgaben setzen oder bearbeiten. Lese dir die jeweiligen Erkläreungen zu den einzelnen Feldern aufmerksam durch!',
	'BC_CONFIG_MAIN_SETTINGS'		=> 'Einstellungen',
	'BC_CONFIG_OK'					=> 'Die Chart Konfiguration wurde erfolgreich aktualisiert',
	'BC_CONFIG_TITLE'				=> ' Music Charts Konfiguration',
	'BC_CONFIG_UPDATED'				=> 'Die Konfiguration wurde erfolgreich aktualisiert',
	'BC_CONF_CLICK_RETURN'			=> 'Klicke %shier%s, um zur Chart Konfiguration zurückzukehren',
	'BC_DELETE_OK'					=> 'Song wurde erfolgreich gelöscht.',
	'BC_EDIT'						=> 'Bearbeite Chart Song',
	'BC_PLACE_FIRST'						=> '%1$s für den 1. Platz',
	'BC_FROM_NAME'					=> 'Eingestellt von',
	'BC_FROM_OF'					=> '%1$s of %2$s',
	'BC_HOURS'						=> 'Std.',
	'BC_LAST_RANK'					=> 'Letzte Platzierung',
	'BC_LAST_VOTERS_WINNER_ID'		=> 'Benutzer-ID des letzten Gewinners',
	'BC_LAST_VOTERS_WINNER_ID_EX'	=> 'Hier wird die Benutzer ID des letzten Gewinners der Bonusrunde angezeigt.',
	'BC_MANAGE'						=> 'Charts verwalten',
	'BC_MAX_ENTRIES'				=> 'Max. Anzahl an Einträgen',
	'BC_MAX_ENTRIES_EXPLAIN'		=> 'Gib hier die max. Anzahl an Einträgenein, die insgesamt eingetragen werden können. Dieser Wert wird auch für die Option <b>Alle zeigen</b> verwendet. Dieser Wert muss größer, als der Top xx Wert sein! Setze 0, um beliebig viele Einträge zuzulassen.',
	'BC_MAX_PER_PAGE'				=> 'Anzahl Einträge pro Seite (Benutzer)',
	'BC_MAX_PER_PAGE_EXPLAIN'		=> 'Gib hier an, wieviel Einträge pro Seite auf der Benutzerebene angezeigt werden sollen, wenn <strong>Zeige alle Songs</strong> gewählt wird.',
	'BC_NEED_ARTIST'				=> 'Du musst schon einen Interpreten eingeben',
	'BC_NEED_DATA'					=> 'Es fehlen erforderliche Daten',
	'BC_NEED_TITLE'					=> 'Du musst schon einen Titel eingeben',
	'BC_NEXT_RESET'					=> 'Nächstes Datum zum Zurücksetzen',
	'BC_NEXT_RESET_EXPLAIN'			=> 'Hier siehst du, wann die Charts das nächste Mal zurückgesetzt werden. Wenn du die Startzeit oben änderst, mußt du möglicherweise die Konfiguration 2 x speichern, damit diese Zeit richtig abgezeigt wird!',
	'BC_NO_BONUS_WINNER'			=> 'Es gibt keinen Eintrag',
	'BC_NO_ENTRY'					=> 'Kein Eintrag vorhanden',
	'BC_OK'							=> 'Konforme',
	'BC_PAGE_DESC'					=> 'Hier kannst Du die Charteinträge, die in Deinem Forum eingetragen wurden, bearbeiten oder löschen.',
	'BC_PAGE_TITLE'					=> ' Music Charts Verwaltung',
	'BC_PERIOD'						=> 'Periode in Wochen',
	'BC_PERIOD_EXPLAIN'				=> 'Gib hier die Zeit in Sekunden ein (<strong>auf die Woche gesehen</strong>). Eine Woche entspricht 604800 Sekunden.',
	'BC_PM'							=> 'Private Nachricht',
	'BC_PM_ENABLE'					=> 'PN für die Gewinner?',
	'BC_PM_ENABLE_EXPLAIN'			=> 'Stelle ein, ob die Gewinner der jeweiligen Periode und der zufällige Gewinner aus den Benutzern, die abgestimmt haben (wenn aktiviert), eine Glückwunsch PN bekommen sollen.',
	'BC_PM_EXPLAIN'					=> 'Hier kannst du Einstellungen für das Versenden von PNs vornehmen.',
	'BC_PM_USER'					=> 'Charts Verwalter',
	'BC_PM_USER_EXPLAIN'			=> 'Gib hier die ID des Benutzers ein, von dem die PN kommt, die an die Gewinner gesendet wird.',
	'BC_POINTS_PER_VOTE'			=> '%s pro abgebener Stimme',
	'BC_POINTS_PER_VOTE_EXPLAIN'	=> 'Gib hier an, wieviele %s die Benutzer pro abgebener Stimme erhalten sollen (0 = diese Option deaktivieren)',
	'BC_RANKING'					=> '%1$s für die drei besten Songs',
	'BC_RANKING_EXPLAIN'			=> 'Hier kannst du %1$s für die drei besten Songs der jeweiligen Periode vergeben. Diese werden dann den Benutzern gutgeschrieben, die den Song eingestellt haben.',
	'BC_REALLY_DELETE'				=> 'Bist du sicher, daß du den gewählten Eintrag löschen willst?',
	'BC_REQUIRED'					=> 'Erforderliche Felder',
	'BC_REQUIRED_ALBUM'				=> 'Album zum Song',
	'BC_REQUIRED_ALBUMCOVER'		=> 'Album Cover',
	'BC_REQUIRED_EXPLAIN'			=> 'Hier kannst du festlegen, welche Felder zusätzlich als Pflichtfelder ausgewiesen werden sollen. Diese Felder werden dann bei der Eingabe eines neuen Songs mit einem [*] gekennzeichnet und geprüft, ob sie befüllt worden sind.',
	'BC_REQUIRED_VIDEO'				=> 'Video Clip',
	'BC_REQUIRED_WEBSITE'			=> 'Link zur Website des Künstlers',
	'BC_REQUIRED_YEAR'				=> 'Erscheinungsjahr',
	'BC_PLACE_SECOND'						=> '%1$s für den 2. Platz',
	'BC_SINGLE'						=> '1 Eintrag',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Gib hier den Titel des Albums ein aus dem der Song stammt',
	'BC_SONG_ARTIST'				=> 'Interpret',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Gib hier den Namen des Interpreten, der Interpetin oder der Gruppe ein',
	'BC_SONG_PICTURE'				=> 'Album Bild',
	'BC_SONG_PICTURE_EXPLAIN'		=> 'Gib hier den Link zu einem Bild des Albums ein. Beachte die Urheberrechte und verwende möglichst keine Backlinks!',
	'BC_SONG_TITLE'					=> 'Titel',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Gib hier den Titel des Songs ein',
	'BC_SONG_URL'					=> 'Webseite',
	'BC_SONG_URL_EXPLAIN'			=> 'Gib hier den Link zur Website des Interpreten ein',
	'BC_SONG_VIDEO'					=> 'Video',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Gib hier den Code zum Einbetten eines Videos ein, den du z.B. von YouTube, MyVideo oder anderen Providern erhälst. Achte darauf, daß die Videogröße von <strong>640x385</strong> nicht überschritten wird (siehst du innerhalb des Codes)!<br /><br /><strong>Bitte stelle auch sicher, daß Du nur auf Videos verlinkst, die keine Urheberrechte verletzen!</strong>',
	'BC_SONG_VIDEO_ID'				=> ' Video ID',
	'BC_SONG_VIDEO_ID_EXPLAIN'		=> 'Die  Video Mod ist hier installiert. Wenn das Video, daß du hier einsetzen möchtest, dort bereits existiert, kannst du einfach die ID aus der  Video Mod übernehmen. Du findest die ID, indem du dir im  Video das gewünschte Video anschaust. In der Browserzeile nimmst du die Zahl, die hinter dem v= steht. Diese trägst du dann hier ein. Bei Verwendung der ID brauchst du keinen Code für die Einbettung eingeben.',
	'BC_SONG_YEAR'					=> 'Jahr',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Gib hier das Erscheinungsjahr des Songs oder des Albums ein',
	'BC_STARTING_TIME'				=> 'Startzeit',
	'BC_STARTING_TIME_EXPLAIN'		=> 'Gib hier die Startzeit für die Berechnung zum Beenden der aktuellen Periode ein auf Basis deiner Board Zeitzone ein. Wenn deine persönlich unterschiedlich zur Board Zeitzone ist, dann siehst du eine ander Startzeit in Klammern, als du eingeben hast! <strong>Verwende das UNIX Zeitformat!</strong> Die entsprechende Umwandlung kannst du bei <a href="http://unixtime.de" onclick="window.open(this.href); return false">Unixtime.de</a> vornehmen. Die Kalkulation erfolgt auf Basis dieser Startzeit und der eingebenen Periode. Eine manuelle Eingabe ist nur beim ersten Mal erforderlich. Danach wird diese Zeit automatisch aktualisiert!',
	'BC_PLACE_THIRD'						=> '%1$s für den 3. Platz',
	'BC_TITLE'						=> ' Music Charts',
	'BC_UPDATED'					=> 'Song wurde erfolgreich aktualisiert',
	'BC_UPS'						=> '%1$s für das Einstellen eines neuen Songs',
	'BC_UPS_EXPLAIN'				=> 'Da du den Ultimate Points  installiert hast, kannst du hier festlegen, ob und wieviele %1$s die Benutzer für das Einstellen eines neuen Songs bekommen sollen. Setze 0, wenn sie keine %1$s bekommen sollen',
	'BC_UPS_TITLE'					=> 'Ultimate Points',
	'BC_UPS_TITLE_EXPLAIN'			=> 'Du hast die Ultimate Points  (UPS) installiert. Daher kannst du hier ein paar zusätzliche Einstellungen vornehmen.',
	'BC_VOTERS_POINTS'				=> '%s für den Gewinner beim Abstimmen',
	'BC_VOTERS_POINTS_EXPLAIN'		=> 'Hier kannst du festlegen, ob ein zufällig ausgewählter Benutzer, der abgestimmt hat, einen Bonus in %s erhalten soll (0 = deaktiviert die Option)',
	'BC_WEEK'						=> [
		1	=> '%d Woche',
		2	=> '%d Wochen',
	],
	'BC_DAY'						=> [
		1	=> '%d tag',
		2	=> '%d tage',
	],
	'BC_WINNERS_PER_PAGE'			=> 'Gewinner pro Seite',
	'BC_WINNERS_PER_PAGE_EXPLAIN'	=> 'Gib hier an, wieviel Gewinner auf der Gewinnerseite angezeigt werden sollen',

	'LOG_ADMIN_BC_UPDATED'			=> 'Hat die Charts Konfiguration aktualisiert.',
	'LOG_ADMIN_CHART_DELETED'		=> 'Hat den Charteintrag <strong>%1$s</strong> gelöscht',
	'LOG_ADMIN_CHART_RESET'			=> '<strong>Hat den automatischen Prozess für die Rücksetzung der Music Charts gestartet</strong>',
	'LOG_ADMIN_CHART_UPDATED'		=> 'Hat den Charteintrag <strong>%1$s</strong> aktualisiert.',
	'LOG_USER_ADDED_SONG'			=> 'Hat den Song <strong>%1$s</strong> hinzugefügt',
	'LOG_USER_EDITED_SONG'			=> 'Hat den Song <strong>%1$s</strong> bearbeitet',
));
