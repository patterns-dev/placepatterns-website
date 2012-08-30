=== Better WordPress Recent Comments ===
Contributors: OddOneOut
Donate link: http://betterwp.net/wordpress-plugins/bwp-recent-comments/#contributions
Tags: comments, recent comments, recent comments widgets, wordpress recent comments
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 1.2.0

This plugin displays recent comment lists at assigned locations, with comprehensive support for widgets.

== Description ==

**Support for this plugin has been moved to [the BWP Community](http://betterwp.net/community/)!**

This plugin displays recent comment lists at assigned locations. It does not add any significant load to your website. The comment list is updated on the fly when a visitor adds a comment or when you moderate one. No additional queries are needed for end-users.

A recent comment list, in my opinion, can help stimulate discussion and exploration of your blog tremendously. Now for the past few months I have been using a plugin called Get Recent Comments; though this plugin is configurable and indeed popular, the code is somehow messy and no support for custom post type is found. The worst thing is Get Recent Comment doesn't seem to be updated anymore, so I decide to write another recent comment plugin which is more lightweight and makes use of some nice features provided by WordPress 3.0.

**BWP Recent Comments 1.2.0 RC1 - Your recent comment lists just got better!**

* You can now group comments by post (inspired by the classic Get Recent Comments plugin).
* You can now have AJAX navigation for any recent comment list you want! Comment list on a separate page also supports AJAX.
* Templates are available for both grouped comment lists and the AJAX navigation.
* Added a shortcode (`[bwp-rc]`) to show recent comment lists on a separate page.
* More template tags for you to use:
	* `%author_archive%`: link to a comment author's archive page if found.
	* `%comment_count%`: the number of comments for the current post.
	* `%comment_number%`: a comment's number in the list, e.g. 1, 2, 3, etc. Useful when showing comments on a separate page.
* Added a new CSS file that will be used when you disable avatar.
* Now uses `date_i18n` function for comment date so that it can be localized by default.
* A lot of bug fixes and other improvements which make the plugin much faster and more stable!

**Note:** Due to the complexity of this new release, feedbacks are greatly appreciated! If you have any problem, even the smallest, please [contact me](http://betterwp.net/contact/). Thanks!

**Other Features**

* Has the options to show comment only, trackback only, or show both (separately or all together)
* Get comments from a specific post, using either ID or post name (slug).
* Possibility to add different comment lists with different settings on one page
* You can show comments on a separate page, with pagination and custom template!
* You can sort comment lists descendingly or ascendingly
* Supports custom post type
* Supports Gravatar
* Supports smiley
* Widget-ready
* Template functions ready
* Generate Zero SQL query for end-users
* Possibility to trim post title to a certain number of words.
* Possibility to trim comment to a specific number of words
* Possibility to split long words into smaller chunks
* WordPress Multi-site compatible (not tested with WPMU)
* And more...

**Get in touch**

* I'm available at [BetterWP.net](http://betterwp.net) and you can also follow me on [Twitter](http://twitter.com/0dd0ne0ut).
* Check out [latest WordPress Tips and Ideas](http://feeds.feedburner.com/BetterWPnet) from BetterWP.net.

**Languages**

* English (default)
* French 1.1.0 (fr_FR) - Thanks to [Maître Mô](http://maitremo.fr)!
* Russian 1.1.0 (ru_RU) - Thanks to Konstantin (kg69design)!
* Ukrainian 1.1.0 (ua_UA) - Thanks to Konstantin (kg69design)!

Please [help translate](http://betterwp.net/wordpress-tips/create-pot-file-using-poedit/) this plugin!

Visit [Plugin's Official Page](http://betterwp.net/wordpress-plugins/bwp-recent-comments/) for more information!

== Installation ==

1. Upload the `bwp-recent-comments` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress. After activation, you should see a menu of this plugin on your left. If you can not locate it, click on Settings under the plugin's name.
1. Configure the plugin
1. Enjoy!

[View instructions with images](http://betterwp.net/wordpress-plugins/bwp-recent-comments/installation/).

== Frequently Asked Questions ==

[Check plugin news and ask questions](http://betterwp.net/topic/bwp-recent-comments/).

== Screenshots ==

1. Showing recent comments using customizable widget
2. Paginated comment list on a separate page with custom template
3. AJAX navigation in a widget (WordPress 3.2's Twenty Eleven Theme)
4. Recent comments grouped by posts
5. The configuration page

== Changelog ==

= 1.2.0 RC1 =
* You can now group comments by post, this is inspired by the classic Get Recent Comment plugin.
* You can now have AJAX navigation for any recent comment list you want! Comment list on a separate page also supports AJAX.
* Templates available for both grouped comments and the AJAX navigation.
* Added a shortcode (`[bwp-rc]`) to show recent comment lists on a separate page.
* More template tags for you to use:
	* `%author_archive%`: link to a comment author's archive page if found.
	* `%comment_count%`: the number of comments for the current post.
	* `%comment_number%`: a comment's number in the list, e.g. 1, 2, 3, etc. Useful when showing comments on a separate page.
* Added a new CSS file that will be used when you disable avatar.
* Added a new filter using which you can apply your own default avatar.
* A lot of bug fixes and other improvements which make the plugin much faster and more stable!

Check out the [release announcement](http://betterwp.net/293-bwp-recent-comments-1-2-0/) for more information!

**Note:** Due to the complexity of this new release, feedbacks are greatly appreciated! If you have any problem, even the smallest, please [contact me](http://betterwp.net/contact/). Thanks!

**Note to translators**: There are a lot of text changes in this version, please update your translations when you have time, thanks!

= 1.1.0 =
* You can now show comments on a separate page, with pagination and custom template!
* You can also get comments from a specific post, using either ID or post name (slug)!
* It's now possible to trim post title to a certain number of words.
* Implemented a new way to exclude certain comment authors, using a user's id.
* Added a translation tag to `%post_title%` to make this plugin compatible with multi-lingual websites/blogs.
* Fixed the non-functional `%post_link%` template tag.
* Other unspecified enhancements and bug fixes ;).

To see the awesomeness of this major release, take a look at the [release announcement](http://betterwp.net/237-bwp-recent-comments-1-1-0/)! Thanks for using this plugin!

= 1.0.1 =
* Fixed the bug that strips legit HTML tags in comment templates. Thanks to [Mike McKoy](http://www.hairwegoproducts.com/)!
* Fixed the bug that prevents empty comment templates.
* Fixed the widget class so that it functions more expectedly.
* Fixed some minor bugs that might cause notice or warning messages. Thanks to **Konstantin**!
* Added a reset instances button that will reset all malformed instances caused by 1.0.0's bugs.
* It is now possible to have HTML in 'no comment' and 'stripped comment' message.
* Comments that belong to trashed posts are no longer included in comment lists.

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 1.0.1 =
* A critical bug fix release, all users are advised to update immediately!

= 1.0.0 =
* Enjoy the plugin!