# MediaWiki AgeClassification

Die Pflege der MediaWiki-Erweiterung [AgeClassification](https://www.mediawiki.org/wiki/Extension:AgeClassification) wird von WikiMANNia verwaltet.

The maintenance of the MediaWiki extension [AgeClassification](https://www.mediawiki.org/wiki/Extension:AgeClassification) is managed by WikiMANNia.

El mantenimiento de la extensión de MediaWiki [AgeClassification](https://www.mediawiki.org/wiki/Extension:AgeClassification) está gestionado por WikiMANNia.

## Description

Fügt dem Wiki eine Altersklassifizierung von „Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.“ (Voluntary Self-Regulation of Multimedia Service Providers) (FSM) hinzu.

Adds an age classification from “Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.” (Voluntary Self-Regulation of Multimedia Service Providers) (FSM) to the wiki.

## Use

Enable the AgeClassificationButton. Default is `false`.
* `$wgAgeClassificationButton = true;`

Link to FSM website:
* `$wgAgeClassificationURL = "https://www.altersklassifizierung.de";`

Setup of the meta data:
* `$wgAgeClassificationMetaName = "age-de-meta-label";`
* `$wgAgeClassificationMetaContent = "age=0 hash: yourdigitalcode v=1.0 kind=sl protocol=all";`

## Localization

The extension is localized for the languages "de", "en", "es", "fr", "it", "nl", "pt", and "ru".

## Support

Currently, this extension supports the skins Cologne Blue, Modern, MonoBook, Timeless, and Vector.
Further skins may require additional adjustments, which would have to be made in `resources/css/myskin.css`.

## Compatibility

This extension works from REL1_25 and has been tested up to MediaWiki version `1.39.0`.

The [SkinBuildSidebar](https://www.mediawiki.org/wiki/Manual:Hooks/SkinBuildSidebar) hook of several skins no longer allows images and HTML code to be placed in the sidebar.

A solution for this circumstance is not yet known.
As a minimal solution, a simple text link to the “Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.” is now given. The most important thing is that the metadata insertion still works, so missing image in the navigation bar seems to be tolerable.
This occurs in Skins Cologne Blue, Modern, MonoBook and Vector since REL1_37. Skin Timeless still works as usual.

For Skins MonoBook and Vector in REL1_35 and REL1_36 see these [Hacks](https://www.mediawiki.org/wiki/Extension:WimaAdvertising#Hacks).

## Version history

1.0.0

* First public release

1.1.0

* SVG images "fsm-aks.svg" optimized
* Global variable `wgAgeClassificationButtonIMG` removed, now the image in the folder `resources/images` will be accessed.

1.2.0

* Support added for MediaWiki REL1_37

1.3.0

* Support added for Skin "fallback", "timeless", and "vector-2022"
