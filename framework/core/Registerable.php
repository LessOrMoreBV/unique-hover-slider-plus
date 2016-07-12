<?php
// Namespaced to prevent class conflict.
namespace UniqueHoverSliderPlus\Core;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}

require_once('Plugin.php');

interface Registerable
{
    public function set_parent(Plugin $plugin);
    public function register();
}
