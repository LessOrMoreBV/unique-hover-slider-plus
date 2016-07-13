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
     * The taxonomy key.
     */
    const TAXONOMY = 'slide_page';

    /**
     * Registers the taxonomy.
     * @return void
     */
    public function register()
    {
        // The parent is required to register the post type.
        if (!$this->plugin) {
            throw new Exception('Could not register taxonomy: ' . self::TAXONOMY . ' - Could not find parent plugin to register to.');
        }

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
                    'name'                       => __('Sliders', $this->translate_key),
                    'singular_name'              => __('Slider', $this->translate_key),
                    'menu_name'                  => __('Add or Edit Sliders', $this->translate_key),
                    'all_items'                  => __('All Sliders', $this->translate_key),
                    'new_item_name'              => __('New Slider Name', $this->translate_key),
                    'add_new_item'               => __('Add Slider', $this->translate_key),
                    'edit_item'                  => __('Edit Slider', $this->translate_key),
                    'update_item'                => __('Update Slider', $this->translate_key),
                    'separate_items_with_commas' => __('Separate sliders with commas', $this->translate_key),
                    'search_items'               => __('Search Sliders', $this->translate_key),
                    'add_or_remove_items'        => __('Add or remove sliders', $this->translate_key),
                    'choose_from_most_used'      => __('Choose from the most used sliders', $this->translate_key),
                    'not_found'                  => __('Not Found', $this->translate_key),
                ),
            )
        );

        add_action(self::TAXONOMY . '_add_form_fields', [$this, 'add_form_fields']);
        add_action(self::TAXONOMY . '_edit_form_fields', [$this, 'edit_form_fields']);
    }

    /**
     * Adds extra form fields to the create new taxonomy page.
     * @return void
     */
    public function add_form_fields()
    {
        echo $this->plugin->render_template('slide_page_add_form_fields.php');
    }

    /**
     * Adds extra form fields to the edit existing taxonomy page.
     * @return void
     */
    public function edit_form_fields()
    {
        echo $this->plugin->render_template('slide_page_edit_form_fields.php');
    }
}
