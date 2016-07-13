<tr class="form-field">
    <th scope="row" valign="top"><label for="overlay_color"><?= _e('Overlay color', $this->translate_key) ?></label></th>
    <td>
        <input type="text" name="overlay_color" id="overlay_color" size="40" placeholder="#FF0000">
        <p class="description"><?= _e('Set a hexadecimal color code for the overlay that covers the slider.', $this->translate_key) ?></p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="overlay_opacity"><?= _e('Overlay opacity', $this->translate_key) ?></label></th>
    <td>
        <input type="text" name="overlay_opacity" id="overlay_opacity" size="40" placeholder="60%">
        <p class="description"><?= _e('Set the transparancy value of the overlay. If you want to disable the overlay you can set this value to 0%.', $this->translate_key) ?></p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="arrow_buttons"><?= _e('Arrow buttons', $this->translate_key) ?></label></th>
    <td>
        <input type="checkbox" name="arrow_buttons" id="arrow_buttons" size="40">
        <p class="description"><?= _e('Check the box if you want to activate the navigation arrows on the left and right side of the slider.', $this->translate_key) ?></p>
    </td>
</tr>
