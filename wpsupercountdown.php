<?php

/*
Plugin Name: WPSuperCountdown
Plugin URI: http://www.superlevel.de/
Description: Use Countdowns to make your blog posts more interesting.
Author: Matthias Kunze
Version: 0.8.15
Author URI: http://www.superlevel.de
*/


class WPSuperCountdown {

        var $counterLayout = '{hnn}:{mnn}:{snn}';
        var $counterFormat = 'hms';

        function __construct() {
                $base = basename(dirname(__FILE__));

                $this->pluginDir = get_option('siteurl') . '/wp-content/plugins/' . $base;

                add_filter('the_content', array(&$this, 'parsePlaceholder'));
                add_filter('wp_head', array(&$this, 'addStyles'));
                add_action('wp_print_scripts', array(&$this, 'addScripts'));

                load_plugin_textdomain('wpsupercountdown', 'wp-content/plugins/' . $base, $base);
        }

        function WPSuperCountdown() {
                $this->__construct();
        }

        function addStyles() {
                // countdown style
                echo '<link rel="stylesheet" href="' . $this->pluginDir . '/styles.css" type="text/css" media="screen"  />';
                // for cookie path setting
                echo "<script type=\"text/javascript\">/* <![CDATA[ */ var wpHome = '" . SITECOOKIEPATH . "'; /* ]]> */</script>";
        }

        function addScripts() {
                wp_enqueue_script('wpsc', $this->pluginDir . '/wpsc.js', array('jquery'), '0.8.15');
        }

        /**
         * Replace countdown code
         * @param str $content
         */
        function parsePlaceholder($content) {
                global $post;
                $postID = $post->ID;

                // don't waste time
                if (!stripos($content, 'countdown')) {
                        return $content;
                }

                // little hack for more-tag
                $content = str_replace('>[countdown', '> [countdown', $content);

                // no display in feed
                if (is_feed()) {
                        $replace = '<em>' . __('Here you find special content that cannot be shown in the feedreader.', 'wpsupercountdown') . '</em>';
                        $content = preg_replace('/\[countdown\:([^\]]+)\]/is', $replace, $content);
                        return $content;
                }

                if (preg_match_all('/[<p>]*\[countdown\:([^\]]+)\][<\/p>]*/is', $content, $matches)) {
                        foreach ($matches[1] as $index => $match) {
		                      $id = 'countdown-' . $postID . '-' . $index;
		                      list($until, $rest) = explode('|', trim($match), 2);
		                      // cookie not set
		                      if (!isset($_COOKIE[$id])) {
		                            $until = trim($until);
        		                    $replace = '<span class="wpscd-layer" id="' . $id . '"></span>'
                                             . '<script type="text/javascript">/* <![CDATA[ */ jQuery(\'#' . $id . '\').countdown({format: \'' . $this->counterFormat . '\', until: \'' . $until . '\', layout: \'' . $this->counterLayout . '\''
                                             // load content if $rest is given
                                             . (isset($rest) ? ', onExpiry: function () { loadHiddenText(' . $postID . ', ' . $index . '); }' : '')
                                             . '});/* ]]> */ </script>';
        		                    $content = str_replace($matches[0][$index], $replace, $content);
		                      }
		                      // cookie set > show content immediately
		                      else {
                                    $content = str_replace($matches[0][$index], wpautop(wptexturize($rest)), $content);
                              }
		                }
                }

                return $content;
        }
}

// ajax call
if (isset($_GET['load'])) {
        // get wp-root dir
        $root = dirname(dirname(dirname(dirname(__FILE__))));
        // include wp-core
        if (file_exists($root . '/wp-load.php')) {
        		require_once($root . '/wp-load.php'); // wp 2.6
        }
        else {
        		require_once($root . '/wp-config.php'); // before 2.6
        }

        $id = intval($_GET['load']);
        $n = intval($_GET['n']);
        // is valid post-id
        if ($post = get_post($id)) {
                $content = $post->post_content;
                // little hack for more-tag
                $content = str_replace('>[countdown', '> [countdown', $content);
                if (preg_match_all('/[<p>]*\[countdown\:([^\]]+)\][<\/p>]*/is', $content, $matches)) {
                        foreach ($matches[1] as $index => $match) {
                                // get specified countdown
                                if ($n == $index) {
                                        list($until, $rest) = explode('|', trim($match), 2);
                                        $rest = wpautop(wptexturize($rest));
                                        break;
                                }
		                }
                }
                // response
                die(json_encode($rest));
        }
        die('');
}

$WPSuperCountdown = new WPSuperCountdown();