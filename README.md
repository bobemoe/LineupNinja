# LineupNinja Wordpress Plugin 

Simple plugin to display event lineup data from https://lineup.ninja/ on to a wordpress site.

It will create an new "virtual" page for every `location`, `label`, and `session`.  Contributors do not have their own page, but are shown under the session information which they are involved in. 

It will also provide a new shortcode allow embedding of `location`, `label`, `session` and `contributor` into the conent of pages.

## Installing
1. Clone this repo into the wp-content/plugins directory. It should be called lineupninja (lowercase).
2. Copy the config.php.dist file to config.php and edit the settings.

## Configure Lineup Ninja
In the LineupNinja admin area, go to "Publish" and create a new "json" publication. This will give you an API URL, and optinal username and password. You need to enter these into the wordpress plugin settings (see below)

If you want your wordpress site to update each time the "Publish" button is clicked in LN, then enter the following URL into the callback field: `https://yourdomain.com/wp-content/plugins/lineupninja/publish.php` If you leave this out you will have to manually visit the URL each time you want your site to update (you will still need to click Publish in LN too)

## Settings
* api_url : the URL from LineupNinja that was given to you when you published your lineup, including your username/password if you set one in your pubsishing settings. It should look something like https://username:password@api.lineup.ninja/json/your_publication_url
* post_parent : the wordpress post ID that you'd like to be the parent of all session and lineup index pages. This will be used for breadcrumb links etc. 
* url_prefix : to keep the virtual pages all in one place and avoid naming conflicts with your real pages, choose a URL prefix here. Recommended to use "/lineup".
* archive : `true` to enable keeping archive copies of the published data (useful for debugging/rolling back). `false` will only keep the current published version.

## Suggested Usage
Create a wordpress page called "Lineup" with the url "/lineup". Put the following content/shortcodes onto the page, feel free to add your own text and customize this page: 

```
<h2>Browse by category:</h2>
[LineupNinja type=labels]
<h2>Browse by venue:</h2>
[LineupNinja type=locations]
```

Find the wordpress ID of this page and add that to the "parent post" in the config. 

This will give you a good overview index page to allow browsing of your sessions. 

## Index and Session Virtual Pages
Assuming you kept "/lineup" as your URL prefix in config, the following virtual pages will now be available on your site:

These pages are Virtual, becase they are not editable in wordpress admin. The content is pulled from LN and the style/layout is defined in the code of the module. 

* yourdomain.com/lineup/location/NAME this will list (index) all the sessions in the `location` NAME. This is equivelent to the shortcode `[LineupNinja type=sessions location=NAME]` see below
* yourdomain.com/lineup/label/NAME this will list (index) all the sessions in the `label` NAME. this is equivelent to the shortcode `[LineupNinja type=sessions label=NAME]` see below
* yourdomain.com/lineup/session/UUID this will display the session (and contributor) details for session UUID.

## Shortcodes
* `[LineupNinja type=labels]` lists all your labels, linking of the the Label Index page (/lineup/label/NAME)
* `[LineupNinja type=locations]` lists all your locations, linking off to the Location Index page (/lineup/location/NAME)
* `[LineupNinja type=sessions]` lists all your sessions (see optional filters below)
* `[LineupNinja type=contributors]` lists all your contributors (see optional filters below)

The `sessions` and `contributors` shortcode have optional paramater to filter on label and/or location. Just add the `labels="X"` or `locations="X"` to the shortcode where X matches the name of a location or label in LN. Comma seperated lists are allowed. e.g: `[LineupNinja type=sessions labels=music locations="main stage,acoustic stage"]` Don't forget to add the quotes (") if you have spaces in your names.

