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

// Include base plugin class.
require_once('core/Plugin.php');

class UniqueHoverSliderPlus extends Plugin
{
    /**
     * Debug mode toggle.
     * @var boolean
     */
    protected $debug = false;

    /**
     * The plugin name.
     * @var string
     */
    protected $name = 'Unique Hover Slider Plus';

    /**
     * The plugin slug.
     * @var string
     */
    protected $slug = 'unique-hover-slider-plus';

    /**
     * The theme version.
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Loads assets. Automatically called after 'wp_enqueue_scripts' hook.
     * @hook   wp_enqueue_scripts
     * @return void
     */
    public function assets()
    {
        $this->enqueue_script('script', 'script.js', ['jquery']);
        $this->enqueue_style('style', 'stylesheet.min.css');
    }

    /**
     * Automatically called when the class is done booting.
     * @return void
     */
    public function boot()
    {
        $this->add_shortcode('uhsp', 'render');
    }

    /**
     * Renders the HTML.
     * @return string
     */
    public function render()
    {
        // #TODO: Make better, screw ob.
        ob_start();
        define('PLUGIN_URI', $this->get_uri('plugin'));
        include $this->get_dir('plugin') . 'index.php';
        $template = ob_get_contents(); // get contents of buffer
        ob_end_clean();
        return $template;
    }
}

$uhsp = new UniqueHoverSliderPlus();
