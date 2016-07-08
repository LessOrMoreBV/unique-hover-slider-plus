<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\Core;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

abstract class Plugin
{
    /**
     * Debug mode toggle.
     * @var boolean
     */
    protected $debug = false;

    /**
     * The plugin name.
     * @var string
     */
    protected $name = 'My Plugin';

    /**
     * A shortened name for menu displays.
     * @var string
     */
    protected $short_name = 'MP';

    /**
     * The plugin slug.
     * @var string
     */
    protected $slug = 'my-plugin';

    /**
     * The theme version.
     * @var string
     */
    protected $version = '0.0.0';

    /**
     * The user capability required to edit this plugin.
     * @var string
     */
    protected $capability = 'manage_options';

    /**
     * The d efault directories to be extended by the
     * directories property below it.
     * @var array
     */
    protected $_directories = [
        'plugin' => '/',
        'assets' => '/assets',
        'images' => '/images',
        'templates' => '/templates',
    ];

    /**
     * A list of directories.
     * @var array
     */
    protected $directories = [];

    /**
     * The default URI's, can be extended by the uris
     * property below it.
     * @var array
     */
    protected $_uris = [
        'plugin' => '/',
        'assets' => '/assets',
        'images' => '/images',
    ];

    /**
     * A list of URI's.
     * @var array
     */
    protected $uris = [];

    /**
     * A list of hooks and their method registration.
     * @var array
     */
    protected $hooks = [];

    /**
     * Merges the URI and directory listings.
     */
    public function __construct()
    {
        $this->init_properties();
        $this->register_options();
        $this->register_actions();

        // Call a boot method to indicate that we're ready.
        $this->boot();
    }

    /**
     * Initializes directory and uri props.
     * @return void
     */
    public function init_properties()
    {
        // Combine _directories and directories to create a single
        // array of constants. Same goes for URI's.
        $this->directories = array_merge($this->_directories, $this->directories);
        $this->uris = array_merge($this->_uris, $this->uris);
    }

    /**
     * Registers options used by the plugin.
     * @return void
     */
    public function register_options()
    {
        foreach ($this->options as $key => $value) {
            add_option(str_replace('-', '_', $this->prefix($key)), $value);
        }
    }

    /**
     * Registers all the hooks.
     * @return void
     */
    public function register_actions()
    {
        // Define static actions that should always happen.
        $this->add_action('admin_menu', 'menus');

        // Attach all hooks registered on the property as well.
        foreach ($this->hooks as $hook) {
            call_user_func_array([$this, 'add_action'], $hook);
        }
    }

    /**
     * Checks if the user can edit this plugin.
     * @return boolean
     */
    public function user_can_modify()
    {
        return current_user_can($this->capability);
    }

    /**
     * Kills the page if the user doesn't have enough permissions.
     * @param  string $permission
     * @return void
     */
    public function check_user_permission($permission)
    {
        if (!call_user_func([$this, 'user_can_' . $permission])) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    }

    /**
     * Prepends the plugin slug to the given string.
     * @param  string $str
     * @return string
     */
    public function prefix($str)
    {
        return "{$this->slug}-{$str}";
    }

    /**
     * Retrieves the given directory as a full path.
     * @param  string $dir
     * @return string
     */
    public function get_dir($dir)
    {
        // Retrieve the directory from the dirlist if it exists.
        if (array_key_exists($dir, $this->directories)) {
            $dir = $this->directories[$dir];
        }

        // Prepend full dir path.
        return $this->full_path($this->trim_prepended_slash($dir));
    }

    /**
     * Retrieves the full path to the given directory.
     * @param  string $dir
     * @return string
     */
    public function full_path($dir)
    {
        return ABSPATH . "wp-content/plugins/{$this->slug}/{$dir}";
    }

    /**
     * Retrieves the full path to a given template.
     * @param  string $file
     * @return string
     */
    public function template($file)
    {
        $file = $this->trim_prepended_slash($file);
        return $this->get_dir('templates') . "/{$file}";
    }

