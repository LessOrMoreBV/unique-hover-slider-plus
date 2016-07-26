<?php
namespace UniqueHoverSliderPlus;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

// Require the parent theme class for type checks.
require_once('contracts/HandlesAssetsAndTranslateKey.php');
require_once('traits/WordpressHelpers.php');

use UniqueHoverSliderPlus\Contracts\HandlesAssetsAndTranslateKey;
use UniqueHoverSliderPlus\Traits\WordpressHelpers;

abstract class Shortcode {
    use WordpressHelpers;

    /**
     * The shortcode name.
     * @var string
     */
    public $name = '';

    /**
     * The callback method that will render the shortcode.
     * @var string
     */
    public $callback = 'render';

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
    protected $_uris = [];

    /**
     * URI's that the user can extend.
     * @var array
     */
    protected $uris = [];

    /**
     * Defualt hooks.
     * @var array
     */
    protected $_hooks = [
        ['vc_before_init', 'vc_map'],
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
     * The key used to mark translations.
     * @var string
     */
    protected $translate_key = '';

    /**
     * Slug copied from parent, in the case of Registerables only used to
     * determine the path to directories.
     * @var string
     */
    protected $slug = '';

    /**
     * Root copied from parent, in the case of Registerables only used to
     * determine the path to directories.
     * @var string
     */
    protected $root = '';

    /**
     * The parent class if applicable.
     * @var HandlesAssetsAndTranslateKey
     */
    protected $parent;

    /**
     * Visual composer options that should be marked for translation.
     * @var array
     */
    protected $vc_text_options = [
        'name',
        'description',
        'category',
        'heading',
    ];

    /**
     * Initializes the shortcode.
     * @event '*_shortcode_registered'
     */
    public function __construct(HandlesAssetsAndTranslateKey $parent = null)
    {
        // Set a default translate key if none is set.
        if (!$this->translate_key) {
            $this->translate_key = $this->name;
        }

        // If a valid theme was passed, we can set it as our parent theme.
        if ($parent) {
            $this->set_parent($parent);
        }

        // Register the directories to the class.
        $this->register_directories();
        $this->register_actions();

        // Add the shortcode to WP.
        $this->add_shortcode($this->name, $this->callback);

        // Trigger an action so that the user can hook into our post-registration
        // event.
        do_action($this->name . '_shortcode_registered');
    }

    /**
     * Attaches a parent theme as property.
     * @param Theme $theme
     */
    public function set_parent(HandlesAssetsAndTranslateKey $parent)
    {
        $this->parent = $parent;

        // If the parent has a translate key, we copy it.
        if ($parent->get_translate_key()) {
            $this->translate_key = $parent->get_translate_key();
        }

        // Same goes for the slug.
        if ($parent->get_slug()) {
            $this->slug = $parent->get_slug();
        }

        // And for the root property.
        if ($parent->get_root()) {
            $this->root = $parent->get_root();
        }
    }

    /**
     * Maps the shortcode to visual composer if the props are set.
     * @return void
     */
    public function vc_map()
    {
        // Make sure we actually have some settings to map.
        if (!empty($this->vc_options)) {
            // Translate the options.
            $this->translate_text_opts();

            // Map the shortcode to visual composer.
            vc_map($this->vc_options);
        }
    }

    /**
     * Marks all visual composer options that actually need translations.
     * @return void
     */
    public function translate_text_opts()
    {
        foreach ($this->vc_options as $key => $opt) {
            if (in_array($key, $this->vc_text_options)) {
                $this->vc_options[$key] = __($opt, $this->translate_key);
            }
        }

        foreach ($this->vc_options['params'] as $i => $param) {
            foreach ($param as $param_key => $param_opt) {
                if (in_array($param_key, $this->vc_text_options)) {
                    $this->vc_options['params'][$i][$param_key] = __($param_opt, $this->translate_key);
                }
            }
        }
    }
}