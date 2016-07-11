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


require_once('core/Plugin.php');
require_once('post-types/SlidePostType.php');

use UniqueHoverSliderPlus\Core\Plugin;
use UniqueHoverSliderPlus\PostTypes\SlidePostType;

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
    public $name = 'Unique Hover Slider Plus';

    /**
     * The plugin slug.
     * @var string
     */
    public $slug = 'unique-hover-slider-plus';

    /**
     * A shortened name for menu displays.
     * @var string
     */
    public $short_name = 'UHSP Slider';

    /**
     * The theme version.
     * @var string
     */
    public $version = '0.1.0';

    /**
     * The options used by this plugin.
     * @var array
     */
    protected $options = [
        // ...
    ];

    /**
     * Top level menu pages. Please don't add more than one.. and make
     * sure that the one you do add is really required to be a top level
     * menu item.
     * @var array
     */
    protected $menu_pages = [
        [
            'menu_slug' => 'uhsp-menu',
            'method' => 'menu_dashboard',
            'icon' => 'images/icon.svg',
            'children' => [
                [
                    'page_title' => 'USHP Slides',
                    'menu_title' => 'Slides',
                    'menu_slug' => 'uhsp-slides',
                    'method' => 'menu_slides'
                ],
                [
                    'page_title' => 'USHP Sliders',
                    'menu_title' => 'Sliders',
                    'menu_slug' => 'uhsp-sliders',
                    'method' => 'menu_sliders'
                ],
            ]
        ],
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
        ['init', 'register_post_types'],
    ];

    /**
     * Shortcodes to be registered.
     * @var string
     */
    protected $shortcodes = [
        ['uhsp', 'render_slider'],
    ];

    /**
     * Post types created by this plugin.
     * @var array
     */
    protected $post_types = [
        'uhsp_slide' => [
            'labels' => [],
        ]
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
     * Register custom post types on init.
     * @return void
     */
    public function register_post_types()
    {
        SlidePostType::register($this);
    }

    /**
     * Loads assets. Automatically called after 'wp_enqueue_scripts' hook.
     * @hook   wp_enqueue_scripts
     * @return void
     */
    public function assets()
    {
        $this->enqueue_script('vendor', 'js/vendor.js');
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
     * Renders the slides page.
     * @return void
     */
    public function menu_slides()
    {
        echo "<h2>UHSP Slides</h2>";
    }

    /**
     * Renders the sliders page.
     * @return string
     */
    public function menu_sliders()
    {
        echo "<h2>UHSP Sliders</h2>";
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
