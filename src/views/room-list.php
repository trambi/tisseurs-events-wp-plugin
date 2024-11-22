<?php
// views/room-list.php

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

// Traitement de la suppression si demandée
if (isset($_POST['action']) && $_POST['action'] === 'delete_room' && isset($_POST['room_id'])) {
    if (wp_verify_nonce($_POST['delete_room_nonce'], 'delete_room_' . $_POST['room_id'])) {
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . 'rooms',
            ['id' => intval($_POST['room_id'])],
            ['%d']
        );
        echo '<div class="notice notice-success is-dismissible"><p>Salle supprimée avec succès.</p></div>';
    }
}

// Récupération des salles
global $wpdb;
$table_name = $wpdb->prefix . 'rooms';
$rooms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY name ASC");
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Liste des Salles</h1>
    <a href="<?php echo admin_url('admin.php?page=room-manager-add'); ?>" class="page-title-action">Ajouter</a>
    
    <?php if (empty($rooms)) : ?>
        <p>Aucune salle n'a été créée pour le moment.</p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Capacité</th>
                    <th>Horaires d'ouverture</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room) : ?>
                    <tr>
                        <td><strong><?php echo esc_html($room->name); ?></strong></td>
                        <td>
                            <?php 
                            if ($room->capacity == -1) {
                                echo 'Illimitée';
                            } else {
                                echo esc_html($room->capacity) . ' personnes';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $opening_hours = json_decode($room->opening_hours, true);
                            if (!empty($opening_hours)) {
                                $days_fr = [
                                    'monday' => 'Lundi',
                                    'tuesday' => 'Mardi',
                                    'wednesday' => 'Mercredi',
                                    'thursday' => 'Jeudi',
                                    'friday' => 'Vendredi',
                                    'saturday' => 'Samedi',
                                    'sunday' => 'Dimanche'
                                ];
                                echo '<ul style="margin: 0;">';
                                foreach ($opening_hours as $day => $hours) {
                                    if (isset($days_fr[$day])) {
                                        printf(
                                            '<li>%s : %s - %s</li>',
                                            esc_html($days_fr[$day]),
                                            esc_html($hours['start']),
                                            esc_html($hours['end'])
                                        );
                                    }
                                }
                                echo '</ul>';
                            } else {
                                echo 'Non définis';
                            }
                            ?>
                        </td>
                        <td><?php echo !empty($room->description) ? esc_html(wp_trim_words($room->description, 10)) : '—'; ?></td>
                        <td>
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="<?php echo admin_url('admin.php?page=room-manager-add&action=edit&room_id=' . $room->id); ?>">
                                        Modifier
                                    </a> |
                                </span>
                                <span class="delete">
                                    <form method="post" style="display:inline;">
                                        <?php wp_nonce_field('delete_room_' . $room->id, 'delete_room_nonce'); ?>
                                        <input type="hidden" name="action" value="delete_room">
                                        <input type="hidden" name="room_id" value="<?php echo esc_attr($room->id); ?>">
                                        <button type="submit" class="button-link button-link-delete" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');">
                                            Supprimer
                                        </button>
                                    </form>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
