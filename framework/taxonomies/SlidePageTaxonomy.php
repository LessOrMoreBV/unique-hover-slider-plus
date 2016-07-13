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
     * @var string
     */
    const TAXONOMY = 'slide_page';

    /**
     * The default overlay opacity.
     * @var string
     */
    const DEFAULT_OPACITY = '75%';

    /**
     * The default overlay color.
     * @var string
     */
    const DEFAULT_OVERLAY_COLOR = '#334D5C';

    /**
     * The default overlay color.
     * @var string
     */
    const DEFAULT_TITLE_COLOR = '#F5C949';

    /**
     * The default overlay color.
     * @var string
     */
    const DEFAULT_SUBTITLE_COLOR = '#FFFFFF';

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
     * Parses the input to make proper values all the time.
     * @param  array $input
     * @return array
     */
    public function parse_input($input)
    {
        // The arrow_buttons property should be a tinyint property.
        $arrow_buttons = (int) array_key_exists('arrow_buttons', $input);

        // Color has to be a valid hex color code.
        $overlay_color = strtoupper($input['overlay_color']);
        if (!preg_match("/^\#?[A-F0-9]{6}$/", $overlay_color)) $overlay_color = static::DEFAULT_OVERLAY_COLOR;
        if (substr($overlay_color, 0, 1) !== '#') $overlay_color = '#' . $overlay_color;

        $title_color = strtoupper($input['title_color']);
        if (!preg_match("/^\#?[A-F0-9]{6}$/", $title_color)) $title_color = static::DEFAULT_TITLE_COLOR;
        if (substr($title_color, 0, 1) !== '#') $title_color = '#' . $title_color;

        $subtitle_color = strtoupper($input['subtitle_color']);
        if (!preg_match("/^\#?[A-F0-9]{6}$/", $subtitle_color)) $subtitle_color = static::DEFAULT_SUBTITLE_COLOR;
        if (substr($subtitle_color, 0, 1) !== '#') $subtitle_color = '#' . $subtitle_color;

        // Opacity has to be between 0 - 100 with a percentage sign.
        $overlay_opacity = ( strlen($input['overlay_opacity']) > 0 ? $input['overlay_opacity'] : static::DEFAULT_OPACITY );
        $overlay_opacity = (int) str_replace('%', '', $overlay_opacity);
        if ($overlay_opacity < 0) $overlay_opacity = 0;
        if ($overlay_opacity > 100) $overlay_opacity = 100;
        $overlay_opacity = (string) $overlay_opacity . '%';

        return [
            'title_color' => $title_color,
            'subtitle_color' => $subtitle_color,
            'arrow_buttons' => $arrow_buttons,
            'overlay_color' => $overlay_color,
            'overlay_opacity' => $overlay_opacity,
        ];
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

        echo $this->plugin->render_template('slide_page_edit_form_fields.php', ['meta' => $meta, 'id' => $id]);
    }

    /**
     * Stores custom meta data for the given ID.
     * @param  integer $id
     * @return void
     */
    public function save_custom_meta($id)
    {
        if (isset($_POST[self::TAXONOMY . '_meta'])) {
            // Parse received input.
            $input = $this->parse_input($_POST[self::TAXONOMY . '_meta']);

            $meta = static::get_option($id);

            foreach ($input as $key => $value) {
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
        $headings = $cb + [self::TAXONOMY . '_shortcode' => __('Shortcode', $this->translate_key)] + $headings;
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
        if ($col_name === self::TAXONOMY . '_shortcode') {
            return "<pre style=\"display: inline-block; background-color: white; margin: 1px 5px\">[uhsp id=\"{$id}\"]</pre>";
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
