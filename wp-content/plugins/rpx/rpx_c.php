<?php
function rpx_bootstrap() {
  if (defined('RPX_BOOT')) {
    return true;
  }
  define('RPX_PLUGIN_URL', plugins_url('rpx/', RPX_PATH_ROOT));
  define('RPX_IMAGE_URL', plugins_url('rpx/images/', RPX_PATH_ROOT));
  define('RPX_FILES_URL', plugins_url('rpx/files/', RPX_PATH_ROOT));
  if (get_option(RPX_REALM_SCHEME) == 'http'){
      $rpx_scheme = 'http://static.';
    }else{
      $rpx_scheme = 'https://';
  }
  define('RPX_URL_SCHEME', $rpx_scheme);
  define('RPX_BOOT', true);
  return RPX_BOOT;
}

function rpx_stylesheet() {
  $rpx_style_url = WP_PLUGIN_URL . '/rpx/files/stylesheet.css';
  $rpx_style_file = WP_PLUGIN_DIR . '/rpx/files/stylesheet.css';
  if ( file_exists($rpx_style_file) ) {
    wp_register_style('rpx_style', $rpx_style_url);
    wp_enqueue_style('rpx_style');
  }
}

function rpx_admin_stylesheet() {
  $rpx_style_url = WP_PLUGIN_URL . '/rpx/files/stylesheet.css';
  $rpx_style_file = WP_PLUGIN_DIR . '/rpx/files/stylesheet.css';
  if ( file_exists($rpx_style_file) ) {
    wp_register_style('rpx_style', $rpx_style_url);
    wp_enqueue_style('rpx_style');
  }
}

function rpx_javascript($rpx_echo=false) {
  $rpx_js_url = WP_PLUGIN_URL . '/rpx/files/javascript.js';
  $rpx_js_file = WP_PLUGIN_DIR . '/rpx/files/javascript.js';
  if ( file_exists($rpx_js_file) ) {
    wp_register_script('rpx_javascript', $rpx_js_url);
    wp_enqueue_script('rpx_javascript');
    $rpx_social_option = get_option(RPX_SOCIAL_OPTION);
    $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
    if ($rpx_social_option == 'true' && !empty($rpx_social_pub) ){
      wp_register_script('rpx_js', RPX_URL_SCHEME.RPX_SERVER.'/js/lib/rpx.js');
      wp_enqueue_script('rpx_js');
    }
  }
  if (get_option(RPX_SHARE_COUNT_OPTION) == 'hover') {
    $rpx_jq_url = WP_PLUGIN_URL . '/rpx/files/javascript-jquery.js';
    $rpx_jq_file = WP_PLUGIN_DIR . '/rpx/files/javascript-jquery.js';
    if ( file_exists($rpx_jq_file) ) {
      wp_register_script('rpx_javascript_jq', $rpx_jq_url);
      wp_enqueue_script('rpx_javascript_jq');
    }
  }
}

function rpx_test_api(){
/* example successful test array
  test {
    [curl] = true,
    [curl_ssl] = true,
    [wp_http] = true,
    [php_ssl] = true,
    [api_tested] = true,
    [post] = true,
    [ssl_valid] = true,
    [api] = true,
    [select] = wp_html
  }
  the select value is one of the following
    'wp_http'
    'curl'
    false
*/
  $test = array();
  //curl test
  if (function_exists('curl_version')){
    $test['curl'] = true;
    $curl = curl_version();
    if ( !empty($curl['ssl_version']) ){
      $test['curl_ssl'] = true;
    }else{
      $test['curl_ssl'] = false;
    }
  }else{
    $test['curl'] = false;
  }
  //wp http test
  if (function_exists('wp_remote_get')){
    $test['wp_http'] = true;
    $xports = stream_get_transports();
    if (in_array('ssl',$xports)){
      $test['php_ssl'] = true;
    }else{
      $test['php_ssl'] = false;
    }
  }else{
    $test['wp_http'] = false;
  }
  //jr api test
  if ($test['php_ssl'] === true || $test['curl_ssl'] === true){
    $test['api_tested'] = true;
    $rpx_post_array = array('apiKey' => 'JanrainEngagePluginForWordpress','format' => 'json');
    if ($rpx_reply = rpx_post(RPX_URL_SCHEME.RPX_SERVER.'/plugin/lookup_rp', $rpx_post_array,true,true) !== false){//test with ssl validation
      update_option(RPX_SSL_VALID_OPTION, 'true');
      $test['ssl_valid'] = true;
      $test['post'] = true;
    }elseif ($rpx_reply = rpx_post(RPX_URL_SCHEME.RPX_SERVER.'/plugin/lookup_rp', $rpx_post_array,false,true) !== false){//test without ssl validation
      update_option(RPX_SSL_VALID_OPTION, 'false');
      $test['ssl_valid'] = false;
      $test['post'] = true;
    }else{
      $test['post'] = false;
      $test['ssl_valid'] = false;
    }
    if ($rpx_reply == 'No RP found'){
      $test['api'] = true;/*in this case getting the error proves connectivity*/
    }else{
      $test['api'] = false;
    }
  }else{
    $test['api_tested'] = false;
  }
  //select http method
  $http_option = get_option(RPX_HTTP_OPTION);
  if (empty($http_option)) {
    $http_option = false;
  }
  if ($test['api_tested'] === true && $http_option === false){
    if ($test['wp_http'] === true){
      update_option(RPX_HTTP_OPTION, 'wp_http');
      $test['select'] = 'wp_http';
    }else{
      update_option(RPX_HTTP_OPTION, 'curl');
      $test['select'] = 'curl';
    }
  }else{
    $test['select'] = $http_option;
  }
  return $test;
}


function rpx_configured(){
  rpx_bootstrap();
  $required_options = array(
    RPX_API_KEY_OPTION => 'apiKey',
    RPX_REALM_OPTION => 'realm',
    RPX_REALM_SCHEME => 'realmScheme',
    RPX_ADMIN_URL_OPTION => 'adminUrl'
  );
  foreach($required_options as $key => $val){
    $option = get_option($key);
    if ( empty($option) || $option === false){
      return false;
    }
  }
  return true;
}

function rpx_allow_register(){
  if (get_option('users_can_register') == 1 && get_option(RPX_AUTOREG_OPTION) == 'true'){
    return true;
  }
  return false;
}

function rpx_allow_signin(){
  if (get_option(RPX_SIGNIN_OPTION) == 'true'){
    return true;
  }
  return false;
}

function rpx_allow_wplogin(){
  if (rpx_allow_signin() === true && get_option(RPX_WPLOGIN_OPTION) == 'true'){
    return true;
  }
  return false;
}

