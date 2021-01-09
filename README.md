# MediaWiki AgeClassification
Adds an age classification from “Freiwillige Selbstkontrolle Multimedia-Diensteanbieter e.V.” (Voluntary Self-Regulation of Multimedia Service Providers) (FSM)

## Use

Enable the AgeClassificationButton. Default is false.
* $wgAgeClassificationButton = true;

Link to FSM website:
* $wgAgeClassificationURL = "www.altersklassifizierung.de";

Link to FSM classification image:
* $wgAgeClassificationIMG = "yourdomain.org/resources/image/fsm-aks148.png";

Setup of the meta data:
* $wgAgeClassificationMetaName = "age-de-meta-label";
* $wgAgeClassificationMetaContent = "age=0 hash: yourdigitalcode v=1.0 kind=sl protocol=all";

## Localization

The extension is localized for the languages "de", "en", "es", "fr", "it", "nl", "pt" and "ru".

## Support

Currently, this extension supports the skins Cologne Blue, Modern, MonoBook and Vector.
Further skins may require additional adjustments, which would have to be made in "resources/css/myskin.css".