# LineupNinja Wordpress Plugin 
Simple plugin to display lineup data from https://lineup.ninja/ on a wordpress site.

## Installing
1. Clone into the wp-content/plugins directory.
2. Copy the config.php.dist file to config.php and edit the settings.

## Suggested Usage
Create a wordpress page called "Lineup" with the url "/lineup". Put the following content/shortcodes onto the page: 

```
<h2>Browse by category:</h2>
[LineupNinja type=labels]
<h2>Browse by venue:</h2>
[LineupNinja type=locations]
```

Find the wordpress ID of this page and add that to the "parent post" in the config. 

This will give you a good overview index page to allow browsing of your sessions. 

## Index and Session pages
Assuming you kept "/lineup" as your URL prefix in config, the following pages will now be available on your site:
* yourdomain.com/lineup/location/NAME this will list (index) all the sessions in the `location` NAME. This is equivelent to the shortcode `[LineupNinja type=sessions location=NAME]` see below
* yourdomain.com/lineup/label/NAME this will list (index) all the sessions in the `label` NAME. this is equivelent to the shortcode `[LineupNinja type=sessions label=NAME]` see below
* yourdomain.com/lineup/session/UUID this will display the session details for session UUID of the session to display.

## Shortcode
* `[LineupNinja type=labels]` lists all your labels, linking of the the Label Index page (/lineup/label/NAME)
* `[LineupNinja type=locations]` lists all your locations, linking off to the Location Index page (/lineup/location/NAME)
* `[LineupNinja type=sessions]` lists all your sessions (see optional filters below)
* `[LineupNinja type=contributors]` lists all your contributors (see optional filters below)

Type sessions and contributors have optional paramater to filter on label or location.
TODO: document them

