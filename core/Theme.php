<?php
namespace UniqueHoverSliderPlus;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

require_once('traits/WordpressHelpers.php');
require_once('contracts/HandlesAssetsAndTranslateKey.php');

use UniqueHoverSliderPlus\Contracts\HandlesAssetsAndTranslateKey;
use UniqueHoverSliderPlus\Traits\WordpressHelpers;
use WP_Customize_Color_Control;

abstract class Theme implements HandlesAssetsAndTranslateKey
{
    use WordpressHelpers;

    /**
     * The theme name.
     * @var string
     */
    protected $name = 'My Theme';

    /**
     * The theme slug.
     * @var string
     */
    protected $slug = 'my-theme';

    /**
     * The theme version.
     * @var string
     */
    protected $version = '0.0.0';

    /**
     * The user capability required to edit this plugin.
     * @var string
     */
    public $capability = 'manage_options';

    /**
     * Language code if used.
     * @var string
     */
    protected $lang = '';

    /**
     * The translation key used to mark strings for translation.
     * @var string
     */
    protected $translate_key = '';

    /**
     * The directory we should use as main directory.
     * @var string
     */
    protected $root = 'themes';

    /**
     * Top level menu pages to add.
     * @var array
     */
    protected $admin_menu_pages = [];

    /**
     * Submenu pages to add.
     * @var array
     */
    protected $admin_submenu_pages = [];

    /**
     * Default options.
     * @var array
     */
    protected $_options = [];

    /**
     * Options to automatically register for the plugin.
     * @var array
     */
    protected $options = [];

    /**
     * Defualt hooks.
     * @var array
     */
    protected $_hooks = [
        ['admin_menu', 'menus'],
        ['init', 'init_language'],
        ['wp_enqueue_scripts', 'assets'],
        ['after_setup_theme', 'supports'],
        ['customize_register', 'register_customization_options'],
    ];

    /**
     * A list of hooks and their method registration.
     * @var array
     */
    protected $hooks = [];

    /**
     * Default filters.
     * @var array
     */
    protected $_filters = [];

    /**
     * A list of filters and their method registration.
     * @var array
     */
    protected $filters = [];

    /**
     * Default shortcodes.
     * @var array
     */
    protected $_shortcodes = [];

    /**
     * Shortcodes to be registered.
     * @var string
     */
    protected $shortcodes = [];

    /**
     * Default directories.
     * @var array
     */
    protected $_directories = [
        'root' => '/',
        'assets' => '/assets',
        'styles' => '/assets/css',
        'fonts' => '/assets/fonts',
        'images' => '/assets/images',
        'scripts' => '/assets/js',

        'languages' => '/languages',
        'includes' => '/includes',
        'templates' => '/templates',
        'framework' => '/framework',
        'helpers' => '/framework/helpers',
        'admin' => '/framework/admin',
        'post_types' => '/framework/post-types',
        'taxonomies' => '/framework/taxonomies',
        'shortcodes' => '/framework/shortcodes',
        'loops' => '/framework/loops',
        'integrations' => '/framework/integrations',
    ];

    /**
     * Directories that the user can extend.
     * @var array
     */
    protected $directories = [];

    /**
     * Default URI's.
     * @var array
     */
    protected $_uris = [
        'root' => '/',
        'assets' => '/assets',
        'styles' => '/assets/css',
        'fonts' => '/assets/fonts',
        'images' => '/assets/images',
        'scripts' => '/assets/js',
    ];

    /**
     * URI's that the user can extend.
     * @var array
     */
    protected $uris = [];

    /**
     * Directories of which all files should automatically be included.
     * @var array
     */
    protected $autoload = [
        'helpers',
        'post_types',
        'taxonomies',
        'shortcodes',
        'loops',
        'integrations',
    ];

    /**
     * Post type classes to register.
     * @var array
     */
    protected $post_types = [];