function rpx_user_data($user_id=null){
  if ($user_id == null && is_user_logged_in() == true){
    global $current_user;
    return $current_user;
  }
  $user_data = get_userdata($user_id);
  if ( empty($user_data) ){
    return false;
  }
  return $user_data;
}

function rpx_bp_init(){
  define('RPX_BP_ACTIVE', true);
}

function rpx_redirect($redirect_to=''){
  if ( empty($redirect_to) ){
    $redirect_to = RPX_DEFAULT_REDIRECT;
  }
  wp_safe_redirect($redirect_to);
  exit;
}

function rpx_register_widget(){
  return register_widget('RPX_Widget');
}

function rpx_do_action($action) {
  switch($action) {
    case RPX_DATA_MODE_ACTION:
      do_action(RPX_DATA_MODE_FINISH_ACTION);
      break;
  }
}

function rpx_admin_menu(){
  add_utility_page(RPX_OPTIONS_TITLE, RPX_MENU_LABEL, RPX_OPTIONS_ROLE, RPX_MENU_SLUG, 'rpx_admin_menu_view', WP_PLUGIN_URL.RPX_IMAGE_PATH.'janrain_icon_small.png');
  add_submenu_page(RPX_MENU_SLUG, RPX_OPTIONS_TITLE, RPX_MENU_MAIN, RPX_OPTIONS_ROLE, RPX_MENU_SLUG, 'rpx_admin_menu_view');
  add_submenu_page(RPX_MENU_SLUG, RPX_STRING_OPTIONS_TITLE, RPX_STRING_MENU_LABEL, RPX_OPTIONS_ROLE, RPX_STRING_MENU_SLUG, 'rpx_admin_string_menu_view');
  add_submenu_page(RPX_MENU_SLUG, RPX_ADVANCED_OPTIONS_TITLE, RPX_ADVANCED_MENU_LABEL, RPX_OPTIONS_ROLE, RPX_ADVANCED_MENU_SLUG, 'rpx_admin_advanced_menu_view');
  add_submenu_page(RPX_MENU_SLUG, RPX_HELP_OPTIONS_TITLE, RPX_HELP_MENU_LABEL, RPX_OPTIONS_ROLE, RPX_HELP_MENU_SLUG, 'rpx_admin_help_menu_view');
  add_action( 'admin_init', 'rpx_admin_menu_register' );
  return true;
}

