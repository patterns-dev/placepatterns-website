<?php
//OPTIONS
define('RPX_API_KEY_OPTION',               'rpx_api_key_option');
define('RPX_REALM_OPTION',                 'rpx_realm_option');
define('RPX_REALM_SCHEME',                 'rpx_realm_scheme');
define('RPX_APP_ID_OPTION',                'rpx_app_id_option');
define('RPX_ADMIN_URL_OPTION',             'rpx_admin_url_option');
define('RPX_VEMAIL_OPTION',                'rpx_vemail_option');
define('RPX_VEMAIL_OPTION_DEFAULT',        'false');
define('RPX_SOCIAL_OPTION',                'rpx_social_option');
define('RPX_SOCIAL_OPTION_DEFAULT',        'false');
define('RPX_SOCIAL_COMMENT_OPTION',        'rpx_social_comment_option');
define('RPX_SOCIAL_COMMENT_OPTION_DEFAULT','false');
define('RPX_SOCIAL_PUB',                   'rpx_social_pub');
define('RPX_S_LOC_OPTION',                 'rpx_s_loc_option');
define('RPX_S_LOC_OPTION_DEFAULT',         'top');
define('RPX_S_STYLE_OPTION',               'rpx_share_style_option');
define('RPX_S_STYLE_OPTION_DEFAULT',       'icon');
define('RPX_S_TXT_OPTION',                 'rpx_share_txt_option');
define('RPX_S_TXT_OPTION_DEFAULT',         'share');
define('RPX_COMMENT_OPTION',               'rpx_comment_login_action');
define('RPX_COMMENT_OPTION_DEFAULT',       '');
define('RPX_HTTP_OPTION',                  'rpx_http_option');
define('RPX_HTTP_OPTION_DEFAULT',          'wp_http');
define('RPX_SSL_VALID_OPTION',             'rpx_ssl_valid_option');
define('RPX_SSL_VALID_OPTION_DEFAULT',     'true');
define('RPX_AUTOREG_OPTION',               'rpx_autoreg_option');
define('RPX_AUTOREG_OPTION_DEFAULT',       'false');
define('RPX_VERIFYNAME_OPTION',            'rpx_verifyname_option');
define('RPX_VERIFYNAME_OPTION_DEFAULT',    'false');
define('RPX_PROVIDERS_OPTION',             'rpx_providers_option');
define('RPX_AVATAR_OPTION',                'rpx_avatar_option');
define('RPX_AVATAR_OPTION_DEFAULT',        'false');
define('RPX_STRINGS_OPTION',               'rpx_strings_option');
define('RPX_ADVANCED_OPTION',              'rpx_advanced_option');
define('RPX_PARAMS_OPTION',                'rpx_add_params');
define('RPX_PARAMS_OPTION_DEFAULT',        '');
define('RPX_REMOVABLE_OPTION',             'rpx_removable_option');
define('RPX_REMOVABLE_OPTION_DEFAULT',     'false');
define('RPX_SHARE_COUNT_OPTION',           'rpx_share_count_option');
define('RPX_SHARE_COUNT_OPTION_DEFAULT',   'false');
define('RPX_SIGNIN_OPTION',                'rpx_signin_option');
define('RPX_SIGNIN_OPTION_DEFAULT',        'true');
define('RPX_WPLOGIN_OPTION',               'rpx_wplogin_option');
define('RPX_WPLOGIN_OPTION_DEFAULT',       'true');
define('RPX_NEW_WIDGET_OPTION',            'rpx_new_widget_option');
define('RPX_NEW_WIDGET_OPTION_DEFAULT',    'true');
define('RPX_NONCE',                        'rpx_nonce');
define('RPX_CSRF_TOKEN',                   'rpx_csrf_token');
define('RPX_SERVER',                       'rpxnow.com');
define('RPX_API_PATH',                     '/api/v2/');
define('RPX_IMAGE_PATH',                   '/rpx/images/');
define('RPX_FILES_PATH',                   '/rpx/files/');
define('RPX_MENU_SLUG',                    'JUMP');
define('RPX_MENU_PARENT',                  'Janrain Engage');
define('RPX_OPTIONS_TITLE',                'Janrain Engage Options');
define('RPX_MENU_LABEL',                   'Janrain Engage');
define('RPX_MENU_MAIN',                    'Setup');
define('RPX_OPTIONS_ROLE',                 'administrator');
define('RPX_STRING_OPTIONS_TITLE',         'Janrain Engage Strings');
define('RPX_STRING_MENU_LABEL',            'Strings');
define('RPX_STRING_MENU_SLUG',             'JUMPstrings');
define('RPX_HELP_OPTIONS_TITLE',           'Janrain Engage Help');
define('RPX_HELP_MENU_LABEL',              'Docs + Help');
define('RPX_HELP_MENU_SLUG',               'JUMPhelp');
define('RPX_ADVANCED_OPTIONS_TITLE',       'Janrain Engage Expert');
define('RPX_ADVANCED_MENU_LABEL',          'Expert');
define('RPX_ADVANCED_MENU_SLUG',           'JUMPexpert');
define('RPX_TOKEN_ACTION',                 'rpx_token');
define('RPX_TOKEN_ACTION_NAME',            'rpx_token');
define('RPX_REGISTER_FORM_ACTION',         'rpx_register');
define('RPX_REGISTER_FORM_ACTION_NAME',    'rpx_register');
define('RPX_REMOVE_ACTION',                'rpx_remove');
define('RPX_REMOVE_ACTION_NAME',           'rpx_remove');
define('RPX_DATA_MODE_ACTION',             'rpx_data');
define('RPX_DATA_MODE_ACTION_NAME',        'rpx_data');
define('RPX_DATA_MODE_FINISH_ACTION',      'rpx_data_finish');
define('RPX_TOKEN_URL',                    str_replace('./','/',site_url()));//Strip trailing dot. (dot terminated FQDN is currently unsupported)
define('RPX_META_IDENTIFIER',              'rpx_identifier');
define('RPX_META_PROVIDER',                'rpx_provider');
define('RPX_META_LOCKED',                  'rpx_locked');
define('RPX_META_SESSION',                 'rpx_session');
define('RPX_META_URL',                     'rpx_url');
define('RPX_META_PHOTO',                   'rpx_photo');
define('RPX_META_PROFILE',                 'rpx_serial_profile');
define('RPX_META_CONTACTS',                'rpx_serial_contacts');
define('RPX_META_EULA',                    'rpx_eula');
define('RPX_COMMENT_PROMPT_OPTION',        'rpx_comment_prompt_option');
define('RPX_SIGNIN_SETUP_URL',             '/setup_providers#steps');
define('RPX_SOCIAL_SETUP_URL',             '/social_publishing_2#steps');
define('RPX_SETTINGS_SETUP_URL',           '/settings');
define('RPX_IS_WPMU',                      WP_ALLOW_MULTISITE);
define('RPX_BP_REG_PATH',                  'register');
define('RPX_WP_REG_PATH',                  'wp-login.php?action=register');
define('RPX_PLUGIN_HELP_URL',              'https://support.janrain.com/forums/232466-wordpress-plugin-q-a');
define('RPX_PLUGIN_HELP_RSS',              RPX_PLUGIN_HELP_URL.'/posts.rss');
define('RPX_POST_META_COUNTS',             'rpx_share_counts');
define('RPX_POST_META_URLS',               'rpx_share_urls');
define('RPX_BUTTONS_STYLE_SMALL',          'small');
define('RPX_BUTTONS_STYLE_LARGE',          'large');

