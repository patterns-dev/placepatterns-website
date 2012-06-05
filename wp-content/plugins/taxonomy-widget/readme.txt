=== Taxonomy Widget ===

Contributors: mfields
Donate link: http://wordpress.mfields.org/donate/
Tags: taxonomy, tag, category, widget, cloud, dropdown
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: trunk

Creates widgets for any public taxonomy. Display terms as an ordered list, unordered list, term cloud or dropdown menu.

==Description==
The Taxonomy Widget Plugin enables users to create widgets in their sidebar that display all terms of any given taxonomy. Users can choose between 3 different templates including two types of lists, a term cloud or a dropdown menu.


= Options =
__Title__ - You can enter a custom title for your widget in this text input. If you leave this field blank, The name of the taxonomy will be used. If you do not want a title displayed at all, you can toggle this by un-checking the *Display title* box under *Advanced Options*.

__Taxonomy__ - You can select the taxonomy whose terms you would like displayed by selecting it from the dropdown menu.

__Template__ - Select a template for your terms by selecting one of the radio buttons in the *Display Taxonomy As* section.

__Display title__ - If checked the title will be displayed. Un-checking this option will hide the title. Defaults to checked.

__Show post counts__ - If checked, the number of posts associated with each term will be displayed to the right of the term name in the template. This option has no effect in the *cloud* template.

__Show hierarchy__ - If checked, the terms will be indented from the left if they are children of other terms. This option has no effect in the *cloud* template.


== Screenshots ==
1. Administration view.


= Support =

If you have questions about integrating this plugin into your site, please [add a new thread to the WordPress Support Forum](http://wordpress.org/tags/taxonomy-widget?forum_id=10#postform). I try to answer these, but I may not always be able to. In the event that I cannot there may be someone else who can help.

= Bugs, Suggestions =

Development of this plugin is hosted in a public repository on [Github](https://github.com/mfields/taxonomy-widget). If you find a bug in this plugin or have a suggestion to make it better, please [create a new issue](https://github.com/mfields/taxonomy-widget/issues/new)

= Need More Taxonomy Plugins? =

I've released a handfull of plugins dealing with taxonomies. Please see my [plugin page](http://wordpress.org/extend/plugins/profile/mfields) for more info.

==Installation==

1. Download
1. Unzip the package and upload to your /wp-content/plugins/ directory.
1. Log into WordPress and navigate to the "Plugins" panel.
1. Activate the plugin.


==Changelog==

= 0.6.1 =
* Set value of the "taxonomies" property a bit later in the action sequence. 

= 0.6 =
* Cleanup.
* Provide alternative default if categories are disabled.
* Do not register widget if no taxonomies are registered.

= 0.5.1 =
* stupid comma ...
* another stupid comma !!!

= 0.5 =
* Better escaping throughout.
* Use get_term_link() for javascript redircts.

= 0.4 =
* Never officially released.
* Dropped support for 2.9 branch.
* Removed mfields_walk_taxonomy_dropdown_tree().
* Removed mfields_dropdown_taxonomy_terms().
* Removed global variables.
* Moved javascript listeners into mfields_taxonomy_widget class.
* Create mfields_taxonomy_widget::clean_args() to sanitize user input.
* Removed mfields_taxonomy_widget::sanitize_template().
* Removed mfields_taxonomy_widget::sanitize_taxonomy().
* Tag clouds will now only display if their setting allow.
* Tested with post formats.
* Removed mfields_taxonomy_widget::get_query_var_name() using $taxonomy->query_var instead.

= 0.3 =
* Added filters for show_option_none text in dropdown.

= 0.2.2 =
* Don't remember. Sorry...

= 0.2.1 =
* __BUGFIX:__ Dropdown now displays 'Please Choose' on non-taxonomy views.

= 0.2 =
* Now compatible with WordPress Version 2.9.2.

= 0.1 =
* Original Release - Works With: wp 3.0 Beta 2.
