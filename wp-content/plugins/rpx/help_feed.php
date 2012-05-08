<?php
define('RPX_PLUGIN_HELP_URL', 'https://support.janrain.com/forums/232466-wordpress-plugin-q-a');
define('RPX_PLUGIN_HELP_RSS', RPX_PLUGIN_HELP_URL.'/posts.rss');
function rpx_help_feed() {
  $feed = file_get_contents(RPX_PLUGIN_HELP_RSS);
  if (empty($feed)) {
    return false;
  }
  return $feed;
}
$rpx_help_feed = rpx_help_feed();
if (!empty($rpx_help_feed)) {
  header ("Content-Type:text/xml");
  echo $rpx_help_feed;
}
exit;
?>
