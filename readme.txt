=== Designer Pages ===
Contributors: nickohrn
Tags: widget, admin, designer, api, external
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: 1.0.0

Allows you to easily add a widget from a Designer Pages (http://www.designerpages.com) collection to your WordPress website.

== Description ==

'''Note''': This plugin requires cURL or the ability to fetch remote files by their URL.

The Designer Pages widget allows you to easily integrate your favorite collection from www.designerpages.com into
your WordPress site.  The process is simple, and the widget is tweakable in many ways.  You can change

* Header Font Color
* Header Background Color
* Font Color
* Background Color
* Border Color
* Collection to fetch from
* Font Family
* Width
* Number of Products

When tweaking these parameters the plugin gives you a handy live preview to see how your widget will look when activated
and displayed on your blog.

== Installation ==

1. Upload `designer-pages` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the widget via the `Designer Pages` settings menu
1. Place the designer pages in one of your dynamic sidebars or...
1. Use the template tag `designer_pages_widget` to output the appropriate HTML for the widget - <?php designer_pages_widget(); ?>