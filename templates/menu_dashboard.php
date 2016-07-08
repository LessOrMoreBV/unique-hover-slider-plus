<div class="wrap">
    <h2><?= $this->name ?></h2>
    <h3><?= _e("Add new slider", $this->slug) ?></h3>
    <form name="uhsp_add_slider" method="post" action="">

        <!-- Event input determines how the form is handled in the back-end. -->
        <input type="hidden" name="event" value="add_slider">

        <p><?= _e('Slider title', $this->slug) ?></p>
        <input type="text" name="uhsp-slider-title" placeholder="The title">
        <p><?= _e('Slider slug', $this->slug) ?></p>
        <input type="text" name="uhsp-slider-slug" placeholder="The slug">
        <p class="submit">
            <input type="submit" name="submit" class="button-primary" value="<?= _e('Add slider', $this->slug) ?>">
        </p>
    </form>
</div>