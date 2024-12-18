<?php
/*  
    Copyright 2024 Bertrand Madet
    This file is part of tisseurs-events-wp-plugin.

     tisseurs-events-wp-plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 
     tisseurs-events-wp-plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 
     You should have received a copy of the GNU General Public License along with tisseurs-events-wp-plugin. If not, see <https://www.gnu.org/licenses/>.
*/
/**
 * View to list rooms
 *
 * @package tisseurs-events-wp-plugin
 * @since 0.1.0
 */

 use TisseursEventScheduler\Db;

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$myDb = new Db($wpdb);
$tableName = $myDb->getRoomTableName();

// Traitement de la suppression si demandée
if (isset($_POST['action']) && $_POST['action'] === 'delete_room' && isset($_POST['room_id'])) {
    if (wp_verify_nonce($_POST['delete_room_nonce'], 'delete_room_' . $_POST['room_id'])) {
        $wpdb->delete(
            $tableName,
            ['id' => intval($_POST['room_id'])],
            ['%d']
        );
        echo '<div class="notice notice-success is-dismissible"><p>Salle supprimée avec succès.</p></div>';
    }
}

// Récupération des salles
$rooms = $wpdb->get_results("SELECT * FROM $tableName ORDER BY name ASC");
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