//PROFILE MAP
$rpx_profile_map = array();
$rpx_profile_map['identifier']         ='rpx_identifier';
$rpx_profile_map['providerName']       ='rpx_provider';
$rpx_profile_map['email']              ='rpx_email';
$rpx_profile_map['verifiedEmail']      ='rpx_verifiedEmail';
$rpx_profile_map['preferredUsername']  ='rpx_username';
$rpx_profile_map['displayName']        ='rpx_displayname';
$rpx_profile_map['name']['givenName']  ='rpx_given';
$rpx_profile_map['name']['familyName'] ='rpx_family';
$rpx_profile_map['name']['formatted']  ='rpx_realname';
$rpx_profile_map['photo']              ='rpx_photo';
$rpx_profile_map['url']                ='rpx_url';
$rpx_profile_map['gender']             ='rpx_gender';
$rpx_profile_map['birthday']           ='rpx_birthday';
$rpx_profile_map['utcOffset']          ='rpx_utcOffset';

$rpx_wp_user_map = array();
$rpx_wp_user_map['rpx_username']    = 'user_login';
$rpx_wp_user_map['rpx_url']         = 'user_url';
$rpx_wp_user_map['rpx_email']       = 'user_email';
$rpx_wp_user_map['rpx_displayname'] = 'display_name';
$rpx_wp_user_map['rpx_given']       = 'first_name';
$rpx_wp_user_map['rpx_family']      = 'last_name';

//MESSAGE MAP
$rpx_message_map['stat']        ='rpx_stat';
$rpx_message_map['limitedData'] ='rpx_limited';
$rpx_message_map['err']['msg']  ='rpx_error';
$rpx_message_map['err']['code'] ='rpx_code';

