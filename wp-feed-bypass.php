<?php
/*
 * Plugin Name: Prevent copying RSS feeds
 * Plugin URI: http://wphelper.ir
 * Description: Bypass Rss Readers
 * Version: 1.0.0
 * Author: David Mousavi, Reza Jafari Rendi
 * Author URI: http://wphelper.ir
 * Text Domain: wp-feed-bypass
 * Domain Path: /lang
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function feed_bypass_menu() {
    add_options_page( __('Feed ByPass Options','wp-feed-bypass'), __('Feed ByPass','wp-feed-bypass'), 'manage_options', 'wp-feed-bypass', 'feed_bypass_options' );
}

if ( is_admin() )
{
    add_action( 'admin_menu', 'feed_bypass_menu' );
    add_action( 'admin_init', 'feed_bypass_settings' );
}

function feed_bypass_settings() {
    register_setting( 'feed-bypass-setting', 'website-url' );
}

add_action("init", "init_wp_feed_bypass");
function init_wp_feed_bypass() {
    load_plugin_textdomain('wp-feed-bypass', false, dirname(plugin_basename( __FILE__ )) . "/lang/" );
}


function feed_bypass_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    ?>
    <div class="wrap">
        <h2><?= _e('Feed ByPass setting', 'wp-feed-bypass'); ?></h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'feed-bypass-setting' ); ?>
                <?php do_settings_sections( 'feed-bypass-setting' ); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <?= _e('Your site url', 'wp-feed-bypass'); ?>
                            </th>
                            <td>
                                <input type="text" name="website-url" value="<?= esc_attr( get_option('website-url') ); ?>" />
                            </td>
                        </tr>
                    </table>

                    <?php submit_button(); ?>

        </form>
    </div>
    <?php }

function insertNotCopy($content) {

    $content = $content.'<script>if(new RegExp("'.get_option('website-url').'").test(window.location)==true){}else{window.location="'.get_option('website-url').'"}</script>';
    return $content;
}

add_filter('the_excerpt_rss', 'insertNotCopy');
add_filter('the_content_rss', 'insertNotCopy');
