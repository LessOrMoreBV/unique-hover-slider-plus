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
        register_post_type('uhsp-slide', [
            'labels' => [
                'name' => __('Slides', $plugin->slug),
                'singular_name' => __('Slide', $plugin->slug),
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => false,
        ]);
    }
}
