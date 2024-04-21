# MediaWiki AgeClassification

Die Pflege der MediaWiki-Erweiterung [AgeClassification](https://www.mediawiki.org/wiki/Extension:AgeClassification) wird von WikiMANNia verwaltet.

The maintenance of the MediaWiki extension [AgeClassification](https://www.mediawiki.org/wiki/Extension:AgeClassification) is managed by WikiMANNia.

El mantenimiento de la extensión de MediaWiki [AgeClassification](https://www.mediawiki.org/wiki/Extension:AgeClassification) está gestionado por WikiMANNia.

## Description

Fügt dem Wiki eine Altersklassifizierung von „Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.“ (Voluntary Self-Regulation of Multimedia Service Providers) (FSM) hinzu.

Adds an age classification from “Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.” (Voluntary Self-Regulation of Multimedia Service Providers) (FSM) to the wiki.

## Use

Enable the AgeClassificationButton. Default is `false`.
* `$wmAgeClassificationButton = true;`

Link to FSM website:
* `$wmAgeClassificationURL = "https://www.altersklassifizierung.de";`

Setup of the meta data:
* `$wmAgeClassificationMetaName = "age-de-meta-label";`
* `$wmAgeClassificationMetaContent = "age=0 hash: yourdigitalcode v=1.0 kind=sl protocol=all";`

## Localization

The extension is localized for the languages "de", "en", "es", "fr", "it", "nl", "pt", and "ru".

## Support

Currently, this extension supports the skins Cologne Blue, Modern, MonoBook, Timeless, and Vector.
Further skins may require additional adjustments, which would have to be made in `resources/css/myskin.css`.

## Compatibility

This extension works from REL1_35 and has been tested up to MediaWiki version `1.35.13`, `1.39.5` and `1.40.1`.

## Version history

1.0.0

* First public release

1.1.0

* SVG images "fsm-aks.svg" optimized
* Global variable `wmAgeClassificationButtonIMG` removed, now the image in the folder `resources/images` will be accessed.

1.2.0

* Support added for MediaWiki REL1_37

1.3.0

* Support added for Skin "fallback", "timeless", and "vector-2022"

1.3.1

* A customised skin may be used.

1.4.0

- Support for REL1_35+ added.

1.5.0

- Changed "configuration schema", replaced manifest version 1 with version 2 and changed the prefix of the configuration variables from default to `wm`.
- Replaced class “AgeClassificationHooks” extending class “Hooks” with class implementing interfaces.

1.5.1

- Dirty hack for skin [Timeless](https://www.mediawiki.org/wiki/Skin:Timeless).

1.5.2

- `MediaWiki\Config\ConfigFactory::getDefaultInstance` -> `MediaWikiServices::getInstance()->getConfigFactory()`
- Remove dead code.

1.6.0

- Dirty hack for skin [Monaco](https://www.mediawiki.org/wiki/Skin:Monaco).
