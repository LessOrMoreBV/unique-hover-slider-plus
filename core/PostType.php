<?php
namespace UniqueHoverSliderPlus;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

// Require the parent theme class for type checks.
require_once('Registerable.php');
use UniqueHoverSliderPlus\Registerable;

abstract class PostType extends Registerable {
    /**
     * The type of registerable; either post_type or taxonomy.
     * @var string
     */
    public $type = 'post_type';

    /**
     * Options that should be resolved as callbacks.
     * @var array
     */
    protected $callback_opts = [
        'register_meta_box_cb',
    ];

    /**
     * Options that should be resolved as assets.
     * @var array
     */
    protected $asset_opts = [
        'menu_icon',
    ];

    /**
     * Defualt hooks.
     * @var array
     */
    protected $_hooks = [
        ['save_post', 'save_meta', 1, 2],
    ];

    /**
     * Meta fields to register.
     * @var array
     */
    protected $meta_fields = [];

    /**
     * We override wrap_callback_opts to bind our own 'register_meta_box_cb'
     * if the user added any meta fields.
     * @return void
     */
    public function wrap_callback_opts()
    {
        if (count($this->meta_fields) > 0) {
            // Before we register the meta fields, we want to generate a
            // nonce and proper field name for all of the fields.
            $this->generate_nonces_and_field_names();

            // If any fields are set we override the register_meta_box_cb
            // option.
            $this->opts['register_meta_box_cb'] = 'register_meta_fields';
        }

        parent::wrap_callback_opts();
    }

    /**
     * ... Generates nonced and field names.
     * @return void
     */
    public function generate_nonces_and_field_names()
    {
        foreach ($this->meta_fields as $field => $props)
        {
            // We also want to add some generated properties to the property,
            // so that we can access them later when saving the post.
            $field_name = $this->name . '_' . $field;
            $nonce = $this->name . '_' . $field;
            $nonce_field_name = $field_name . '_nonce';

            $this->meta_fields[$field]['meta_field_name'] = $field_name;
            $this->meta_fields[$field]['nonce'] = $nonce;
            $this->meta_fields[$field]['nonce_field_name'] = $nonce_field_name;
        }
    }

    /**
     * Registers meta fields, big whoop yo.
     * @return void
     */
    public function register_meta_fields()
    {
        // Loop through all the given meta fields to register them.
        foreach ($this->meta_fields as $field => $props) {
            // Try to generate all required properties for adding
            // a meta box.
            $id = $field . '_metabox';
            $title = __($props['title'], $this->translate_key);

            // The callback is always our render meta field method.
            $callback = [$this, 'render_meta_field'];

            // The screen will always be the current custom post type.
            $screen = $this->name;

            // Context and priority default to their WP defaults.
            $context = ( isset($props['context']) ? $props['context'] : 'advanced' );
            $priority = ( isset($props['priority']) ? $props['priority'] : 'default' );

            // Within our callback we need to know which template we should render,
            // since we call the same method for every meta field.
            $callback_args = $this->meta_fields[$field];
            $callback_args['field'] = $field;

            // Register the meta box in WP.
            add_meta_box(
                $id,
                $title,
                $callback,
                $screen,
                $context,
                $priority,
                $callback_args
            );
        }
    }

    /**
     * Renders the meta field from the given template.
     * @param  WP_Post $post
     * @param  array   $metabox
     * @return void
     */
    public function render_meta_field($post, $metabox)
    {
        $post_meta = self::retrieve_meta($post->ID);

        // Render the template through the plugin core.
        echo $this->render_template(
            $metabox['args']['template'],
            [
                'meta' => ( array_key_exists($metabox['args']['field'], $post_meta) ? $post_meta[$metabox['args']['field']] : '' ),
                'meta_field_name' => $metabox['args']['meta_field_name'],
                'nonce' => wp_create_nonce($metabox['args']['nonce']),
                'nonce_field_name' => $metabox['args']['nonce_field_name'],
            ]
        );
    }

    /**
     * Triggers when the post is saved.
     * @param  integer $post_id
     * @param  WP_Post $post
     * @return void
     */
    public function save_meta($post_id, $post)
    {
        // We only want to listen to events for our current post_type.
        if ($post->post_type !== $this->name) {
            return;
        }

        // Make sure the current user is allowed to edit the post.
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        // If we're in the correct post type, we'll loop through all
        // registered meta fields.
        foreach ($this->meta_fields as $field => $props) {
            // Verify the nonce we set as a hidden input.
            if (
                isset($_POST[$props['nonce_field_name']]) &&
                !wp_verify_nonce($_POST[$props['nonce_field_name']], $props['nonce'])
            ) {
                continue;
            }

            // Fetch the meta property from the global $_POST.
            $value = '';
            if (isset($_POST[$props['meta_field_name']])) {
                $value = $_POST[$props['meta_field_name']];
            }

            // Update the meta value.
            if (get_post_meta($post->ID, $field, false)) {
                update_post_meta($post->ID, $field, $value);
            } else {
                add_post_meta($post->ID, $field, $value);
            }

            // Delete the meta field if the value is empty.
            // #TODO: Copied from other script, is this needed?
            // if (!$value) {
            //     delete_post_meta($post->ID, $field);
            // }
        }
    }
}