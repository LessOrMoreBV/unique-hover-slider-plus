<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\Taxonomies;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

require_once(__DIR__ . '/../core/Plugin.php');
require_once(__DIR__ . '/../core/Registerable.php');

// Include base plugin class.
use UniqueHoverSliderPlus\Core\Plugin;
use UniqueHoverSliderPlus\Core\Registerable;

class SlidePageTaxonomy implements Registerable
{
    /**
     * The parent plugin that governs this taxonomy.
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
     * The taxonomy key.
     */
    const TAXONOMY = 'slide_page';

    /**
     * Registers the taxonomy.
     * @return void
     */
    public function register()
    {
        register_taxonomy(self::TAXONOMY, 'slide',
            array(
                'hierarchical' => true,
                'label' => 'Slider',
                'query_var' => true,
                'rewrite' => true,
                'hierarchical' => true,
                'show_in_nav_menus' => false,
                'show_tagcloud' => false,
                'labels' => array(
                    'name'                       => __('Sliders', $this->plugin->slug),
                    'singular_name'              => __('Slider', $this->plugin->slug),
                    'menu_name'                  => __('Add or Edit Sliders', $this->plugin->slug),
                    'all_items'                  => __('All Sliders', $this->plugin->slug),
                    'new_item_name'              => __('New Slider Name', $this->plugin->slug),
                    'add_new_item'               => __('Add Slider', $this->plugin->slug),
                    'edit_item'                  => __('Edit Slider', $this->plugin->slug),
                    'update_item'                => __('Update Slider', $this->plugin->slug),
                    'separate_items_with_commas' => __('Separate sliders with commas', $this->plugin->slug),
                    'search_items'               => __('Search Sliders', $this->plugin->slug),
                    'add_or_remove_items'        => __('Add or remove sliders', $this->plugin->slug),
                    'choose_from_most_used'      => __('Choose from the most used sliders', $this->plugin->slug),
                    'not_found'                  => __('Not Found', $this->plugin->slug),
                ),
            )
        );
    }
}