    /**
     * Renders the given template using ob_get_contents.
     * @param  string $file
     * @return string
     */
    public function render_template($file)
    {
        // Resolve to full file path.
        $file = $this->template($file);

        // #TODO: Make better, screw ob.
        ob_start();
        define('PLUGIN_URI', $this->get_uri('plugin'));
        include $file;
        $template = ob_get_contents(); // get contents of buffer
        ob_end_clean();

        return $template;
    }

    /**
     * Retrieves the given directory as a full URI.
     * @param  string $uri
     * @return string
     */
    public function get_uri($uri)
    {
        // Retrieve the uri from the urilist if it exists.
        if (array_key_exists($uri, $this->uris)) {
            $uri = $this->uris[$uri];
        }

        // Prepend full uri path.
        return $this->full_uri($this->trim_prepended_slash($uri));
    }

    /**
     * Retrieves the full uri to the given directory.
     * @param  string $uri
     * @return string
     */
    public function full_uri($uri)
    {
        return plugins_url("{$this->slug}/{$uri}");
    }

    /**
     * Returns the full uri to the given asset file.
     * @param  string $file
     * @return string
     */
    public function asset($file)
    {
        $file = $this->trim_prepended_slash($file);
        return $this->get_uri('assets') . "/{$file}";
    }

    /**
     * Wraps the WP add_action function by always calling it with
     * an array as Callable.
     * @param string $event
     * @param string $method
     * @return void
     */
    protected function add_action($event, $method)
    {
        add_action($event, [&$this, $method]);
    }

    /**
     * Wraps the WP add_shortcode function by always calling it with
     * an array as Callable.
     * @param string $code
     * @param string $method
     * @return void
     */
    protected function add_shortcode($code, $method)
    {
        add_shortcode($code, [&$this, $method]);
    }

    /**
     * Prepends a slash to a string if it doesn't contain one yet.
     * @param  string $str
     * @return string
     */
    public function trim_prepended_slash($str)
    {
        if (strpos('/', $str) === 0) {
            return substr($str, 1);
        }

        return $str;
    }

    /**
     * Wrapper for isset $_POST.
     * @param  string  $key
     * @return boolean
     */
    public function has_input($key)
    {
        return (isset($_POST[$key]));
    }

    /**
     * Wrapper for $_POST[$key].
     * @param  string $key
     * @return mixed
     */
    public function get_input($key)
    {
        return $_POST[$key];
    }

    /**
     * Enqueue script wrapper that automatically prepends slug to handle
     * and generates a proper uri for the given file.
     * @param  string  $handle
     * @param  string  $file
     * @param  array   $deps
     * @param  string  $version
     * @param  boolean $in_footer
     * @return void
     */
    public function enqueue_script($handle, $file, $deps = [], $version = null, $in_footer = false)
    {
        if (!$version) {
            $version = $this->version;
        }

        wp_enqueue_script(
            "{$this->slug}-{$handle}",
            $this->asset($file),
            $deps,
            $version,
            $in_footer
        );
    }

    /**
     * Enqueue style wrapper that automatically prepends slug to handle
     * and generates a proper uri for the given file.
     * @param  string $handle
     * @param  string $file
     * @param  array  $deps
     * @param  string $version
     * @param  string $media
     * @return void
     */
    public function enqueue_style($handle, $file, $deps = [], $version = null, $media = 'all')
    {
        if (!$version) {
            $version = $this->version;
        }

        wp_enqueue_style(
            "{$this->slug}-{$handle}",
            $this->asset($file),
            $deps,
            $version,
            $media
        );
    }

    /**
     * Renders pre-defined menus. Automatically called on 'admin_menu' hook.
     * @hook   admin_menu
     * @return void
     */
    public function menus()
    {
        // Try to add top level domains.
        $this->menu_pages = array_map(function ($menu_page) {
            return [
                $this->name,
                $this->short_name,
                $this->capability,
                $this->slug,
                [$this, $menu_page[0]],
                $this->asset($menu_page[1])
            ];
        }, $this->menu_pages);
        foreach ($this->menu_pages as $menu_page) {
            call_user_func_array('add_menu_page', $menu_page);
        }
    }

    /**
     * For the user to overwrite.
     * @return void
     */
    public function assets()
    {
        // ...
    }

    /**
     * For the user to overwrite.
     * @return void
     */
    public function boot()
    {
        // ...
    }
}
