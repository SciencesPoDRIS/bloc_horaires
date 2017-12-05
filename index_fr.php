<?php

/***
 * Variables
 ***/

$schedule_date_format = 'd-m-Y';
// $schedule_url = 'https://api3-eu.libcal.com/api_hours_grid.php?iid=3328&format=json&weeks=1&systemTime=1&lid=5832';
$schedule_url = 'data_fr.json';


/***
 * Functions
 ***/

function get_final_schedule($schedule_array, $schedule_date = null) {
    global $schedule_date_format;
    $schedule_return = key(array_count_values($schedule_array));
    $schedule_return_bis = '';
    $schedule_first_open_day = 'Lundi';
    $schedule_last_open_day = 'Vendredi';
    // If there is only one value in this array, return this value
    if(array_count_values($schedule_array)[$schedule_return] != 5 && !empty($schedule_date)) {
        // If there is a holiday in this week
        // If the librairies are closed on Monday
        if($schedule_array['Monday'] != $schedule_return) {
            $schedule_return_bis .= '<li>Attention, fermé lundi ' . date($schedule_date_format, strtotime($schedule_date)) . '.</li>';
            $schedule_first_open_day = 'Mardi';
        }
        // If the librairies are closed on Tuesday
        if($schedule_array['Tuesday'] != $schedule_return) {
            $schedule_return_bis .= '<li>Attention, fermé mardi ' . date($schedule_date_format, strtotime($schedule_date . ' +1 day')) . '.</li>';
            // If the libraries are also closed on Monday
            if($schedule_array['Monday'] != $schedule_return) {
                $schedule_first_open_day = 'Mercredi';
            }
            // If the librairies are also closed on Wednesday, Thursday and Friday
            if(($schedule_array['Wednesday'] != $schedule_return) and ($schedule_array['Thursday'] != $schedule_return) and ($schedule_array['Friday'] != $schedule_return)) {
                $schedule_last_open_day = 'Lundi';
        }
        // If the librairies are closed on Wednesday
        if($schedule_array['Wednesday'] != $schedule_return) {
            $schedule_return_bis .= '<li>Attention, fermé mercredi ' . date($schedule_date_format, strtotime($schedule_date . ' +2 day')) . '.</li>';
            // If the librairies are also closed on Monday and Tuesday
            if(($schedule_array['Monday'] != $schedule_return) && ($schedule_array['Tuesday'] != $schedule_return)) {
                $schedule_first_open_day = 'Jeudi';
            }
            // If the librairies are also closed on Thursday and Friday
            if(($schedule_array['Thursday'] != $schedule_return) and ($schedule_array['Friday'] != $schedule_return)) {
                $schedule_last_open_day = 'Mardi';
            }
        }
        // If the librairies are closed on Thursday
        if($schedule_array['Thursday'] != $schedule_return) {
            $schedule_return_bis .= '<li>Attention, fermé jeudi ' . date($schedule_date_format, strtotime($schedule_date . ' +3 day')) . '.</li>';
            // If the librairies are also closed on Monday, Tuesday and Wednesday
            if(($schedule_array['Monday'] != $schedule_return) && ($schedule_array['Tuesday'] != $schedule_return) && ($schedule_array['Wednesday'] != $schedule_return)) {
                $schedule_first_open_day = 'Vendredi';
            }
            // If the librairies are also closed on Friday
            if($schedule_array['Friday'] != $schedule_return) {
                $schedule_last_open_day = 'Mercredi';
            }
        }
        // If the librairies are closed on Friday
        if($schedule_array['Friday'] != $schedule_return) {
            $schedule_return_bis .= '<li>Attention, fermé vendredi ' . date($schedule_date_format, strtotime($schedule_date . ' +4 day')) . '.</li>';
            $schedule_last_open_day = 'Jeudi';
        }
    }
    return Array($schedule_return, $schedule_return_bis, $schedule_first_open_day, $schedule_last_open_day);
}


/***
 * Script / Main
 ***/

// Collect data from Libcal API
$schedule_json = file_get_contents($schedule_url);
$schedule_data = json_decode($schedule_json, TRUE);

// Grab interesting data
// Week
// 27 rue Saint Guillaume, Week, Opening hour
$schedule_27rsg_week_open_array = Array(
    'Monday' => $schedule_data['loc_5858']['weeks'][0]['Monday']['times']['hours'][0]['from'],
    'Tuesday' => $schedule_data['loc_5858']['weeks'][0]['Tuesday']['times']['hours'][0]['from'],
    'Wednesday' => $schedule_data['loc_5858']['weeks'][0]['Wednesday']['times']['hours'][0]['from'],
    'Thursday' => $schedule_data['loc_5858']['weeks'][0]['Thursday']['times']['hours'][0]['from'],
    'Friday' => $schedule_data['loc_5858']['weeks'][0]['Friday']['times']['hours'][0]['from']
);
list($schedule_27rsg_week_open, $schedule_27rsg_week_message, $schedule_first_open_day, $schedule_last_open_day) = get_final_schedule($schedule_27rsg_week_open_array, $schedule_data['loc_5858']['weeks'][0]['Monday']['date']);
// 27 rue Saint Guillaume, Week, Closing hour
$schedule_27rsg_week_close_array = Array(
    'Monday' => $schedule_data['loc_5858']['weeks'][0]['Monday']['times']['hours'][0]['to'],
    'Tuesday' => $schedule_data['loc_5858']['weeks'][0]['Tuesday']['times']['hours'][0]['to'],
    'Wednesday' => $schedule_data['loc_5858']['weeks'][0]['Wednesday']['times']['hours'][0]['to'],
    'Thursday' => $schedule_data['loc_5858']['weeks'][0]['Thursday']['times']['hours'][0]['to'],
    'Friday' => $schedule_data['loc_5858']['weeks'][0]['Friday']['times']['hours'][0]['to']
);
$schedule_27rsg_week_close = get_final_schedule($schedule_27rsg_week_close_array)[0];
// 30 rue Saint Guillaume, Week, Opening hour
$schedule_30rsg_week_open_array = Array(
    'Monday' => $schedule_data['loc_5859']['weeks'][0]['Monday']['times']['hours'][0]['from'],
    'Tuesday' => $schedule_data['loc_5859']['weeks'][0]['Tuesday']['times']['hours'][0]['from'],
    'Wednesday' => $schedule_data['loc_5859']['weeks'][0]['Wednesday']['times']['hours'][0]['from'],
    'Thursday' => $schedule_data['loc_5859']['weeks'][0]['Thursday']['times']['hours'][0]['from'],
    'Friday' => $schedule_data['loc_5859']['weeks'][0]['Friday']['times']['hours'][0]['from']
);
$schedule_30rsg_week_open = get_final_schedule($schedule_30rsg_week_open_array)[0];
// 30 rue Saint Guillaume, Week, Closing hour
$schedule_30rsg_week_close_array = Array(
    'Monday' => $schedule_data['loc_5859']['weeks'][0]['Monday']['times']['hours'][0]['to'],
    'Tuesday' => $schedule_data['loc_5859']['weeks'][0]['Tuesday']['times']['hours'][0]['to'],
    'Wednesday' => $schedule_data['loc_5859']['weeks'][0]['Wednesday']['times']['hours'][0]['to'],
    'Thursday' => $schedule_data['loc_5859']['weeks'][0]['Thursday']['times']['hours'][0]['to'],
    'Friday' => $schedule_data['loc_5859']['weeks'][0]['Friday']['times']['hours'][0]['to']
);
$schedule_30rsg_week_close = get_final_schedule($schedule_30rsg_week_close_array)[0];
// 27 rue Saint Guillaume, Saturday, Opening hour
$schedule_27rsg_saturday_open = $schedule_data['loc_5858']['weeks'][0]['Saturday']['times']['hours'][0]['from'];
// 27 rue Saint Guillaume, Saturday, Closing hour
$schedule_27rsg_saturday_close = $schedule_data['loc_5858']['weeks'][0]['Saturday']['times']['hours'][0]['to'];
// 30 rue Saint Guillaume, Saturday, Opening hour
$schedule_30rsg_saturday_open = $schedule_data['loc_5859']['weeks'][0]['Saturday']['times']['hours'][0]['from'];
// 30 rue Saint Guillaume, Saturday, Closing hour
$schedule_30rsg_saturday_close = $schedule_data['loc_5859']['weeks'][0]['Saturday']['times']['hours'][0]['to'];

// Build the schedules bloc
$schedule_block = "<li>$schedule_first_open_day - $schedule_last_open_day:</li>";
$schedule_block .= "<li>$schedule_27rsg_week_open - $schedule_27rsg_week_close (27SG) | $schedule_30rsg_week_open - $schedule_30rsg_week_close (30SG)</li>";
// If the library is closed on saturday
if(empty($schedule_27rsg_saturday_open)) {
    $schedule_27rsg_week_message .= '<li>Attention, fermé samedi ' . date($schedule_date_format, strtotime($schedule_data['loc_5858']['weeks'][0]['Saturday']['date'])) . '.</li>';
} else {
    $schedule_block .= '<li>Samedi:</li>';
    $schedule_block .= "<li>$schedule_27rsg_saturday_open - $schedule_27rsg_saturday_close (27SG) | $schedule_30rsg_saturday_open - $schedule_30rsg_saturday_close (30SG)</li>";
}
if(!empty($schedule_27rsg_week_message)) {
    $schedule_block .= "$schedule_27rsg_week_message";
}

$schedule_html = '';
$schedule_html .= '<div id="entre-etudiant">';
$schedule_html .= '<h2>Etudiants</h2>';
$schedule_html .= '<ul>';
$schedule_html .= '<li><a href="http://sciencespo.libcal.com/booking/salles-travail-groupe" target="_blank">Réserver une salle de travail</a></li>';
$schedule_html .= '<li><a href="/bibliotheque/fr/etudier/photocopier-imprimer">Imprimer, scanner, killprint</a></li>';
$schedule_html .= '<li><a href="http://www.sciencespo.fr/bibliotheque/fr/rechercher/eressources" target="_blank">Lire la presse en ligne</a></li>';
$schedule_html .= '<li><a href="/bibliotheque/fr/etudier/etudiants-campus-regions">Campus en région</a></li>';
$schedule_html .= '<li><a href="http://www.sciencespo.fr/bibliotheque/fr/a-votre-ecoute/enquetes/libqual/libqual2017">Enquête Libqual+ 2017</a></li>';
$schedule_html .= '</ul>';
$schedule_html .= '</div>';
$schedule_html .= '<div id="entre-enseignant">';
$schedule_html .= '<h2>Enseignants et Chercheurs</h2>';
$schedule_html .= '<ul>';
$schedule_html .= '<li><a href="http://www.sciencespo.fr/ecole-doctorale/fr/content/bibliotheque-de-lecole-doctorale" target="_blank">Bibliothèque de recherche</a></li>';
$schedule_html .= '<li><a href="/bibliotheque/fr/enseignants-chercheurs/gestion-donnees-recherche">Gestion des données de la recherche</a></li>';
$schedule_html .= '<li><a href="/bibliotheque/fr/enseignants-chercheurs/navette">Navette chercheurs</a></li>';
$schedule_html .= '<li><a href="https://docs.google.com/a/sciencespo.fr/forms/d/e/1FAIpQLSfVnVnYZIW8QpVm5p8TEjoQQccdLHDdThJa-jkj7Q_tqGqIwQ/viewform" target="_blank">Numérisation à la demande</a></li>';
$schedule_html .= '<li><a href="http://spire.sciences-po.fr/" target="_blank">Spire, l\'archive ouverte</a></li>';
$schedule_html .= '<li><a href="/bibliotheque/fr/rechercher/trucs-astuces">Trucs et astuces</a></li>';
$schedule_html .= '</ul>';
$schedule_html .= '</div>';
$schedule_html .= '<div id="entre-venir">';
$schedule_html .= '<h2>Venir</h2>';
$schedule_html .= '<ul>';
$schedule_html .= $schedule_block;
$schedule_html .= '<li><a href="http://www.sciencespo.fr/bibliotheque/fr/venir/horaires">Tous les horaires</a></li>';
$schedule_html .= '<li><a href="/bibliotheque/fr/venir/conditions-acces">S\'inscrire</a></li>';
$schedule_html .= '</ul>';
$schedule_html .= '</div>';

// Display the whole block
print $schedule_html;

?>