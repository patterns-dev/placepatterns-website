<?php
/*
Copyright 2011  Janrain  (email : info@janrain.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
Plugin Name: Janrain Engage
Plugin URI: https://support.janrain.com/
Description: Plugin to add authentication via the Janrain Engage service.
Version: 1.0.9
Author: forestb
Author URI: http://janrain.com/
License: GPL2
*/
define('RPX_PLUGIN_NAME',                  'Janrain Engage for Wordpress');
define('RPX_PLUGIN_VERSION',               '1.0.9');

/*catch the current setting and disable display errors for security*/
if (ob_start()){
  $rpx_buffer = true;
}else{
  $rpx_buffer = false;
}
$rpx_original_edisplay = ini_get('display_errors');
ini_set('display_errors', 0);
$rpx_original_ereport = error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
define('RPX_PATH_ROOT', dirname(__FILE__));
require_once(RPX_PATH_ROOT . '/rpx_m.php');
require_once(RPX_PATH_ROOT . '/rpx.conf.php');
require_once(RPX_PATH_ROOT . '/rpx_c.php');
require_once(RPX_PATH_ROOT . '/rpx_v.php');

function rpx_activate() {
  $rpx_api_key = get_option(RPX_API_KEY_OPTION);
  if ( empty($rpx_api_key) ){ $rpx_api_key = @strip_tags($_POST[RPX_API_KEY_OPTION]); }
  if ( !empty($rpx_api_key) ){
    $rpx_rp = rpx_get_rp($rpx_api_key);
    update_option(RPX_API_KEY_OPTION,   $rpx_rp['apiKey']);
    update_option(RPX_REALM_OPTION,     $rpx_rp['realm']);
    update_option(RPX_REALM_SCHEME,     $rpx_rp['realmScheme']);
    update_option(RPX_APP_ID_OPTION,    $rpx_rp['appId']);
    update_option(RPX_ADMIN_URL_OPTION, $rpx_rp['adminUrl']);
    update_option(RPX_SOCIAL_PUB,       $rpx_rp['socialPub']);   
    update_option(RPX_SOCIAL_OPTION,    RPX_SOCIAL_OPTION_DEFAULT);
    update_option(RPX_VEMAIL_OPTION,    RPX_VEMAIL_OPTION_DEFAULT);
    update_option(RPX_AUTOREG_OPTION,   RPX_AUTOREG_OPTION_DEFAULT);
    update_option(RPX_VERIFYNAME_OPTION,RPX_VERIFYNAME_OPTION_DEFAULT);
    update_option(RPX_AVATAR_OPTION,    RPX_AVATAR_OPTION_DEFAULT);
    update_option(RPX_S_STYLE_OPTION,   RPX_S_STYLE_OPTION_DEFAULT);   
    update_option(RPX_S_TXT_OPTION,     RPX_S_TXT_OPTION_DEFAULT);
    update_option(RPX_PARAMS_OPTION,    RPX_PARAMS_OPTION_DEFAULT);
    update_option(RPX_REMOVABLE_OPTION, RPX_REMOVABLE_OPTION_DEFAULT);
    update_option(RPX_SHARE_COUNT_OPTION, RPX_SHARE_COUNT_OPTION_DEFAULT);
    update_option(RPX_SIGNIN_OPTION,    RPX_SIGNIN_OPTION_DEFAULT);
    update_option(RPX_WPLOGIN_OPTION,   RPX_WPLOGIN_OPTION_DEFAULT);
    update_option(RPX_NEW_WIDGET_OPTION, RPX_NEW_WIDGET_OPTION_DEFAULT);
  }
}

function rpx_deactivate() {
  return true;
}

function rpx_uninstall() {
  delete_option(RPX_API_KEY_OPTION);
  delete_option(RPX_REALM_OPTION);
  delete_option(RPX_REALM_SCHEME);
  delete_option(RPX_APP_ID_OPTION);
  delete_option(RPX_ADMIN_URL_OPTION);
  delete_option(RPX_SOCIAL_PUB);
  delete_option(RPX_SOCIAL_OPTION);
  delete_option(RPX_VEMAIL_OPTION);
  delete_option(RPX_HTTP_OPTION);
  delete_option(RPX_SSL_VALID_OPTION);
  delete_option(RPX_AUTOREG_OPTION);
  delete_option(RPX_VERIFYNAME_OPTION);
  delete_option(RPX_AVATAR_OPTION);
  delete_option(RPX_S_STYLE_OPTION);
  delete_option(RPX_S_LOC_OPTION);
  delete_option(RPX_S_TXT_OPTION);
  delete_option(RPX_PROVIDERS_OPTION);
  delete_option(RPX_PARAMS_OPTION);
  delete_option(RPX_STRINGS_OPTION);
  delete_option(RPX_ADVANCED_OPTION);
  delete_option(RPX_SHARE_COUNT_OPTION);
  delete_option(RPX_SIGNIN_OPTION);
  delete_option(RPX_WPLOGIN_OPTION);
  delete_option(RPX_NEW_WIDGET_OPTION);
}

