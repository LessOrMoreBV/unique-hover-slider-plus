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
        add_action('create_' . self::TAXONOMY, [$this, 'save_custom_meta']);
        add_action('edited_' . self::TAXONOMY, [$this, 'save_custom_meta']);
        add_filter('manage_edit-' . self::TAXONOMY . '_columns' , [$this, 'table_heading']);
        add_filter('manage_' . self::TAXONOMY . '_custom_column', [$this, 'table_column'], 5, 3);
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
    public function edit_form_fields($slide_page)
    {
        $id = $slide_page->term_id;
        $meta = static::get_option($id);

        echo $this->plugin->render_template('slide_page_edit_form_fields.php', ['meta' => $meta]);
    }

    /**
     * Stores custom meta data for the given ID.
     * @param  integer $id
     * @return void
     */
    public function save_custom_meta($id)
    {
        if (isset($_POST[self::TAXONOMY . '_meta'])) {
            // Manually set the arrow option to a boolean value.
            $_POST[self::TAXONOMY . '_meta']['arrow_buttons'] = (int) array_key_exists('arrow_buttons', $_POST[self::TAXONOMY . '_meta']);

            $meta = static::get_option($id);

            foreach ($_POST[self::TAXONOMY . '_meta'] as $key => $value) {
                $meta[$key] = $value;
            }

            static::update_option($id, $meta);
        }
    }

    /**
     * Inserts the slider ID into the taxonomy table heading.
     * @param  array $headings
     * @return array
     */
    public function table_heading($headings)
    {
        $cb = array_splice($headings, 0, 1);
        $headings = $cb + [self::TAXONOMY . '_id' => __('ID', $this->translate_key)] + $headings;
        return $headings;
    }

    /**
     * Inserts the slider ID into the taxonomy table columns.
     * @param  mixed   $value
     * @param  string  $col_name
     * @param  integer $id
     * @return integer
     */
    public function table_column($value, $col_name, $id)
    {
        if ($col_name === self::TAXONOMY . '_id') {
            return $id;
        }
    }

    /**
     * Updates the meta belonging to the given slider id.
     * @param  integer $id
     * @param  array   $meta
     * @return void
     */
    public static function update_option($id, $meta)
    {
        $opt = 'taxonomy_' . self::TAXONOMY . '_' . $id;
        update_option($opt, $meta);
    }

    /**
     * Retrieves the option belonging to the given slider id.
     * @param  integer $id
     * @return array
     */
    public static function get_option($id)
    {
        $opt = 'taxonomy_' . self::TAXONOMY . '_' . $id;
        return get_option($opt);
    }

    /**
     * Formats the options array to be used in a template.
     * @param  integer $id
     * @return array
     */
    public static function get_formatted_option($id)
    {
        $meta = static::get_option($id);

        // Replace properties to be used within HTML / CSS.
        $meta['overlay_opacity'] = (int) str_replace('%', '', $meta['overlay_opacity']) / 100;

        return $meta;
    }
}
