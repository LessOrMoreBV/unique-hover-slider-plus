<?php
/**
 * @package Unique Hover Slider Plus
 * @version 0.1.0
 */
/*
Plugin Name: Unique Hover Slider Plus
Plugin URI: http://www.lessormore.nl
Description: A slider with a unique hover.
Author: Less or More
Author URI: http://www.lessormore.nl
Version: 0.1.0
License: GNU General Public License
License URI: licence/GPL.txt
*/
class UniqueHoverSliderPlus
{
    /**
     * Registers the shortcode.
     */
    public function __construct()
    {
        add_shortcode('uhsp', [$this, 'render']);
    }

    /**
     * Renders the HTML.
     * @return string
     */
    public function render()
    {
        return file_get_contents(ABSPATH . 'wp-content/plugins/unique-hover-slider-plus/index.php');
    }
}

$uhsp = new UniqueHoverSliderPlus();
