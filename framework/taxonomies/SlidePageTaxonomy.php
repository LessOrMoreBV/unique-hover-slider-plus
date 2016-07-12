<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\Taxonomies;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

// Include base plugin class.
use UniqueHoverSliderPlus\Core\Plugin;

class SlidePageTaxonomy
{
    /**
     * Registers the post type.
     */
    public static function register(Plugin $plugin)
    {
        register_taxonomy('slide-page', 'slide',
            array(
                'hierarchical' => true,
                'label' => 'Slider',
                'query_var' => true,
                'rewrite' => true,
                'hierarchical' => true,
                'show_in_nav_menus' => false,
                'show_tagcloud' => false,
                'labels' => array(
                    'name'                       => __('Sliders', $plugin->slug),
                    'singular_name'              => __('Slider', $plugin->slug),
                    'menu_name'                  => __('Add or Edit Sliders', $plugin->slug),
                    'all_items'                  => __('All Sliders', $plugin->slug),
                    'new_item_name'              => __('New Slider Name', $plugin->slug),
                    'add_new_item'               => __('Add Slider', $plugin->slug),
                    'edit_item'                  => __('Edit Slider', $plugin->slug),
                    'update_item'                => __('Update Slider', $plugin->slug),
                    'separate_items_with_commas' => __('Separate sliders with commas', $plugin->slug),
                    'search_items'               => __('Search Sliders', $plugin->slug),
                    'add_or_remove_items'        => __('Add or remove sliders', $plugin->slug),
                    'choose_from_most_used'      => __('Choose from the most used sliders', $plugin->slug),
                    'not_found'                  => __('Not Found', $plugin->slug),
                ),
            )
        );
    }
}