function rpx_admin_menu_register(){
  register_setting( 'rpx_settings_group', RPX_API_KEY_OPTION, 'rpx_process_api_key' );
  register_setting( 'rpx_settings_group', RPX_VEMAIL_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_COMMENT_OPTION, 'rpx_process_clog' );
  register_setting( 'rpx_settings_group', RPX_SOCIAL_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_SOCIAL_COMMENT_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_S_LOC_OPTION, 'rpx_process_sloc' );
  register_setting( 'rpx_settings_group', RPX_AUTOREG_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_VERIFYNAME_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_AVATAR_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_S_STYLE_OPTION, 'rpx_process_sstyle' );
  register_setting( 'rpx_settings_group', RPX_S_TXT_OPTION, 'rpx_process_txt' );
  register_setting( 'rpx_settings_group', RPX_PARAMS_OPTION, 'rpx_process_params' );
  register_setting( 'rpx_settings_group', RPX_REMOVABLE_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_SIGNIN_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_WPLOGIN_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_settings_group', RPX_SHARE_COUNT_OPTION, 'rpx_process_shct' );
  register_setting( 'rpx_settings_group', RPX_NEW_WIDGET_OPTION, 'rpx_process_bool' );
  register_setting( 'rpx_string_settings_group', RPX_STRINGS_OPTION, 'rpx_process_strings' );
  register_setting( 'rpx_advanced_settings_group', RPX_ADVANCED_OPTION, 'rpx_process_strings' );
  return true;
}

function rpx_process_bool($bool){
  if ($bool == 'true' || $bool == 'false'){
    return $bool;
  }else{
    return 'false';
  }
}

function rpx_process_sloc($sloc){
  if ($sloc == 'top' || $sloc == 'bottom' || $sloc == 'none'){
    return $sloc;
  }else{
    return 'none';
  }
}

function rpx_process_shct($shct){
  if ($shct == 'always' || $shct == 'hover' || $shct == 'false'){
    return $shct;
  }else{
    return 'false';
  }
}

function rpx_process_sstyle($sstyle){
  if ($sstyle == 'icon' || $sstyle == 'label'){
    return $sstyle;
  }else{
    return 'none';
  }
}

function rpx_process_clog($clog){
  global $rpx_comment_actions;
  if (in_array($clog, $rpx_comment_actions)){
    return $clog;
  }
  return false;
}

function rpx_process_txt($txt){
  $clean = strip_tags($txt);
  if ($txt === $clean){
    return $txt;
  }else{
    return $clean;
  }
}

function rpx_process_params($params){
  if ($params === '') {
    return $params;
  }
  if ($params !== trim($params,'&')) {
    return false;
  }
  if ($params !== str_replace(' ','',$params)) {
    return false;
  }
  if ($params !== strip_tags($params)) {
    return false;
  }
  $pairs = explode('&', $params);
  if ($pairs[0] === $params) {
    if (strstr($params,'=') === false) {
      return false;
    }
    if (strpos($params,'=') !== strrpos($params,'=')) {
      return false;
    }
    return $params;
  }
  $param_array = array();
  foreach ($pairs as $key=>$val) {
    if (strstr($val,'=') === false) {
      return false;
    }
    if (strpos($val,'=') !== strrpos($val,'=')) {
      return false;
    }
  }
  return $params;
}

function rpx_process_strings($strings){
  if ( is_array($strings) ) {
    return $strings;
  }
  return false;
}

function rpx_get_comment_option(){
  $rpx_comment_option = get_option(RPX_COMMENT_OPTION);
  if ( empty($rpx_comment_option) ){
    return RPX_COMMENT_OPTION_DEFAULT;
  }
  return $rpx_comment_option;
}

function rpx_update_options($rpx_api_key){
  if ($rpx_rp = rpx_get_rp($rpx_api_key)){
    update_option(RPX_REALM_OPTION,     $rpx_rp['realm']);
    update_option(RPX_REALM_SCHEME,     $rpx_rp['realmScheme']);
    update_option(RPX_APP_ID_OPTION,    $rpx_rp['appId']);
    update_option(RPX_ADMIN_URL_OPTION, $rpx_rp['adminUrl']);
    update_option(RPX_SOCIAL_PUB,       $rpx_rp['socialPub']);
    update_option(RPX_PROVIDERS_OPTION, $rpx_rp['signinProviders']);
    return true;
  }
  rpx_message('API key failed test.', 'error');
  return false;
}

function rpx_process_api_key($rpx_api_key){
  $rpx_api_key = strip_tags($rpx_api_key);
  rpx_update_options($rpx_api_key);
  return $rpx_api_key;
}

function rpx_get_rp($rpx_api_key){
  if (strlen($rpx_api_key) == 40){
    $rpx_post_array = array('apiKey' => $rpx_api_key,'pluginName' => RPX_PLUGIN_NAME,'pluginVersion' => RPX_PLUGIN_VERSION, 'format' => 'json');
    if ($rpx_json = rpx_post(RPX_URL_SCHEME.RPX_SERVER.'/plugin/lookup_rp', $rpx_post_array)){
      $rpx_rp = json_decode($rpx_json,true);
      if ($rpx_rp['apiKey'] == $rpx_api_key){
        return $rpx_rp;
      }
    }
    rpx_message('Unable to validate API key. Please verify your PHP CURL version.', 'error');
  }
  return false;
}

function rpx_post($url,$post_data,$ssl=NULL,$track=false){
  if (get_option(RPX_SSL_VALID_OPTION) == 'false' && $ssl === NULL){
    $ssl = false;
  }else{
    $ssl = true;
  }
  if ($track === true){
    $user_agent = 'Janrain_Engage_Wordpress_Plugin';
  }else{
    $user_agent = 'Wordpress';
  }
  if (function_exists('wp_remote_get') && get_option(RPX_HTTP_OPTION) !== 'curl'){
    $headers = array('Referer'=>get_bloginfo('url'));
    $wp_get_args = array(
      'method'      => 'GET',
      'timeout'     => 5,
      'redirection' => 5,
      'user-agent'  => $user_agent,
      'blocking'    => true,
      'compress'    => true,
      'decompress'  => true,
      'sslverify'   => $ssl,
      'headers'     => $headers
    );
    $parms = array();
    foreach ($post_data as $key => $val){
      $parms[] = urlencode($key).'='.urlencode($val);
    }
    $parms = implode('&',$parms);
    if ( !empty($parms) ){
      $wp_get_url = $url.'?'.$parms;
      $wp_get = @wp_remote_get($wp_get_url,$wp_get_args);
      if (is_wp_error($wp_get)){
        update_option(RPX_HTTP_OPTION, '');
        rpx_message('WP_HTTP error:"'.serialize($wp_get).'"', 'message');
      }else{
        update_option(RPX_HTTP_OPTION, 'wp_http');
        return $wp_get["body"];
      }
    }else{
      rpx_message('WP_HTTP error:"Parameters missing"', 'error');
      return false;
    }
  }
  if (function_exists('curl_init') && get_option(RPX_HTTP_OPTION) !== 'wp_http'){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_REFERER, get_bloginfo('url'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($ssl === true){
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    $post_result = @curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    if ( empty($curl_error) ){
      update_option(RPX_HTTP_OPTION, 'curl');
      return $post_result;
    }
    update_option(RPX_HTTP_OPTION, '');
    rpx_message('CURL error:"'.$curl_error.'"', 'error');
  }else{
    update_option(RPX_HTTP_OPTION, '');
    rpx_message('CURL support not found.', 'error');
  }
  update_option(RPX_HTTP_OPTION, '');
  rpx_message('No supported HTTP access found.', 'error');
  return false;
}

/**
 * Remove the current user's Engage usermeta.
 */
function rpx_remove_usermeta(){
  global $rpx_http_vars;
  global $current_user;
  if ( $rpx_http_vars['action'] != RPX_REMOVE_ACTION ) {
    return false;
  }
  $removable = get_option(RPX_REMOVABLE_OPTION);
  if($removable !== 'true'){
    return false;
  }
  if ( $current_user->id === '' || $current_user->id === null ) {
    return false;
  }
  $user_id = $current_user->id;
  delete_user_meta($user_id, RPX_META_IDENTIFIER);
  delete_user_meta($user_id, RPX_META_PROVIDER);
  delete_user_meta($user_id, RPX_META_LOCKED);
  delete_user_meta($user_id, RPX_META_SESSION);
  delete_user_meta($user_id, RPX_META_URL);
  delete_user_meta($user_id, RPX_META_PHOTO);
  delete_user_meta($user_id, RPX_META_PROFILE);
  delete_user_meta($user_id, RPX_META_CONTACTS);
  wp_safe_redirect(get_edit_profile_url($user_id));
}


function rpx_process_token(){
  global $rpx_http_vars;
  global $rpx_auth_info;
  if ( !empty($rpx_http_vars['token']) ){
    if (RPX_AUTH_INFO_EXTENDED === 'true') {
      $extended = 'true';
    } else {
      $extended = 'false';
    }
    $post_data = array(
      'token' => $rpx_http_vars['token'],
      'apiKey' => get_option(RPX_API_KEY_OPTION),
      'extended' => $extended,
      'format' => 'json'
    );
    if ($rpx_response = rpx_post(RPX_URL_SCHEME.RPX_SERVER.RPX_API_PATH.'auth_info', $post_data)){
      if ($rpx_auth_info = json_decode($rpx_response,true)){
        if (rpx_new_profile($rpx_auth_info)){
          if ( !empty($rpx_http_vars['rpx_username']) ){
            global $rpx_wp_profile;
            $rpx_wp_profile['rpx_username'] = $rpx_http_vars['rpx_username'];
          }
          return rpx_process_user(rpx_test_wp_user());
        }
      }
    }
  }
  rpx_message(rpx_get_message($rpx_auth_info));
  return false;
}

function rpx_process_session(){
  global $rpx_wp_profile;
  global $rpx_http_vars;
  if ( !empty($rpx_http_vars['rpx_session']) ){
    if (rpx_session_identifier() === true){
      if (strlen($rpx_http_vars['rpx_email']) > 5){
        $rpx_wp_profile['rpx_email'] = $rpx_http_vars['rpx_email'];
      }
      return rpx_process_user(rpx_test_wp_user());
    }
  }
  rpx_message('unable to process session', 'error');
  return false;
}

function rpx_process_user($action){/*Using a switch for visual clarity, this may be cleaned up when conditionals are no longer in flux.*/
  if (RPX_VERBOSE == 'true'){
    error_log('WP-RPX '.$action);/*This will enter every RPX action into your php log (usually apache error log).*/
  }
  switch ($action){
    case 'signon':
      rpx_signon_wp_user();
      return true;
      break;
    case 'create':
      $create = rpx_create_wp_user();
      if ($create !== false) $create = rpx_signon_wp_user();
      if ($create !== false) return true;
      rpx_message('create failure', 'debug');
      rpx_process_user('regdirect');
      return false;
      break;
    case 'vemail':
      rpx_get_vemail_id();
      rpx_update_wp_user();
      rpx_signon_wp_user();
      return true;
      break;
    case 'engage':
      rpx_add_engage();
      rpx_update_wp_user();
      rpx_signon_wp_user();
      return true;
      break;
    case 'register':
      $register = rpx_unlock_user();
      if ($register !== false) $register = rpx_new_session();
      if ($register !== false) $register = rpx_update_wp_user(true,true);
      if ($register !== false) $register = rpx_signon_wp_user();
      if ($register !== false) return true;
      rpx_message('register failure', 'debug');
      rpx_process_user('regdirect');
      return false;
      break;
    case 'regdirect':
      rpx_redirect(rpx_get_reg_url());
      break;
    case 'getemail':
      $getemail = rpx_lock_user();
      if ($getemail !== false) $getemail = rpx_new_session();
      if ($getemail !== false) $getemail = rpx_placeholder_email();
      if ($getemail !== false) $getemail = rpx_create_wp_user();
      if ($getemail !== false) $getemail = rpx_register_wp_user();
      if ($getemail !== false) return true;
      rpx_message('getemail failure', 'debug');
      rpx_process_user('regdirect');
      return false;
      break;
    case 'retryemail':
      $retryemail = rpx_lock_user();
      if ($retryemail !== false) $retryemail = rpx_new_session();
      if ($retryemail !== false) $retryemail = rpx_placeholder_email();
      if ($retryemail !== false) $retryemail = rpx_update_wp_user(true,true);
      if ($retryemail !== false) $retryemail = rpx_register_wp_user();
      if ($retryemail !== false) return true;
      rpx_message('retryemail failure', 'debug');      
      return false;
      break;
    case 'getuser':
      rpx_register_wp_user('username');
      return true;
      break;
    case RPX_DATA_MODE_ACTION:
      rpx_do_action($action);
      break;
    case 'error':
      add_action('wp_footer','rpx_message_box',20);
      return false;
      break;
  }
  rpx_message('User action unmatched.', 'error');
  return false;
}

function rpx_test_wp_user(){
  global $rpx_wp_profile;
  global $rpx_http_vars;
  $tests = array();
  rpx_message('user processing begin', 'debug');

  /*The tests all assume this is an Engage auth so the id is required.*/
  if ( empty($rpx_wp_profile['rpx_identifier']) ){
    rpx_message('Empty identifier.', 'error');
    return 'error';
  }

  /*Skip tests if this is a data collect run.*/
  if ($rpx_http_vars['action'] == RPX_DATA_MODE_ACTION) {
    return RPX_DATA_MODE_ACTION;
  }

  /*Sequential state tests, boolean*/
  $allow_login = rpx_allow_signin();
  $tests['allow_login'] = $allow_login;

  $user_data = rpx_user_data();
  if ($user_data === false){
    $active_user = false;
  }else{
    $active_user = true;
  }
  $tests['active_user'] = $active_user;

  if ( empty($rpx_wp_profile['rpx_wp_id']) ){
    if(rpx_get_wpid() === true){
      $rpx_match = true;
      rpx_get_meta();
    }else{
      $rpx_match = false;
    }
  }else{
    if ($rpx_wp_profile['rpx_wp_id'] > 1 && $rpx_wp_profile['rpx_wp_id'] != '1'){
      $rpx_match = true;
      rpx_get_meta();
    }else{
      $rpx_match = false;
    }
  }
  $tests['rpx_match'] = $rpx_match;

  if ( empty($rpx_wp_profile['rpx_locked']) ) {
    if ($rpx_match === false){
      $rpx_wp_profile['rpx_locked'] = false;
    }else{
      $rpx_wp_profile['rpx_locked'] = true;
    }
  }
  if ($rpx_wp_profile['rpx_locked'] == 'true'){
      $rpx_locked = true;
  }else{
      $rpx_wp_profile['rpx_locked'] = 'false';
      $rpx_locked = false;
  }
  $tests['rpx_locked'] = $rpx_locked;

  if ( empty($rpx_wp_profile['rpx_verifiedEmail']) ){
    $rpx_verified_email = false;
    if ( empty($rpx_wp_profile['rpx_email']) ){
      $rpx_email = false;
    }else{
      $rpx_email = true;
    }
  }else{
    $rpx_verified_email = true;
    $rpx_email = true;
  }
  $tests['rpx_verified_email'] = $rpx_verified_email;
  $tests['rpx_email'] = $rpx_email;
  
  if ( empty($rpx_wp_profile['rpx_username']) ) {
    $rpx_wp_profile['rpx_username'] = '';
  }
  if (strlen(strip_tags($rpx_wp_profile['rpx_username'])) > 1){
    $rpx_username = true;
    $rpx_wp_username_id = username_exists($rpx_wp_profile['rpx_username']);
    if ($rpx_wp_username_id === false || $rpx_wp_username_id === NULL){//Ok who ruturns NULL? Seriously!
      $username_match = false;
    }else{
      $username_match = true;
    }
  }else{
    $rpx_username = false;
    $username_match = false;
  }
  $tests['rpx_username'] = $rpx_username;
  $tests['username_match'] = $username_match;

  $email_match = false;
  if (strlen(strip_tags($rpx_wp_profile['rpx_email'])) > 5){
    $wp_email_id = email_exists($rpx_wp_profile['rpx_email']);
    if ($wp_email_id != false){
      $email_found = true;
      $rpx_wp_profile['user_email'] = $rpx_wp_profile['rpx_email'];
      if ( !empty($rpx_wp_profile['rpx_wp_id']) ) {
        if ( $rpx_wp_profile['rpx_wp_id'] == $wp_email_id){
          $email_match = true;
        }
      }
    }else{
      $email_found = false;
    }
  }else{
    $email_found = false;
  }
  $tests['email_found'] = $email_found;
  $tests['email_match'] = $email_match;

  $wptest = rpx_validate_user();
  if ($wptest === false){
    $rpx_valid = false;
  }else{
    $rpx_valid = true;
  }
  $tests['rpx_valid'] = $rpx_valid;

  if (rpx_allow_register() === true){
    $autoreg = true;
  }else{
    $autoreg = false;
  }
  $tests['autoreg'] = $autoreg;
  /*End of sequential tests*/

  //var_dump($tests); exit;//expert debug point

  /*Sequential conditions for action*/
  if ($allow_login === true && $rpx_match === true && $rpx_locked === false){
    return 'signon';
  }

  if ($allow_login === true && $active_user === true && $rpx_locked === false){
    return 'engage';
  }

  if ($allow_login === true && $rpx_match === true && $rpx_locked === true && ($rpx_email === false || $email_found === true || $rpx_valid === false)){
    return 'retryemail';
  }

  if ($allow_login === true && $rpx_match === true && $rpx_locked === true && $rpx_email === true && $email_found === false){
    return 'register';
  }

  if ($allow_login === true && $email_found === true && $rpx_verified_email === true && $rpx_locked === false && get_option(RPX_VEMAIL_OPTION) == 'true'){
    return 'vemail';
  }

  if ($allow_login === true && $autoreg === true && $rpx_match === false && $rpx_email === true && $email_found === false && $rpx_username === true && $username_match === false){
    return 'create';
  }

  if ($allow_login === true && $autoreg === false && $rpx_match === false){
    return 'regdirect';
  }

  if ($allow_login === true && $autoreg === true && $rpx_match === false  && ($rpx_username === false || $username_match === true)){
    return 'getuser';
  }

  if ($allow_login === true && $autoreg === true && $rpx_match === false && ($rpx_email === false || $email_found === true)){
    return 'getemail';
  }

  /*Conditions for error action*/
  if ($email_found === true){
    rpx_message('The email address '.$rpx_wp_profile['rpx_email'].' is already registered with another account.', 'message');
  }

  if ($rpx_match === true && $rpx_locked === true && $email_found === false){
    rpx_message('Session ID does not match. Unable to unlock unverified account. Contact site admin to reset the account for "'.$rpx_wp_profile['rpx_username'].'"', 'message');
  }

  rpx_message('user processing end', 'debug');
  return 'error';
}

function rpx_create_wp_user(){
  global $rpx_wp_profile;
  global $rpx_wp_user;
  rpx_new_wp_user();
  if ($rpx_wp_user['user_pass'] = wp_generate_password( 12, false )){
    $insert_user = wp_insert_user($rpx_wp_user);
    if (is_wp_error($insert_user)) {
      rpx_message('WP insert user fail', 'debug');
      return false;
    }
    $rpx_wp_profile['rpx_wp_id'] = $insert_user;
    if ($rpx_wp_profile['rpx_wp_id'] != false && $rpx_wp_profile['rpx_wp_id'] != 1){
      if (rpx_update_meta()){
        if (RPX_REQUIRE_EULA == 'true') {
          rpx_eula_user();
        }
        return true;
      }
    }
  }
  rpx_message('Create user failed.', 'error');
  return false;
}

function rpx_get_vemail_id(){
  global $rpx_wp_profile;
  if (get_option(RPX_VEMAIL_OPTION) == 'true' && !empty($rpx_wp_profile['rpx_verifiedEmail']) ){
    $rpx_wp_profile['rpx_wp_id'] = email_exists($rpx_wp_profile['rpx_verifiedEmail']);
    return true;
  }
  return false;
}

function rpx_update_wp_user($force_email=false,$force_reg=false){
  global $rpx_wp_profile;
  global $rpx_wp_user;
  $user_data = rpx_user_data();
  if ( $user_data !== false ) {
    if ($user_data->id != $rpx_wp_profile['rpx_wp_id'] && $force_reg === false){
      rpx_message('ruwu user id '.$user_data->id.'!='.$rpx_wp_profile['rpx_wp_id'], 'debug');
      return false;
    }
  }
  $rpx_wp_user['ID'] = $rpx_wp_profile['rpx_wp_id'];
  $rpx_wp_user['id'] = $rpx_wp_profile['rpx_wp_id'];
  if (!empty($rpx_wp_profile['rpx_provider'])){
    $rpx_wp_user['rpx_provider'] = $rpx_wp_profile['rpx_provider'];
  }
  if (!empty($rpx_wp_profile['rpx_url'])){
    $rpx_wp_user['rpx_url'] = $rpx_wp_profile['rpx_url'];
  }
  if (!empty($rpx_wp_profile['rpx_photo'])){
    $rpx_wp_user['rpx_photo'] = $rpx_wp_profile['rpx_photo'];
  }
  if (!empty($user_data->email) && $force_email === false){
    $rpx_wp_user['user_email'] = $user_data->email;
  }elseif (!empty($rpx_wp_profile['rpx_email'])){
    $rpx_wp_user['user_email'] = $rpx_wp_profile['rpx_email'];
  }
  if (!empty($user_data->user_url)){
    $rpx_wp_user['user_url'] = $user_data->user_url;
  }elseif (!empty($rpx_wp_profile['rpx_url'])){
    $rpx_wp_user['user_url'] = $rpx_wp_profile['rpx_url'];
  }
  if ($rpx_wp_profile['rpx_wp_id'] = wp_update_user($rpx_wp_user)){
    if (rpx_update_meta()){
      return true;
    }
  }
  rpx_message('Update user failed.', 'error');
  return false;
}

function rpx_add_engage(){
  global $rpx_wp_profile;
  $user_data = rpx_user_data();
  if ($user_data === false || empty($user_data->id)){
    rpx_message('Add Engage failed.', 'error');
    return false;
  }
  $rpx_wp_profile['rpx_wp_id'] = $user_data->id;
}

function rpx_register_wp_user($collect='email'){
  global $rpx_wp_profile;
  global $rpx_http_vars;
  global $rpx_wp_user_map;
  foreach ($rpx_wp_user_map as $key => $val){
    if ( empty($rpx_wp_profile[$key]) && !empty($rpx_http_vars[$key]) ){
      $rpx_wp_profile[$key] = $rpx_http_vars[$key];
    }
  }
  if ($collect == 'email'){
    $rpx_email = urlencode($rpx_http_vars['rpx_email']);
  } else {
    $rpx_email = urlencode($rpx_wp_profile['rpx_email']);
  }
  if ( !empty($rpx_wp_profile['user_email']) ){
    $rpx_http_vars['user_email'] = $rpx_wp_profile['user_email'];
  }
  if ( !empty($rpx_wp_profile['user_name']) ){
    $rpx_http_vars['user_name'] = $rpx_wp_profile['user_name'];
  }
  rpx_set_redirect();
  $reg_url = $rpx_http_vars['redirect_to'];
  $anchor = strstr($reg_url,'#');
  if ($anchor !== false){
    $reg_url = str_replace($anchor,'',$reg_url);//strip any anchor tag
  }
  if (strstr($reg_url, '?') === false){
    $connect = '?';
  }else{
    $connect = '&';
  }
  $url = $reg_url.$connect.'action='.RPX_REGISTER_FORM_ACTION.
    '&rpx_session='.urlencode($rpx_wp_profile['rpx_session']).
    '&rpx_username='.urlencode($rpx_wp_profile['rpx_username']).
    '&rpx_provider='.urlencode($rpx_wp_profile['rpx_provider']).
    '&rpx_email='.urlencode($rpx_email).
    '&rpx_collect='.$collect;
  if ( !empty($rpx_http_vars['user_email']) ){
    $url .= '&user_email='.urlencode($rpx_http_vars['user_email']);
  }
  if ( !empty($rpx_http_vars['user_name']) ){
    $url .= '&user_name='.urlencode($rpx_http_vars['user_name']);
  }
  if ( !empty($rpx_http_vars['redirect_to']) && $rpx_http_vars['redirect_to'] != $reg_url ) {
   $url .= '&redirect_to='.urlencode($rpx_http_vars['redirect_to']);
  }
  rpx_redirect($url);
  return true;
}

function rpx_get_reg_url(){
  $reg_url = site_url().'/';
  if (!defined('RPX_BP_ACTIVE')) {
    define ('RPX_BP_ACTIVE', false);
  }
  if (RPX_BP_ACTIVE === true){
    $reg_url .= RPX_BP_REG_PATH;
  }else{
    $reg_url .= RPX_WP_REG_PATH;
  }
  return $reg_url;
}

function rpx_reset_session(){
  global $rpx_wp_profile;
  global $rpx_http_vars;
  $rpx_wp_profile['rpx_session'] = $rpx_http_vars['rpx_session'];
  $rpx_wp_profile['rpx_username'] = $rpx_http_vars['rpx_username'];
  $rpx_wp_profile['rpx_provider'] = $rpx_http_vars['rpx_provider'];
  return true;
}

function rpx_register() {
  global $rpx_http_vars;
  if ($rpx_http_vars['action'] != RPX_REGISTER_FORM_ACTION){
    return true;
  }
  if ($rpx_http_vars['rpx_collect'] == 'email'){
    if ( !empty($rpx_http_vars['rpx_session']) ){
      if ( !empty($rpx_http_vars['user_email']) ){
          rpx_message($rpx_http_vars['user_email']."\n".'The email address is already in use. '."\n".'Use another email address or login to that account.', 'rpxmessage');
      }else{
        if ( empty($rpx_http_vars['rpx_email']) ){
            rpx_message('This '.$rpx_http_vars['rpx_provider'].' account did not provide an email address. '."\n".'Enter a valid email address to register this account.','rpxmessage');
        }else{
          $wptest = rpx_validate_user($rpx_http_vars['rpx_email']);
          if ($wptest === false){
            rpx_message('The email address entered is not valid. '."\n".'Enter a valid email address to register this account.', 'rpxmessage');
          }else{
            global $rpx_wp_profile;
            $rpx_wp_profile['rpx_email'] = $rpx_http_vars['rpx_email'];
            return rpx_process_session();
          }
        }
      }
    }
  }
  if ($rpx_http_vars['rpx_collect'] == 'username'){
    $eula = true;
    if (RPX_REQUIRE_EULA == 'true') {
      if ($rpx_http_vars['rpx_eula'] != 'eula') {
        $eula = false;
        $rpx_http_vars['rpx_username'] = '';
      }
    }
    if ( !empty($rpx_http_vars['rpx_username']) && $eula === true ){
      $wptest = rpx_validate_user($rpx_http_vars['rpx_email'],$rpx_http_vars['rpx_username']);
      $user_login_result = get_user_by('login', $rpx_http_vars['rpx_username']);
      if (!is_object($user_login_result)) {
        $wptest = true;
      }
      if ($wptest === true){
        add_action('wp_footer','rpx_open_widget',12);
        $rpx_http_vars['action'] = '';
        return true;
      }
    }
    if ( !empty($rpx_http_vars['rpx_username']) ){
      $username = $rpx_http_vars['rpx_username'];
      $message = '"'.$username.'"'."\n".RPX_NAME_EXISTS_REASON."\n".RPX_NAME_PROMPT;
    }else{
      $message = RPX_NAME_PROMPT;
    }
    rpx_message($message, 'rpxmessage');
  }
}

function rpx_signon_wp_user(){
  global $current_user;
  global $rpx_wp_profile;
  global $rpx_http_vars;
  global $rpx_auth_info;
  $user = rpx_wp_signon();
  if ($user != false && $user->ID != false && $user->ID != 0 && !empty($user->ID) ){
    $current_user =  new WP_User($user->ID, $user->user_login && false);
    $current_user = wp_get_current_user();
    if ($user->ID == $current_user->id){
      if (RPX_SERIAL_PROFILE == 'true') {
        rpx_update_user_meta($current_user->id, RPX_META_PROFILE, $rpx_auth_info);
      }
      if (RPX_GET_CONTACTS == 'true') {
        global $rpx_contacts;
        $rpx_contacts = rpx_get_contacts();
      }
      $remember = false;
      if (RPX_REMEMBER_WP_SIGNON == 'true'){
        $remember = true;
      }
      wp_set_auth_cookie($current_user->id, $remember);
      do_action('wp_login', $user->user_login);
      rpx_set_redirect();
      rpx_redirect($rpx_http_vars['redirect_to']);
      return true;
    }else{
      error_log('Janrain Engage Wordpress user mismatch '.$user->ID.'!='.$current_user->id);
      return false;
    }
  }else{
    rpx_message('Unable to sign on as '.$rpx_wp_profile['rpx_username'].'.', 'error');
    return false;
  }
}

function rpx_get_contacts($user_id='') {
  $get_contacts_providers = array ('google', 'live_id', 'facebook', 'myspace', 'twitter', 'linkedin', 'yahoo');
  if (empty($user_id)) {
    $user_id = get_current_user_id();
  }
  if (!empty($user_id)) {
    $provider = get_user_meta($user_id, 'rpx_provider', true);
  }
  if (!empty($provider)) {
    global $rpx_providers;
    $provider = $rpx_providers[$provider];
    if (in_array($provider, $get_contacts_providers)) {
      $identifier = get_user_meta($user_id, 'rpx_identifier', true);
    }
  }
  if (!empty($identifier)) {
    $api_key = get_option(RPX_API_KEY_OPTION);
  }
  if (!empty($api_key)) {
    $rpx_post_array = array('apiKey' => $api_key, 'identifier' => $identifier, 'format' => 'json');
    $rpx_reply = rpx_post(RPX_URL_SCHEME.RPX_SERVER.'/api/v2/get_contacts', $rpx_post_array);
    if ($rpx_reply !== false) {
      $rpx_contacts = json_decode($rpx_reply,true);
      if (RPX_SERIAL_CONTACTS == 'true') {
        rpx_update_user_meta($user_id, RPX_META_CONTACTS, $rpx_contacts);
      }
      return $rpx_contacts;
    }
  }
  return false;
}

function rpx_new_profile($rpx_auth_info){
  global $rpx_profile_map;
  global $rpx_wp_profile;
  if ( get_option(RPX_VERIFYNAME_OPTION) == 'true' ) {
    $rpx_profile_map['preferredUsername'] = 'blocked';
  }
  if ($rpx_auth_info['stat'] == 'ok'){
    $rpx_profile = $rpx_auth_info['profile'];
    foreach ($rpx_profile_map as $key => $value){
      if (is_array($value)){
        foreach ($value as $skey => $svalue){
          if ( !empty($rpx_profile["$key"]["$skey"]) ){
            $rpx_wp_profile["$svalue"] = $rpx_profile["$key"]["$skey"];
          }else{
            $rpx_wp_profile["$svalue"] = '';
          }
        }
      }elseif ( !empty($rpx_profile["$key"]) ){
        $rpx_wp_profile["$value"] = $rpx_profile["$key"];
      }else{
        $rpx_wp_profile["$value"] = '';
      }
    }
    return true;
  }
  return false;
}

function rpx_new_wp_user(){
  global $rpx_wp_user_map;
  global $rpx_wp_profile;
  global $rpx_wp_user;
  foreach ($rpx_wp_user_map as $key => $value){
    if ( !empty($rpx_wp_profile["$key"]) ){
      $rpx_wp_user["$value"] = $rpx_wp_profile["$key"];
    }
  }
  return true;
}

function rpx_validate_user($email='',$username=''){
  global $rpx_wp_profile;
  if ( empty($email) && !empty($rpx_wp_profile['rpx_email']) ){
    $email = $rpx_wp_profile['rpx_email'];
  }
  if ( empty($username) && !empty($rpx_wp_profile['rpx_username']) ){
    $username = $rpx_wp_profile['rpx_username'];
  }
  if ( !empty($email) ) {
    if ( !empty($username) ) {
      if (RPX_IS_WPMU === true){
        $wpmutest = wpmu_validate_user_signup($username, $email);
        if (is_wp_error($wpmutest)){
          $errors = $wpmutest->get_error_messages();
          /* convert wp errors into rpx messages here */
          return false;
        }else{
          return true;
        }
      }else{
        $wpuser = username_exists($username);
        if ($wpuser == NULL){
          $wpuser = false;
        }else{
          $wpuser = true;
        }
        $wpemail = email_exists($email);
        $wptest = is_email($email);
        if ($wptest == $email){
          $wptest = true;
        }else{
          $wptest = false;
        }
        if ($wpuser === false && $wpemail === false && $wptest === true){
          return true;
        }
        return false;
      }
    }else{
      $wptest = is_email($email);
      if ($wptest == $email){
        return true;
      }else{
        return false;
      }
    }
  }
}

function rpx_get_message($rpx_auth_info){
  if ($rpx_auth_info['stat'] == 'fail'){
    $message = $rpx_auth_info['err']['msg'];
  }else{
    $message = 'Message missing.';
  }
  return $message;
}

function rpx_wp_signon() {
  global $rpx_wp_profile;
  if ($rpx_wp_profile['rpx_wp_id'] == 1 && RPX_BLOCK_ADMIN == 'true') {
    return false;
  }
  $user = get_userdata($rpx_wp_profile['rpx_wp_id']);
  $username = $user->user_login;
  if ( is_a($user, 'WP_User') ) {
    return $user;
  }
  if ( empty($username) ) {
    rpx_message('The username field is empty.', 'message');
    return false;
  }
  if ( is_multisite() ) {
    if ( 1 == $user->spam){
      rpx_message('Your account has been marked as a spammer.', 'message');
      return false;
    }
    if ( !is_super_admin( $user->ID ) && isset($user->primary_blog) ) {
      $details = get_blog_details( $user->primary_blog );
      if ( is_object( $details ) && $details->spam == 1 ){
        rpx_message('Site Suspended.', 'message');
        return false;
      }
    }
  }
  return $user;
}

function rpx_get_wpid() {
  global $wpdb;
  global $rpx_wp_profile;
  if ( empty($rpx_wp_profile['rpx_identifier']) ){
    rpx_message('Empty ID', 'debug');
    return false;
  }
  $sql = 'SELECT user_id FROM '.$wpdb->usermeta.' WHERE meta_key = %s AND meta_value = %s';
  $sql = $wpdb->prepare($sql, RPX_META_IDENTIFIER, addslashes($rpx_wp_profile['rpx_identifier']));
  $result = $wpdb->get_var($sql);
  if ($result != NULL){
    if ( !empty($result) && $result != false){
      $rpx_wp_profile['rpx_wp_id'] = $result;
      return true;
    }
  }
  rpx_message('No user found.', 'debug');
  return false;
}

function rpx_get_wpuser() {
  global $rpx_wp_profile;
  global $rpx_wp_user_map;
  if ( !empty($rpx_wp_profile['rpx_wp_id']) ) {
    $user = get_userdata($rpx_wp_profile['rpx_wp_id']);
  } else {
    $user = get_currentuserinfo();
  }
  foreach ($rpx_wp_user_map as $key => $val){
    if ( empty($rpx_wp_profile[$key]) && !empty($user->$val) ){
      $rpx_wp_profile[$key] = $user->$val;
    }
  }
}

function rpx_clean_locked() {
  $cleanup_age = RPX_CLEANUP_AGE;
  settype($cleanup_age, 'int');
  global $wpdb;
  $count = 0;
  $sql = 'SELECT user_id FROM '.$wpdb->usermeta.' WHERE meta_key = %s AND meta_value = %s';
  $sql = $wpdb->prepare($sql, RPX_META_LOCKED, 'true');
  $result = $wpdb->get_col($sql);
  foreach ($result as $key=>$val){
    $rpx_clean_meta = false;
    if ($val != NULL){
      if ($val > 1){
        $sql = 'SELECT UTC_TIMESTAMP() FROM '.$wpdb->usermeta;
        $sql = $wpdb->prepare($sql);
        $sqlnow = $wpdb->get_var($sql);
        $user = get_userdata($val);
        if (strlen($user->rpx_session) > 1){
          $sqlnow = strtotime($sqlnow);
          $usertime = $user->user_registered;
          $usertime = strtotime($usertime);
          $user_aged = 0;
          if ($sqlnow >= $usertime && $usertime >= 0){
            $user_aged = $sqlnow - $usertime;
            $user_aged = $user_aged / 60;
          }
          if ($user_aged > RPX_CLEANUP_AGE){
            if(strpos($user->user_email,$user->rpx_session) === false){//test if the email address contains the session_id
              $rpx_clean_meta = true;
            }else{
              wp_delete_user($val);
              $count++;
              $rpx_clean_meta = false;
            }
          }
        }
      }
      if ($rpx_clean_meta === true){
        $sql = 'DELETE FROM '.$wpdb->usermeta.' WHERE user_id = %d AND meta_key LIKE %s';
        $sql = $wpdb->prepare($sql, $val, 'rpx_%%');
        $del_result = $wpdb->query($sql);
        $count++;
      }
    }
  }
  rpx_message('Cleaned '.$count.' users.', 'message');
  return true;
}

function rpx_session_identifier() {
  global $wpdb;
  global $rpx_wp_profile;
  global $rpx_http_vars;
  $sql = 'SELECT user_id FROM '.$wpdb->usermeta.' WHERE meta_key = \''.RPX_META_SESSION.'\' AND meta_value = %s';
  $sql = $wpdb->prepare($sql, $rpx_http_vars['rpx_session']);
  $result = $wpdb->get_var($sql);
  if ($result != NULL){
    $rpx_wp_profile['rpx_wp_id'] = $result;
    if ($rpx_wp_profile['rpx_identifier'] = rpx_get_user_meta($result, RPX_META_IDENTIFIER)){
      return true;
    }
    rpx_message('identifier not found', 'debug');
    return false;
  }
  rpx_message('session not found', 'debug');
  return false;
}

function rpx_get_meta() {/*no point in trying to catch errors since empty fields return false*/
  global $rpx_wp_profile;
  $rpx_wp_profile['rpx_identifier'] = @rpx_get_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_IDENTIFIER);
  $rpx_wp_profile['rpx_provider'] = @rpx_get_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_PROVIDER);
  $rpx_wp_profile['rpx_session'] = @rpx_get_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_SESSION);
  $rpx_wp_profile['rpx_locked'] = @rpx_get_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_LOCKED);
  $rpx_wp_profile['rpx_photo'] = @rpx_get_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_PHOTO);
  $rpx_wp_profile['rpx_url'] = @rpx_get_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_URL);
}

