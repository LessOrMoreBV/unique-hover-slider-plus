=== Unique Hover Slider Plus ===
Contributors: lessormore
Tags: slider
Requires at least: 4.5.0
Tested up to: 4.5.4
Stable tag: 4.5
License: http://www.gnu.org/licenses/gpl-3.0.html

A cool slider with unique hover functionality that you won't find anywhere else!

== Description ==

The Unique Hover Slider Plus is a slider unlike many other common sliders. It features a unique hover effect (hence the name) to increase user interaction. It does not slide through your slides by itself, but instead highlights the centerpiece. This makes it ideal for a full-width header on your homepage, as it manages to mix the captivating presence of a static header visual with the interaction of a sliding one.

== Installation ==

To install Unique Hover Slider Plus:

1. Upload the plugin files to the `wp-content/plugins/unique-hover-slider-plus` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.

== Frequently Asked Questions ==

= What is the PHP version requirement? =

PHP 5.4 or higher is required to run this plugin, tested up to PHP 7.0.

= How do I create a slider? =

To create a new slider, go to the "Add or Edit Sliders" submenu in the "UHSP Slider" menu. Creating a new slider gives you several options, primarily the default Wordpress taxonomy settings such as "Name", "Slug", "Parent" and "Description". Take note that these aren't actually used by the plugin itself, but you can use them for yourself to mark which slider does what. The slider configuration can be found below these options:

* Title color: Decides the color of the slide main titles. (Largest text)
* Subtitle color: Decides the color of the slide subtitles. (Smaller text)
* Overlay color: Decides the color of the overlay that covers all slide backgrounds.
* Overlay opacity: Decides the opacity of the overlay that covers all slide backgrounds.
* Arrow buttons: Check the box to enable slide navigation with previous / next arrows.

After creating your slider you'll have to add some slides to it. For this plugin, we have a custom post type called "slide" which contains the options to populate the slide:

* Title: The title of the slide. (Largest text)
* Description: The subtitle of the slide. (Smaller text)
* Featured Image: The background image of the slide.
* Foreground Icon: The foreground icon position in the center of the slide.
* Order: The position the slide should have within the slider.
* Sliders: The sliders that this slide should appear in.

= How many slides can I add to a slider? =

**Sliders are limited to 5 slides**; this is required for the stylistic display of the slide titles. Adding any more would clutter the slider too much and overflow the slide titles. If you are looking to support more slides, this slider plugin is simply not the on you are looking for.

= How do I change the styling? =

If you want to change the size of the fonts in the slider, you can change the CSS manually.

The slider titles:
`
.uhsp-slider-wrapper .uhsp-slide-title {
    font-size: 30px;
}
`

The slider subtitles / descriptions:
`
.uhsp-slider-wrapper .uhsp-slide-subtitle {
    font-size: 12px;
}
`

The slider uses the same font as the theme you use, but if you like you can add your own font here.
`
.uhsp-slider-wrapper .uhsp-slide-title,
.uhsp-slider-wrapper .uhsp-slide-subtitle {
    font-family: "Your Font", Helvetica, sans-serif;
}
`

== Screenshots ==

1. An example of the plugin in action.

== Changelog ==

= 1.0 =
* Base plugin functionality.

== Upgrade Notice ==

= 1.0 =
The original version: if you're upgrading to this version, thank you for installing!