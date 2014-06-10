=== Plugin Name ===
Contributors: mister-ben
Tags: brightcove, video, embed, player
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows a Brightcove Video Cloud player to be easily embedded into a post by its player URL.

== Description ==

This allows a Brighcove Video Cloud video to be easily embedded by its player URL. It does not need a Video Cloud Media API key, so works with any Brightcove account edition.


First get a link to the video and player you want to use from the Video Cloud media module.

* Select a video
* In the **Quick video publish** box on the right, select a player
* Under **Copy publishing code**, select **URL**
* Copy the URL 

In the post editor, add the the URL surrounded by `[bc]` and `[/bc]`, for example:

    [bc]http://bcove.me/f8b43rfg[/bc]

It doesn't have to be a bcove.me URL. You can also use a link.brightcove.com URL or the URL of any page that has a Video Cloud player in its HTML, e.g.

    [bc]http://www.brightcove.com[/bc]
    
The player publishing code found at the URL is used as-is. You can override the player width and/or height like this:

    [bc width="240" height="135"]http://bcove.me/f8b43rfg[/bc]

Or override autostart like this:

    [bc autoStart="false"]http://bcove.me/f8b43rfg[/bc]

== Installation ==

1. Either use the automatic installation within wordpress, or upload `brightcove-wordpress-simple.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does this work with HTTPS? =

Yes, HTTP or HTTPS versions of Brightcove's publishing code are used appropriately and automatically. 

= What can I use as a player URL? =

Either the player URL from the studio, or any other URL that has a Video Cloud player publishing page in its HTML.

= I used a URL with a player, but it doesn't work =

If the HTML at the URL does not have a standard Video Cloud player publishing code in its HTML, this won't work. That might be because the player at that URL uses a Flash-only embed, an iframe, or is created dynamically at runtime.

== Screenshots ==

1. Player in post
2. Post editor

== Changelog ==

= 0.8 =
Using DOMDocument::saveXML instead of DOMDocument::saveHTML for PHP < 3.6 compatibility

= 0.7 =
Fixed deprecation notice

= 0.6 =
Tweaked IE8 fix and some additional fixes to video ID detection

= 0.5 =
Fix for IE8

= 0.4 =
First release

== Upgrade Notice ==
Fix for Internet Explorer 8
