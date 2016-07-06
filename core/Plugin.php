<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

if (!class_exists('Plugin')) {
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
         * The d efault directories to be extended by the
         * directories property below it.
         * @var array
         */
        protected $_directories = [
            'plugin' => '/',
            'assets' => '/assets',
            'images' => '/images',
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
         * Merges the URI and directory listings.
         */
        public function __construct()
        {
            // Combine _directories and directories to create a single
            // array of constants. Same goes for URI's.
            $this->directories = array_merge($this->_directories, $this->directories);
            $this->uris = array_merge($this->_uris, $this->uris);

            // Define static actions that should always happen.
            $this->add_action('wp_enqueue_scripts', 'assets');

            // Call a boot method to indicate that we're ready.
            $this->boot();
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
            if (!version) {
                $version = $this->version;
            }

            wp_enqueue_script(
                "{$this->slug}-{$handle}",
                $this->get_uri('assets') . "/{$file}",
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
            if (!version) {
                $version = $this->version;
            }

            wp_enqueue_style(
                "{$this->slug}-{$handle}",
                $this->get_uri('assets') . "/{$file}",
                $deps,
                $version,
                $media
            );
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
}
