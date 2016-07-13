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


require_once('framework/core/Plugin.php');
require_once('framework/post-types/SlidePostType.php');
require_once('framework/taxonomies/SlidePageTaxonomy.php');

use UniqueHoverSliderPlus\Core\Plugin;
use UniqueHoverSliderPlus\PostTypes\SlidePostType;
use UniqueHoverSliderPlus\Taxonomies\SlidePageTaxonomy;

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
     *
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
    ];/**/

    /**
     * Hooks automatically registered during the boot
     * sequence of the class.
     * @var array
     */
    protected $hooks = [
        ['wp_enqueue_scripts', 'assets'],
        ['wp_head', 'meta_viewport'],
        ['uhsp_add_slider', 'on_add_slider'],
        ['init', 'on_init'],
    ];

    /**
     * Filters automatically registered during the boot
     * sequence of the class.
     * @var array
     */
    protected $filters = [
        ['upload_mimes', 'cc_mime_types'],
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
     * Register custom post types on init.
     * @return void
     */
    public function on_init()
    {
        add_image_size('uhsp-foreground-icon@2x', 740, 500, true);
        add_image_size('uhsp-foreground-icon', 370, 250, true);
        $this->register_post_type(new SlidePostType);
        $this->register_taxonomy(new SlidePageTaxonomy);
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
    // public function menu_dashboard()
    // {
    //     // Kills the page if the user doesn't have enough permissions.
    //     $this->check_user_permission('modify');

    //     // Echo the rendered template.
    //     echo $this->render_template('menu_dashboard.php');
    // }

    /**
     * Renders the slides page.
     * @return void
     */
    // public function menu_slides()
    // {
    //     echo "<h2>UHSP Slides</h2>";
    // }

    /**
     * Renders the sliders page.
     * @return string
     */
    // public function menu_sliders()
    // {
    //     echo "<h2>UHSP Sliders</h2>";
    // }

    /**
     * Renders an extra meta tag to manage the viewport on mobile.
     * @return string
     */
    public function meta_viewport()
    {
        echo $this->render_template('meta_viewport.php');
    }

    /**
     * Extends the default mime type array with the svg mime type.
     * @param  array  $mimes
     * @return string
     */
    public function cc_mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Renders the slider as HTML.
     * @return string
     */
    public function render_slider($attributes, $content)
    {
        extract($attributes);

        // Retrieve the slides belonging to the slide_page.
        $args = [
            'posts_per_page' => 5,
            'no_found_rows' => true,
            'post_type' => 'slide',
            'tax_query' => [
                [
                    'taxonomy' => SlidePageTaxonomy::TAXONOMY,
                    'field' => 'id',
                    'terms' => $id,
                ]
            ]
        ];
        $slides = new WP_Query($args);

        // Retrieve the extra meta.
        $opt = 'taxonomy_' . SlidePageTaxonomy::TAXONOMY . '_' . $id;
        $meta = get_option($opt);
        $meta['overlay_opacity'] = (int) str_replace('%', '', $meta['overlay_opacity']) / 100;

        return $this->render_template('slider.php', ['slides' => $slides, 'meta' => $meta]);
    }

    /**
     * When a new slider is submitted via a form.
     * @return void
     */
    public function on_add_slider()
    {
        // Kills the page if the user doesn't have enough permissions.
        $this->check_user_permission('modify');

        // ... Do stuff.
    }
}

$uhsp = new UniqueHoverSliderPlus();
