<?php
namespace UniqueHoverSliderPlus\Shortcodes;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

require_once(__DIR__ . '/../../core/Shortcode.php');
require_once(__DIR__ . '/../post-types/Slide.php');
use UniqueHoverSliderPlus\Shortcode;
use UniqueHoverSliderPlus\PostTypes\Slide;
use UniqueHoverSliderPlus\PostTypes\SlidePage;

class Slider extends Shortcode
{
    /**
     * The shortcode name.
     * @var string
     */
    public $name = 'uhsp';

    /**
     * Options to map the shortcode to visual composer.
     * @var array
     */
    // public $vc_options = [
    //     'name' => 'Before & After Comparator',
    //     'base' => 'comparator',
    //     'class' => '',
    //     'category' => 'Content',
    //     'params' => [
    //         [
    //             'type' => 'attach_image',
    //             'heading' => 'Before Image',
    //             'param_name' => 'before_image',
    //             'value' => '',
    //             'description' => 'The image that will be visible from the top of the comparator.',
    //         ],
    //         [
    //             'type' => 'textfield',
    //             'heading' => 'Before Image alt text',
    //             'param_name' => 'before_image_alt',
    //             'value' => 'Before image.',
    //             'description' => 'The alt text for the before image.',
    //         ],
    //         [
    //             'type' => 'attach_image',
    //             'heading' => 'After Image',
    //             'param_name' => 'after_image',
    //             'value' => '',
    //             'description' => 'The image that will be visible from the bottom of the comparator.',
    //         ],
    //         [
    //             'type' => 'textfield',
    //             'heading' => 'After Image alt text',
    //             'param_name' => 'after_image_alt',
    //             'value' => 'After image.',
    //             'description' => 'The alt text for the after image.',
    //         ],
    //         [
    //             'type' => 'colorpicker',
    //             'heading' => 'Handle Arrow Color',
    //             'param_name' => 'arrow_color',
    //             'value' => '#000000',
    //             'description' => 'The color of the arrows within the handle.',
    //         ],
    //         [
    //             'type' => 'colorpicker',
    //             'heading' => 'Handle Background Color',
    //             'param_name' => 'arrow_background_color',
    //             'value' => '#FFFFFF',
    //             'description' => 'The handle background color.',
    //         ],
    //         [
    //             'type' => 'dropdown',
    //             'heading' => 'Device',
    //             'param_name' => 'device',
    //             'value' => [
    //                 'Browser' => 'browser',
    //                 'Tablet' => 'tablet',
    //                 'Mobile' => 'mobile',
    //                 'None' => 'none',
    //             ],
    //             'std' => 'Browser',
    //             'description' => 'Pick a device to display around the comparator.',
    //         ],

    //     ]
    // ];

    /**
     * Renders the comparator as HTML.
     * @return string
     */
    public function render($attributes, $content)
    {
        // ID will be available in the list of attributes.
        extract($attributes);

        // Retrieve the slides belonging to the slide_page.
        $slides = Slide::query_slides_of_slider($id);

        // Retrieve the extra meta.
        $meta = SlidePage::get_formatted_option($id);

        return $this->render_template('slider.php', ['slides' => $slides, 'meta' => $meta]);
    }
}