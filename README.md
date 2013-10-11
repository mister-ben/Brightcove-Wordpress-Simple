Brightcove-Wordpress
====================
`brightcove-wordpress-simple.php` is intended as a simple way to add a player to Wordpress.

# Installation
* In Worpress Admin, go to **Plugins > Add New** then click on the link to upload a plugin from a zip file.  
* Upload the zip file
* Activate the plugin

# Usage
First get a link to the video and player you want to use from the Video Cloud media module.

* Select a video
* In the **Quick video publish** box on the right, select a player
* Under **Copy publishing code**, select **URL**
* Copy the URL 

In Worpress's post editor, add the the URL surrounded by `[bc]` and `[/bc]`, for example:

    [bc]http://bcove.me/f8b43rfg[/bc]

You can also use the URL of any page that has a Video CLoud player in its HTML, e.g.

    [bc]http://www.brightcove.com[/bc]

# Notes
This will use the HTTP or HTTPS version of the publsihing code as appropriate.

This just uses the Brightcove player object found in the HTML at the URL given. It uses the parameters given there, including width and height. I'll probably add the option to override at least some of the params at a later date.

This might not trap every error situation.

It doesn't do anything fancy like listing the videos in your account. Maybe that will come later.

Report bugs [here](https://github.com/Brightcodes/Brightcove-Wordpress/issues).