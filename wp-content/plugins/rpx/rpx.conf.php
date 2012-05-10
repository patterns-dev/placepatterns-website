<?php

/*  ALL OF THESE OPTIONS HAVE MOVED INTO THE ADMIN UI */

/*
  Provider names for icon bars:
  'Facebook' 'Google' 'LinkedIn' 'MySpace' 'Twitter' 'Windows Live' 'Yahoo!' 'AOL' 'Blogger'
  'Flickr' 'Hyves' 'LiveJournal' 'MyOpenID' 'Netlog' 'OpenID' 'Verisign' 'Wordpress' 'PayPal'
*/

/* set rpx_icon_override to true and use the arrays below to override automatic icons */
$rpx_icon_override = false;

/* SMALL ICON BAR - shown on comment form and widget */
/* Use the "Provider names for icon bars" above to control the icons shown. */
$rpx_providers_small = array('Facebook', 'Google', 'LinkedIn', 'MySpace', 'Twitter', 'Windows Live', 'Yahoo!');
$rpx_providers_small = array_intersect(array_flip($rpx_providers),$rpx_providers_small);/* do not edit this line */

/* LARGE ICON BAR - shown on login.php */
/* Use the "Provider names for icon bars" above to control the icons shown. */
$rpx_providers_large = array('Facebook', 'Google', 'LinkedIn', 'MySpace', 'Twitter', 'Windows Live', 'Yahoo!', 'OpenID');
$rpx_providers_large = array_intersect(array_flip($rpx_providers),$rpx_providers_large);/* do not edit this line */

?>
