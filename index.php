<?php
/*
Plugin Name: Tiny-directory
Plugin URI: https://wpusermanager.com/
Plugin URI: https://wpusermanager.com/
Description: ! Nécéssite le plugin WP User Manager ! Récupère les utilisateurs dans la base de donnée et affiches leurs informations sous la forme d'un annuaire dépliable
Author: Robin Simonneau
Version: 1.0
Author URI: https://robin-sim.fr/
*/


add_shortcode('users_directory','createDirectoryFromDBUsers');

/**
 * Récupère les utilisateurs dans la base de donnée et les affect à un tableau HTML
 * @return string Le tableau HTML
 */
function createDirectoryFromDBUsers(): string{
    //Ajout de la feuille de style et du javascript
    wp_enqueue_style('tiny-directory-css',plugins_url('tiny-directory.css',__FILE__));
    wp_enqueue_script('tiny-directory-js',plugins_url('tiny-directory.js',__FILE__),array(), false, true);
    $users = get_users();
    // Vérifier s'il y a des utilisateurs
    if ( !empty( $users ) ) {
        //Génère le tableau
        $html = <<<HTML
    <table class="tiny-directory-table">
    <thead >
        <tr class="tiny-directory-tr">
            <th class="tiny-directory-th" colspan="1">NOM / Prénom</th>
            <th class="tiny-directory-th" colspan="1">Email</th> 
            <th class="tiny-directory-th" colspan="1">Téléphone</th> 
            <th class="tiny-directory-th" colspan="1">Fonction</th> 

    </thead>
    <tbody>
    HTML;
        foreach ( $users as $user ) {
            $userID = $user->ID;
            $userAvatar = get_avatar($userID);
            $linkToProfilePage = home_url()."/profile/$user->user_nicename";
            $html.= <<<HTML
        <tr class="user-$userID tiny-directory-tr">
            <td class="no-display-fields" id="pp-$userID" data-id="$userID">$userAvatar</td>
            <td class="no-display-fields" id="login-$userID">$linkToProfilePage</td>
            <td class="tiny-directory-td name-$userID">$user->display_name</td>
            <td class="tiny-directory-td email-$userID"><a href="mailto:$user->user_email">$user->user_email</td>
            <td class="tiny-directory-td phone-$userID">E.T téléphone maison</td>
            <td class="tiny-directory-td position-$userID">Fonction</td>
        </tr>
HTML;
        }
        $html.= <<<HTML
    </tbody>
</table>

HTML;
        return $html;
    } else {
        return "Error no users has been found :(";
    }
}