//PROVIDER LIST
$rpx_providers = array();
$rpx_providers['Facebook']     = 'facebook';
$rpx_providers['Google']       = 'google';
$rpx_providers['GoogleApps']   = 'google';
$rpx_providers['LinkedIn']     = 'linkedin';
$rpx_providers['MySpace']      = 'myspace';
$rpx_providers['Twitter']      = 'twitter';
$rpx_providers['Windows Live'] = 'live_id';
$rpx_providers['Yahoo!']       = 'yahoo';
$rpx_providers['AOL']          = 'aol';
$rpx_providers['Blogger']      = 'blogger';
$rpx_providers['Flickr']       = 'flickr';
$rpx_providers['Hyves']        = 'hyves';
$rpx_providers['LiveJournal']  = 'livejournal';
$rpx_providers['MyOpenID']     = 'myopenid';
$rpx_providers['Netlog']       = 'netlog';
$rpx_providers['OpenID']       = 'openid';
$rpx_providers['Verisign']     = 'verisign';
$rpx_providers['Wordpress']    = 'wordpress';
$rpx_providers['PayPal']       = 'paypal';
$rpx_providers['Orkut']        = 'orkut';
$rpx_providers['VZN']          = 'vzn';
$rpx_providers['Salesforce']   = 'salesforce';
$rpx_providers['Foursquare']   = 'foursquare';

//HTTP VARS
$rpx_http_vars = array();
$rpx_http_vars['rpx_identifier'] ='';
$rpx_http_vars['rpx_provider']   ='';
$rpx_http_vars['rpx_username']   ='';
$rpx_http_vars['rpx_email']      ='';
$rpx_http_vars['rpx_redirect']   ='';
$rpx_http_vars['rpx_error']      ='';
$rpx_http_vars['rpx_message']    ='';
$rpx_http_vars['rpx_install']    ='';
$rpx_http_vars['rpx_remove']     ='';
$rpx_http_vars['rpx_config']     ='';
$rpx_http_vars['rpx_attach']     ='';
$rpx_http_vars['rpx_update']     ='';
$rpx_http_vars['rpx_session']    ='';
$rpx_http_vars['action']         ='';
$rpx_http_vars['token']          ='';
$rpx_http_vars['user_email']     ='';
$rpx_http_vars['user_name']      ='';
$rpx_http_vars['redirect_to']    ='';
$rpx_http_vars['rpx_cleanup']    ='';
$rpx_http_vars['rpx_collect']    ='';
$rpx_http_vars['rpx_icons']      ='';
$rpx_http_vars['rpx_eula']       ='';
$rpx_http_vars['rpx_data']       ='';

foreach ($rpx_http_vars as $parm => $value){
  $rpx_parm = urldecode(strip_tags($_REQUEST[$parm]));
  if ( !empty($rpx_parm) ){
    $rpx_http_vars[$parm] = $rpx_parm;
  }
}

if ( empty($rpx_http_vars['redirect_to']) ){
    if ( !empty($redirect_to) ){
      $redirect_to = $rpx_http_vars['redirect_to'];
    }
}

//COMMENT FORM LOGIN ACTIONS
$rpx_comment_actions = array();
$rpx_comment_actions['comment_form_before *']                 = 'comment_form_before';
$rpx_comment_actions['comment_form_top *']                    = 'comment_form_top';
$rpx_comment_actions['comment_form']                          = 'comment_form';
$rpx_comment_actions['comment_form_before_fields *']          = 'comment_form_before_fields';
$rpx_comment_actions['comment_form_after *']                  = 'comment_form_after';
$rpx_comment_actions['comment_form_must_log_in_after &sup1;'] = 'comment_form_must_log_in_after';


$rpx_csrf    = strip_tags($_REQUEST[RPX_CSRF_TOKEN]);
$rpx_api_key = strip_tags($_REQUEST[RPX_API_KEY_OPTION]);

$rpx_messages   = array();
$rpx_wp_profile = array();
$rpx_wp_user    = array();

$rpx_button_count = 0;


