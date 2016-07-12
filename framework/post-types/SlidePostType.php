<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\PostTypes;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

// Include base plugin class.
use UniqueHoverSliderPlus\Core\Plugin;

class SlidePostType
{
    /**
     * Registers the post type.
     */
    public static function register(Plugin $plugin)
    {
        register_post_type('slide', [
            'labels' => [
                'name'               => __('Slides', $plugin->slug),
                'singular_name'      => __('Slide', $plugin->slug),
                'menu_name'          => __('UHSP Slider', $plugin->slug),
                'parent_item_colon'  => __('Parent Slide:', $plugin->slug),
                'all_items'          => __('Add or Edit Slides', $plugin->slug),
                'view_item'          => __('View Slide', $plugin->slug),
                'add_new_item'       => __('Add New Slide', $plugin->slug),
                'add_new'            => __('Add New Slide', $plugin->slug),
                'edit_item'          => __('Edit Slide', $plugin->slug),
                'update_item'        => __('Update Slide', $plugin->slug),
                'search_items'       => __('Search Slide', $plugin->slug),
                'not_found'          => __('Not found', $plugin->slug),
                'not_found_in_trash' => __('Not found in Trash', $plugin->slug),
            ],
            'exclude_from_search' => true,
            'has_archive' => false,
            'menu_icon' => $plugin->asset('images/icon.svg'),
            'menu_position' => 100,
            'public' => false,
            'publicly_queriable' => true,
            'rewrite' => false,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
        ]);
    }
}
