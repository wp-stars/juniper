<?php
namespace W3TC;

global $wp_version;

if (version_compare($wp_version, '6.1-beta1', '>=') ) {
    include_once ABSPATH . WPINC . '/class-wpdb.php';
} else {
    include_once ABSPATH . WPINC . '/wp-db.php';
}

class DbCache_WpdbBase extends \wpdb
{
}