function rpx_update_meta() {/*Wordpress uses update for insert and update.*/
  global $rpx_wp_profile;
  $results = array();
  $results[] = rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_IDENTIFIER, $rpx_wp_profile['rpx_identifier']);
  $results[] = rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_PROVIDER, $rpx_wp_profile['rpx_provider']);
  $results[] = rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_SESSION, $rpx_wp_profile['rpx_session']);
  $results[] = rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_LOCKED, $rpx_wp_profile['rpx_locked']);
  $results[] = rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_PHOTO, $rpx_wp_profile['rpx_photo']);
  $results[] = rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_URL, $rpx_wp_profile['rpx_url']);
  if (in_array(false,$results)){
    return false;
  }
  return true;
}

function rpx_update_user_meta($wp_id, $meta_label, $value) {/*wrapper for Wordpress update to avoid returning false on updates that match current values*/
  $result = rpx_get_user_meta($wp_id, $meta_label);
  if ($result == $value){
    return true;
  }
  if(update_user_meta($wp_id, $meta_label, $value, $result) === false){
    rpx_message('Meta update failed', 'error');
    return false;
  }
  return true;
}

function rpx_get_user_meta($wp_id, $meta_label, $single = true) {/*wrapper to make single result the default*/
  $result = get_user_meta($wp_id, $meta_label, $single);
  return $result;
}

