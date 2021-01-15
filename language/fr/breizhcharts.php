<?php

/**
* breizhcharts [French]
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
	'BC_CHARTS'					=> 'Hit Parade',
	'BC_CHARTS_NEW'				=> 'Nouvelles chansons dans le Hit Parade',
	'BC_ACTUAL'					=> 'Actuel: <span>%s</span>',
	'BC_LATEST'					=> 'Dernier: <span>%s</span>',
	'BC_BEST_POS'				=> 'Meilleur: <span>%s</span>',
	'BC_AJAX_ALREADY_VOTE'		=> 'Vous avez déjà noté cette image!',
	'BC_AJAX_NOTE_TOTAL'		=> 'Note moyenne: <span>%s</span>',
	'BC_AJAX_NO_VOTE'			=> 'Vous n’êtes pas autorisé à noter les images…',
	'BC_AJAX_NOTE'				=> [
		0	=> 'Vous n’avez pas noté…',
		1	=> 'Vous avez déja noté: <span>%s</span>',
	],
	'BC_AJAX_NOTE_NB'			=> [
		0	=> '<span>%s</span> note',
		1	=> '<span>%s</span> note',
		2	=> '<span>%s</span> notes',
	],
	'BC_AJAX_STARS'				=> [
		1	=> 'Noter %s étoile sur 10',
		2	=> 'Noter %s étoiles sur 10',
	],
	'BC_AJAX_THANKS'			=> 'Merci d’avoir noté !',
	'BC_AJAX_UPDATING'			=> 'Mise à jour de la note…',
	'BC_AJAX_VIDEO'				=> 'La vidéo existe bien…',
	'BC_AJAX_VIDEO_NO'			=> 'La vidéo n’existe pas…',
	'BC_ADD_SONG'				=> 'Ajouter une chanson',
	'BC_ADDED_BY'				=> 'Ajouté par',
	'BC_ADDED_TIME'				=> '<i>Ajouté le: <strong>%1$s</strong></i>',
	'BC_ALL_TITLE'				=> 'Hit Parade - Toutes les chansons',
	'BC_ALREADY_EXISTS_ERROR'	=> 'La chanson <strong>%1$s</strong> de <strong>%2$s</strong> existe déjà. Veuillez en choisir une autre.',
	'BC_ALREADY_VOTED'			=> 'Vous avez déjà voté pour cette chanson',
	'DM_ENTER'					=> 'Entrée',
	'BC_DATE'					=> 'l j F Y à H:i',
	'BC_ANNOUNCE_MSG'			=> 'Bonjour à tous,' . "\n\n" . 'Il y a une nouvelle chanson dans le Hit Parade!' . "\n" . '[img]%4$s[/img]' . "\n" . 'Titre:  [b]%1$s [/b]' . "\n" . 'Artiste:  [b]%2$s [/b]' . "\n\n" . 'Cliquez  [b]%3$s [/b] pour voir la liste des nouveautés!' . "\n\n" . 'Bonne écoute des nouveautés et n’oubliez pas de voter!',
	'BC_ANNOUNCE_TITLE'			=> '[Chanson] %1$s de %2$s',
	'BC_ARTIST_ERROR'			=> 'Vous devez entrer un artiste ou un groupe.',
	'BC_BACKLINK'				=> '%sRetour au classement général%s',
	'BC_BACKLINK_ADD'			=> '<br /><br />%sRetour à la page d’ajout%s',
	'BC_BACKLINK_EDIT'			=> '<br /><br />%sRetour à la page d’édition de la chanson%s',
	'EDIT_SONG'					=> 'éditer la chanson',
	'DELETE_SONG'				=> 'supprimer la chanson',
	'BC_BEST_RATED'				=> 'Les chansons les mieux notées',
	'BC_BONUS_WINNER'			=> 'Le gagnant du bonus dans les votants est: %1$s<br /><br />Bravo pour le bonus <strong>%2$s %3$s</strong>!',
	'BC_BONUS_WINNER_HEADER'	=> 'Bravo',
	'BC_CLICK_LINK'				=> 'Cliquez %sici%s pour retourner au classement',
	'BC_CLICK_VIDEO'			=> 'Cliquez ici pour voir la vidéo',
	'BC_COPYRIGHT'				=> 'Breizh Chart Extension V%1$s par %2$s',
	'BC_COUNT_ERROR'			=> 'Désolé, Nous sommes arrivés au nombre maximal d’entrées! Contactez l’administrateur pour ajouter de nouveaux titres',
	'BC_COVER_FORMAT_ERROR'		=> 'La couverture de l’album n’est pas une image valide',
	'BC_CURR_POS'				=> 'Position',
	'BC_DELETE_SONG'			=> 'Supprimer la chanson',
	'BC_DELETE_SONG_REGULAR'	=> 'Êtes vous sûr de vouloir supprimer cette chanson?',
	'BC_DELETE_SONG_UPS'		=> 'Êtes vous sûr de vouloir supprimer cette chanson et de retirer le %1$s, que l’utilisateur avait reçu ?',
	'BC_DELETE_SUCCESS'			=> 'La chanson <strong>%1$s</strong> a été supprimée.',
	'BC_EDIT_SONG'				=> 'Éditer une chanson',
	'BC_FIELDS_ERROR'			=> 'Vous devez entrer un titre de chanson et son artiste.',
	'BC_FIRST'					=> '1ère Place',
	'BC_FROM'					=> ' de ',
	'BC_FROM_OF'				=> '%1$s de %2$s',
	'BC_GOTO_WEB'				=> 'Site web de %1$s',
	'BC_GO_CHARTS'				=> 'ici',
	'BC_HEADER'					=> 'Hit Parade - Le Classement',
	'BC_HEADER_EXPLAIN'			=> 'Ici vous pouvez créer vos propres classements et les évaluer. Chaque utilisateur peut ajouter des chansons et tous les utilisateurs  peuvent voter pour eux.<br />Durant la période de vote en cours d’exécution, vous ne pouvez voter qu’une seule fois par chanson.<br />Dès que la période actuelle se termine, vous pourrez voter à nouveau pour vos chansons préférées.<br /><strong>Bien sûr</strong>, vous pourrez voter pour des chansons différentes, pas que pour une seule!<br /><strong>! Astuce :</strong> cliquez sur le pseudo d’un membre pour voir la liste de ses chansons.<br /><br />la  période de vote se termine le: <strong>%1$s</strong><br /><br /><strong>Amusez-vous bien et bonne chance pour vos favoris!</strong>',
	'BC_INDEX_WINNER'			=> 'Le dernier gagnant du classement Hit Parade du',
	'BC_LAST'					=> ' Derniers gagnants',
	'BC_LAST_POS'				=> 'Classement',
	'BC_LAST_WINNERS'			=> 'Résultats du dernier vote',
	'BC_LAST_WINNERS_PORTAL'	=> 'Les gagnants du dernier vote',
	'BC_MULTI_VOTERS'			=> '<strong>Actuellement %1$s utilisateurs ont voté dans notre classement</strong>.',
	'BC_NEEDED'					=> 'Les champs avec [*] sont obligatoires, tous les autres sont optionnels.<br /><strong>Ne fonctionne QUE avec les vidéos YouTube.</strong>',
	'BC_NEWEST_XX'				=> 'Les nouvelles chansons',
	'BC_NEWEST_PERIOD'			=> 'Les dernières chansons ajoutées pour le vote',
	'BC_NEW_PLACED'				=> 'Cette chanson a été ajoutée dans les nouveautés : %s',
	'BC_NEW_SONG'				=> 'Nouvelle chanson',
	'BC_NO_EXISTS'				=> 'La chanson <strong>%1$s</strong> de <strong>%2$s</strong> est acceptée.',
	'BC_NOT_LOGGED_IN'			=> 'Vous devez être connecté pour voter',
	'BC_NO_CHARTS'				=> 'Désolé, il n’y a pas de classement actuellement',
	'BC_NO_SONGS'				=> 'Pas de chansons',
	'BC_NO_VOTES'				=> 'Personne n’a voté lors du dernier vote, Il n’y a pas d’heureux gagnants.',
	'BC_NO_VOTERS'				=> '<strong>Actuellement aucun utilisateur n’a voté sur le classement.</strong>',
	'BC_NO_WINNER'				=> 'Pas de gagnants actuellement',
	'BC_OK'						=> 'Conforme',
	'BC_OF_USER'				=> 'Les Chansons de %s',
	'BC_OF_USER_TITLE'			=> 'Voir toutes les Chansons de %s',
	'BC_OWN'					=> 'Mes Chansons',
	'BC_OWN_CHARTS'				=> [
		0				=> 'n’a ajouté aucune chanson jusqu’à maintenant',
		1				=> '<strong>1</strong> chanson ajoutée jusqu’à maintenant',
		2				=> '<strong>%s</strong> chansons ajoutée jusqu’à maintenant',
	],
	'BC_PERIOD'					=> 'Vote: <strong>%1$s %2$s</strong>',
	'BC_PICTURE_HOT_TITLE'		=> 'Cliquez ici, si vous aimez la chanson de %1$s',
	'BC_PICTURE_NOT_TITLE'		=> 'Cliquez ici, si vous n’aimez pas la chanson de %1$s',
	'BC_PICTURE_TITLE'			=> 'La pochette de la chanson de %1$s',
	'BC_PM_MESSAGE'				=> 'Bonjour %1$s,' . "\n\n" . 'bravo pour votre [b]%2$s  place[/b] dans le classement!' . "\n" . 'La chanson [b]%3$s[/b] de [b]%4$s[/b] que vous avez ajouté au classement, a été votée à la [b]%2$s  place[/b] par les utilisateurs du forum pendant le vote!',
	'BC_PM_MESSAGE_UPS'			=> 'Bonjour %1$s,' . "\n\n" . 'bravo pour votre [b]%2$s place[/b] dans le classement!' . "\n" . 'La chanson [b]%3$s[/b] de [b]%4$s[/b] que vous avez ajouté au classement, a été votée à la [b]%2$s place[/b] par les utilisateurs du forum pendant le vote!' . "\n\n" . 'En cadeau, nous sommes heureux de vous offrir [b]%5$s %6$s[/b] pour ceci.',
	'BC_PM_SUBJECT'				=> 'Bravo pour la %s place!',
	'BC_PM_VOTERS_SUBJECT'		=> 'Bravo pour le bonus!',
	'BC_PM_VOTERS_MESSAGE'		=> 'Boujour %1$s,' . "\n\n" . 'de tous les utilisateurs, qui ont pris part au classement, vous êtes l’heureux vainqueur du bonus [b]%2$s %3$s[/b].' . "\n\n" . 'N’hésitez pas à voter au prochain vote!',
	'BC_PORTAL_ARTIST'			=> 'Artiste',
	'BC_PORTAL_GOTO'			=> 'Cliquez ici pour le classement',
	'BC_PORTAL_HEADER'			=> 'Classement récent',
	'BC_PORTAL_POSTER'			=> 'Ajouté par',
	'BC_PORTAL_TITLE'			=> 'Titre',
	'BC_POSITION_DOWN'			=> 'En baisse : %s',
	'BC_POSITION_EQUAL'			=> 'Égale : %s',
	'BC_POSITION_UP'			=> 'En hausse : %s',
	'BC_RANK'					=> 'Rang',
	'BC_RATE'					=> 'Évaluation',
	'BC_REQUIRED'				=> 'Les champs marqués d’un<strong>[*]</strong> doivent être remplis obligatoirement.',
	'BC_REQUIRED_COVER_ERROR'	=> 'Vous devez entrer un <strong>lien vers la pochette</strong> pour l’album.',
	'BC_REQUIRED_ALBUM_ERROR'	=> 'Vous devez entrer un <strong>album</strong> pour la chanson.',
	'BC_REQUIRED_VIDEO_ERROR'	=> 'Vous devez entrer un <strong>code valide</strong> pour le clip video de la chanson.',
	'BC_REQUIRED_WEBSITE_ERROR'	=> 'Vous devez entrer un <strong>lien vers le site web</strong> de l’artiste.',
	'BC_REQUIRED_YEAR_ERROR'	=> 'Vous devez entrer <strong>l’année de publication</strong> de la chanson.',
	'BC_SECOND'					=> '2ème Place',
	'BC_SHOW_ALL'				=> 'Les chansons les mieux classées',
	'BC_SHOW_VIDEO'				=> 'Regardez le clip de la chanson de %1$s',
	'BC_SONG_NB'				=> [
		1				=> '%d chanson',
		2				=> '%d chansons',
	],
	'BC_WEEK'					=> [
		1				=> '%d semaine',
		2				=> '%d semaines',
	],
	'BC_DAY'					=> [
		1				=> '%d jour',
		2				=> '%d jours',
	],
	'BC_SINGLE_VOTER'			=> '<strong>Actuellement un seul utilisateur a voté dans notre classement.</strong>',
	'BC_SONG'					=> 'Titre',
	'BC_SONG_ADDED'				=> 'Votre chanson a bien été ajoutée.<br />',
	'BC_SONG_ADDED_UPS'			=> 'Votre chanson a bien été ajoutée et vous recevez <strong>%1$s %2$s</strong> pour ceci.<br />',
	'BC_SONG_ALBUM'				=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'		=> 'Nom de l’album correspondant au titre',
	'BC_SONG_PICTURE'			=> 'Jaquette',
	'BC_SONG_ALBUM_PICTURE'		=> 'Jaquette de l’album',
	'BC_SONG_PICTURE_EXPLAIN'	=> 'Entrez ici le lien vers la Jaquette de l’album<br />Merci de privilégier un lien sécurisé <strong>https</strong><br />(ex: https://domain.com/images/cover.jpg)',
	'BC_SONG_ARTIST'			=> 'Nom de l’artiste ou du groupe',
	'BC_SONG_ARTIST_EXPLAIN'	=> 'Entrez ici le nom de l’artiste ou du groupe',
	'BC_SONG_EDIT_SUCCESS'		=> 'La chanson <strong>%1$s</strong> a été editée avec succès.',
	'BC_SONG_TITLE'				=> 'Titre',
	'BC_SONG_TITLE_EXPLAIN'		=> 'Entrez ici le titre de la chanson',
	'BC_SONG_VIDEO'				=> 'Clip video',
	'BC_SONG_VIDEO_EXPLAIN'		=> 'Entrez ici l’url de la vidéo YouTube.<br />Pour la récupérer, cliquez droit dans la vidéo voulue et: <br /><em>Copier l’url de la vidéo<em><br /><br /><strong>Prenez garde de ne violer aucun copyright!</strong>',
	'BC_SONG_WEBSITE_EXPLAIN'	=> 'Entrez ici le lien vers le site de l’artiste ou du groupe<br />(ex: https://www.goupechanteur.com)',
	'BC_SONG_WEBSITE'			=> 'Site Web',
	'BC_SONG_YEAR'				=> 'Année',
	'BC_SONG_YEAR_EXPLAIN'		=> 'L’année de publication de l’album',
	'BC_THIRD'					=> '3ème Place',
	'BC_TITLE_ERROR'			=> 'Vous devez entrer un titre de chanson.',
	'BC_TOP_TEN'				=> 'Voir le Top %s',
	'BC_TOP_XX'					=> 'Le Top %1$s',
	'BC_USER'					=> 'Utilisateur',
	'BC_VIDEO_EXIST_ERROR'		=> 'l’ID du mode DM vidéo n’existe pas. Vérifiez le.',
	'BC_VIDEO_NO_EXPLAIN'		=> 'Le mode DM Video est installé. Si la vidéo semble déjà exister ou avoir été ajoutée, vous pouvez simplement entrer l’ID de la vidéo ici. Pour obtenir l’ID, vérifiez le lien de la vidéo dans votre navigateur. Prenez le numéro après le signe v=. Si vous utilisez cette option, vous n’aurez pas à entrer le code embed.',
	'BC_VIDEO_NO'				=> 'DM Video ID',
	'BC_VIEWONLINE'				=> 'regarde le Hit Parade',
	'BC_VOTE_CHECK_FIRST'		=> '<br /><br />Bonjour %1$s,<br /><br />le vote du classement vient de débuter! Profitez-enn pour voter pour vos chansons favorites. Si rien ne vous plait, n’hésitez pas a rajouter vos propres chansons.<br /><br /><strong>Merci!</strong>',
	'BC_VOTE_CHECK_LINK'		=> '<strong>%sCliquez ici pour rejoindre le classement!%s</strong><br /><br />',
	'BC_VOTE_CHECK_SECOND'		=> '<br /><br />Bonjour %1$s,<br /><br />Le vote se termine prochainement! n’hésitez pas a voter pour notre classement s’il y a de nouvelles chansons pour lesquelles vous n’avez pas encore voté.<br /><br /><strong>Merci!</strong>',
	'BC_VOTE_SUCCESS'			=> '<strong>Votre vote a été pris en compte.</strong><br /><br />Merci d’avoir voté pour cette chanson <strong>%1$s</strong> de <strong>%2$s</strong>.',
	'BC_VOTE_SUCCESS_UPS'		=> '<strong>Votre vote a été pris en compte.</strong><br /><br />Merci d’avoir voté pour cette chanson <strong>%1$s</strong> de <strong>%2$s</strong>.<br />Vous recevez <strong>%3$s %4$s</strong> pour votre vote.',
	'BC_VOTED_USERS'			=> [
		1		=> 'Utilisateur ayant déjà voté: ',
		2		=> 'Utilisateurs ayant déjà voté: ',
	],
	'BC_WEBSITE_FORMAT_ERROR'	=> 'Le lien de la page de l’artiste ou du groupe n’est pas une url valide',
	'BC_WON'					=> 'Gains',
	'BC_WON_VALUE'				=> '%1$s %2$s',
	'BC_YEAR_FORMAT_ERROR'		=> 'l’année de publication doit être entre le <strong>20ème ou 21ème siècle</strong>!',
	'ACL_A_BC_MANAGE'			=> 'Peut administrer le Hit Parade', 
	'ACL_U_BC_VIEW'				=> 'Peut voir le Hit Parade',
	'ACL_U_BC_VOTE'				=> 'Peut voter dans le Hit Parade',
	'ACL_U_BC_ADD'				=> 'Peut ajouter des chansons au Hit Parade',
	'ACL_U_BC_EDIT'				=> 'Peut éditer des chansons dans le Hit Parade',
	'GO_TO_YOUTUBE'				=> '<a href="https://www.youtube.com" onclick="window.open(this.href);return false;" title="aller sur YouTube"><img src="%1$s" alt="youtube" /></a> Site YouTube<br />Vous pouvez trouver le manuel d’utilisation ici:</span> <a href="%2$s">Tutoriel d’utilisation</a>',
]);
