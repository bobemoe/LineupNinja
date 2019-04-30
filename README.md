# LineupNinja Wordpress Plugin 

Simple plugin to display event lineup data from https://lineup.ninja/ on to a wordpress site.

It will create an new "virtual" page for every `location`, `label`, and `session`.  Contributors do not currently have their own page, but are shown under the session information which they are involved in. 

It will also provide a new shortcode allow embedding of `location`, `label`, `session` and `contributor` into the content of pages.

## Installing & Configure
1. Clone this repo into the wp-content/plugins directory. It should be called lineupninja (lowercase).
2. Copy the config.php.dist file to config.php and edit the settings.

## Configure the WP Plugin.
Global:
* archive : `true` to enable keeping archive copies of the published data (useful for debugging/rolling back). `false` will only keep the current published version.
Feeds:
Define as many feeds as you like (e.g. different events or years)
Note each feed must have and numerical ID (2018 and 2019 in the settings example)
* api_url : the URL from LineupNinja that was given to you when you published your lineup, including your username/password if you set one in your publishing settings. It should look something like `https://username:password@api.lineup.ninja/json/your_publication_url`
* post_parent : the wordpress post ID that you'd like to be the parent of all session and lineup index pages. This will be used for breadcrumb links etc. 
* url_prefix : to keep the virtual pages all in one place and avoid naming conflicts with your real pages, choose a URL prefix here. Recommended to use "/lineup".
* shortcode : the shortcode to use

## Configure Lineup Ninja
In the LineupNinja admin area, go to "Publish" and create a new "json" publication. This will give you an API URL, and optional username and password. You need to enter these into the wordpress plugin settings (see below)

If you want your wordpress site to update each time the "Publish" button is clicked for this publication in LN, then enter the following URL into the callback field: `https://YOURDOMAIN.COM/wp-content/plugins/lineupninja/publish.php` If you leave this out you will have to manually visit the URL (or trigger publish.php from the command line) each time you want your site to update (you will still need to click Publish FIRST in LN too)

## Suggested Usage
Assuming you have a config with `url_prefix: lineup` and `shortcode: LineupNinja`.
Create a wordpress page called "Lineup" with the url "lineup" (matches the url_prefix) 
Put the following content/shortcodes onto the page, feel free to add your own text and customize this page: 

```
<h2>Browse by category:</h2>
[LineupNinja type=labels]
<h2>Browse by venue:</h2>
[LineupNinja type=locations]
```

Find the wordpress ID of this page and add that to the "parent post" in the config. 

This will give you a good overview index page to allow browsing of your sessions. 

## Index and Session Virtual Pages
Assuming you kept "lineup" as your URL prefix in config, the following virtual pages will now be available on your site.

These pages are "virtual", because they are not editable in wordpress admin. The content is pulled from LN and the style/layout is defined in the code of the module. 

* YOURDOMAIN.COM/lineup/location/NAME this will list (index) all the sessions in the `location` NAME. This is equivalent to the shortcode `[LineupNinja type=sessions location=NAME]` see below
* YOURDOMAIN.COM/lineup/label/NAME this will list (index) all the sessions in the `label` NAME. this is equivalent to the shortcode `[LineupNinja type=sessions label=NAME]` see below
* YOURDOMAIN.COM/lineup/session/UUID this will display the session (and contributor) details for the session identified by UUID.

## Shortcodes
* `[LineupNinja type=labels]` lists all your labels, linking of the the Label Index page (/lineup/label/NAME)
* `[LineupNinja type=locations]` lists all your locations, linking off to the Location Index page (/lineup/location/NAME)
* `[LineupNinja type=sessions order=az|time]` lists all your sessions. by default they are ordered alphabetically, use `order=time` to order chronologically. (see additional optional filters below)
* `[LineupNinja type=contributors]` lists all your contributors (see optional filters below)

The `sessions` and `contributors` shortcode have optional parameter to filter on label and/or location. Just add the `labels="X"` or `locations="X"` to the shortcode where X matches the name of a location or label in LN. Comma separated lists are allowed. e.g: `[LineupNinja type=sessions labels=music locations="main stage,acoustic stage"]` Don't forget to add the quotes (") if you have spaces in your names.

## Change Log
v1.0.0
*Initial release.

V1.1.0
*Added support for multiple feeds. config.php not compatable with v1.0.0
