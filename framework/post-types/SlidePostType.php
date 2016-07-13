<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\PostTypes;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

require_once(__DIR__ . '/../core/Plugin.php');
require_once(__DIR__ . '/../core/Registerable.php');
require_once(__DIR__ . '/../taxonomies/SlidePageTaxonomy.php');
require_once(__DIR__ . '/../vendor/MultiPostThumbnails/MultiPostThumbnails.php');

// Include base plugin class.
use UniqueHoverSliderPlus\Core\Plugin;
use UniqueHoverSliderPlus\Core\Registerable;
use UniqueHoverSliderPlus\Taxonomies\SlidePageTaxonomy;
use MultiPostThumbnails;
use WP_Query;

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
                'all_items'          => __('Edit Slides', $this->translate_key),
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
            'hierarchical' => false,
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
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        ]);

        new MultiPostThumbnails([
            'label' => 'Foreground Icon',
            'id' => 'foreground-icon',
            'post_type' => self::POST_TYPE
        ]);

        add_filter('manage_edit-' . self::POST_TYPE . '_columns' , [$this, 'table_heading']);
        add_filter('manage_' . self::POST_TYPE . '_posts_custom_column', [$this, 'table_column']);
        add_filter('manage_edit-' . self::POST_TYPE . '_sortable_columns',[$this, 'table_sortable']);
    }

    /**
     * Adds the order of the slides into the overview table.
     * @param  array $headings
     * @return array
     */
    public function table_heading($headings)
    {
        $headings[self::POST_TYPE . '_order'] = __('Order', $this->translate_key);
        return $headings;
    }

    /**
     * Inserts the order into the post type table columns.
     * @param  string  $col_name
     * @return integer
     */
    public function table_column($col_name)
    {
        global $post;

        if ($col_name === self::POST_TYPE . '_order') {
            echo $post->menu_order;
        }
    }

    /**
     * Allow the table to be sortable by menu_order.
     * @param  array $columns
     * @return array
     */
    public function table_sortable($columns)
    {
        $columns[self::POST_TYPE . '_order'] = 'menu_order';
        return $columns;
    }

    /**
     * Queries sliders belonging to a given slider.
     * @param  integer  $id
     * @return WP_Query
     */
    public static function query_slides_of_slider($id)
    {
        $args = [
            'posts_per_page' => 5,
            'no_found_rows' => true,
            'post_type' => 'slide',
            'orderby' => 'menu_order',
            'tax_query' => [
                [
                    'taxonomy' => SlidePageTaxonomy::TAXONOMY,
                    'field' => 'id',
                    'terms' => $id,
                ]
            ]
        ];
        return new WP_Query($args);
    }
}
