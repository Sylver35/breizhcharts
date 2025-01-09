<?php

/**
* info_acp_breizhcharts [French]
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
	'BC_ACP_PAGE'					=> 'Nombre de chansons par page (dans l’ACP)',
	'BC_ACP_PAGE_EXPLAIN'			=> 'Mettez ici combien de chansons vous souhaitez voir par page dans l’ACP.',
	'BC_ADD'						=> 'Ajouter',
	'BC_ALL_CHARTS'					=> 'Toutes les entrées',
	'BC_ANNOUNCE_SETTINGS'			=> 'Paramètres des annonces',
	'BC_CALENDAR'					=> 'Calendrier',
	'BC_DATE'						=> 'l j F Y à H:i',
	'BC_DATE_ADDED'					=> 'l j F Y',
	'BC_CATEGORIES'					=> 'Gestion des genres',
	'BC_CATEGORIES_EXPLAIN'			=> 'Vous pouvez ajouter de nouveaux genres musicaux, les supprimer, les modifier.<br>Il est possible de modifier l’ordre d’affichage avec les flèches haut et bas.<br>Modification de l’ordre des listes déroulantes dans la page de configuration',
	'BC_CAT_TITLE'					=> '🎼 Gestion des genres musicaux',
	'BC_CAT_SORT'					=> 'Genre musical',
	'BC_CAT_NEED'					=> 'Vous devez définir un genre musical',
	'BC_CAT_NB_SONGS'				=> 'Nombre de chansons',
	'BC_CAT_TOTAL'					=> '%1$s genres musicaux',
	'BC_CAT_EDIT'					=> 'Éditer le genre musical : <strong>%1$s</strong>',
	'BC_CAT_NAME'					=> 'Nom du genre',
	'BC_CAT_NAME_EXPLAIN'			=> 'Choisissez un nom pour le genre musical',
	'BC_CAT_NB_EDIT'				=> 'Nombre de chansons',
	'BC_CAT_NB_EDIT_EXPLAIN'		=> 'Vous permet de voir le nombre de chansons avec ce style musical.<br>Ne le modifiez que si vous avez trouvé une erreur de compte.',
	'BC_CAT_ADD'					=> 'Ajouter un genre musical',
	'BC_CAT_ADD_EXPLAIN'			=> '',
	'BC_CAT_EDIT_OK'				=> 'Le genre musical <strong>%1$s</strong> a été édité avec succès.',
	'BC_CAT_ADD_OK'					=> 'Le genre musical <strong>%1$s</strong> a été ajouté avec succès.',
	'BC_CAT_DELETE_OK'				=> 'Le genre musical <strong>%1$s</strong> a été supprimé avec succès.',
	'BC_CAT_DELETE_NB'				=> [
		1	=> '<br>Mais %d chanson n’a plus de genre musical attribué',
		2	=> '<br>Mais %d chansons n’ont plus de genre musical attribué',
	],
	'BC_CHOICE_WEEKS'				=> 'Semaines',
	'BC_CHOICE_DAYS'				=> 'Jours',
	'BC_CHOICE_CHART'				=> 'Bloc chansons aléatoires',
	'BC_CHOICE_CHART_EXPLAIN'		=> 'Choix pour l’affichage des chansons aléatoires dans les pages du Hit Parade',
	'BC_CHOICE_INDEX'				=> 'Bloc chansons aléatoires sur l’index',
	'BC_CHOICE_INDEX_EXPLAIN'		=> 'Choix pour l’affichage des chansons aléatoires sur l’index du forum',
	'BC_CHOICE_INDEX_0'				=> 'Non activé',
	'BC_CHOICE_INDEX_1'				=> 'En haut de la page',
	'BC_CHOICE_INDEX_2'				=> 'Avant la liste des forums',
	'BC_CHOICE_INDEX_3'				=> 'Après la liste des forums',
	'BC_CHOICE_INDEX_4'				=> 'En bas de la page',
	'BC_CHECK_1_ENABLE'				=> 'Informer les membres de la nouvelle période',
	'BC_CHECK_1_ENABLE_EXPLAIN'		=> 'Déterminer si les membres doivent être informés qu’un nouveau classement a débuté. Ce message ne sera affiché qu’une seule fois pour chaque membre connecté.',
	'BC_CHECK_2_ENABLE'				=> 'Informer les membres avant la fin de la période',
	'BC_CHECK_2_ENABLE_EXPLAIN'		=> 'Déterminer si les membres doivent voir le message de rappel, si la période actuelle est trop courte avant la fin. Vous aurez besoin du temps ci-dessous. Ce message sera ne affiché qu’une seule fois pour les utilisateurs.<br><strong>L’option du dessus doit être activée impérativement.</strong>',
	'BC_CHECK_TIME'					=> 'Délai',
	'BC_CHECK_TIME_EXPLAIN'			=> 'Entrez ici combien de temps avant la fin de la période, les membres seront informés (si l’option ci-dessus est activée).',
	'BC_CLICK_RETURN'				=> 'Cliquez %sici%s pour revenir à la gestion du Hit Parade',
	'BC_CONFIG'						=> 'Configuration',
	'BC_CONFIG_ANNOUNCE_ENABLE'		=> 'Annonce pour les nouvelles chansons du Hit Parade',
	'BC_CONFIG_ANNOUNCE_ENABLE_EX'	=> 'Mettre sur Oui, si vous souhaitez annoncer les nouvelles chansons dans le forum sélectionné ci-dessous',
	'BC_CONFIG_ANNOUNCE_FORUM'		=> 'Sélectionner un forum',
	'BC_CONFIG_ANNOUNCE_FORUM_EX'	=> 'Sélectionner ici le forum, dans lequel les nouvelles chansons seront annoncées',
	'BC_CONFIG_EXPLAIN'				=> 'Ici vous pouvez définir ou modifier les valeurs différentes. Veuillez lire toutes les explications très attentivement!',
	'BC_CONFIG_MAIN_SETTINGS'		=> 'Paramètres',
	'BC_CONFIG_OK'					=> 'La configuration du Hit Parade a été éditée avec succès.',
	'BC_CONFIG_TITLE'				=> '🎼 Configuration du Hit Parade',
	'BC_CONFIG_UPDATED'				=> 'La configuration a été mise a jour avec succès.',
	'BC_CONF_CLICK_RETURN'			=> 'Cliquez %sici%s pour retourner a la configuration du hit-parade.',
	'BC_DELETE_OK'					=> 'La chanson a été supprimée avec succès.',
	'BC_EDIT'						=> 'Éditer une chanson',
	'BC_PLACE_FIRST'				=> '%1$s pour la première place',
	'BC_FROM_NAME'					=> 'posté par',
	'BC_FROM_OF'					=> '%1$s de %2$s',
	'BC_HOURS'						=> 'heures',
	'BC_ACTUAL_NOTE'				=> 'Note actuelle',
	'BC_LAST_DATE'					=> 'Date d’ajout',
	'BC_LAST_RANK'					=> 'Dernière position',
	'BC_LAST_VOTERS_WINNER_ID'		=> 'ID du membre qui a gagné le dernier bonus',
	'BC_LAST_VOTERS_WINNER_ID_EX'	=> 'Ici vous pouvez voir l’ID du membre qui a gagné le dernier bonus',
	'BC_MANAGE'						=> 'Gestion des Chansons',
	'BC_MAX_ENTRIES'				=> 'Le maximum d’entrées autorisées',
	'BC_MAX_ENTRIES_EXPLAIN'		=> 'Mettez ici le maximum. Pour le nombre de chansons, qui seront autorisés dans le Hit Parade. 0 signifie illimité.',
	'BC_MAX_PER_PAGE'				=> 'Nombre de chansons par page (coté utilisateurs)',
	'BC_MAX_PER_PAGE_EXPLAIN'		=> 'Mettez ici le nombre de chansons, qui devront être affichées par page.',
	'BC_NEED_ARTIST'				=> 'Vous devez entrer un artiste',
	'BC_NEED_DATA'					=> 'Il manque les données nécessaires',
	'BC_NEED_TITLE'					=> 'Vous devez entrer un titre',
	'BC_NEXT_RESET'					=> 'Prochaine date de mise a zéro',
	'BC_NEXT_RESET_EXPLAIN'			=> 'Ici vous pouvez voir, quand les votes seront remis à zéro la prochaine fois. Si vous changez la date de début ci-dessus, vous pourriez avoir besoin d’enregistrer à deux reprises la configuration, jusqu’à ce que vous voyez la date exacte de réinitialisation!',
	'BC_NO_BONUS_WINNER'			=> 'Il n’y a pas d’entrée',
	'BC_NO_ENTRY'					=> 'Aucune entrées',
	'BC_OK'							=> 'Conforme',
	'BC_PAGE_DESC'					=> 'Ici, vous pouvez modifier ou supprimer les entrées des membres faites sur la page du Hit Parade.',
	'BC_PAGE_TITLE'					=> '🎼 Gestion du Hit Parade',
	'BC_PERIOD'						=> 'Période des votes',
	'BC_PERIOD_EXPLAIN'				=> 'Entrez ici la durée de la période des votes, en jours ou en semaines',
	'BC_PM'							=> 'Messages Privés',
	'BC_PM_ENABLE'					=> 'MP pour le gagnant',
	'BC_PM_ENABLE_EXPLAIN'			=> 'Définissez ici, si vous souhaitez envoyer un MP à tous les heureux gagnants ainsi que le prix du bonus (si activé).',
	'BC_PM_EXPLAIN'					=> 'Ici vous pouvez faire quelques réglages pour l’envoi de messages privés.',
	'BC_PM_USER'					=> 'Gestionnaire du Hit Parade',
	'BC_PM_USER_EXPLAIN'			=> 'Entrez ici l’ID du membre, qui enverra les messages privés aux gagnants.<br>Ainsi que les messages privés du système de rapports.',
	'BC_POINTS_PER_VOTE'			=> '%s par vote',
	'BC_POINTS_PER_VOTE_EXPLAIN'	=> 'Entrez ici combien de %s les membres recevront pour chaque vote qu’ils donnent (0 = désactive cette option)',
	'BC_RANKING'					=> '%1$s pour les trois chansons du Top',
	'BC_RANKING_EXPLAIN'			=> 'Ici vous pouvez définir combien de %1$s seront attribuées pour les trois meilleures chansons durant cette période. Cela sera attribué aux membres ayant posté ces chansons.',
	'BC_REALLY_DELETE'				=> 'Etes-vous sûr de vouloir supprimer l’entrée sélectionnée?',
	'BC_REQUIRED'					=> 'Les champs obligatoires',
	'BC_REQUIRED_ALBUM'				=> 'Album de la chanson',
	'BC_REQUIRED_EXPLAIN'			=> 'Ici vous pouvez mettre, si vous voulez d’autres champs marqués requis. Ces champs seront ensuite marqué avec [*] sur la page et il devront êtres obligatoirement remplis.',
	'BC_REQUIRED_VIDEO'				=> 'Vidéo Clip',
	'BC_REQUIRED_YEAR'				=> 'Année de publication',
	'BC_PLACE_SECOND'				=> '%1$s pour la deuxième place',
	'BC_SELECT_ORDER'				=> 'Choix du tri des genres musicaux',
	'BC_SELECT_ORDER_EXPLAIN'		=> 'Déterminez comment vous souhaitez trier les genres musicaux dans les listes déroulantes de sélection',
	'BC_SELECT_ORDER_POSITION'		=> 'Position',
	'BC_SELECT_ORDER_NATURAL'		=> 'Ordre alphabétique',
	'BC_SONG_ALBUM'					=> 'Album',
	'BC_SONG_ALBUM_EXPLAIN'			=> 'Entrez ici le titre de l’album, depuis la chanson dont elle est extraite.',
	'BC_SONG_ARTIST'				=> 'Artiste',
	'BC_SONG_ARTIST_EXPLAIN'		=> 'Entrez le nom de l’artiste ou du groupe',
	'BC_SONG_TITLE'					=> 'Titre',
	'BC_SONG_TITLE_EXPLAIN'			=> 'Entrez ici le titre de la chanson',
	'BC_SONG_VIDEO'					=> 'Vidéo',
	'BC_SONG_VIDEO_EXPLAIN'			=> 'Entrez ici l’url de la vidéo Youtube.<br><br><strong>Veuillez prendre soin de ne pas violer les droits d’auteur.</strong>',
	'BC_SONG_YEAR'					=> 'Année',
	'BC_SONG_YEAR_EXPLAIN'			=> 'Entrez ici l’année de la chanson ou de l’album quand il a été enregistré.',
	'BC_STARTING_TIME'				=> 'Date de début',
	'BC_STARTING_TIME_EXPLAIN'		=> 'Entrez ici l’heure de début (basé sur le temps du fuseau horaire de votre serveur), qui sera la base pour le calcul de la fin de cette période. Le calcul sera ensuite utilisé pour cette valeur et la valeur de la période pour calculer l’heure de fin de la période de vote actuel. Une saisie manuelle n’est nécessaire que lorsque vous démarrez les votes. Plus tard, les valeurs seront définies automatiquement!',
	'BC_PLACE_THIRD'				=> '%1$s pour la troisième place',
	'BC_TITLE'						=> 'Hit Parade',
	'BC_UPDATED'					=> 'La chanson a été éditée avec succès',
	'BC_UPS'						=> '%1$s pour ajouter une nouvelle chanson',
	'BC_UPS_EXPLAIN'				=> 'Ici, vous pouvez définir combien de %1$s le membre recevra, quand il/elle ajoute une nouvelle chanson. Mettre à 0 pour désactiver cette fonction.',
	'BC_UPS_TITLE'					=> 'Ultimate Points',
	'BC_UPS_TITLE_EXPLAIN'			=> 'Puisque vous avez l’extension ultimate points et les points activés, vous pouvez configurer certains paramètres supplémentaires.',
	'BC_VOTES_PERIOD'				=> 'Activation des périodes de votes',
	'BC_VOTES_PERIOD_EXPLAIN'		=> 'Activez ici le système des périodes de votes ou non',
	'BC_VOTERS_POINTS'				=> '%s pour le gagnant des votes',
	'BC_VOTERS_POINTS_EXPLAIN'		=> 'Ici vous pouvez définir un bonus de %s que recevra un membre alléatoire gagnant des votes (0 = désactive la function)',
	'BC_VIDEO_WIDTH'				=> 'Largeur de la vidéo',
	'BC_VIDEO_WIDTH_EXPLAIN'		=> 'Entrez ici la largeur de la vidéo sur la page de vue des vidéos',
	'BC_VIDEO_HEIGHT'				=> 'Hauteur de la vidéo',
	'BC_VIDEO_HEIGHT_EXPLAIN'		=> 'Entrez ici la hauteur de la vidéo sur la page de vue des vidéos',
	'BC_WEEK'						=> [
		1	=> '%d semaine',
		2	=> '%d semaines',
	],
	'BC_DAY'						=> [
		1	=> '%d jour',
		2	=> '%d jours',
	],

	'LOG_ADMIN_BC_UPDATED'			=> '<strong>Mise à jour de la configuration du Hit Parade</strong>',
	'LOG_ADMIN_CHART_RESET'			=> '<strong>Démarrage de la réinitialisation automatique du Hit Parade</strong>',
	'LOG_ADMIN_CHART_DELETED'		=> '<strong>Chanson du Hit Parade supprimée</strong><br>» %1$s',
	'LOG_ADMIN_CHART_UPDATED'		=> '<strong>Chanson du Hit Parade éditée</strong><br>» %1$s',
	'LOG_ADMIN_CAT_UPDATED'			=> '<strong>Genre musical édité</strong> » %1$s',
	'LOG_ADMIN_CAT_ADDED'			=> '<strong>Nouveau genre musical ajouté</strong> » %1$s',
	'LOG_ADMIN_CAT_DELETED'			=> '<strong>Genre musical supprimé</strong> » %1$s',
	'LOG_USER_ADDED_SONG'			=> '<strong>Chanson ajoutée au Hit Parade</strong><br>» %1$s.',
	'LOG_USER_EDITED_SONG'			=> '<strong>Chanson du Hit Parade éditée</strong><br>» %1$s.',
	'LOG_USER_REPORT_SONG'			=> '<strong>Chanson de Hit Parade rapportée par %1$s</strong><br>» %2$s',
	'LOG_USER_REPORT_CLOSE'			=> '<strong>Rapport du Hit Parade cloturé</strong><br>» %1$s',
	'LOG_USER_REPORT_SONG_AUTO'		=> '<strong>Chanson de Hit Parade rapportée automatiquement</strong><br>» %1$s',
));
