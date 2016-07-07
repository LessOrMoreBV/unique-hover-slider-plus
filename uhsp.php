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
        define('PLUGIN_DIR', ABSPATH . 'wp-content/plugins/unique-hover-slider-plus/');
        define('PLUGIN_URI', plugins_url('unique-hover-slider-plus/'));
        add_action('wp_enqueue_scripts', [$this, 'load']);
        add_shortcode('uhsp', [$this, 'render']);
    }

    /**
     * Loads assets.
     * @return void
     */
    public function load()
    {
        wp_enqueue_script('uhsp-app', PLUGIN_URI . 'assets/js/script.js', ['jquery']);
        wp_enqueue_style('uhsp-css', PLUGIN_URI . 'assets/css/stylesheet.min.css');
    }

    /**
     * Renders the HTML.
     * @return string
     */
    public function render()
    {
        require(PLUGIN_DIR . 'index.php');
    }
}

$uhsp = new UniqueHoverSliderPlus();
