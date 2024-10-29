# Alpine Photo Tile for Social
* Contributors: theAlpinePress
* Tags: photos, instagram, gallery, wall, lightbox
* Requires at least: 3.2
* Tested up to: 5.4.2
* Stable tag: 0.3.0
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

**Simple, stylish, and compact plugin for displaying Instagram photos in a sidebar, post, or page.**

== Description ==

Upgraded to Latest API (Instagram Graph API) - 10 Jul 2020

[Demo](http://thealpinepress.com/alpine-phototile-for-social/ "Plugin Demo")

**Alpine Photo Tile for Social Features:**

* One-Click connect. No manual entring access token.
* Unlimited accounts, no limit
* No Shortcode management, create or edit the fly.
* Multiple styles to allow for customization
* Display photos in a sidebar, post, or page
* Lighbox feature for interactive slideshow (Magnific, FancyBox, Colorbox)
* Simple instructions for customizing features
* Widget & shortcode options
* Feed caching/storage for improved page loading


Retrieve photos from any Instagram user you have account access and display them on your WordPress site using the Alpine Photo Tile for Social.
The photos can be linked to the your Instagram page, a specific URL, or to a Lightbox slideshow.
Also, the Shortcode Generator makes it easy to insert the widget into posts without learning any of the code.
This lightweight but powerful widget takes advantage of WordPress's built in JQuery scripts to create a sleek presentation that I hope you will like.



**Quick Start Guide:**

1. After installing the Alpine PhotoTile plugin on your WordPress site, make sure it is activated by logging into your admin area and going to Plugins in the left menu.
2. Before using the plugin, you must authorize your WordPress website to access your Instagram account by adding a user. This can be done by going to *Settings->AlpineTile: Instagram->Add User* and following the directions on the page.
3. To add the plugin to a sidebar, go to *Appearance->Widgets* in the left menu.
4. Find the rectangle labeled Alpine PhotoTile. Click and drag the rectangle to one of the sidebar containers on the right.
5. Once you drop the rectangle in a sidebar area, it should open to reveal a menu of options. The only required information for the plugin to work is a Username. Select an available ID and click save in the right bottom corner of the menu.
6. Open another page/window in your web browser and navigate to your WordPress site to see how the sidebar looks with the Alpine PhotoTile included.
7. Play around with the various styles and options to find what works best for your site.


# TODOs (in progress)

* Add caption to all available styles, right now only shows on Magnific Popup
* Load more option

If you find this plugin useful, care to leave a review.
If you have any more questions, please open a support ticket.

I am a one-man development team and I distribute these plugins for free, so please be patient with me.

== Installation ==

**Plugin Installation**

1. Go to the *Plugins->Add New* section of the Admin Panel.
2. Either search for "Alpine Photo Tile for Social" or upload the `alpine-photo-tile-for-instagram` folder using the upload tool.
3. Go to the *Plugins->Installed Plugins* and activate the plugin.

**Add a User**

4. Instagram is quite protective of its users. Before your WordPress website can retrieve images from Instagram, you must authorize your WordPress site to access your Instagram account. On the *Plugins->Installed Plugins* page, click on "Add User" under "Alpine Photo Tile for Social". You will be directed to one of the Instagram plugin's "Add Instagram User" page.
5. Follow the directions on the "Add User" page to add and save a user to the plugin.


**Using the Widget**

6. Use the widget like any other widget. Go to *Appearance->Widgets* in the left menu. Find the rectangle labeled "Alpine PhotoTile". Click and drag the rectangle to one of the sidebar containers on the right.
7. Customize Alpine PhotoTile plugin based on your preference.

**Using the Shortcode**

8. A shortcode is a line of texted used for loading plugins within WordPress pages or posts. Rather than explaining how to setup the shortcode, I have added a tool to the Alpine PhotoTile plugin that generates the shortcode for you. Visit the "Shortcode Generator" on the plugin's settings page (*Settings->AlpineTile: Instagram->Shortcode Generator*).

== Frequently Asked Questions ==

**I'm getting the message "Instagram feed was successfully retrieved, but no photos found". What does that mean?**

This message simply means that while no distinguishable errors occurred, the plugin found your feed to be empty.

**Can I insert the plugin in posts or pages? Is there a shortcode function?**

Yes, you can display photos in posts or pages using what is called a shortcode. Rather than explaining how to setup the shortcode, I have added a tool to the Alpine PhotoTile plugin that generates the shortcode for you. Visit the "Shortcode Generator" on the plugin's settings page (*Settings->AlpineTile: Instagram->Shortcode Generator*).

**Why doesn't the widget show my most recent photos?**

By default, the plugin caches or stores the feed for three hours (see Caching above). If the new photos have still not appeared after this time, it is possible that Instagram is responsible for the delay.



== Screenshots ==

1. Gallery Layout
2. Cascade Layout
3. Wall Layout
4. Popup


== Changelog ==

= 0.3.0 =
* One-Click account connection

= 0.2.4 =
* Upgraded to Latest Alpine (Instagram Basic Display)

= 0.2.3 =
* Remove Manual Curl
* Fixed Profile image, instagram link on Maginific popup

= 0.2.2 =
* Fixed Profile image in admin area

= 0.2.1 =
* Client id santized as text value instead of integer

= 0.2 =
* Replaced check_admin_referer with wp_verify_nonce

= 0.1 =
* Updated code of https://wordpress.org/plugins/alpine-photo-tile-for-instagram/
* Releasing new version due to Instagram Copyright issues
