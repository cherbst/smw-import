=== SMW Import ===
Contributors: chherbst
Donate link:
Tags: import, smw, json, synchronize
Requires at least: 3.0.0
Tested up to: 3.2
Stable tag: 0.2

Plugin to synchronize a wordpress blog with a JSON source, e.g. a 
Semantic Media Wiki.

== Description ==

This plugin synchronizes information of an Semantic Media Wiki 
with a wordpress blog. 

It is supposed to be used in production without any downtime of the site. 
The way SMW data is imported can be configured using a simple php mapping array.

Until now it supports importing poss, categories, attachments, meta data,
links and full galleries.
Only data which has actually changed will be imported, thus minimizing the 
time on successive imports.

The supported import format is JSON. Thus it could also be used
for data sources other than Semantic Media Wikis, as long as they export their
data in JSON format.


== Installation ==

1. Upload `smw-import` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add data sources in the admin page
1. Edit and adjust the mapping in the `smwimport-mapping.php` file

