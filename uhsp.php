<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

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
use UniqueHoverSliderPlus\Core\Plugin;

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
     * A shortened name for menu displays.
     * @var string
     */
    protected $short_name = 'UHSP Slider';

    /**
     * The theme version.
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * The options used by this plugin.
     * @var array
     */
    protected $options = [
        'sliders' => [],
    ];

    /**
     * Top level menu pages. Please don't add more than one.. and make
     * sure that the one you do add is really required to be a top level
     * menu item.
     * @var array
     */
    protected $menu_pages = [
        ['menu_dashboard', 'images/icon.svg'],
        // ['menu_dashboard', 'dashicons-images-alt2'],
    ];

    /**
     * Hooks automatically registered during the boot
     * sequence of the class.
     * @var array
     */
    protected $hooks = [
        ['wp_enqueue_scripts', 'assets'],
        ['wp_head', 'meta_viewport'],
        ['uhsp_add_slider', 'on_add_slider'],
    ];

    /**
     * Shortcodes to be registered.
     * @var string
     */
    protected $shortcodes = [
        ['uhsp', 'render_slider'],
    ];

    /**
     * Automatically called when the class is done booting.
     * @return void
     */
    public function boot()
    {
        // ...
    }

    /**
     * Loads assets. Automatically called after 'wp_enqueue_scripts' hook.
     * @hook   wp_enqueue_scripts
     * @return void
     */
    public function assets()
    {
        $this->enqueue_script('script', 'js/script.js', ['jquery']);
        $this->enqueue_style('style', 'css/stylesheet.min.css');
    }

    /**
     * What the options page should look like. Automatically called as part
     * of the add_options_page method which in turn is called on the admin_menu
     * hook.
     * @hook   admin_menu
     * @return void
     */
    public function menu_dashboard()
    {
        // Kills the page if the user doesn't have enough permissions.
        $this->check_user_permission('modify');

        // Echo the rendered template.
        echo $this->render_template('menu_dashboard.php');
    }

    /**
     * Renders an extra meta tag to manage the viewport on mobile.
     * @return string
     */
    public function meta_viewport()
    {
        echo $this->render_template('meta_viewport.php');
    }

    /**
     * Renders the slider as HTML.
     * @return string
     */
    public function render_slider()
    {
        return $this->render_template('slider.php');
    }

    /**
     * When a new slider is submitted via a form.
     * @return void
     */
    public function on_add_slider()
    {
        // Kills the page if the user doesn't have enough permissions.
        $this->check_user_permission('modify');

        die('YOLO');
    }
}

$uhsp = new UniqueHoverSliderPlus();