//STRINGS
$rpx_strings = array();
$rpx_strings['RPX_MESSAGE_PRE']        = array('default' => '', 'desc' => 'Added prefix text for all errors or messages created by this plugin.(debug)');
$rpx_strings['RPX_COMMENT_PROMPT']     = array('default' => 'To comment, click below to log in.', 'desc' => 'Shown along with the icon bar for comments.');
$rpx_strings['RPX_LOGIN_PROMPT']       = array('default' => 'Log in with', 'desc' => 'Shown in the widget.');
$rpx_strings['RPX_CONNECT_PROMPT']     = array('default' => 'Connect this account to your', 'desc' => 'Shown in widget to users signed in without a social provider.');
$rpx_strings['RPX_OR_LOGIN_PROMPT']    = array('default' => 'Or log in with', 'desc' => 'Shown on wp-login.php.');
$rpx_strings['RPX_OR_REGISTER_PROMPT'] = array('default' => 'Or register with', 'desc' => 'Shown in wp-login.php.');
$rpx_strings['RPX_NAME_PROMPT']        = array('default' => 'Please enter a username/screenname.[br](The name seen on comments.)[br]You will be asked to sign in a second time.', 'desc' => 'Prompt for username.');
$rpx_strings['RPX_NAME_EXISTS_REASON'] = array('default' => 'Username already taken.', 'desc' => 'Reason for username prompt. Added on duplicates.');
$rpx_strings['RPX_WIDGET_TITLE']       = array('default' => 'Janrain Engage', 'desc' => 'The title for the old widget. (deprecated)');
$rpx_strings['RPX_COMMENTED_ON']       = array('default' => 'Commented on', 'desc' => 'Verb for shared comments.');
$rpx_strings['RPX_COMMENT_LABEL']      = array('default' => 'Share:', 'desc' => 'Label for shared comments.');
$rpx_strings['RPX_SHARED']             = array('default' => 'Shared', 'desc' => 'Verb for shared posts.');
$rpx_strings['RPX_SHARE_LABEL']        = array('default' => 'Share:', 'desc' => 'Label for shared posts.');


$rpx_strings_array = get_option(RPX_STRINGS_OPTION);
foreach($rpx_strings as $key => $val) {
  if ( !empty($rpx_strings_array[$key]) ) {
    $val = $rpx_strings_array[$key];
  }else{
    $val = $val['default'];
  }
  define($key, $val);
}

//ADVANCED OPTIONS
$rpx_advanced = array();
$rpx_advanced['RPX_REMEMBER_WP_SIGNON'] = array('default' => 'true', 'desc' => 'The "Stay signed in" option.');
$rpx_advanced['RPX_BLOCK_ADMIN']        = array('default' => 'false', 'desc' => 'Completly block user ID 1.');
$rpx_advanced['RPX_DEFAULT_REDIRECT']   = array('default' => urldecode(home_url()), 'desc' => 'Used if there is no redirect_to passed.');
$rpx_advanced['RPX_VERBOSE']            = array('default' => 'false', 'desc' => 'Set this to true to get errors and actions sent to the default PHP error log.');
$rpx_advanced['RPX_CLEANUP_AGE']        = array('default' => '15', 'desc' => 'Minimum age in minutes to enable account deletion of abandoned signup (no email) by admin cleanup feature.');
$rpx_advanced['RPX_SMALL_ICONS_LIMIT']  = array('default' => '6', 'desc' => 'Maximum number of icons in the small icon button.');
$rpx_advanced['RPX_LARGE_ICONS_LIMIT']  = array('default' => '7', 'desc' => 'Maximum number of icons in the large icon button.');
$rpx_advanced['RPX_SHARE_ICON_CLASS']   = array('default' => 'rpx_size30', 'desc' => 'This is the class that determines the share icon sprite. rpx_size30/rpx_size16');
$rpx_advanced['RPX_REQUIRE_EULA']       = array('default' => 'false', 'desc' => 'Require automatically registered users to agree to EULA. Must also enable username selection.');
$rpx_advanced['RPX_EULA_URL']           = array('default' => '', 'desc' => 'URL for EULA when required.');
$rpx_advanced['RPX_EULA_PROMPT']        = array('default' => 'Check box to agree to EULA (required).', 'desc' => 'Text to prompt visitors to agree to EULA.');
$rpx_advanced['RPX_SERIAL_PROFILE']     = array('default' => 'false', 'desc' => 'Set to true to store the full profile in a serialized usermeta each signon (RPX_META_PROFILE).');
$rpx_advanced['RPX_AUTH_INFO_EXTENDED'] = array('default' => 'false', 'desc' => 'PLUS/PRO/ENT Set to true to return auth_info extended this data could be used by other plugins or custom themes.');
$rpx_advanced['RPX_SERIAL_CONTACTS']    = array('default' => 'false', 'desc' => 'PLUS/PRO/ENT Set to true to store the get_contacts in a serialized usermeta (RPX_META_CONTACTS) each time it succeeds.');
$rpx_advanced['RPX_GET_CONTACTS']       = array('default' => 'false', 'desc' => 'PLUS/PRO/ENT (not for production use) Set to true to collect the get_contacts data each sign on. This is slow.(populates $rpx_contacts global)');

$rpx_advanced_array = get_option(RPX_ADVANCED_OPTION);
foreach($rpx_advanced as $key => $val) {
  if ( !empty($rpx_advanced_array[$key]) ) {
    $val = $rpx_advanced_array[$key];
  }else{
    $val = $val['default'];
  }
  define($key, $val);
}

?>
