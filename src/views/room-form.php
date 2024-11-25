<?php
/*  
    Copyright 2024 Bertrand Madet
    This file is part of tisseurs-event-scheduler.

     tisseurs-event-scheduler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 
     tisseurs-event-scheduler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 
     You should have received a copy of the GNU General Public License along with tisseurs-event-scheduler. If not, see <https://www.gnu.org/licenses/>.
*/

?>
<div class="wrap">
    <h1>Ajouter une Salle</h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('create_room', 'room_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="room_name">Nom de la salle</label></th>
                <td><input type="text" id="room_name" name="room_name" class="regular-text" required></td>
            </tr>
            
            <tr>
                <th scope="row"><label for="room_capacity">Capacité</label></th>
                <td>
                    <input type="number" id="room_capacity" name="room_capacity" min="1" class="small-text">
                    <label>
                        <input type="checkbox" id="infinite_capacity" name="room_capacity" value="infinite">
                        Capacité illimitée
                    </label>
                </td>
            </tr>
            
            <tr>
                <th scope="row">Créneaux d'ouverture</th>
                <td>
                    <?php
                    $days = array(
                        'monday' => 'Lundi',
                        'tuesday' => 'Mardi',
                        'wednesday' => 'Mercredi',
                        'thursday' => 'Jeudi',
                        'friday' => 'Vendredi',
                        'saturday' => 'Samedi',
                        'sunday' => 'Dimanche'
                    );
                    
                    foreach ($days as $day_key => $day_label) : ?>
                        <div class="opening-hours">
                            <label>
                                <input type="checkbox" name="<?php echo $day_key; ?>_enabled">
                                <?php echo $day_label; ?>
                            </label>
                            <input type="time" name="<?php echo $day_key; ?>_start">
                            à
                            <input type="time" name="<?php echo $day_key; ?>_end">
                        </div>
                    <?php endforeach; ?>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><label for="room_description">Description</label></th>
                <td>
                    <textarea id="room_description" name="room_description" class="large-text" rows="5"></textarea>
                </td>
            </tr>
        </table>
        
        <input type="submit" name="create_room" class="button button-primary" value="Créer la salle">
    </form>
</div>
