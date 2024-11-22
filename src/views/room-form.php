<?php
// views/room-form.php
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
