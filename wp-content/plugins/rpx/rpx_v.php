<?php
// [rpxshare link="permalink" title="the_title" style="icon" label="option label" comment="false" imgsrc="img url"]summary[/rpxshare]
function rpxshare_shortcode($atts, $content=null) {
  $output = '';
  $rpx_social_option = get_option(RPX_SOCIAL_OPTION);
  $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
  if ($rpx_social_option !== 'true' || empty($rpx_social_pub) ){
    return $output;
  }
  $att_def = array();
  $att_def['link']    = '';
  $att_def['title']   = '';
  $att_def['style']   = 'icon';
  $att_def['label']   = '';
  $att_def['comment'] = 'false';
  $att_def['imgsrc']  = '';
  $attributes = shortcode_atts($att_def, $atts);
  if ($attributes['comment'] == 'true') {
    $attributes['comment'] = true;
  } else {
    $attributes['comment'] = false;
  }
  if ( empty($content) ) {
    $content = '';
  }
  $output = rpx_social_share($content, $attributes['comment'], $attributes['style'], $attributes['label'], $attributes['title'], $attributes['link'], $attributes['imgsrc']);
  if ( empty($output) ) {
    $output = '';
  }
  return $output;
}
// [rpxlogin redirect="http://www.janrain.com" prompt="Cool Link" style="large" autohide="true" datamode="false"]
function rpxlogin_shortcode($atts, $content=null) {
  global $rpx_widget_options;
  $output = '';
  $att_def = array();
  $att_def['redirect'] = '';
  $att_def['prompt']   = '';
  $att_def['style']    = 'small';
  $att_def['autohide'] = 'false';
  $att_def['datamode'] = 'false';
  $attributes = shortcode_atts($att_def, $atts);
  if ( $attributes['autohide'] === 'true' ) {
    if ( is_user_logged_in() ) {
      return '';
    }
  }
  if ( $attributes['datamode'] === 'true' ) {
    rpx_set_action(RPX_DATA_MODE_ACTION);
    $action = RPX_DATA_MODE_ACTION;
  }
  if ( !empty($attributes['redirect']) ) {
    rpx_set_redirect($attributes['redirect']);
  }
  $output = rpx_buttons($attributes['style'], $attributes['prompt'], $attributes['redirect'], $action);
  if ( empty($output) ) {
    $output = '';
  }
  return $output;
}

// [rpxdata fetch="rpx_photo" default=""]
function rpxdata_shortcode($atts, $content=null) {
  global $rpx_wp_profile;
  global $current_user;
  wp_get_current_user();
  $output = '';
  $att_def = array();
  $att_def['fetch'] = '';
  $att_def['default'] = '';
  $attributes = shortcode_atts($att_def, $atts);
  if ( !empty($attributes['default']) ) {
    $output = $attributes['default'];
  }
  $fetch = $attributes['fetch'];
  if ( !empty($current_user->ID) ) {
    $output = get_user_meta($current_user->ID, $fetch, true);
  }
  if ( !empty($rpx_wp_profile[$fetch]) ) {
    $fetch_data = $rpx_wp_profile[$fetch];
    if ( !is_array($fetch_data) ) {
      $output = $fetch_data;
    }
  }
  if ( empty($output) ) {
    $output = '';
  }
  return $output;
}

// [rpxwidget redirect="http://www.janrain.com" title="Social" prompt="Connect" style="small" hide="false" avatar="true"]
function rpxwidget_shortcode($atts, $content=null) {
  $output = '';
  $att_def = array();
  $att_def['redirect']    = '';
  $att_def['title']   = '';
  $att_def['prompt']   = '';
  $att_def['style']   = 'small';
  $att_def['hide'] = 'false';
  $att_def['avatar'] = 'true';
  $attributes = shortcode_atts($att_def, $atts);
  if ( !empty($attributes['redirect']) ) {
    rpx_set_redirect($attributes['redirect']);
  }
  $user_data = rpx_user_data();
  if ($attributes['hide'] == 'false') {
    $title = $attributes['title'];
    if ( !empty( $title ) ) {
      $output .= '<h4>'.$title.'</h4>';
    }
  }
  if ($user_data != false && isset($user_data->rpx_provider) && $attributes['hide'] == 'false'){
    global $rpx_http_vars;
    if ($attributes['avatar'] == 'true') {
      $rpx_user_icon = rpx_user_provider_icon();
      $avatar = '';
      if ( !empty($user_data->rpx_photo) ) {
        $avatar = '<img class="rpx_sidebar_avatar" src="'.$user_data->rpx_photo.'">';
      }else{
        $avatar = '<div class="rpx_sidebar_avatar">&nbsp;</div>';
      }
      $output .= '<div class="rpx_user_icon">'."\n";
      $output .=  $avatar;
      $output .=  $rpx_user_icon;
      $output .= '</div>'."\n";
    }
    $output .= '<a href="'.wp_logout_url( $rpx_http_vars['redirect_to'] ).'" title="Logout">Log out</a>';
  }
  if ($user_data != false && !isset($user_data->rpx_provider) ){
    if ( empty($attributes['prompt']) ){
      $attributes['prompt'] = RPX_CONNECT_PROMPT;
    }
    $output .= rpx_buttons($attributes['style'], $attributes['prompt'], $attributes['redirect']);
  }
  if ($user_data == false) {
    if ( empty($attributes['prompt']) ){
      $attributes['prompt'] = RPX_LOGIN_PROMPT;
    }
    $output .= rpx_buttons($attributes['style'], $attributes['prompt'], $attributes['redirect']);
  }
  if ( empty($output) ) {
    $output = '';
  }
  return $output;
}

// [rpxavatar badge="true" name="true"]
function rpxavatar_shortcode($atts, $content=null) {
  $output = '';
  $att_def = array();
  $att_def['badge'] = 'true';
  $att_def['name'] = 'true';
  $attributes = shortcode_atts($att_def, $atts);
  if ($attributes['name'] == 'true') {
    $with_name = true;
  }else{
    $with_name = false;
  }
  $user_data = rpx_user_data();
  $rpx_user_icon = rpx_user_provider_icon(NULL, $with_name);
  if ( $rpx_user_icon !== false && $user_data != false){
    $avatar = '';
    if ( !empty($user_data->rpx_photo) ){
      $avatar = '<img class="rpx_sidebar_avatar" src="'.$user_data->rpx_photo.'">';
    }else{
      $avatar = '<div class="rpx_sidebar_avatar">&nbsp;</div>';
    }
    if ($attributes['badge'] === 'true'){
      $output .= '<div class="rpx_user_icon">'."\n";
      $output .=  $avatar;
      $output .=  $rpx_user_icon;
      $output .= '</div>'."\n";
    }
  }
  return $output;
}

// [rpxuser]Content shown for found Engage user. Supports shortcodes and keywords below.[/rpxuser]
// {provider} = Name of connected provider.
// {username} = Current Wordpress display name or user name.
// {givenname} = Recorded first name.
// {familyname} = Recorded last name.
// {avatarurl} = Collected avatar URL.
// {profileurl} = Collected profile URL.
// Empty values return as empty.
// Provider and username should never be empty.
function rpxuser_shortcode($atts=null, $content=null){
  $output = '';
  if ( empty($content) ){
    $content = '';
  }
  $keywords = array();
  $keywords['provider'] = 'rpx_provider';
  $keywords['username'] = 'display_name';
  $keywords['username'] = 'user_login';
  $keywords['givenname'] = 'user_firstname';
  $keywords['familyname'] = 'user_lastname';
  $keywords['avatarurl'] = 'rpx_photo';
  $keywords['profileurl'] = 'rpx_url';
  $keywords['profileurl'] = 'user_url';
  $user_data = rpx_user_data();
  if ( !empty($user_data) && isset($user_data->rpx_provider)){
    foreach ($keywords as $key=>$property){
      if(isset($user_data->$property)){
        $keyword = '{'.$key.'}';
        $replacement = $user_data->$property;
        $content = str_replace($keyword, $replacement, $content);
      }
    }
    foreach ($keywords as $key=>$property){
      $keyword = '{'.$key.'}';
      $replacement = '';
      $content = str_replace($keyword, $replacement, $content);
    }
    $output = do_shortcode($content);
  }
  return $output;
}

// [rpxnotuser]Content shown if Engage user not found. Supports shortcodes.[/rpxnotuser]
function rpxnotuser_shortcode($atts=null, $content=null){
  $output = '';
  if ( empty($content) ){
    $content = '';
  }
  $user_data = rpx_user_data();
  if ( empty($user_data) || ( !empty($user_data) && !isset($user_data->rpx_provider) )) {
    $output = do_shortcode($content);
  }
  return $output;
}

function rpx_js_escape($string){
  if ( empty($string) ){
    $string = ' ';
  }
  $string = strip_tags($string);
  $string = str_replace("\\",' ',$string);
  $string = str_replace("\0",' ',$string);
  $string = str_replace("\n",' ',$string);
  $string = str_replace("\r",' ',$string);
  $string = str_replace('  ',' ',$string);
  $string = str_replace('"','&quot;',$string);
  $string = str_replace("'",'&apos;',$string);
  $string = strip_shortcodes($string);
  return $string;
}

function rpx_inline_stylesheet(){

?>
<style type="text/css">
/* Janrain Engage plugin dynamic CSS elements */
.rpx_counter {
  background-image:url('<?php echo RPX_IMAGE_URL; ?>bubble-32.png');
}
.rpx_ct_total {
  background-image:url('<?php echo RPX_IMAGE_URL; ?>bubble-short.png');
}
.rpx_size30 {
  background-image:url('<?php echo RPX_IMAGE_URL; ?>rpx-icons30.png');
}
.rpx_size16 {
  background-image:url('<?php echo RPX_IMAGE_URL; ?>rpx-icons16.png');
}
</style>
<?php
}

