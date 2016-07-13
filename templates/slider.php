<?php
if ($slides->have_posts()) {
?>
    <section class="uhsp-slider-wrapper">

        <div class="uhsp-left">
            <svg viewBox="0 0 12.657 23.749"><polyline points="12.304,0.354 0.707,11.95 12.153,23.396 "/></svg>
        </div>
        <div class="uhsp-right">
            <svg viewBox="0 0 12.657 23.749"><polyline points="0.707,23.396 12.304,11.799 0.858,0.354 "/></svg>
        </div>

        <section class="uhsp-slider-titles">
            <ul class="uhsp-title-list">
                <?php
                while ($slides->have_posts()) {
                    $slides->the_post();
                ?>
                    <li class="uhsp-title">
                        <h2 class="uhsp-slide-title"><?= get_the_title(); ?></h2>
                        <div class="uhsp-slide-subtitle"><?= get_the_content(); ?></div>
                    </li>
                <?php
                }
                ?>
                <div class="uhsp-hover-bar">
                    <div class="uhsp-hover-bar-color"></div>
                </div>
            </ul>
        </section>

        <section class="uhsp-slider-images">
            <?php
            while ($slides->have_posts()) {
                $slides->the_post();
            ?>
            <article class="uhsp-single-slide" style="background-image: url('<?= wp_get_attachment_image_src(the_post_thumbnail_url()) ?>')">
                <img class="uhsp-single-image" src="<?= MultiPostThumbnails::get_post_thumbnail_url('slide', 'foreground-icon', get_the_ID(), 'uhsp-foreground-icon@2x') ?>" alt="web-applicaties">
                <div class="ushp-slide-overlay"></div>
            </article>
            <?php
            }
            ?>
        </section>
    </section>
<?php
}
?>