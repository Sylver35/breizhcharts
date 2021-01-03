//
Calendar._TT = {};
Calendar._FD = 1;
switch(calendarLanguage){
	case 'fr':
		Calendar._DN = ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
		Calendar._SDN = ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam","Dim"];
		Calendar._MN = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];
		Calendar._SMN = ["Jan","Fév","Mar","Avr","Mai","Juin","Juil","Aou","Sep","Oct","Nov","Déc"];
		Calendar._TT = {
			"INFO": "À propos du calendrier",
			"ABOUT": "Sélection de la date:\n" +"- Utiliser les boutons \xab, \xbb pour sélectionner l'année\n" +"- Utiliser les boutons " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pour sélectionner le mois\n" +"- En conservant pressé le bouton de la souris sur l'un de ces boutons, la sélection devient plus rapide.",
			"ABOUT_TIME": "\n\n" +"Sélection de l\'heure:\n" +"- Cliquer sur l'une des parties du temps pour l'augmenter\n" +"- ou Maj-clic pour le diminuer\n" +"- ou faire un cliquer-déplacer horizontal pour une modification plus rapide.",
			"PREV_YEAR": "Année préc. (maintenir pour afficher menu)",
			"PREV_MONTH": "Mois préc. (maintenir pour afficher menu)",
			"GO_TODAY": "Atteindre la date du jour",
			"NEXT_MONTH": "Mois suiv. (maintenir pour afficher menu)",
			"NEXT_YEAR": "Année suiv. (maintenir pour afficher menu)",
			"SEL_DATE": "Sélectionner une date",
			"DRAG_TO_MOVE": "Glisser pour déplacer",
			"PART_TODAY": " (aujourd'hui)",
			"DAY_FIRST": "Afficher %s en premier",
			"WEEKEND": "0,6",
			"CLOSE": "Fermer",
			"TODAY": "Aujourd'hui",
			"TIME_PART": "(Maj-)Clic ou glisser pour changer la valeur",
			"DEF_DATE_FORMAT": "%d.%m.%Y",
			"TT_DATE_FORMAT": "%A, %e %B",
			"WK": "sem.",
			"TIME": "Heure:",
		};
	break;
	case 'de':
		Calendar._DN = ["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag"];
		Calendar._SDN = ["So","Mo","Di","Mi","Do","Fr","Sa","So"];
		Calendar._MN = ["Januar","Februar","M\u00e4rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];
		Calendar._SMN = ["Jan","Feb","M\u00e4r","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"];
		Calendar._TT = {
			"INFO": "\u00DCber dieses Kalendarmodul",
			"ABOUT": "DHTML Date/Time Selector\n" +"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +"\n\n" +"Datum ausw\u00e4hlen:\n" +"- Benutzen Sie die \xab, \xbb Buttons um das Jahr zu w\u00e4hlen\n" +"- Benutzen Sie die " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " Buttons um den Monat zu w\u00e4hlen\n" +"- F\u00fcr eine Schnellauswahl halten Sie die Maustaste \u00fcber diesen Buttons fest.",
			"ABOUT_TIME": "\n\n" +"Zeit ausw\u00e4hlen:\n" +"- Klicken Sie auf die Teile der Uhrzeit, um diese zu erh\u00F6hen\n" +"- oder klicken Sie mit festgehaltener Shift-Taste um diese zu verringern\n" +"- oder klicken und festhalten f\u00fcr Schnellauswahl.",
			"PREV_YEAR": "Voriges Jahr (Schnellauswahl: festhalten)",
			"PREV_MONTH": "Voriger Monat (Schnellauswahl: festhalten)",
			"GO_TODAY": "Heute ausw\u00e4hlen",
			"NEXT_MONTH": "N\u00e4chst. Monat (Schnellauswahl: festhalten)",
			"NEXT_YEAR": "N\u00e4chst. Jahr (Schnellauswahl: festhalten)",
			"SEL_DATE": "Datum ausw\u00e4hlen",
			"DRAG_TO_MOVE": "Zum Bewegen festhalten",
			"PART_TODAY": " (Heute)",
			"DAY_FIRST": "Woche beginnt mit %s ",
			"WEEKEND": "0,6",
			"CLOSE": "Schlie\u00dfen",
			"TODAY": "Heute",
			"TIME_PART": "(Shift-)Klick oder Festhalten und Ziehen um den Wert zu \u00e4ndern",
			"DEF_DATE_FORMAT": "%d.%m.%Y",
			"TT_DATE_FORMAT": "%a, %b %e",
			"WK": "wk",
			"TIME": "Zeit:",
		};
	break;
	case 'es':
		Calendar._DN = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];
		Calendar._SDN = ["Dom","Lun","Mar","Mié","Jue","Vie","Sáb","Dom"];
		Calendar._MN = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		Calendar._SMN = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
		Calendar._TT = {
			"INFO": "Acerca del calendario",
			"ABOUT": "Selector DHTML de Fecha/Hora\n" +"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +"Para conseguir la última versión visite: http://www.dynarch.com/projects/calendar/\n" +"Distribuido bajo licencia GNU LGPL. Visite http://gnu.org/licenses/lgpl.html para más detalles." +"\n\n" +"Selección de fecha:\n" +"- Use los botones \xab, \xbb para seleccionar el año\n" +"- Use los botones " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para seleccionar el mes\n" +"- Mantenga pulsado el ratón en cualquiera de estos botones para una selección rápida.",
			"ABOUT_TIME": "\n\n" +"Selección de hora:\n" +"- Pulse en cualquiera de las partes de la hora para incrementarla\n" +"- o pulse las mayúsculas mientras hace clic para decrementarla\n" +"- o haga clic y arrastre el ratón para una selección más rápida.",
			"PREV_YEAR": "Año anterior (mantener para menú)",
			"PREV_MONTH": "Mes anterior (mantener para menú)",
			"GO_TODAY": "Ir a hoy",
			"NEXT_MONTH": "Mes siguiente (mantener para menú)",
			"NEXT_YEAR": "Año siguiente (mantener para menú)",
			"SEL_DATE": "Seleccionar fecha",
			"DRAG_TO_MOVE": "Arrastrar para mover",
			"PART_TODAY": " (hoy)",
			"DAY_FIRST": "Hacer %s primer día de la semana",
			"WEEKEND": "0,6",
			"CLOSE": "Cerrar",
			"TODAY": "Hoy",
			"TIME_PART": "(Mayúscula-)Clic o arrastre para cambiar valor",
			"DEF_DATE_FORMAT": "%d/%m/%Y",
			"TT_DATE_FORMAT": "%A, %e de %B de %Y",
			"WK": "sem",
			"TIME": "Hora:",
		};
	break;
	case 'nl':
		Calendar._FD = 0;
		Calendar._SDN_len = 2;
		Calendar._SMN_len = 3;
		Calendar._DN = ["Zondag","Maandag","Dinsdag","Woensdag","Donderdag","Vrijdag","Zaterdag","Zondag"];
		Calendar._MN = ["Januari","Februari","Maart","April","Mei","Juni","Juli","Augustus","September","Oktober","November","December"];
		Calendar._TT = {
			"INFO": "Info",
			"ABOUT": "DHTML Datum/Tijd Selector\n" +"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +"Ga voor de meest recente versie naar: http://www.dynarch.com/projects/calendar/\n" +"Verspreid onder de GNU LGPL. Zie http://gnu.org/licenses/lgpl.html voor details." +"\n\n" +"Datum selectie:\n" +"- Gebruik de \xab \xbb knoppen om een jaar te selecteren\n" +"- Gebruik de " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " knoppen om een maand te selecteren\n" +"- Houd de muis ingedrukt op de genoemde knoppen voor een snellere selectie.",
			"ABOUT_TIME": "\n\n" +"Tijd selectie:\n" +"- Klik op een willekeurig onderdeel van het tijd gedeelte om het te verhogen\n" +"- of Shift-klik om het te verlagen\n" +"- of klik en sleep voor een snellere selectie.",
			"PREV_YEAR": "Vorig jaar (ingedrukt voor menu)",
			"PREV_MONTH": "Vorige maand (ingedrukt voor menu)",
			"GO_TODAY": "Ga naar Vandaag",
			"NEXT_MONTH": "Volgende maand (ingedrukt voor menu)",
			"NEXT_YEAR": "Volgend jaar (ingedrukt voor menu)",
			"SEL_DATE": "Selecteer datum",
			"DRAG_TO_MOVE": "Klik en sleep om te verplaatsen",
			"PART_TODAY": " (vandaag)",
			"DAY_FIRST": "Toon %s eerst",
			"WEEKEND": "0,6",
			"CLOSE": "Sluiten",
			"TODAY": "(vandaag)",
			"TIME_PART": "(Shift-)Klik of sleep om de waarde te veranderen",
			"DEF_DATE_FORMAT": "%d-%m-%Y",
			"TT_DATE_FORMAT": "%a, %e %b %Y",
			"WK": "wk",
			"TIME": "Tijd:",
		};
	break;
	case 'pt':
		Calendar._FD = 0;
		Calendar._DN = ["Domingo","Segunda","Terca","Quarta","Quinta","Sexta","Sabado","Domingo"];
		Calendar._SDN = ["Dom","Seg","Ter","Qua","Qui","Sex","Sab","Dom"];
		Calendar._MN = ["Janeiro","Fevereiro","Marco","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
		Calendar._SMN = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];
		Calendar._TT = {
			"INFO": "Sobre o calendario",
			"ABOUT": "DHTML Date/Time Selector\n" +"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +"Ultima versao visite: http://www.dynarch.com/projects/calendar/\n" +"Distribuido sobre GNU LGPL.  Veja http://gnu.org/licenses/lgpl.html para detalhes." +"\n\n" +"Selecao de data:\n" +"- Use os botoes \xab, \xbb para selecionar o ano\n" +"- Use os botoes " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para selecionar o mes\n" +"- Segure o botao do mouse em qualquer um desses botoes para selecao rapida.",
			"ABOUT_TIME": "\n\n" +"Selecao de hora:\n" +"- Clique em qualquer parte da hora para incrementar\n" +"- ou Shift-click para decrementar\n" +"- ou clique e segure para selecao rapida.",
			"PREV_YEAR": "Ant. ano (segure para menu)",
			"PREV_MONTH": "Ant. mes (segure para menu)",
			"GO_TODAY": "Hoje",
			"NEXT_MONTH": "Prox. mes (segure para menu)",
			"NEXT_YEAR": "Prox. ano (segure para menu)",
			"SEL_DATE": "Selecione a data",
			"DRAG_TO_MOVE": "Arraste para mover",
			"PART_TODAY": " (hoje)",
			"DAY_FIRST": "Mostre %s primeiro",
			"WEEKEND": "0,6",
			"CLOSE": "Fechar",
			"TODAY": "Hoje",
			"TIME_PART": "(Shift-)Click ou arraste para mudar valor",
			"DEF_DATE_FORMAT": "%d/%m/%Y",
			"TT_DATE_FORMAT": "%a, %e %b",
			"WK": "sm",
			"TIME": "Hora:",
		};
	break;
	default :
	case 'en':
		Calendar._FD = 0;
		Calendar._DN = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
		Calendar._SDN = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
		Calendar._MN = ["January","February","March","April","May","June","July","August","September","October","November","December"];
		Calendar._SMN = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
		Calendar._TT = {
			"INFO": "About the calendar",
			"ABOUT": "DHTML Date/Time Selector\n" +"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +"\n\n" +"Date selection:\n" +"- Use the \xab, \xbb buttons to select year\n" +"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +"- Hold mouse button on any of the above buttons for faster selection.",
			"ABOUT_TIME": "\n\n" +"Time selection:\n" +"- Click on any of the time parts to increase it\n" +"- or Shift-click to decrease it\n" +"- or click and drag for faster selection.",
			"PREV_YEAR": "Prev. year (hold for menu)",
			"PREV_MONTH": "Prev. month (hold for menu)",
			"GO_TODAY": "Go Today",
			"NEXT_MONTH": "Next month (hold for menu)",
			"NEXT_YEAR": "Next year (hold for menu)",
			"SEL_DATE": "Select date",
			"DRAG_TO_MOVE": "Drag to move",
			"PART_TODAY": " (today)",
			"DAY_FIRST": "Display %s first",
			"WEEKEND": "0,6",
			"CLOSE": "Close",
			"TODAY": "Today",
			"TIME_PART": "(Shift-)Click or drag to change value",
			"DEF_DATE_FORMAT": "%Y-%m-%d",
			"TT_DATE_FORMAT": "%a, %b %e",
			"WK": "wk",
			"TIME": "Time:",
		};
	break;
}
	