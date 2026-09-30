# Globe Wordpress Plugin

Adds a bunch of custom taxonomy to Wordpress for the Globe CMS

⚠️ There is a lot of copy-and-pasta in this plugin.

## Inside the box

- A WordPress plugin

## Local Setup

1. Download Wordpress from wordpress.org and spin up however you like
2. Clone this repo into `/wp-content/plugins`
3. Enable the plugin in the admin panel
4. Start cooking

## Purpose

- Expose content via WP API
- Adds an edits user profile fields
- Adds extra post types for sermons
- Adds link trees (Linktree style pages) for custom navigation

## WP API

Base: `/wp-json/wp/v2/`

- Pages: `pages?per_page=50`
- Users: `users?per_page=50`
- Posts: `posts?per_page=50`
- Sermons: `sermons?per_page=50`
- Sermon Series: `sermon_series?per_page=50`
- Teams: `teams?per_page=50`
- Team types: `team_types?per_page=50`
- Podcast: `podcast?per_page=50`
- Link trees: `link-trees?per_page=50`

### Link trees

Each tree has a `links` field (in order) and a `featuredImage`:

```json
"links": [
  {
    "title": "About us",
    "description": "Who we are",
    "url": "/about",
    "img": "https://example.com/image.jpg",
    "img_square": "https://example.com/image-800x800.jpg"
  }
]
```

`url` is returned as entered: a full address, or a path on the site such as `/about`. `img` is the original image and `img_square` is a 1:1 centre crop (800×800). Both are `null` if the link has no image.

_Wordpress gotcha: permalinks need to be enabled for this to work- make sure you enable that in the admin panel first_

## Shortcodes

### `globePeople`

```
[globePeople people="1"]
```

Where `people` is a comma seperated list of user ids

## To Do

[x] Add custom field into sermons page for uploading an MP3
[x] Import old content (data only)
[x] Import Series
[x] Import Blog posts
[x] Import Sermons
[x] Give authors profile pictures
[x] Upload old assets to DO bucket
[x] Serving team post type
[] Work out how teams should populate… shortcode or something?
[x] Deploy button should actually do something
[x] Add post author data to API response (name, bio, image)
[x] Add post featured image URL to API response
[] Sermon series custom fields in API endpoint
[] featured image to pages
