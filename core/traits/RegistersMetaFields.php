<?php
namespace UniqueHoverSliderPlus\Traits;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

trait RegistersMetaFields {
    /**
     * ... Generates nonced and field names.
     * @return void
     */
    public function generate_nonces_and_field_names()
    {
        foreach ($this->meta_fields as $field => $props)
        {
            // Decide if we use class name or slug.
            $id = ( property_exists($this, 'type') && $this->type === 'post_type' ? $this->name : $this->slug );

            // We also want to add some generated properties to the property,
            // so that we can access them later when saving the post.
            $field_name = $id . '_' . $field;
            $nonce = $id . '_' . $field;
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

            // Post type can either be passed through as property, otherwise
            // we'll use the current name property.
            $post_type = ( array_key_exists('post_type', $props) ? $props['post_type'] : $this->name );

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
                $post_type,
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

            // If a post type is set in the properties, we want to compare
            // that one to the given post type. If they don't match, we skip.
            if (array_key_exists('post_type', $props)) {
                if ($props['post_type'] !== $post->post_type) {
                    continue;
                }
            // If the post type isn't set in properties however, we'll asume
            // we're using the current name property as post type.
            } else if ($post->post_type !== $this->name) {
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
        }
    }
}