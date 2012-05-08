<?php
ob_start();
define('RPX_PATH_ROOT', dirname(__FILE__));
define('RPX_WP_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(RPX_WP_ROOT . '/wp-config.php');
require_once(RPX_PATH_ROOT . '/rpx_m.php');

function rpx_check_url($url){
  //from http://phpcentral.com/208-url-validation-in-php.html
  $regex ='|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i';
  $regex_out = preg_match($regex, $url);
  if ( $regex_out == 0 ){
    return false;
  }
  return true;
}

$rpx_sharepost_stat = 'ok';
$rpx_sharepost = array();
$rpx_error = '';

/*
 * Is this feature enabled?
 */
if ($rpx_sharepost_stat == 'ok'){
  $share_count_option = get_option(RPX_SHARE_COUNT_OPTION);
  if ( $share_count_option == 'false' ){
    $rpx_sharepost_stat = 'fail';
    $rpx_error = 'feature disabled';
  }
}

/*
 *Are all the variables clean?
 */
if ($rpx_sharepost_stat == 'ok'){
  $rpx_post_id = strip_tags($_GET['post_id']);
  $rpx_provider = strip_tags($_GET['provider']);
  $rpx_share_url = strip_tags($_GET['share_url']);

  if ( !is_numeric($rpx_post_id) ){
    $rpx_sharepost_stat = 'fail';
    $rpx_error = 'post ID check failure';
  }
  if ( array_search($rpx_provider, $rpx_providers) === false ){
    $rpx_sharepost_stat = 'fail';
    $rpx_error = 'provider check failure';
  }
  if ( !rpx_check_url($rpx_share_url) ){
    $rpx_sharepost_stat = 'fail';
    $rpx_error = 'URL check failure'.$rpx_share_url;
  }
}

/*
 * Can user view this post?
 */
if ($rpx_sharepost_stat == 'ok'){
  //I have not found a way to do this.
}

/*
 * Incrementing the share count in post meta.
 */
if ($rpx_sharepost_stat == 'ok'){
  $rpx_provider_name = $rpx_provider;
  $rpx_social_pub = get_option(RPX_SOCIAL_PUB);
  $rpx_social_pub = explode(',',$rpx_social_pub);
  //var_dump($rpx_social_pub); exit;
  $rpx_share_count_init = array();
  foreach($rpx_social_pub as $key=>$value) {
    $rpx_share_count_init[$value] = 0;
  }
  add_post_meta($rpx_post_id, RPX_POST_META_COUNTS, $rpx_share_count_init, true);
  $rpx_share_counts = get_post_meta($rpx_post_id, RPX_POST_META_COUNTS, true);
  if ( !empty($rpx_share_counts) ){
    $rpx_share_counts[$rpx_provider_name] = $rpx_share_counts[$rpx_provider_name] + 1;
    $rpx_update_counts = update_post_meta($rpx_post_id, RPX_POST_META_COUNTS, $rpx_share_counts);
    if ($rpx_update_counts !== true) {
      $rpx_sharepost_stat = 'fail';
      $rpx_error = 'post meta update failure';
    }
  } else {
    $rpx_sharepost_stat = 'fail';
    $rpx_error = 'add post meta failure';
  }

}

/*
 * Add pingback URL to post.
 */
if ($rpx_sharepost_stat == 'ok'){
  $rpx_ping = add_ping($rpx_post_id, $rpx_share_url);
  if ($rpx_ping < 1) {
    $rpx_sharepost_stat = 'fail';
    $rpx_error = 'add pingback failure';
  }
}

if ( !empty($rpx_error) ) {
  $rpx_sharepost['error'] = $rpx_error;
}
$rpx_sharepost['stat'] = $rpx_sharepost_stat;
$rpx_sharepost_json = json_encode($rpx_sharepost);
ob_end_clean();
echo $rpx_sharepost_json;
?>
