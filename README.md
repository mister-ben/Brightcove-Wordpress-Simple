Simple Wordpress Plugin for Wordpress
=====================================
This is intended as a simple way to add a player to Wordpress. You just need to get the URL for the video from the Video Cloud studio.

# Installation
* In Wordpress Admin, go to **Plugins > Add New** then click on the link to upload a plugin from a zip file.  
* Upload the zip file from the dist folder
* Activate the plugin

# Usage
First get a link to the video and player you want to use from the Video Cloud media module.

* Select a video
* In the **Quick video publish** box on the right, select a player
* Under **Copy publishing code**, select **URL**
* Copy the URL 

In Wordpress's post editor, add the the URL surrounded by `[bc]` and `[/bc]`, for example:

    [bc]http://bcove.me/f8b43rfg[/bc]

It doesn't have to be a bcove.me URL.) You can also use the URL of any page that has a Video Cloud player in its HTML, e.g.

    [bc]http://www.brightcove.com[/bc]
    
The player publishing code found at the URL is used as-is. You can override the player width and/or height like this:

    [bc width="240" height="135"]http://bcove.me/f8b43rfg[/bc]

Or override autostart like this:

    [bc autoStart="false"]http://bcove.me/f8b43rfg[/bc]

# Notes
This will use the HTTP or HTTPS version of the publsihing code as appropriate.

This might not trap every error situation.

It doesn't do anything fancy like listing the videos in your account. Maybe that will come later.

Report bugs [here](https://github.com/Brightcodes/Brightcove-Wordpress/issues).

# Changes
## 0.3
* Added option to override autostart
## 0.2
* Added URL validation
* Added ability to override height/width