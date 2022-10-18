# MediaWiki AgeClassification
Adds an age classification from “Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.” (Voluntary Self-Regulation of Multimedia Service Providers) (FSM)

## Use

Enable the AgeClassificationButton. Default is false.
* $wgAgeClassificationButton = true;

Link to FSM website:
* $wgAgeClassificationURL = "www.altersklassifizierung.de";

Setup of the meta data:
* $wgAgeClassificationMetaName = "age-de-meta-label";
* $wgAgeClassificationMetaContent = "age=0 hash: yourdigitalcode v=1.0 kind=sl protocol=all";

## Localization

The extension is localized for the languages "de", "en", "es", "fr", "it", "nl", "pt", and "ru".

## Support

Currently, this extension supports the skins Cologne Blue, Modern, MonoBook and Vector.
Further skins may require additional adjustments, which would have to be made in "resources/css/myskin.css".

## Compatibility

This extension works from REL1_25 and has been tested up to MediaWiki version 1.39.0-rc.1.

The [SkinBuildSidebar](https://www.mediawiki.org/wiki/Manual:Hooks/SkinBuildSidebar) hook of several skins no longer allows images and HTML code to be placed in the sidebar.

A solution for this circumstance is not yet known.
As a minimal solution, a simple text link to the “Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.” is now given. The most important thing is that the metadata insertion still works, so missing image in the navigation bar seems to be tolerable.
This occurs in Skin Vector since REL1_35 and Skins Cologne Blue, Modern and MonoBook since REL1_37. Skin Timeless still works as usual.