    /**
     * Taxonomy classes to register.
     * @var array
     */
    protected $taxonomies = [];

    /**
     * All registered class instances.
     * @var array
     */
    protected $_registered = [];

    /**
     * Options that should be customizable.
     * @var array
     */
    protected $customization = [];

    /**
     * Initializes the theme.
     */
    public function __construct()
    {
        // Boot the WP Helper trait.
        $this->init_wp_helpers();

        // I don't really know what this is used for, I just copied it.
        $this->check_language_code();

        // Call the boot method in case the user wants to do more.
        $this->boot();
    }

    /**
     * Automatically sets the translate key if none was set.
     * @return void
     */
    private function check_translation_key()
    {
        if (!$this->translate_key) {
            // Set the translate key.
            $this->translate_key = $this->slug;
        }
    }

    /**
     * Checks if ICL_LANGUAGE_CODE is set, and if so, copies it
     * as a property.
     * @return void
     */
    private function check_language_code()
    {
        if (defined("ICL_LANGUAGE_CODE")) {
            $this->lang = ICL_LANGUAGE_CODE;
        }
    }

    /**
     * Adds all the theme support. Automatically called on 'after_setup_theme' hook.
     * @hook   after_setup_theme
     * @return void
     */
    public function supports()
    {
        $this->default_theme_supports();
        $this->register_menus();
        $this->register_image_sizes();
    }

    /**
     * Options that the theme should support by default.
     * @return void
     */
    public function default_theme_supports()
    {
        add_theme_support('menus');
        add_theme_support('editor-style');
        add_theme_support('post-thumbnails');
        add_theme_support('post-formats', array(
            'audio',
            'gallery',
            'image',
            'video',
        ));
        add_theme_support('html5', array(
            'comment-list',
            'comment-form',
            'search-form',
        ));
    }

    /**
     * Translates all menu labels and registers them using
     * register_nav_menus.
     * @return void
     */
    public function register_menus()
    {
        // Map through the registered menus to tranlsate them.
        $menus = array_map(function ($label) {
            return __($label, $this->translate_key);
        }, $this->menus);

        register_nav_menus($menus);
    }

    /**
     * Adds all the registered image sizes using add_image_size.
     * @return void
     */
    public function register_image_sizes()
    {
        $images = array_map(function ($image) {
            // The image size name is listed at the start
            // of the array, so let's change that to include
            // the theme slug.
            $image[0] = $this->prefix($image[0]);
            return $image;
        }, $this->images);

        foreach ($this->images as $image) {
            call_user_func_array('add_image_size', $image);
        }
    }

    /**
     * Parse the theme_customization property to set the options.
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    public function register_customization_options($wp_customize)
    {
        // Check if there are colors to register.
        if (array_key_exists('colors', $this->customization)) {
            $this->register_color_customization($wp_customize);
        }

        // #TODO: Add more customization settings.
    }

    /**
     * Registers all colors listed under the 'color' key in the
     * customization options.
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    public function register_color_customization($wp_customize)
    {
        $wp_customize->add_section($this->prefix('colors'), [
            'title' => __('Theme Colors', $this->translate_key),
            'priority' => 200,
        ]);

        foreach ($this->customization['colors'] as $color) {
            $wp_customize->add_setting($color['name'], [
                'default' => $color['default'],
            ]);

            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $color['name'], [
                'label' => __($color['label'], $this->translate_key),
                'section' => $this->prefix('colors'),
                'settings' => $color['name'],
            ]));
        }
    }

    /**
     * Method that has to be called to add assets. Automatically
     * called on 'wp_enqueue_scripts' hook.
     * @hook   wp_enqueue_scripts
     * @return void
     */
    public function assets() {
        // For the user to implement.
    }

    /**
     * Will be called after the theme is done setting things up. Can
     * be used as an extention of __construct() of sorts.
     * @return void
     */
    public function boot() {
        // For the user to implement.
    }
}
