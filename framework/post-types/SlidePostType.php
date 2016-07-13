<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\PostTypes;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

require_once(__DIR__ . '/../core/Plugin.php');
require_once(__DIR__ . '/../core/Registerable.php');
require_once(__DIR__ . '/../vendor/MultiPostThumbnails/MultiPostThumbnails.php');

// Include base plugin class.
use UniqueHoverSliderPlus\Core\Plugin;
use UniqueHoverSliderPlus\Core\Registerable;
use MultiPostThumbnails;

class SlidePostType implements Registerable
{
    /**
     * The parent plugin that governs this post type.
     * @var Plugin
     */
    protected $plugin;

    /**
     * The translate key used for all translations.
     * @var string
     */
    public $translate_key;

    /**
     * Sets the parent plugin.
     * @param Plugin $plugin
     */
    public function set_parent(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->translate_key = $plugin->translate_key;
    }

    /**
     * The post type.
     */
    const POST_TYPE = 'slide';

    /**
     * Registers the post type.
     * @return void
     */
    public function register()
    {
        // The parent is required to register the post type.
        if (!$this->plugin) {
            throw new Exception('Could not register custom post type: ' . self::POST_TYPE . ' - Could not find parent plugin to register to.');
        }

        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Slides', $this->translate_key),
                'singular_name'      => __('Slide', $this->translate_key),
                'menu_name'          => __('UHSP Slider', $this->translate_key),
                'parent_item_colon'  => __('Parent Slide:', $this->translate_key),
                'all_items'          => __('Add or Edit Slides', $this->translate_key),
                'view_item'          => __('View Slide', $this->translate_key),
                'add_new_item'       => __('Add New Slide', $this->translate_key),
                'add_new'            => __('Add New Slide', $this->translate_key),
                'edit_item'          => __('Edit Slide', $this->translate_key),
                'update_item'        => __('Update Slide', $this->translate_key),
                'search_items'       => __('Search Slide', $this->translate_key),
                'not_found'          => __('Not found', $this->translate_key),
                'not_found_in_trash' => __('Not found in Trash', $this->translate_key),
            ],
            'exclude_from_search' => true,
            'has_archive' => false,
            'menu_icon' => $this->plugin->asset('images/icon.svg'),
            'menu_position' => 100,
            'public' => false,
            'publicly_queriable' => true,
            'query_var' => true,
            // 'register_meta_box_cb' => [$this, 'add_meta_box'],
            'rewrite' => false,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
        ]);

        new MultiPostThumbnails([
            'label' => 'Foreground Icon',
            'id' => 'foreground-icon',
            'post_type' => self::POST_TYPE
        ]);
    }

    // public function add_meta_box()
    // {
    //     add_meta_box(self::POST_TYPE . '_foreground_image', 'Foreground Image', [$this, 'foreground_image'], self::POST_TYPE, 'normal', 'high');
    // }

    // public function foreground_image()
    // {
    //     global $post;
    //     $post_ID = $post->ID; // global used by get_upload_iframe_src
    //     printf("<iframe frameborder='0' src=' %s ' style='width: 100%%; height: 400px;'> </iframe>", get_upload_iframe_src('media'));
    // }
}