function rpx_inline_javascript(){
  global $rpx_inline_js_done;
  if ($rpx_inline_js_done === true) {
    return false;
  }
  $rpx_social_option = get_option(RPX_SOCIAL_OPTION);
  $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
  if ($rpx_social_option == 'true' || !empty($rpx_social_pub) ){
?>
<script type="text/javascript">//<!--
function rpxWPsocial (rpxLabel, rpxSummary, rpxLink, rpxLinkText, rpxComment, rpxImageSrc, rpxPostID, rpxElement){
  if (typeof console != 'object') {
    //Dummy console log.
    var console = new Object();
    console.data = new Array();
    console.log = function(err) {
      this.data.push(err);
    }
  }
  RPXNOW.init({appId: '<?php echo get_option(RPX_APP_ID_OPTION); ?>', xdReceiver: '<?php echo RPX_PLUGIN_URL; ?>rpx_xdcomm.html'});
  RPXNOW.loadAndRun(['Social'], function () {
    var activity = new RPXNOW.Social.Activity(
       rpxLabel,
       rpxLinkText,
       rpxLink);
    activity.setUserGeneratedContent(rpxComment);
    activity.setDescription(rpxSummary);
    if (document.getElementById('rpxshareimg') != undefined && (rpxImageSrc == '' || rpxImageSrc == null)) {
      rpxImageSrc = document.getElementById('rpxshareimg').src;
    }
    if (rpxImageSrc != '' && rpxImageSrc != null) {
      var shareImage = new RPXNOW.Social.ImageMediaCollection();
      shareImage.addImage(rpxImageSrc,rpxLink);
      activity.setMediaItem(shareImage);
    }
    RPXNOW.Social.publishActivity(activity,
      {finishCallback:function(data){
        var rpxSharePost = new Array();
        var rpxShareParams = new Array();
        for (i in data) {
          try {
            var theData = data[i];
            if (theData.success == true && (rpxPostID != '' || rpxPostID != null)) {
              rpxSharePost[i] = new XMLHttpRequest();
              rpxSharePost[i].myData = theData;
              rpxSharePost[i].onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                  var rpxShareData = JSON.parse(this.responseText);
                  if (rpxShareData.stat == 'ok') {
                    var theDivs = rpxElement.getElementsByTagName('div');
                    var theTotal = 0;
                    var totalDiv = null;
                    for (n in theDivs) {
                      try {
                        var theDiv = theDivs[n];
                        if (typeof theDiv == 'object') {
                          var classReg = new RegExp('rpx_ct_'+this.myData.provider_name); 
                          if (theDiv.getAttribute('class').search(classReg) >= 0) {
                            var theCount = Number(theDiv.innerHTML);
                            theCount++;
                            theTotal++;
                            theDiv.innerHTML = String(theCount);
                            try {
                              rpx_showhide(theDiv);
                            } catch(err) {
                              console.log(err);
                            }
                          }
                          classReg = new RegExp('rpx_ct_total'); 
                          if (theDiv.getAttribute('class').search(classReg) >= 0) {
                            totalDiv = theDiv;
                          }
                        }
                      } catch(err) {
                        console.log(err);
                      }
                    }
                    if (totalDiv != null) {
                      totalDiv.innerHTML = String(Number(totalDiv.innerHTML) + theTotal);
                      totalDiv = null;
                    }
                  }
                }
              }
              rpxShareParams[i] = '?post_id='+encodeURIComponent(rpxPostID);
              rpxShareParams[i] += '&provider='+encodeURIComponent(theData.provider_name);
              rpxShareParams[i] += '&share_url='+encodeURIComponent(theData.provider_activity_url);
              rpxSharePost[i].open('GET','<?php echo RPX_PLUGIN_URL; ?>rpx_sharePost.php'+rpxShareParams[i],true);
              rpxSharePost[i].send();
            }
          } catch(err) {
            console.log(err);
          }
        }
      }
    });
  });
}
//--></script>
<?php
    if ( get_option(RPX_SHARE_COUNT_OPTION) == 'hover' ) {
?>
<script type="text/javascript">//<!--
function rpx_jquery_load() {
  if (typeof jQuery != 'undefined') {
    if (typeof $ == 'undefined') {
      $ = jQuery;
    }
    try{
      rpx_effects();
    }catch(err){
    }
    return true;
  }
  if (typeof rpx_jquery_written == 'undefined'){
    document.write("<scr" + "ipt type=\"text/javascript\" src=\"<?php echo RPX_PLUGIN_URL; ?>/files/jquery-1.6.2.min.js\"></scr" + "ipt>");
    rpx_jquery_written = true;
  }
  setTimeout('rpx_jquery_load()', 60);
  return false;
}
rpx_jquery_load();
//--></script>
<?php
    }
  }
  if ( get_option(RPX_NEW_WIDGET_OPTION) == 'true' ) {
    $realm_domain = get_option(RPX_REALM_OPTION);
    $realm = str_replace('.rpxnow.com', '', $realm_domain);
?>
<script type="text/javascript">
(function() {
    if (typeof window.janrain !== 'object') window.janrain = {};
    window.janrain.settings = {};
    
    janrain.settings.tokenUrl = '<?php echo urldecode(rpx_get_token_url()); ?>';
    janrain.settings.type = 'embed';

    function isReady() { janrain.ready = true; };
    if (document.addEventListener) {
      document.addEventListener("DOMContentLoaded", isReady, false);
    } else {
      window.attachEvent('onload', isReady);
    }

    var e = document.createElement('script');
    e.type = 'text/javascript';
    e.id = 'janrainAuthWidget';

    if (document.location.protocol === 'https:') {
      e.src = 'https://rpxnow.com/js/lib/<?php echo $realm; ?>/engage.js';
    } else {
      e.src = 'http://widget-cdn.rpxnow.com/js/lib/<?php echo $realm; ?>/engage.js';
    }

    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(e, s);
})();
</script>
<?php
  }
  $rpx_inline_js_done = true;
  return true;
}


function rpx_print_messages(){
  global $rpx_messages;
  $output = '';
  $id = 0;
  foreach ($rpx_messages as $key => $message_array){
    if ( empty($message_array['message']) ){
      $message_array['message'] = 'Empty message.';
    }
    if ( empty($message_array['class']) ){
      $message_array['class'] = 'message';
    }
    if ($message_array['class'] != 'html'){
      $message_array['class'] = htmlentities($message_array['class']);
      $message_array['message'] = nl2br(htmlentities($message_array['message']));
      $message_array['message'] = str_replace('[br]', '<br>', $message_array['message']);
    }else{
      $message_array['class'] = 'message updated';
    }
    $id++;
    $output .= '<p id="rpx_message_'.$id.'" class="'.$message_array['class'].'">'.RPX_MESSAGE_PRE.$message_array['message'].'</p>';
  }
  if ( !empty($output) ){
    echo '<div id="rpxmessage" class="message">'.$output.'</div>';
  }
}