register_activation_hook(__FILE__, 'rpx_activate');
register_deactivation_hook(__FILE__, 'rpx_deactivate');
register_uninstall_hook(__FILE__, 'rpx_uninstall');


function rpx_init() {
  rpx_bootstrap();
  if (rpx_configured()) {
    if (!function_exists('email_exists') || !function_exists('username_exists')){
      require_once(ABSPATH . WPINC . '/registration.php');
    }
    global $rpx_http_vars;
    global $current_user;
    get_currentuserinfo();
    $rpx_comment_option = rpx_get_comment_option();
    add_action(RPX_TOKEN_ACTION_NAME, 'rpx_process_token');
    add_action(RPX_REGISTER_FORM_ACTION_NAME, 'rpx_register');
    add_action(RPX_REMOVE_ACTION_NAME, 'rpx_remove_usermeta');
    add_action(RPX_DATA_MODE_ACTION_NAME, 'rpx_process_token');
    if ($rpx_http_vars['action'] == RPX_TOKEN_ACTION){
      do_action(RPX_TOKEN_ACTION_NAME);
    }
    if ($rpx_http_vars['action'] == RPX_REGISTER_FORM_ACTION){
      do_action(RPX_REGISTER_FORM_ACTION_NAME);
    }
    if ($rpx_http_vars['action'] == RPX_REMOVE_ACTION){
      do_action(RPX_REMOVE_ACTION_NAME);
    }
    if ($rpx_http_vars['action'] == RPX_DATA_MODE_ACTION){
      do_action(RPX_DATA_MODE_ACTION_NAME);
    }
    add_action('login_head',    'rpx_login_head');
    add_action('login_form',    'rpx_login_form');
    add_action('register_form', 'rpx_login_form');
    if ( !empty($rpx_comment_option) ){
      add_action($rpx_comment_option, 'rpx_comment_login');
    }
    //add_action('comment_form_logged_in_after', 'rpx_share_comment');
    //wp_register_sidebar_widget('rpx_widget', 'Old '.RPX_WIDGET_TITLE.' widget', 'rpx_widget');//deprecated
    add_filter('the_content', 'rpx_content_filter');
    add_filter('comment_text', 'rpx_comment_filter');
    add_filter('get_comment_author_link', 'rpx_user_provider_icon');
    add_filter('get_avatar', 'rpx_avatar_filter');
    add_filter('manage_users_columns', 'rpx_add_custom_column');
    add_filter('manage_users_custom_column', 'rpx_custom_column', 10, 3);
    add_filter('manage_users_sortable_columns', 'rpx_add_custom_column');
    add_filter('request', 'rpx_column_orderby');
    add_action('wp_footer', 'rpx_wp_footer');
    add_action('admin_head', 'rpx_inline_javascript',10);
    add_action('admin_footer', 'rpx_wp_footer',10);
    add_action('bp_include', 'rpx_bp_init');//Detect BuddyPress for reg URL
    add_action('show_user_profile', 'rpx_edit_user_profile');
    add_action('edit_user_profile', 'rpx_admin_edit_user_profile');
    add_shortcode('rpxshare', 'rpxshare_shortcode');
    add_shortcode('rpxlogin', 'rpxlogin_shortcode');
    add_shortcode('rpxdata', 'rpxdata_shortcode');
    add_shortcode('rpxwidget', 'rpxwidget_shortcode');
    add_shortcode('rpxavatar', 'rpxavatar_shortcode');
    add_shortcode('rpxuser', 'rpxuser_shortcode');
    add_shortcode('rpxnotuser', 'rpxnotuser_shortcode');
  }else{
    rpx_activate();
  }
  add_action('wp_head', 'rpx_inline_stylesheet',11);
  add_action('wp_head', 'rpx_inline_javascript',12);
  add_action('admin_print_styles', 'rpx_inline_stylesheet',11);
  add_action('wp_print_styles', 'rpx_stylesheet',11);
  add_action('wp_print_scripts', 'rpx_javascript',11);
  add_action('admin_print_styles', 'rpx_admin_stylesheet',11);
  add_action('admin_menu', 'rpx_admin_menu');
  return true;
}

add_action('init', 'rpx_init');
if (rpx_configured()){
  add_action('widgets_init', 'rpx_register_widget',11);
}

if (!function_exists('is_login_page')){
  function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
  }
}

/*restore display errors to original setting*/
ini_set('display_errors', $rpx_original_edisplay);
error_reporting($rpx_original_ereport);
if ($rpx_buffer === true){
  if (function_exists('error_get_last')){
    if (is_array(error_get_last())){
      ob_end_clean();
    }
  }else{
    ob_end_flush();
  }
}
?>
