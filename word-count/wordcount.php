<?php

/*
Plugin Name: Word Count
Description: A simple plugin to print word count and other cool features in the blog post.
Version: 1.0.0
Author: Karim Toulba
Author URI: https://karimtoulba.com
Text Domain: wcpdomain
Domain Path: /languages
*/

class TheWordCountPlugin {

    // Construct
    function __construct() {
        add_action('admin_menu', array($this, 'toAdminMenu')); // Admin Menu
        add_action('admin_init', array($this, 'toSettings')); // Settings Page
        //add_action('init', 'toLanguages'); // Languages Translation
        add_filter('the_content', array($this, 'toBlogPost')); // Print in Frontend
    }

    // Admin Menu
    function toAdminMenu() {
        add_options_page( esc_html__('Word Count Settings', 'wcpdomain'), esc_html__('Word Count', 'wcpdomain'), 'manage_options', 'wcp-settings', array($this, 'toSettingsPage') );
    }
    // toSettingsPage Settings Page
    function toSettingsPage() { ?>
        <div class="wrap">
            <h3><?php echo esc_html__('Word Count Settings', 'wcpdomain'); ?></h3>
            <form action="options.php" method="POST">
                <?php
                settings_fields( 'wcp_group' );
                do_settings_sections( 'wcp-settings' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    // toSettings Register Settings
    function toSettings() {
        add_settings_section( 'wcp_default_section', null, null, 'wcp-settings' );

        // Display Location
        add_settings_field( 'wcp_location', esc_html__('Display Location', 'wcpdomain'), array($this, 'LocationHTML'), 'wcp-settings', 'wcp_default_section' );
        register_setting( 'wcp_group', 'wcp_location', array( 'sanitize_callback' => array($this, 'sanitizeHTML'), 'default' => '0') );

        // Headline Text
        add_settings_field( 'wcp_headline', esc_html__('Headline Text', 'wcpdomain'), array($this, 'headlineHTML'), 'wcp-settings', 'wcp_default_section' );
        register_setting( 'wcp_group', 'wcp_headline', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => esc_html__('Post Statistics', 'wcpdomain') ) );
        
        //WordCount
        add_settings_field( 'wcp_wordcount', esc_html__('Word Count', 'wcpdomain'), array($this, 'wordcountHTML'), 'wcp-settings', 'wcp_default_section' );
        register_setting( 'wcp_group', 'wcp_wordcount', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '0' ) );

        // Characters Count
        add_settings_field( 'wcp_charcount', esc_html__('Character Count', 'wcpdomain'), array($this, 'charcountHTML'), 'wcp-settings', 'wcp_default_section' );
        register_setting( 'wcp_group', 'wcp_charcount', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '0' ) );

        // Read Time
        add_settings_field( 'wcp_readtime', esc_html__('Read Time', 'wcpdomain'), array($this, 'readtimeHTML'), 'wcp-settings', 'wcp_default_section' );
        register_setting( 'wcp_group', 'wcp_readtime', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '0' ) );
    }
    // Read Time
    function readtimeHTML() { ?>
        <input type="checkbox" name="wcp_readtime" value="1" <?php checked( get_option('wcp_readtime') ); ?> />
    <?php }
    // Characters Count
    function charcountHTML() { ?>
        <input type="checkbox" name="wcp_charcount" value="1" <?php checked( get_option('wcp_charcount') ); ?> />
    <?php }
    // Word Count
    function wordcountHTML() { ?>
        <input type="checkbox" name="wcp_wordcount" value="1" <?php checked( get_option('wcp_wordcount') ); ?> />
    <?php }
    //headlineHTML
    function headlineHTML() { ?>
        <input type="text" name="wcp_headline" value="<?php echo get_option('wcp_headline'); ?>">
    <?php }
    //LocationHTML
    function LocationHTML() { ?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option('wcp_location') ); ?> ><?php echo esc_html__('Beginning of Post', 'wcpdomain'); ?></option>
            <option value="1" <?php selected( get_option('wcp_location') ); ?> ><?php echo esc_html__('End of Post', 'wcpdomain'); ?></option>
        </select>
    <?php }
    //sanitizeHTML
    function sanitizeHTML($input) {
        if ($input != '0' AND $input != '1') {
            add_settings_error( 'wcp_location', 'wcp_location_error', esc_html__('Display Location should either be Beginning or End of the post.', 'wcpdomain') );
            return ( get_option('wcp_location') );
        }
        return $input;
    }

    //toBlogPost
    function toBlogPost($content) {
        $html = '<h1>' . get_option( 'wcp_headline' ) . '</h1><br />';

        // if wordCount
        if ( get_option('wcp_wordcount') == '1' ) {
            $wordCount = str_word_count(strip_tags($content));
            $html .= esc_html__('This post has', 'wcpdomain') . ' ' . $wordCount . ' ' . esc_html__('words', 'wcpdomain') . '.' . '<br />';
        }
        // if charCount
        if ( get_option('wcp_charcount') == '1' ) {
            $charsCount = strlen(strip_tags($content));
            $html .= esc_html__('This post has', 'wcpdomain') . ' ' . $charsCount . ' ' . esc_html__('characters', 'wcpdomain') . '.' . '<br />';
        }
        // if charCount
        if ( get_option('wcp_readtime') == '1' ) {
            $readTime = round( str_word_count($content)/255 ) ;
            $html .= esc_html__('You can read this post in', 'wcpdomain') . ' ' . $readTime . ' ' . esc_html__('minute(s)', 'wcpdomain') . '.<br />';
        }
        // Beginning or End?
        if ( get_option('wcp_location') == '0' ) {
            return $html . $content;
        } else if ( get_option('wcp_location') == '1' ) {
            return $content . $html;
        }

    }

}

$TheWordCountPlugin = new TheWordCountPlugin();
