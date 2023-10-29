# Globe Wordpress Plugin

Adds a bunch of custom taxonomy to Wordpress for the Globe CMS

⚠️ There is a lot of copy-and-pasta in this plugin.

## Inside the box

- A WordPress plugin

## Local Setup

1. Download Wordpress from wordpress.org and spin up however you like
2. Clone this repo into `/wp-content/themes`
3. Enable the plugin in the admin panel
4. Start cooking

## Purpose

- Expose content via WP API
- Adds an edits user profile fields
- Adds extra post types for sermons

## WP API

Base: `/wp-json/wp/v2/`

- Pages: `pages?per_page=50`
- Users: `users?per_page=50`
- Posts: `posts?per_page=50`
- Sermons: `sermons?per_page=50`
- Sermon Series: `sermon_series?per_page=50`

## ToDo

[x] Add custom field into sermons page for uploading an MP3
[x] Import old content (data only)
[x] Import Series
[x] Import Blog posts
[x] Import Sermons
[x] Give authors profile pictures
[x] Upload old assets to DO bucket
[] Work out how teams should populate… shortcode or something?
[x] Deploy button should actually do something
[x] Add post author data to API response (name, bio, image)
[x] Add post featured image URL to API response
[] Sermon series custom fields in API endpoint
[] featured image to pages
