<?php 

/*
Plugin Name: Attention Quiz
Description: A simple plugin that will register a custom quiz block to the Gutenberg editor.
Version: 1.0.0
Author: Karim Toulba
Author URI: https://karimtoulba.com
Text Domain: quizdomain
Domain Path: /languages
*/

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

class NewAttentionQuiz {

    function __construct() {
        add_action('init', array($this, 'toRegisterBlock')); 
    }

    function toRegisterBlock() {
        wp_register_script( 'myNewPlugin', plugin_dir_url( __FILE__ ) . 'src/index.js', array('wp-blocks', 'wp-element') );
        register_block_type( 'ourplugin/attentionquiz', array(
            'editor_script' => 'myNewPlugin',
            'render_callback' => array($this, 'myHTML')
        ) );
    }

    function myHTML($attributes) {
        return '<p>' . 'The sky is ' . $attributes['skyColor'] . ' and the grass is ' . $attributes['grassColor'] . '</p>' ;
    }

}

$NewAttentionQuiz = new NewAttentionQuiz();