function rpx_eula_user(){
  global $rpx_wp_profile;
  rpx_update_user_meta($rpx_wp_profile['rpx_wp_id'], RPX_META_EULA, 'true');
}

function rpx_lock_user(){
  global $rpx_wp_profile;
  $rpx_wp_profile['rpx_locked'] = 'true';
}

function rpx_unlock_user(){
  global $rpx_wp_profile;
  $rpx_wp_profile['rpx_locked'] = 'false';
}

function rpx_new_session(){
  global $rpx_wp_profile;
  $rpx_wp_profile['rpx_session'] = uniqid('rpx_',true);
}

function rpx_placeholder_email(){
  global $rpx_wp_profile;
  $rpx_wp_profile['rpx_email'] = $rpx_wp_profile['rpx_session'].'@'.get_option(RPX_REALM_OPTION);
}

function rpx_set_redirect($url=''){
  global $rpx_http_vars;
  $this_url = (!empty($_SERVER['HTTPS'])) ? 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
  $url_parts = parse_url($this_url);
  $this_clean_url = $url_parts['scheme'] . '://';
  $this_clean_url .= $url_parts['host'];
  $this_clean_url .= $url_parts['path'];
  if ( !empty($url) ){
    $rpx_http_vars['redirect_to'] = strip_tags($url);
  }
  if ( empty($rpx_http_vars['redirect_to']) ){
    if (get_post_type() != false){
      $rpx_http_vars['redirect_to'] = get_permalink();
      $rpx_http_vars['redirect_to'] .= '#respond';
    }else{
      $rpx_http_vars['redirect_to'] = $this_clean_url;
    }
  }
  if ( empty($rpx_http_vars['redirect_to']) ) {
    $rpx_http_vars['redirect_to'] = get_bloginfo('url');
  }
}

function rpx_set_action($action){
  global $rpx_http_vars;
  if ( !empty($action) ){
    $rpx_http_vars['action'] = $action;
  }
}

function rpx_message($message, $class='message') {
  global $rpx_messages;
  if (RPX_VERBOSE == 'true'){
    error_log('WP-RPX '.$class.'='.$message);/*ouput all messages to log*/
  }
  $rpx_messages[] = array( 'message' => $message, 'class' => $class);
}

function puke_die($var=''){/*This is a debug function, it is never called in relased code*/
  global $rpx_http_vars;
  global $rpx_wp_profile;
  global $rpx_wp_user;
  global $rpx_messages;
  echo '<pre>';
  echo '$_REQUEST
  '; var_dump($_REQUEST);
    echo '$rpx_http_vars
  '; var_dump($rpx_http_vars);
    echo '$rpx_wp_profile
  '; var_dump($rpx_wp_profile);
    echo '$rpx_wp_user
  '; var_dump($rpx_wp_user);
    echo '$rpx_messages
  '; var_dump($rpx_messages);
  var_dump($var);
  echo '</pre>';
  exit;
}

?>
