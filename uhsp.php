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
    public $version = '1.0.0';

    /**
     * Hooks automatically registered during the boot
     * sequence of the class.
     * @var array
     */
    protected $hooks = [
        ['wp_enqueue_scripts', 'assets'],
        ['admin_enqueue_scripts', 'admin_assets'],
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
     * Register custom post types and image sizes on init.
     * @return void
     */
    public function on_init()
    {
        // Image sizes required for the foreground icon.
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
     * Loads admin assets. Automatically called after 'wp_enqueue_scripts' hook.
     * @hook   wp_enqueue_scripts
     * @return void
     */
    public function admin_assets()
    {
        $this->enqueue_script('colorpicker', 'js/colorpicker.js', ['jquery']);
        $this->enqueue_style('style', 'css/stylesheet.min.css');
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
        // ID will be available in the list of attributes.
        extract($attributes);

        // Retrieve the slides belonging to the slide_page.
        $slides = SlidePostType::query_slides_of_slider($id);

        // Retrieve the extra meta.
        $meta = SlidePageTaxonomy::get_formatted_option($id);

        return $this->render_template('slider.php', ['slides' => $slides, 'meta' => $meta]);
    }
}

$uhsp = new UniqueHoverSliderPlus();