function rpx_admin_menu_view(){
  if (current_user_can(RPX_OPTIONS_ROLE) === false) {
    return false;
  }
  global $rpx_comment_actions;
  global $rpx_http_vars;

  if ($rpx_http_vars['rpx_cleanup'] == 'true'){
    rpx_clean_locked();
  }
  $rpx_apikey = get_option(RPX_API_KEY_OPTION);
  if (strlen($rpx_apikey) != 40){
    rpx_message ('<strong>Enter valid API key.</strong><br />'
                .'Get your apiKey '
                 .'<a target="new" href="https://login.janrain.com/openid/v2/signin?token_url=https%3A%2F%2Frpxnow.com%2Ffinish">here</a>'
                 .' if you have an Engage account.<br />'
                .'View the account options '
                 .'<a target="new" href="http://www.janrain.com/products/engage/get-janrain-engage">here</a>'
                 .' to become a Janrain Engage customer.', 'html');
  }
  $rpx_test = rpx_test_api();
  $messages = array();
  $messages['select'] = 'No supported HTTP option.';
  $messages['post'] = 'Unable to send POST to Engage server.';
  $messages['api']  = 'Unable to retrive data from Engage API.';
  $messages['ssl_valid'] = 'This PHP does not support SSL certificate validation, certificate testing disabled.';
  foreach ($messages as $key=>$message){
    if ($rpx_test[$key] === false){
      rpx_message ($message, 'error');
    }
  }
?>
<style type="text/css">
.rpx_tr_sub td {
  padding-left:40px;
}
.rpx_tr_lt {
  background-color:#EEE;
}
.rpx_tr_dk {
  background-color:#DDD;
}
.rpx_td_left {
  text-align:left;
}
.rpx_note {
  float:left;
}
</style>
<script type="text/javascript">
function rpxshowhide(box,val) {
  if(document.getElementById(box).checked==true) {
    document.getElementById(val).style.visibility="visible";
  } else {
    document.getElementById(val).style.visibility="hidden";
  }
}
</script>
<div class="wrap">
<h2>Janrain Engage Setup</h2>
<?php rpx_print_messages();  echo $rpx_test['select']; ?>
<form method="post" action="options.php">
  <?php settings_fields( 'rpx_settings_group' ); ?>
  <table class="form-table">
    <tr class="rpx_tr_dk">
      <td class="rpx_td">
      <label for="rpxapikey">Engage API Key: </label>
      <input id="rpxapikey" type="text" name="<?php echo RPX_API_KEY_OPTION; ?>" style="width:40em;" value="<?php echo get_option(RPX_API_KEY_OPTION); ?>" />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr><?php
  if (strlen(get_option(RPX_API_KEY_OPTION)) == 40){?>
    <tr class="rpx_tr_lt">
      <td class="rpx_td">
      <h3>Sign-In Settings</h3>
      Setup Sign-In widget <a target="_blank" href="<?php echo get_option(RPX_ADMIN_URL_OPTION); echo RPX_SIGNIN_SETUP_URL; ?>">here</a>.<br />
      Add your site domain to the list <a target="_blank" href="<?php echo get_option(RPX_ADMIN_URL_OPTION); echo RPX_SETTINGS_SETUP_URL; ?>">here</a>.<br />
      Click save to update Engage provider icons.<?php echo rpx_small_buttons(); ?>
      </td>
      <td class="rpx_td">Expert widget options:
      <br>
      <label for="rpxparamstxt">Additional iframe URL parameters (use &amp; to separate):</label>
      <input id="rpxparamstxt" name="<?php echo RPX_PARAMS_OPTION; ?>" type="text" size=50 value="<?php
  $rpx_params_txt = get_option(RPX_PARAMS_OPTION);
  if ( empty($rpx_params_txt) ) {
    $rpx_params_txt = RPX_PARAMS_OPTION_DEFAULT;
  }
  echo $rpx_params_txt;
  ?>" />
      </td>
    </tr>
    <tr class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxsignin">Allow sign in via Engage: (This must be enabled for any Engage sign-in to work.)</label>
      <input id="rpxsignin" type="checkbox" name="<?php echo RPX_SIGNIN_OPTION; ?>" value="true"<?php if (get_option(RPX_SIGNIN_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxwidget">Enable new Engage widget:</label>
      <input id="rpxwidget" type="checkbox" name="<?php echo RPX_NEW_WIDGET_OPTION; ?>" value="true"<?php if (get_option(RPX_NEW_WIDGET_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxvemail">Allow sign in based on verifiedEmail match:</label>
      <input id="rpxvemail" type="checkbox" name="<?php echo RPX_VEMAIL_OPTION; ?>" value="true"<?php if (get_option(RPX_VEMAIL_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr id="rpx_comment_option" class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
       <span  class="rpx_note"><label for="rpxcomm">Login link for comments:</label>
       <select id="rpxcomm" name="<?php echo RPX_COMMENT_OPTION; ?>">
         <option value="none"<?php if (get_option(RPX_COMMENT_OPTION) == 'none'){ echo ' selected="selected"'; } ?>>None</option><?php
    foreach($rpx_comment_actions as $key => $val){?>
         <option value="<?php echo $val; ?>"<?php if (get_option(RPX_COMMENT_OPTION) == $val){ echo ' selected="selected"'; } ?>><?php echo $key; ?></option><?php
    }?>
       </select></span>
       <span>*&nbsp;Wordpress&nbsp;3&nbsp;themes.<br />&sup1;&nbsp;For&nbsp;&quot;registered&nbsp;and&nbsp;logged&nbsp;in&nbsp;to&nbsp;comment&quot;</span>
      </td>
      <td class="rpx_td_left">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_dk">
      <td class="rpx_td">
      <h3>Sign-In Registration</h3>
      Click <a href="?page=<?php echo RPX_MENU_SLUG; ?>&amp;rpx_cleanup=true">here</a> to remove Engage incomplete (no email) accounts older than <?php echo RPX_CLEANUP_AGE; ?> minutes old.
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_dk rpx_tr_sub">
      <td class="rpx_td"><?php
    $rpx_reg_mesg = '';
    if (get_option('users_can_register') != 1){
      $rpx_reg_mesg = '(You must enable the Wordpress General Setting for Membership "<a href="./options-general.php#users_can_register">Anyone can register</a>".)';
    }
?>
      <label for="rpxautoreg">Enable automatic user registration <?php echo $rpx_reg_mesg; ?></label>
      <input id="rpxautoreg" type="checkbox" name="<?php echo RPX_AUTOREG_OPTION; ?>" value="true"<?php if (get_option(RPX_AUTOREG_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">
      <?php if (get_option(RPX_AUTOREG_OPTION) == 'true'){ ?>
      <label for="rpxverifyname">Force users to select username:</label>
      <input id="rpxverifyname" type="checkbox" name="<?php echo RPX_VERIFYNAME_OPTION; ?>" value="true"<?php if (get_option(RPX_VERIFYNAME_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      <?php } else { ?>
      &nbsp;
      <?php } ?>
      </td>
    </tr>
    <tr class="rpx_tr_lt">
      <td class="rpx_td">
      <h3>General User Experience</h3>
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxwplogin">Show Engage sign in on wp-login page:</label>
      <input id="rpxwplogin" type="checkbox" name="<?php echo RPX_WPLOGIN_OPTION; ?>" value="true"<?php if (get_option(RPX_WPLOGIN_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxavatar">Use social provider avatars on comments:</label>
      <input id="rpxavatar" type="checkbox" name="<?php echo RPX_AVATAR_OPTION; ?>" value="true"<?php if (get_option(RPX_AVATAR_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_lt rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxremovable">Allow users to remove their Engage provider and data:</label>
      <input id="rpxremovable" type="checkbox" name="<?php echo RPX_REMOVABLE_OPTION; ?>" value="true"<?php if (get_option(RPX_REMOVABLE_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <?php
    $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
    if ( !empty($rpx_social_pub) ){?>
    <tr class="rpx_tr_dk">
      <td class="rpx_td">
      <h3>Social Sharing Settings</h3>
      Setup Social Sharing widget <a target="_blank" href="<?php echo get_option(RPX_ADMIN_URL_OPTION); echo RPX_SOCIAL_SETUP_URL; ?>">here</a>.
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr class="rpx_tr_dk rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxsocial">Enable social sharing:</label>
      <input onclick="rpxshowhide('rpxsocial','rpx_share_option');rpxshowhide('rpxsocial','rpx_share_option2');" id="rpxsocial" type="checkbox" name="<?php echo RPX_SOCIAL_OPTION; ?>" value="true"<?php if (get_option(RPX_SOCIAL_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr id="rpx_share_option" class="rpx_tr_dk rpx_tr_sub">
      <td class="rpx_td">
       <label for="rpxsloc">Share link on articles:</label>
       <select id="rpxsloc" name="<?php echo RPX_S_LOC_OPTION; ?>">
         <option value="none"<?php if (get_option(RPX_S_LOC_OPTION) == 'none'){ echo ' selected="selected"'; } ?>>None</option>
         <option value="top"<?php if (get_option(RPX_S_LOC_OPTION) == 'top'){ echo ' selected="selected"'; } ?>>at opening of article</option>
         <option value="bottom"<?php if (get_option(RPX_S_LOC_OPTION) == 'bottom'){ echo ' selected="selected"'; } ?>>at closing of article</option>
       </select>
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr>
    <tr id="rpx_share_option2" class="rpx_tr_dk rpx_tr_sub">
      <td class="rpx_td">
      <label for="rpxsoccom">Share link on comments:</label>
      <input id="rpxsoccom" type="checkbox" name="<?php echo RPX_SOCIAL_COMMENT_OPTION; ?>" value="true"<?php if (get_option(RPX_SOCIAL_COMMENT_OPTION) == 'true'){ echo ' checked="checked"'; } ?> />
      </td>
      <td class="rpx_td">
       <label for="rpxshct">Show count bubbles:</label>
       <select id="rpxshct" name="<?php echo RPX_SHARE_COUNT_OPTION; ?>">
         <option value="false"<?php if (get_option(RPX_SHARE_COUNT_OPTION) == 'false'){ echo ' selected="selected"'; } ?>>none</option>
         <option value="hover"<?php if (get_option(RPX_SHARE_COUNT_OPTION) == 'hover'){ echo ' selected="selected"'; } ?>>on mouse hover</option>
         <option value="always"<?php if (get_option(RPX_SHARE_COUNT_OPTION) == 'always'){ echo ' selected="selected"'; } ?>>always on</option>
       </select>
    </tr>
    <tr id="rpx_share_option3" class="rpx_tr_dk rpx_tr_sub">
      <td class="rpx_td">
       <label for="rpxsloc">Share link style:</label>
       <select id="rpxsloc" name="<?php echo RPX_S_STYLE_OPTION; ?>">
         <option value="icon"<?php if (get_option(RPX_S_STYLE_OPTION) == 'icon'){ echo ' selected="selected"'; } ?>>Icons</option>
         <option value="label"<?php if (get_option(RPX_S_STYLE_OPTION) == 'label'){ echo ' selected="selected"'; } ?>>Text</option>
       </select>
      </td>
      <td class="rpx_td">
        <label for="rpxsharetxt">Share label/button text (use &amp;nbsp; for none):</label>
        <input id="rpxsharetxt" name="<?php echo RPX_S_TXT_OPTION; ?>" type="text" size=30 value="<?php
  $rpx_s_txt = get_option(RPX_S_TXT_OPTION);
  if ( empty($rpx_s_txt) ) {
    $rpx_s_txt = RPX_S_TXT_OPTION_DEFAULT;
  }
  echo $rpx_s_txt;
  ?>" />
      </td>
    </tr>
    <script type="text/javascript">rpxshowhide('rpxsocial','rpx_share_option');rpxshowhide('rpxsocial','rpx_share_option2');rpxshowhide('rpxsocial','rpx_share_option3');</script><?php
    }else{?>
    <tr class="rpx_tr_dk rpx_tr_sub">
      <td class="rpx_td">
      Visit your <a href="<?php echo get_option(RPX_ADMIN_URL_OPTION); echo RPX_SOCIAL_SETUP_URL; ?>" target="_blank">Social Widget Setup</a> if you would like to enable social sharing.<br />
      To update the plugin you must click Save after you are done.
      </td>
      <td class="rpx_td">&nbsp;</td>
    </tr><?php
    }
  }?>
  </table>
  <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </p>
</form>
</div><?php
}

function rpx_admin_advanced_menu_view() {
  if (current_user_can(RPX_OPTIONS_ROLE) === false) {
    return false;
  }
  global $rpx_advanced;
  ?>
<style type="text/css">
.rpx_tr_lt {
  background-color:#EEE;
}
.rpx_tr_dk {
  background-color:#DDD;
}
.rpx_td {
  text-align:left;
}
.rpx_td label {
  display:block;
  float:left;
  width:180px;
}
.rpx_string {
  width:220px;
}
</style>
<div class="wrap">
<h2>Janrain Engage Expert Options</h2>
<?php rpx_print_messages(); ?>
<p style="font-weight:bold">Do not change these if you are unsure. Erase any setting and Save to reset to default.</p>
<form method="post" action="options.php">
  <table class="form-table">
  <?php settings_fields( 'rpx_advanced_settings_group' );
  $rpx_advanced_array = get_option(RPX_ADVANCED_OPTION);
  $rpx_flip = 'lt';
  foreach($rpx_advanced as $key => $val) {
    if ( !empty($rpx_advanced_array[$key]) ) {
      $value = $rpx_advanced_array[$key];
    }else{
      $value = $val['default'];
    }
  ?>
    <tr class="rpx_tr_<?php echo $rpx_flip; if ($rpx_flip == 'lt') { $rpx_flip = 'dk'; }else{ $rpx_flip = 'lt'; } ?>">
      <td class="rpx_td">
      <label class="rpx_label" for="<?php echo $key; ?>"><?php echo $key; ?>: </label>
      <input id="<?php echo $key; ?>" class="rpx_string" type="text" name="<?php echo RPX_ADVANCED_OPTION.'['.$key.']'; ?>" value="<?php echo $value; ?>" style="width:100%"/>
      </td>
      <td class="rpx_td"><?php echo $val['desc']; ?></td>
    </tr><?php
  }
  ?>
  </table>
  <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </p>
</form>
</div>
<?php
}

function rpx_admin_string_menu_view() {
  if (current_user_can(RPX_OPTIONS_ROLE) === false) {
    return false;
  }
  global $rpx_strings;
  ?>
<style type="text/css">
.rpx_tr_lt {
  background-color:#EEE;
}
.rpx_tr_dk {
  background-color:#DDD;
}
.rpx_td {
  text-align:left;
}
.rpx_td label {
  display:block;
  float:left;
  width:180px;
}
.rpx_string {
  width:220px;
}
</style>
<div class="wrap">
<h2>Janrain Engage Strings</h2>
<?php rpx_print_messages(); ?>
<form method="post" action="options.php">
  <table class="form-table">
  <?php settings_fields( 'rpx_string_settings_group' );
  $rpx_strings_array = get_option(RPX_STRINGS_OPTION);
  $rpx_flip = 'lt';
  foreach($rpx_strings as $key => $val) {
    if ( !empty($rpx_strings_array[$key]) ) {
      $value = $rpx_strings_array[$key];
    }else{
      $value = $val['default'];
    }
  ?>
    <tr class="rpx_tr_<?php echo $rpx_flip; if ($rpx_flip == 'lt') { $rpx_flip = 'dk'; }else{ $rpx_flip = 'lt'; } ?>">
      <td class="rpx_td">
      <label class="rpx_label" for="<?php echo $key; ?>"><?php echo $key; ?>: </label>
      <input id="<?php echo $key; ?>" class="rpx_string" type="text" name="<?php echo RPX_STRINGS_OPTION.'['.$key.']'; ?>" value="<?php echo $value; ?>" style="width:100%"/>
      </td>
      <td class="rpx_td"><?php echo $val['desc']; ?></td>
    </tr><?php
  }
  ?>
  </table>
  <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </p>
</form>
</div>
<?php
}

function rpx_admin_help_menu_view() {
  if (current_user_can(RPX_OPTIONS_ROLE) === false) {
    return false;
  }
  $readme_txt = file_get_contents(RPX_PATH_ROOT.'/readme.txt');
  $readme_txt_lines = explode("\n", $readme_txt);
  $readme_txt_clean = '';
  foreach ($readme_txt_lines as $key => $val) {
    $readme_txt_clean .= htmlentities(wordwrap($val, 90, "\n    "));
    $readme_txt_clean .= "\n";
  }
  ?>
<style type="text/css">
.rpx_pre_box {
  display:block;
  overflow:auto;
  font-family:serif;
  font-size:14px;
  width:760px;
  height:570px;
  border:5px solid #C6C6C6;
  border-top:0;
  border-right:2px solid #C6C6C6;
  background-color:transparent;
  margin:4px;
  margin-top:0;  
  padding:6px;
  padding-top:0px;
}
.rpx_pre_box p {
  font-family:serif;
  font-size:14px;
}
#rpx_help_menu {
  width:773px;  
  margin:4px;
  margin-top:16px;
  margin-bottom:0;
  padding:3px;
  background-color:#C6C6C6;
}
#rpx_help_menu span {
  padding:6px;
  margin:5px;
  background-color:#EEE;
}
</style>
<h2>Janrain Engage Help</h2>
<div id="rpx_help_menu">
<span id="rpx_setupguide_button" class="rpx_button" onclick="rpxShowById('rpx_setupguide'); return false"><a href="">setup guide</a></span>
<span id="rpx_shortcodes_button" class="rpx_button" onclick="rpxShowById('rpx_shortcodes'); return false"><a href="">shortcodes</a></span>
<span id="rpx_trouble_button" class="rpx_button" onclick="rpxShowById('rpx_trouble'); return false"><a href="">troubleshooting</a></span>
<span id="rpx_readme_button" class="rpx_button" onclick="rpxShowById('rpx_readme'); return false"><a href="">read me</a></span>
</div>
<div id="rpx_help_items">
<div id="rpx_setupguide" class="rpx_pre_box rpx_help_item">
<h3>Setup Guide</h3>
<p>
Welcome to Janrain Engage for Wordpress. This guide will help you get this plugin setup.
<br>
Use of this plugin requires an Engage application. Janrain offers Engage applications at 
 multiple service levels and with prices starting at $0. Please visit our 
 <a href="http://www.janrain.com/products/engage/features-comparison" target="_blank">feature comparison page</a>
 to find the service level that is right for your site(s).
</p><p>
Once you  
 <a href="https://rpxnow.com/" target="_blank">sign in</a> to your Engage application you 
 can easily access the API key to enable the plugin.
</p>
<h3>Sign In Setup</h3>
<p>
Copy the API key from the Application Info on the main page of your Engage dashboard and paste 
 into the API key option on the Setup panel for Janrain Engage on your Wordpress admin dashboard.
 Click the Save Settings button; the plugin will retrive the needed settings from the Engage system 
 and save those settings for you. This will unlock the full set of options.
</p><p>
Setup the sign in providers on <a href="https://rpxnow.com/" target="_blank">Engage</a>. Select 
 the sign-in for web settings. Skip to the Choose Providers step. Drag and drop your desired
 providers into the widget. If a provider has a grey gear icon it will require setup steps on
 that provider's website. When you drag the provider or click the gear you will be offered a 
 setup guide to walk you through enabling the provider. Click save when you are done.
</p><p>
After you click save you will see a link to allow you to test the widget. Please test each provider 
 to ensure that everything works. Providers that offer an email address will work best with 
 Wordpress due to the requirement for email addresses for each user. Check the 
 <a href="https://rpxnow.com/docs/providers" target="_blank">provider guide</a> to familiarize 
 yourself with what each provider offers in the data returned.
</p><p>
Add your site domain to the token URL domain list. Click the settings option on your Engage 
 dashboard to access the page with the allowed domain list. Add your domain and click save.
</p><p>
Back to the Wordpress Janrain Engage Setup page. Clicking save will update the icons shown to 
 your visitors. Click it a second time if needed.
</p><p>
The verfiedEmail match setting allows existing users to get automatically connected to thier 
 existing account if possible. This is not needed for a new site. There are some security concerns 
 around this feature. Do not enable this if security is a top concern. This feature does not work 
 to connect to the admin account (id 0). If you would like to connect this account to Engage please 
 do so via the profile page for that user.
</p><p>
The login link for comments feature can be confusing. This feature adds an Engage login prompt to
 the comment area. There are many template action hook options for this area and you may need to 
 experiment or examine the template source to find the correct option. For a template that is up to
 WP3 standards the main question is if you require sign in or not. If you do you will want the hook 
 named comment_form_must_log_in, and if not you will likely want comment_form_before_fields.
</p><p>
The plugin includes a Wordpress Sidebar Widget. Look in Appearance -&gt; Widgets to find this widget.
</p><p>
If none of these options are working you may want to look at using a shortcode in your template. 
<br>See the shortcode page for more info.
</p><p>
Hint: 
<br><em>To set a language for the widget you can use the expert option for additional URL parameters.
<br>(e.g. language_preference=fr would set French)</em>
</p>
<h3>Registration Setup</h3>
<p>
Enable automatic user registration if your blog is not using user registration customizations or 
 supplimentary user registration forms (such as BuddyPress). This feature will enable your visitors 
 register and sign in very quickly if they have a full profile shared from the Engage sign on.
</p>
<h3>General User Experience Setup</h3>
<p>
Enable social provider icons to display the avatar from the Engage provider instead of the generic 
 Wordpress icons.
</p>
<h3>Social Sharing Setup</h3>
<p>
Setup the social providers on <a href="https://rpxnow.com/" target="_blank">Engage</a>. Select 
 the social sharing for websites panel. Skip to the Choose Providers step. Drag and drop your desired
 providers into the widget. These providers will require setup steps on each provider's website. 
 When you drag the provider or click the gear you will be offered a setup guide to walk you through 
 enabling the provider. Click save when you are done.
</p><p>
<p>
Enable social sharing and click save changes to reveal the full options. These options are intended 
 to work with fully up to date WP3 templates. The JS for this widget looks for an img element with 
 id="rpxshareimg" and adds this image to the share if found.
 <br>Enable the count bubbles to track and display counters for each successful share per provider.
 <br> (counts are not tracked if this feature is not enabled)
 <br>
 <br>If you are having trouble getting these options to work you may need to add shortcodes to your 
 template. See the shortcode page for more info.
<br>
The share widget button can be displayed as a row of icons or as a text link.
</p>
</div>
<div id="rpx_shortcodes" class="rpx_pre_box rpx_help_item">
<h3>Shortcodes</h3>
<div id="rpxshare_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Social share button</strong> (social sharing must be enabled in setup):</p>
Tag name: <em>rpxshare</em>
<br>Parameters:
<ul>
<li>link = URL for the shared comment, defaults to the permalink for the current post or page.</li>
<li>title = TEXT for the link, defaults to the title of the current post or page.</li>
<li>style = "icon" or "label", defaults to "icon" labelled icons style link. The "label" style produces a text only link.</li>
<li>label = TEXT for the link
<li>imgsrc = URL for an image to be shown with the share, defaults to none unless there is an img with id="rpxshareimg" on the page.</li>
<li>[rpxshare]TEXT[/rpxshare] - The text inside the rpxshare shortcode tags is used as the link descriptive summary for the post.</li>
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxshare link="http://www.janrain.com" title="Cool Link" style="icon" label="cool"]Welcome to social blogging.[/rpxshare]

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
rpx_inline_javascript();
echo do_shortcode('[rpxshare link="http://www.janrain.com" title="Cool Link" style="icon" label="cool"]Welcome to social blogging.[/rpxshare]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
<div id="rpxlogin_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Social login button</strong> (plugin must be enabled and setup):</p>
Tag name: <em>rpxlogin</em>
<br>Parameters:
<ul>
<li>redirect = URL to redirect to after login, defaults to the current post or page.</li>
<li>prompt = TEXT label, defaults to the associated strings.</li>
<li>style = "small" or "large", defaults to "icon" labelled icons style link. The "label" style produces a text only link.</li>
<li>autohide = "true" or "false", defaults to "false" element is always rendered. Set to true to "hide" the element for authenticated users.</li>
<li>datamode = "true" or "false", defaults to "false" running the same actions as wp_login. Set to "true" the user will not be signed in or registered.(expert option)</li> 
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxlogin redirect="http://www.janrain.com" prompt="Authenticate!" style="large" autohide="false" datamode="false"]

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
echo do_shortcode('[rpxlogin redirect="http://www.janrain.com" prompt="Authenticate!" style="large" autohide="false" datamode="false"]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
<div id="rpxwidget_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Social login widget</strong> (plugin must be enabled and setup):</p>
Tag name: <em>rpxwidget</em>
<br>Parameters:
<ul>
<li>redirect = URL to redirect to after login, defaults to the current post or page.</li>
<li>prompt = TEXT label, defaults to the associated strings.</li>
<li>prompt = TEXT for the link, defaults to the title of the current post or page.</li>
<li>style = "small" or "large", defaults to "icon" labelled icons style link. The "label" style produces a text only link.</li>
<li>hide = "true" or "false", defaults to "false" element is always rendered. Set to true to "hide" the element for authenticated users.</li>
<li>avatar = "true" or "false", defaults to "true" renders a provider icon with a small provider badge overlay.(grey box if not available)</li> 
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxwidget redirect="http://www.janrain.com" title="Social" prompt="Connect" style="small" hide="false" avatar="true"]

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
echo do_shortcode('[rpxwidget redirect="http://www.janrain.com" title="Social" prompt="Connect" style="small" hide="false" avatar="true"]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
<div id="rpxavatar_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Social avatar</strong> (plugin must be enabled and setup):</p>
Tag name: <em>rpxavatar</em>
<br>Parameters:
<ul>
<li>badge = "true" or "false", defaults to "true" overlay a badge of the provider on the avatar. </li>
<li>name = "true" or "false", defaults to "true" append the user name. </li>
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxavatar badge="true" name="true"]

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
echo do_shortcode('[rpxavatar badge="true" name="true"]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
<div id="rpxuser_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Conditional content diplayed for Enagage connected users</strong> (plugin must be enabled and setup):</p>
Tag name: <em>rpxuser</em>
<br>Parameters:
<ul>
<li>None, uses content of tags.</li>
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxuser]Content shown for found user {username}.[/rpxuser]
 Supports shortcodes in content and the keywords below:
 {provider} = Name of connected provider.
 {username} = Current Wordpress display name or user name.
 {givenname} = Recorded first name.
 {familyname} = Recorded last name.
 {avatarurl} = Collected avatar URL.
 {profileurl} = Collected profile URL.
 Empty values return as empty.
 Provider and username should never be empty.

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
echo do_shortcode('[rpxuser]Content shown for found user. Supports shortcodes and keywords below like {username}.[/rpxuser]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
<div id="rpxnotuser_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Conditional content diplayed for users not connected via Engage</strong> (plugin must be enabled and setup):</p>
Tag name: <em>rpxnotuser</em>
<br>Parameters:
<ul>
<li>None, uses content of tags.</li>
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxnotuser]Connect to a social account: [rpxlogin][/rpxnotuser]
 Supports shortcodes in content.

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
echo do_shortcode('[rpxnotuser]Connect to a social account: [rpxlogin][/rpxnotuser]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
<div id="rpxdata_shortcode" style="width:734px; background-color:#FEFEFE; padding:6px;">
<p><strong>Render selected Engage data</strong>  (plugin must be enabled and setup):</p>
Tag name: <em>rpxdata</em>
<br>Parameters:
<ul>
<li>fetch = label for data to fetch, defaults to null and returns null.
<br>Valid options:
  <ul style="margin-left:20px">
  <li>rpx_identifier</li>
  <li>rpx_provider</li>
  <li>rpx_email *</li>
  <li>rpx_verifiedEmail *</li>
  <li>rpx_username *</li>
  <li>rpx_displayname *</li>
  <li>rpx_given *</li>
  <li>rpx_family *</li>
  <li>rpx_realname *</li>
  <li>rpx_photo *</li>
  <li>rpx_url *</li>
  <li>rpx_gender *</li>
  <li>rpx_birthday *</li>
  <li>rpx_utcOffset *</li>
  </ul>
  *May be null due to provider or privacy restrictions.
</li>
<li>default = value to return on failure, defaults to null and returns null. Accepts any string.</li>
</ul>
<pre style="font-size:10px;">Example shortcode:
[rpxdata fetch="rpx_provider" default="please connect"]

</pre>
<div style="width:150px; float:left; position:relative;">
Example result:<br><?php 
echo do_shortcode('[rpxdata fetch="rpx_provider" default="please connect"]'); ?>
</div><div class="clear">&nbsp;</div>
</div>
</div>
<div id="rpx_trouble" class="rpx_pre_box rpx_help_item">
<h3>Troubleshooting</h3>
<p>Visit the Q&amp;A discussion on the <a href="<?php echo RPX_PLUGIN_HELP_URL; ?>" target="_blank">Janrain Support site</a>.</p>
<div id="status" style="display:none"></div>
<div id="ajaxreader"></div>
</div>
<div id="rpx_readme" class="rpx_pre_box rpx_help_item">
<h3>Read Me</h3>
<pre>
<?php echo $readme_txt_clean; ?>
</pre>
</div>
</div>
<script type="text/javascript">
function rpxHideAll() {
  var theItems = document.getElementById('rpx_help_items').getElementsByTagName('div');
  if (theItems != null) {
    for(var i in theItems){
      if (theItems[i].id != null) {
        if (theItems[i].className.search('rpx_help_item') >= 0) {
          rpxHideById(theItems[i].id)
        }
      }
    }
  }
  var menuItems = document.getElementById('rpx_help_menu').getElementsByTagName('span');
  if (menuItems != null) {  
    for(var i in menuItems){
      if (menuItems[i].id != null) {
        if (menuItems[i].className.search('rpx_button') >= 0) {
          rpxClearOutById(menuItems[i].id)
        }
      }
    }
  }
}
function rpxOutById(theId) {
  document.getElementById(theId).style.outline = '1px solid #BBB';
}
function rpxClearOutById(theId) {
  document.getElementById(theId).style.outline = '';
}
function rpxShowById(theId) {
  rpxHideAll();
  document.getElementById(theId).style.display = 'block';
  rpxOutById(theId+'_button');
}
function rpxHideById(theId) {
  document.getElementById(theId).style.display = 'none';
}
rpxShowById('rpx_setupguide');
</script>
<script type="text/javascript">
var RSSRequestObject = false; // XMLHttpRequest Object
var Backend = '<?php echo RPX_PLUGIN_URL; ?>/help_feed.php'; // Backend url

if (window.XMLHttpRequest) // try to create XMLHttpRequest
  RSSRequestObject = new XMLHttpRequest();

if (window.ActiveXObject)  // if ActiveXObject use the Microsoft.XMLHTTP
  RSSRequestObject = new ActiveXObject("Microsoft.XMLHTTP");


/*
* onreadystatechange function
*/
function ReqChange() {
  // If data received correctly
  if (RSSRequestObject.readyState==4) {
    // if data is valid
    if (RSSRequestObject.responseText.indexOf('invalid') == -1) {   
      // Parsing RSS
      var node = RSSRequestObject.responseXML.documentElement; 
      // Get Channel information
      var channel = node.getElementsByTagName('channel').item(0);
      var title = channel.getElementsByTagName('title').item(0).firstChild.data;
      var link = channel.getElementsByTagName('link').item(0).firstChild.data;
      var link = '<?php echo RPX_PLUGIN_HELP_URL; ?>';
      content = '<div class="channeltitle"><a href="'+link+'" target="_blank">'+title+'</a></div><ul>';
      // Browse items
      var items = channel.getElementsByTagName('item');
      for (var n=0; n < items.length; n++) {
        var itemTitle = items[n].getElementsByTagName('title').item(0).firstChild.data.replace('in Wordpress Plugin Q&A ','').slice(0,130);
        var itemLink = items[n].getElementsByTagName('link').item(0).firstChild.data;
        try { 
          //theDateShort = items[n].getElementsByTagName('pubDate').item(0).firstChild.data;
          var theDate = new Date(items[n].getElementsByTagName('pubDate').item(0).firstChild.data);
          var theMonth = theDate.getMonth();
          theMonth++;
          var theDay = theDate.getDate();
          var theDateShort = theMonth+'/'+theDay;
          var itemPubDate = '<font color=gray>['+theDateShort+'] ';
        } 
        catch (e) { 
          var itemPubDate = '';
        }
        content += '<li>'+itemPubDate+'</font><a href="'+itemLink+'" target="_blank">'+itemTitle+'</a></li>';
      }
      content += '</ul>';
      // Display the result
      document.getElementById("ajaxreader").innerHTML = content;
      // Tell the reader the everything is done
      document.getElementById("status").innerHTML = "Done.";
    } else {
      // Tell the reader that there was error requesting data
      document.getElementById("status").innerHTML = "<div class=error>Error requesting data.<div>";
    }
    HideShow('status');
  }
}

/*
* Main AJAX RSS reader request
*/
function RSSRequest() {
  // change the status to requesting data
  HideShow('status');
  document.getElementById("status").innerHTML = "Requesting data ...";
  // Prepare the request
  RSSRequestObject.open("GET", Backend , true);
  // Set the onreadystatechange function
  RSSRequestObject.onreadystatechange = ReqChange;
  // Send
  RSSRequestObject.send(null); 
}

/*
* Timer
*/
function update_timer() {
  RSSRequest();
}


function HideShow(id){
  var el = GetObject(id);
  if(el.style.display=="none")
  el.style.display='';
  else
  el.style.display='none';
}

function GetObject(id){
  var el = document.getElementById(id);
  return(el);
}

RSSRequest();
</script>
<?php
}


function rpx_message_box($message='') {
  if ( empty($message) ){
    global $rpx_messages;
    $messages = array();
    foreach ($rpx_messages as $key=>$msg){
      if ($msg['class'] == 'message'){
        $messages[] = $msg['message'];
      }
    }
    $message = '<p>'.implode('<br />', $messages).'</p>';
  }
?>
<div id="rpxmsgbox" class="rpxbox rpxmsgbox">
<div id="rpxmsgw1" class="rpxhoriz"></div>
<table id="rpxmsgw2" class="rpxvert"><tr id="rpxvrow" class="rpxvrow"><td id="rpxvcol" class="rpxvcol">
<span id="rpxmsgborder" class="rpxborder">
<span id="rpxmsgclose" class="rpxclose" onclick="hideRPX('rpxmsgbox')"><img src="<?php echo RPX_IMAGE_URL; ?>close.png" alt="close" /></span>
<div id="rpxmsg" class="rpxmsg">
<div id="rpxmessage" class="rpxmessage"><?php echo $message; ?></div>
</div></span></td></tr></table></div>
<script type="text/javascript">
  showRPX('rpxmsgbox');
</script>
<?php
}

function rpx_register_form($collect='email') {
  global $rpx_http_vars;
  if ($rpx_http_vars['rpx_collect'] == 'username'){
    $collect = 'username';
  }
?>
<div id="rpxregbox" class="rpxbox rpxregbox" onload="showRPX('rpxregbox');">
<div id="rpxregw1" class="rpxhoriz"></div>
<table id="rpxregw2" class="rpxvert"><tr id="rpxvrow" class="rpxvrow"><td id="rpxvcol" class="rpxvcol">
<span id="rpxregborder" class="rpxborder">
<span id="rpxregclose" class="rpxclose" onclick="hideRPX('rpxregbox');"><img src="<?php echo RPX_IMAGE_URL; ?>close.png" alt="close" /></span>
<div id="rpxregister" class="rpxregister">
<?php rpx_print_messages(); ?>
<form id="rpxregform" class="rpxregform" action="" method="get">
 <input type="hidden" name="action"       value="<?php echo RPX_REGISTER_FORM_ACTION ?>" />
 <input type="hidden" name="rpx_collect"  value="<?php echo urlencode($rpx_http_vars['rpx_collect']); ?>" />
 <input type="hidden" name="rpx_session"  value="<?php echo urlencode($rpx_http_vars['rpx_session']); ?>" />
 <input type="hidden" name="rpx_provider" value="<?php echo urlencode($rpx_http_vars['rpx_provider']); ?>" />
 <input type="hidden" name="redirect_to"  value="<?php echo urlencode($rpx_http_vars['redirect_to']); ?>" />
<?php
  if ($collect == 'email'){
    echo ' <input type="hidden" name="rpx_username" value="'.urlencode($rpx_http_vars['rpx_username']).'" />'."\n";
    echo ' <p><input type="text"   name="rpx_email"    value="" id="rpxemail" class="rpxemail" size="30" /></p>';
  }elseif ($collect == 'username'){
    echo ' <input type="hidden" name="rpx_email"    value="'.urlencode($rpx_http_vars['rpx_email']).'" />'."\n";
    echo ' <p><input type="text"   name="rpx_username" value="" id="rpxusername" class="rpxusername" size="30" /></p>';
    if (RPX_REQUIRE_EULA == 'true') {
      echo ' <p><label for="rpx_eula">'.RPX_EULA_PROMPT.'</label><input id="rpx_eula" name="rpx_eula" type="checkbox" value="eula" /><a href="'.RPX_EULA_URL.'" target="_blank">EULA</a><p>';
    }
  }else{
    echo ' <input type="hidden" name="rpx_username" value="'.urlencode($rpx_http_vars['rpx_username']).'" />'."\n";
    echo ' <input type="hidden" name="rpx_email"    value="'.urlencode($rpx_http_vars['rpx_email']).'" />'."\n";
    echo $collect;//$collect as html to ask for something else, maybe useful later
  }
?>
<input id="rpxsubmit" class="rpxsubmit" type="submit" value="Submit" />
</form>
</div></span></td></tr></table></div>
<script type="text/javascript">
  showRPX('rpxregbox');
</script>
<?php
}

function rpx_open_widget(){
  global $rpx_http_vars;
  $add_parameters = '';
  if( !empty($rpx_http_vars['rpx_username']) ){
    $add_parameters .= '&rpx_username='.urlencode($rpx_http_vars['rpx_username']);
  }
  $add_parameters = urlencode($add_parameters);
?>
<script type="text/javascript">
  document.getElementById('rpxiframe').src += '<?php echo $add_parameters; ?>';
  showRPX('rpxlogin');
</script>
<?php
}

function rpx_wp_footer(){
  global $rpx_http_vars;
  global $rpx_footer_done;
  if ($rpx_footer_done === true) {
    return false;
  }
  if ($rpx_http_vars['action'] == RPX_REGISTER_FORM_ACTION){
    rpx_register_form();
  }
  $user_data = rpx_user_data();
  if ($user_data != false && !empty($user_data->rpx_provider) && did_action('show_user_profile') === false){
    return true;
  }
  ?>
<div id="rpxlogin" class="rpxbox" style="display:none">
<div id="fiftyfifty" class="rpxhoriz"></div>
<table id="rpxvertical" class="rpxvert"><tr id="rpxvrow" class="rpxvrow"><td id="rpxvcol" class="rpxvcol">
<span id="rpx_border" class="rpxborder">
<span id="rpx_close" class="rpxclose" onclick="hideRPX('rpxlogin')"><img src="<?php echo RPX_IMAGE_URL; ?>close.png" alt="close" /></span>
<?php
  if (get_option(RPX_NEW_WIDGET_OPTION) == 'true'){
    ?><div id="janrainEngageEmbed"></div><?php
  }else{
    echo rpx_iframe_widget();
  }?>
</span></td></tr></table></div>
<?php
  $rpx_footer_done = true;
}

function rpx_iframe_widget($redirect_url=NULL, $action=RPX_TOKEN_ACTION) {
  $iframe = '';
  $site_url = site_url();
  if (stripos($site_url, 'https:') === false){
    $realm_scheme = 'http';
  }else{
    $realm_scheme = 'https';
  }
  $realm = get_option(RPX_REALM_OPTION);
  $token_url = rpx_get_token_url($redirect_url, $action);
  $add_params = get_option(RPX_PARAMS_OPTION);
  if ( empty($add_params) ) {
    $param_query = '';
  } else {
    $param_query = $add_params.'&';
  }
  //token_url must be the final param to allow for easy JS modification
  $iframe_src = $realm_scheme.'://'.$realm .'/openid/embed?'.$param_query.'token_url='.$token_url;
  $iframe = '<iframe id="rpxiframe" class="rpxiframe" scrolling="no" src="'.$iframe_src.'"></iframe>';
  return $iframe;
}

function rpx_get_token_url($redirect_url=NULL, $action=RPX_TOKEN_ACTION){
  global $rpx_http_vars;
  $rpx_token_url = '';
  $this_url = (!empty($_SERVER['HTTPS'])) ? 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
  $url_parts = parse_url($this_url);
  $this_clean_url = $url_parts['scheme'] . '://';
  $this_clean_url .= $url_parts['host'];
  $this_clean_url .= $url_parts['path'];

  if ( empty($redirect_url) && !empty($rpx_http_vars['redirect_to']) ) {
    $redirect_url = $rpx_http_vars['redirect_to'];
  }
  if (empty($redirect_url) && is_login_page()) {
    $redirect_url = get_bloginfo('url');
  }
  /**** This is likely to not be needed.
  if ( empty($redirect_url) && !empty($permalink) && !is_front_page() ) {
    $permalink = get_permalink();
    $redirect_url = $permalink;
  }
  if ( empty($redirect_url) && !empty($this_url) ) {
    $redirect_url = $this_url;
  }
  if ( empty($redirect_url) && !empty($site_url) ) {
    $redirect_url = $site_url;
  }
  ****/
  if ( !empty($rpx_http_vars['action']) ) {
    if ( $rpx_http_vars['action'] == RPX_DATA_MODE_ACTION ) {
      $action = RPX_DATA_MODE_ACTION;
      $redirect_url = '';
      if ( !empty($rpx_http_vars['redirect_to']) ) {
        $rpx_token_url = urlencode($rpx_http_vars['redirect_to'] .  '?action='.$action );
      }
    }
  }
  if ( empty($rpx_token_url) ) {
    $rpx_token_url = urlencode($this_clean_url . '?action='.$action );
  }
  if ( !empty($redirect_url) ) {
    $redirect_to = urlencode($redirect_url);
    $rpx_token_url .= urlencode('&redirect_to=' . $redirect_to);
  }
  return $rpx_token_url;
}

function rpx_login_form() {
  if ( rpx_allow_wplogin() === false ) {
    return false;
  }
  global $rpx_http_vars;
  if (is_user_logged_in()){
    return true;
  }
  if ($rpx_http_vars['action'] == 'register'){
    if (rpx_allow_register() === false){
      return false;
    }
    $logreg = RPX_OR_REGISTER_PROMPT;
  }else{
    $logreg = RPX_OR_LOGIN_PROMPT;
  }
  rpx_print_messages();
  if ( strstr(wp_login_url(), 'wp-login.php') !== false ) {
    rpx_wp_footer();
  }
  echo rpx_buttons(RPX_BUTTONS_STYLE_LARGE, $logreg);
}

function rpx_login_head() {
  if ( rpx_allow_wplogin() === false ) {
    return false;
  }
  if ( strstr(wp_login_url(), 'wp-login.php') === false ) {
    return false;
  }
?>
<link rel='stylesheet' type='text/css' media='all' href='<?php echo RPX_FILES_URL; ?>stylesheet.css' />
<script type='text/javascript' src='<?php echo RPX_FILES_URL; ?>javascript.js'></script>
<?php
  rpx_inline_stylesheet();
  rpx_inline_javascript();
}

function rpx_content_filter($content){
  $rpx_social_option = get_option(RPX_SOCIAL_OPTION);
  $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
  $rpx_s_loc_option = get_option(RPX_S_LOC_OPTION);
  $in_the_loop = in_the_loop();
  if ($rpx_social_option != 'true' || empty($rpx_social_pub) || $rpx_s_loc_option == 'none' || $in_the_loop === false){
    return $content;
  }
  $rpx_social = rpx_social_share($content);
  if ($rpx_s_loc_option == 'top'){
    return $rpx_social.$content;
  }else{
    return $content.$rpx_social;
  }
}

function rpx_comment_filter($comment){
  $rpx_social_comment_option = get_option(RPX_SOCIAL_COMMENT_OPTION);
  $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
  $in_the_loop = in_the_loop();
  if ($rpx_social_comment_option != 'true' || empty($rpx_social_pub) || $in_the_loop === false){
    return $comment;
  }
  $share = rpx_social_share($comment, true);
  return $comment.$share;
}

function rpx_avatar_filter($avatar){
  $rpx_avatar_option = get_option(RPX_AVATAR_OPTION);
  if ($rpx_avatar_option != 'true'){
    return $avatar;
  }
  $rpx_avatar = $avatar;
  $rpx_photo = '';
  if (in_the_loop() != false){
    $zero = 0;
    $comment = get_comment($zero);
    
    if ($comment && !is_wp_error($comment->user_id)){ //LNI: Inserted test for $comment
      $user = get_userdata($comment->user_id);
      if (!is_wp_error($user)){
        if (isset($user->rpx_photo)){
          $rpx_photo = $user->rpx_photo;
        }
      }
    }
  }
  if ( !empty($rpx_photo) ) {
    $avatar = str_replace("'", '"', $avatar);
    $pattern = '/src="[^"]*"/';
    $replace = 'src="'.$rpx_photo.'"';
    $rpx_avatar = preg_replace($pattern, $replace, $avatar);
  }
  return $rpx_avatar;
}

function rpx_add_custom_column($columns){
  $columns[RPX_META_PROVIDER] = 'Engage-Provider';
  return $columns;
}

function rpx_custom_column($val='', $col_name='', $user_id){
  if ($col_name == RPX_META_PROVIDER){
    $output = '';
    $user_data = get_userdata($user_id);
    if ( !empty($user_data->rpx_locked) && $user_data->rpx_locked == 'true'){
      $output .= 'locked-';
    }
    $output .= $user_data->rpx_provider;
    return $output;
  }
}

function rpx_column_orderby($vars) {
  if ( !empty($vars['orderby']) && $vars['orderby'] == RPX_META_PROVIDER){
    $vars = array_merge( $vars, array( 'meta_key' => RPX_META_PROVIDER, 'orderby' => 'meta_value') );
  }
  return $vars;
}

function rpx_user_provider_icon($author_name = NULL, $with_name = true){
  if (in_the_loop() === false && $author_name != NULL){
    return $author_name;
  }
  global $rpx_providers;
  if ($author_name != NULL){
    $zero = 0;
    $comment = get_comment($zero);
    if (!empty($comment->user_id)){
      $user = get_userdata($comment->user_id);
    }
  }else{
    $user = rpx_user_data();
    $author_name = $user->user_login;
  }
  $icon = '';
  if (!empty($user->rpx_provider)){
    $provider = $user->rpx_provider;
    if ( !empty($provider) ){
      $provider = $rpx_providers["$provider"];
      $author = $user->user_login;
      $url = $user->user_url;
      if ( !empty($user->rpx_url) ){
        $url = $user->rpx_url;
      }
      $icon = '<div class="rpx_icon rpx_size16 rpx_'.$provider.' rpx_author" title="'.$author.'"></div>';
      if (  !empty($url) ){
        $icon = '<a href="'.$url.'" rel="external nofollow" target="_blank">'.$icon.'</a>';
      }
    }
  }
  $return = $icon;
  if ($with_name == true) {
    $return .= $author_name;
  }
  return $return;
}

function rpx_social_share($message, $comment=false, $style=NULL, $share_label=NULL, $title=NULL, $link=NULL, $imgsrc=NULL){
  if ( empty($title) ) {
    $title = get_the_title();
  }
  $posttitle = rpx_js_escape(strip_tags($title));

  if ( empty($link) ) {
    $link = get_permalink();
  }
  $postlink = rpx_js_escape(strip_tags($link));
  
  if ( empty($share_label) ) {
    $share_label = get_option(RPX_S_TXT_OPTION);
  }
  $share_label = strip_tags($share_label);

  if ( empty($imgsrc) ) {
    $imgsrc = '';
  }

  $post_id = get_the_ID();
  
  $postsummary = rpx_js_escape(substr(strip_tags(strip_shortcodes($message)), 0, 128)).'...';
  $blogname    = rpx_js_escape(strip_tags(get_option('blogdescription')));
  $class = 'rpxsocial';
  $verb = RPX_SHARED;
  $label = RPX_SHARE_LABEL;
  if ($comment === true){
    $postlink = rpx_js_escape(strip_tags(get_comment_link()));
    $class = 'rpxsocial_small';
    $verb = RPX_COMMENTED_ON;
    $label = RPX_COMMENT_LABEL;
    $style = 'label';
  }
  if (empty($style)) {
    $style = get_option(RPX_S_STYLE_OPTION);
  }
  $rpx_share_counts = get_post_meta($post_id, RPX_POST_META_COUNTS);
  $share_icons = rpx_social_icons($share_label,$rpx_share_counts);
  switch ($style){
    case 'icon':
      $button = $share_icons;
      $button_label = '';
      break;
    case 'label':
      $button = $share_label;
      $button_label = '';
      break;
    default:
      $button = $share_icons;
      $button_label = $share_label;
      break;
  }
  $share_open  = '<div class="'.$class.'">';
  $share_button = '<div class="rpxsharebutton" onclick="rpxWPsocial(';
  $share_button .= "'".$label."','".$postsummary."','".$postlink."','"
    .$posttitle."','".$verb." ".$posttitle."','".$imgsrc."','".$post_id."', this);".'">'.$button.'</div>';
  $share_label = '';
  if ( !empty($button_label) ) {
    $share_label = '<div class="rpx_share_label">'.$button_label.'</div>';
  }
  $share_close = ' &nbsp;</div> <div class="rpx_clear"></div>';
  $share = $share_open.$share_label.$share_button.$share_close;
  return $share;
}

class RPX_Widget extends WP_Widget {
  function RPX_Widget() {
    $widget_options = array('classname' => 'rpx-widget', 'description' => 'Sign in with Janrain Engage.');
    $this->WP_Widget('janrain-engage-widget', RPX_WIDGET_TITLE, $widget_options);
  }
  function widget( $args, $instance ) {
    if ( empty($instance['style']) ) {
      $instance['style'] = RPX_BUTTONS_STYLE_SMALL;
    }
    $user_data = rpx_user_data();
    if ($instance['hide'] != 'true' || ($user_data == false || empty($user_data->rpx_provider))) {
      extract($args);
      $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
      echo $before_widget;
      if ( !empty( $title ) ) {
        echo $before_title . $title . $after_title;
      }
      if ($user_data != false && !empty($user_data->rpx_provider) ){
        global $rpx_http_vars;
        $rpx_user_icon = rpx_user_provider_icon();
        $avatar = '';
        if ( !empty($user_data->rpx_photo) ) {
          $avatar = '<img class="rpx_sidebar_avatar" src="'.$user_data->rpx_photo.'">';
        }else{
          $avatar = '<div class="rpx_sidebar_avatar">&nbsp;</div>';
        }
  ?><div class="rpx_user_icon">
  <?php
        echo $avatar;
        echo $rpx_user_icon;
  ?></div>
  <a href="<?php echo wp_logout_url( $rpx_http_vars['redirect_to'] ); ?>" title="Logout">Log out</a>
  <?php
      }elseif ($user_data != false && empty($user_data->rpx_provider) ){
        $instance['prompt'] = RPX_CONNECT_PROMPT;
        echo rpx_buttons($instance['style'], $instance['prompt']);
      }else{
        $instance['prompt'] = RPX_LOGIN_PROMPT;
        echo rpx_buttons($instance['style'], $instance['prompt']);
      }
      echo $after_widget;
    }
  }
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['hide'] = strip_tags($new_instance['hide']);
    $instance['style'] = strip_tags($new_instance['style']);
    return $instance;
  }
  function form( $instance ) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'hide' => 'false' ) );
    $instance['title'] = strip_tags($instance['title']);
    $hide_checked = ' checked="checked"';
    if ($instance['hide'] != 'true') {
      $instance['hide'] = 'false';
      $hide_checked = '';
    }
    $select_small = '';
    $select_large = '';
    if ($instance['style'] == RPX_BUTTONS_STYLE_SMALL){
      $select_small = ' selected="selected"';
    }
    if ($instance['style'] == RPX_BUTTONS_STYLE_LARGE){
      $select_large = ' selected="selected"';
    }
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></p>
<p><label for="<?php echo $this->get_field_id('hide'); ?>"><?php _e('Hide widget when connected:'); ?></label>
<input class="checkbox" id="<?php echo $this->get_field_id('hide'); ?>" name="<?php echo $this->get_field_name('hide'); ?>" type="checkbox" value="true"<?php echo $hide_checked; ?>/></p>
<p><label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Widget icons:'); ?></label>
<select class="select" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
<option value="<?php echo RPX_BUTTONS_STYLE_SMALL; ?>"<?php echo $select_small; ?>><?php echo RPX_BUTTONS_STYLE_SMALL; ?></option>
<option value="<?php echo RPX_BUTTONS_STYLE_LARGE; ?>"<?php echo $select_large; ?>><?php echo RPX_BUTTONS_STYLE_LARGE; ?></option>
</select>
</p>
<?php
  }
}


function rpx_widget($args) {//deprecated
  extract($args);
  echo $before_widget;
  echo $before_title.RPX_WIDGET_TITLE.$after_title;
  $user_data = rpx_user_data();
  if ($user_data != false && !empty($user_data->rpx_provider) ){
    global $rpx_http_vars;
    global $rpx_providers;
    $provider = $rpx_providers[$user_data->rpx_provider];
    $displayname = $user_data->display_name;
    $url = $user_data->user_url;
?>
Welcome <?php echo $displayname; ?><?php if ( !empty($provider) ){ ?><div class="rpx_<?php echo $provider; ?>_small rpx_icon_small rpxauthor" onclick="window.location.href='<?php echo $url; ?>'" title="<?php echo $displayname; ?>"></div><?php } ?><br />
<a href="<?php echo wp_logout_url( $rpx_http_vars['redirect_to'] ); ?>" title="Logout">Log out</a>
<?php
  }elseif ($user_data != false && empty($user_data->rpx_provider) ){
    echo rpx_small_buttons(RPX_CONNECT_PROMPT);
  }else{
    echo rpx_small_buttons(RPX_LOGIN_PROMPT);
  }
  echo $after_widget;
}

function rpx_buttons($style=RPX_BUTTONS_STYLE_SMALL, $label_text=RPX_LOGIN_PROMPT, $redirect=NULL, $action=null) {
  global $rpx_button_count;
  global $rpx_icon_override;
  global $rpx_providers;
  global $rpx_providers_small;
  global $rpx_providers_large;
  $providers = '';
  $widget_opts = '';
  $provider_buttons = '';
  $label = '';
  $buttons = '';
  switch ($style){
    case RPX_BUTTONS_STYLE_SMALL:
      $icon_limit = RPX_SMALL_ICONS_LIMIT;
      if ($rpx_icon_override === true){
        $providers = $rpx_providers_small;
      }
      $class = 'rpx_size16';
      $icon_class = 'rpx_small_icons';
      break;
    case RPX_BUTTONS_STYLE_LARGE:
      $icon_limit = RPX_LARGE_ICONS_LIMIT;
      if ($rpx_icon_override === true){
        $providers = $rpx_providers_large;
      }
      $class = 'rpx_size30';
      $icon_class = 'rpx_large_icons';
      break;
  }
  if ( empty($providers) ){
    $rpx_provider_list = get_option(RPX_PROVIDERS_OPTION);
    if (!empty($rpx_provider_list)) {
      $rpx_provider_list = explode(',',$rpx_provider_list);
    }
    if (is_array($rpx_provider_list)){
      $providers = array_flip(array_intersect($rpx_providers,$rpx_provider_list));
      $providers = array_slice($providers, 0, $icon_limit);
    }
  }
  if ( !empty($redirect) ){
    $redirect = urlencode($redirect);
    $widget_opts .= ", '$redirect'";
  }else{
    $widget_opts .= ", ''";
  }
  if ( !empty($action) ){
    $widget_opts .= ", '$action'";
  }else{
    $widget_opts .= ", ''";
  }
  if ( !empty($label_text) ){
    $label = '<div class="rpx_label">'.$label_text.'</div>';
  }
  foreach ($providers as $key => $val){
    $provider_buttons .= '<div class="rpx_icon '.$class.' rpx_'.$key.'" title="'.htmlentities($val).'"></div>';
  }
  $buttons .= '<div class="rpx_button" id="rpx_button_'.++$rpx_button_count.'">';
  $buttons .= '<div class="'.$icon_class.'" id="'.$icon_class.'_'.++$rpx_button_count.'" onclick="showRPX(\'rpxlogin\''.$widget_opts.')">';
  $buttons .= $provider_buttons.'</div></div><div class="rpx_clear"></div>';
  return $label.$buttons;
}

function rpx_small_buttons($label = ''){
  return rpx_buttons(RPX_BUTTONS_STYLE_SMALL ,$label);
}

function rpx_large_buttons($label = ''){
  return rpx_buttons(RPX_BUTTONS_STYLE_LARGE ,$label);
}

function rpx_edit_user_profile(){
  $user_data = rpx_user_data();
  if ( !empty($user_data->rpx_provider) ){
    $provider = htmlentities($user_data->rpx_provider);
    ?>
<h3>Currently connected to <?php echo $provider; ?></h3>
<?php
    $removable = get_option(RPX_REMOVABLE_OPTION);
    if($removable == 'true'){ 
      ?>
<p>You can remove all <?php echo $provider; ?> data and disconnect your account from <?php echo $provider; ?> by clicking <a href="?action=<?php echo RPX_REMOVE_ACTION; ?>">remove</a>.
<br><strong>Be certain before you click "remove" and set a password for this account so you can use it without social sign in.</strong></p>
<?php
    }
  }
  echo rpx_buttons(RPX_BUTTONS_STYLE_LARGE, RPX_CONNECT_PROMPT);
}

function rpx_admin_edit_user_profile(){
  if ( empty($_GET['user_id']) ){
    return;
  }
  $user_id = $_GET['user_id'];
  $user_data = rpx_user_data($user_id);
  $provider = htmlentities($user_data->rpx_provider);
  $social_url = htmlentities($user_data->rpx_url);
  if ( empty($provider) ){
    return;
  }
  ?>
  <div id="rpx_profile" class="rpxprofile">
  <h3>Janrain Engage Social</h3>
  <h4>Currently connected to <?php echo $provider; ?></h4>
<?php
  if ( !empty($social_url) ){
    ?>
  <h4><a href="<?php echo $social_url; ?>" target="_blank"><?php echo $social_url; ?></a></h4>
  <?php
  }
  ?>
  </div>
  <?php
}

function rpx_social_icons($label, $share_counts){
  global $rpx_providers;
  $social_pub = get_option(RPX_SOCIAL_PUB);
  $share_count_option = get_option(RPX_SHARE_COUNT_OPTION);
  if ( $share_count_option != 'false' ){
    $do_count = true;
  } else {
    $do_count = false;
  }
  if ($share_count_option == 'hover') {
    $hide_style = ' style="display: none"';
  } else {
    $hide_style = '';
  }
  $social_providers = array_filter(explode(',',$social_pub));
  $rpx_social_icons = '';
  $total = 0;
  foreach ($social_providers as $key => $val){
    $count = 0;
    $hide = $hide_style;
    $share_count = '';
    if ( $do_count === true ){
      if ( !empty($share_counts[0][$val]) ){
        $count = $share_counts[0][$val];
        $total += $count;
        //$hide = '';
      }
      $share_count = '<div class="rpx_counter rpx_ct_'.$val.'"'.$hide.'>'.$count.'</div>';
    }
    $rpx_social_icons .= '<div class="rpx_icon '.RPX_SHARE_ICON_CLASS.' rpx_'.$val.'" title="'.htmlentities(array_search($val,$rpx_providers)).'">'.$share_count.'</div>';
  }
  $total_count = '';
  if ( $do_count === true ) {
    $total_count = '<div class="rpx_ct_total" title="total">'.$total.'</div><br>';
  }
  $buttons = '<div class="rpx_share_label">'.$total_count.$label.'</div><div class="rpx_social_icons">'.$rpx_social_icons.'</div>';
  return $buttons;
}

function rpx_comment_login() {
  global $rpx_http_vars;
  rpx_set_redirect();
  if (is_user_logged_in()){
    global $current_user;
    get_currentuserinfo();
    ?>
<div id="rpxwidget"><p>Welcome <?php echo $current_user->display_name; ?><br />
<a href="<?php echo wp_logout_url( $rpx_http_vars['redirect_to'] ); ?>" title="Logout">Log out</a></p></div>
<?php
  }else{
    ?>
<div id="rpxcomment"><?php echo rpx_buttons(RPX_BUTTONS_STYLE_SMALL, RPX_COMMENT_PROMPT); ?></div><br />
<?php
  }
}


?>
