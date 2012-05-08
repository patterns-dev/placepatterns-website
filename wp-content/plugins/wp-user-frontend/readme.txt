=== WP User Frontend ===
Contributors: tareq1988
Donate link: http://tareq.wedevs.com
Tags: frontend, post, edit, dashboard, restrict
Requires at least: 3.3
Tested up to: 3.3.1
Stable tag: 0.7

Create, update, delete posts and edit profile from wordpress frontend.

== Description ==

Some of us want something like that the subscriber/contributor will not be able to go in the
wordpress backend and everything these user can control will be done from wordpress frontend.

= Features:  =

So here is my plugin that solves your problem. This features of this plugin are follows:

* User can create a new post and edit from frontend
* They can view their page in the custom dashboard
* Users can edit their profile
* Administrator can restrict any user level to access the wordpress backend (/wp-admin)
* New posts status, submitted by users are configurable via admin panel. i.e. Published, Draft, Pending
* Admin can configure to receive notification mail when the users creates a new post.
* Configurable options if the user can edit or delete their posts.
* Users can upload attachments from the frontend
* Admins can manage their users from frontend
* Pay per post or subscription on posting is possible


== Installation ==

This section describes how to install the plugin and get it working.

1. Create a new Page “New Post” and insert shorcode `[wpuf_addpost]`
2. Create a new Page “Edit” for editing posts and insert shorcode `[wpuf_edit]`
3. Create a new Page “Profile” for editing profile and insert shorcode `[wpuf_editprofile]`
4. Create a new Page “Dashboard” and insert shorcode `[wpuf_dashboard]`
5. Correct the permalink structure of your wordpress installation
6. Insert the “Edit” Page url to the admin options page of the plugin
7. To show the subscription info, insert the shortcdoe `[wpuf_sub_info]`
8. To show the subscription packs, insert the shortcode `[wpuf_sub_pack]`
9. For subscription payment page, create a new page and insert the page ID in WP User frontend's "Paypal Payment Page" option.


== Screenshots ==
1. Admin panel
2. User Dashboard
3. Add Post
4. Edit Posts
5. Edit Profile
6. Custom Field Manager
7. Subscription Pack Manager
8. Subscription packs
9. Edit Users

== Frequently Asked Questions ==

= Can I create new posts from frontend =

Yes

= Can I Edit my posts from frontend =

Yes

= Can I delete my posts from frontend =

Yes

= Can I upload photo/image/video =
Yes

= I am having problem with uploading files =
Please check if you've specified the max upload size on setting

= Why "Edit Post" page shows "invalid post id"?=
This page is for the purpose of editing posts. You shouldn't access this page directly.
First you need to go to the dashboard, then when you click "edit", you'll be
redirected to the edit page with that post id. Then you'll see the edit post form.


== Changelog ==

= version 0.7 =

* admin ui improved
* updated new post notification mail template
* custom fields and attachment show/hide in posts
* post edit link override option
* ajax "posting..." changed
* attachment fields restriction in edit page
* localized ajaxurl and posting message
* improved action hooks and filter hooks

= version 0.6 =
---------------

* fixed error on attachment delete
* added styles on dashboard too
* fixed custom field default dropdown
* fixed output buffering for add_post/edit_post/dashboard/profile pages
* admin panel scripts are added wp_enqueue_script instead of echo
* fixed admin panel block logic
* filter hook added on edit post for post args

= version 0.5 =

* filters on add posting page for blocking the post capa
* subscription pack id added on user meta upon purchase
* filters on add posting page for blocking the post capa
* option for force pack purchase on add post. dropdown p
* subscription info on profile edit page
* post direction fix after payment
* filter added on form builder


= version 0.4 =

* missing custom meta field added on edit post form
* jQuery validation added on edit post form

= version 0.3 =

* rich/plain text on/off fixed
* ajax chained category added on add post form
* missing action added on edit post form
* stripslashes on admin/frontend meta field
* 404 error fix on add post

= version 0.2 =

* Admin settings page has been improved
* Header already sent warning messages has been fixed
* Now you can add custom post meta from the settings page
* A new pay per post and subscription based posting options has been introduced (Only paypal is supported now)
* You can upload attachment with post
* WYSIWYG editor has been added
* You can add and manage your users from frontend now (only having the capability to edit_users )
* Some action and filters has been added for developers to add their custom form elements and validation
* Pagination added in post dashboard
* You can use the form to accept "custom post type" posts. e.g: [wpuf_addpost post_type="event"]. It also applies for showing post on dashboard like "[wpuf_dashboard post_type="event"]"
* Changing the form labels of the add post form is now possible from admin panel.
* The edit post page setting is changed from URL to page select dropdown.
* You can lock certain users from posting from their edit profile page.

== Upgrade Notice ==

Nothing to say
