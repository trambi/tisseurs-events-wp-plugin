<?php
/**
 * Plugin Name: Gestionnaire de Salles
 * Description: Plugin de gestion de réservation de salles
 * Version: 0.1.0
 * Author: Bertrand Madet
 */

// Sécurité
defined('ABSPATH') or die('Accès interdit');

// Activation du plugin
register_activation_hook(__FILE__, 'room_manager_activation');
function room_manager_activation() {
    // Création de la table pour les salles
    global $wpdb;
    $table_name = $wpdb->prefix . 'rooms';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        capacity int(11) DEFAULT NULL,
        description text,
        opening_hours longtext,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Ajout du menu dans l'admin
add_action('admin_menu', 'room_manager_admin_menu');
function room_manager_admin_menu() {
    add_menu_page(
        'Gestion des Salles',
        'Salles',
        'manage_options',
        'room-manager',
        'room_manager_main_page',
        'dashicons-building',
        6
    );
    
    add_submenu_page(
        'room-manager',
        'Ajouter une Salle',
        'Ajouter',
        'manage_options',
        'room-manager-add',
        'room_manager_add_page'
    );
}

// Page principale de gestion des salles
function room_manager_main_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Accès refusé');
    }
    ?>
    <div class="wrap">
        <h1>Gestion des Salles</h1>
        <?php include(plugin_dir_path(__FILE__) . 'views/room-list.php'); ?>
    </div>
    <?php
}

// Page d'ajout d'une salle
function room_manager_add_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Accès refusé');
    }
    
    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_room'])) {
        handle_room_creation();
    }
    
    // Affichage du formulaire
    include(plugin_dir_path(__FILE__) . 'views/room-form.php');
}

// Traitement de la création d'une salle
function handle_room_creation() {
    if (!wp_verify_nonce($_POST['room_nonce'], 'create_room')) {
        wp_die('Sécurité: nonce invalide');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'rooms';
    
    $opening_hours = array();
    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
        if (isset($_POST[$day . '_enabled'])) {
            $opening_hours[$day] = array(
                'start' => sanitize_text_field($_POST[$day . '_start']),
                'end' => sanitize_text_field($_POST[$day . '_end'])
            );
        }
    }
    
    $wpdb->insert(
        $table_name,
        array(
            'name' => sanitize_text_field($_POST['room_name']),
            'capacity' => $_POST['room_capacity'] === 'infinite' ? -1 : intval($_POST['room_capacity']),
            'description' => sanitize_textarea_field($_POST['room_description']),
            'opening_hours' => json_encode($opening_hours)
        ),
        array('%s', '%d', '%s', '%s')
    );
    
    add_action('admin_notices', 'room_created_notice');
}

// Message de confirmation
function room_created_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>La salle a été créée avec succès.</p>
    </div>
    <?php
}
