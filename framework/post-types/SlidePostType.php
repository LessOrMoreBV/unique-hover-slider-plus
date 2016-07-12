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
     * Sets the parent plugin.
     * @param Plugin $plugin
     */
    public function set_parent(Plugin $plugin)
    {
        $this->plugin = $plugin;
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
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Slides', $this->plugin->slug),
                'singular_name'      => __('Slide', $this->plugin->slug),
                'menu_name'          => __('UHSP Slider', $this->plugin->slug),
                'parent_item_colon'  => __('Parent Slide:', $this->plugin->slug),
                'all_items'          => __('Add or Edit Slides', $this->plugin->slug),
                'view_item'          => __('View Slide', $this->plugin->slug),
                'add_new_item'       => __('Add New Slide', $this->plugin->slug),
                'add_new'            => __('Add New Slide', $this->plugin->slug),
                'edit_item'          => __('Edit Slide', $this->plugin->slug),
                'update_item'        => __('Update Slide', $this->plugin->slug),
                'search_items'       => __('Search Slide', $this->plugin->slug),
                'not_found'          => __('Not found', $this->plugin->slug),
                'not_found_in_trash' => __('Not found in Trash', $this->plugin->slug),
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
