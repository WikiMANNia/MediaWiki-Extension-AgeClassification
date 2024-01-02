<?php
/**
 * Hooks for AgeClassification extension
 *
 * @file
 * @ingroup Extensions
 */

use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Skins\Hook\SkinAfterPortletHook;
use MediaWiki\Hook\SkinBuildSidebarHook;
use MediaWiki\MediaWikiServices;

/**
 * @phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
 */
class AgeClassificationHooks implements
	BeforePageDisplayHook,
	SkinAfterPortletHook,
	SkinBuildSidebarHook
{

	/**
	 * https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return void This hook must not abort, it must return no value
	 */
	public function onBeforePageDisplay( $out, $skin ) : void {

		if ( !self::isActive() )  return;

		$skinname = $skin->getSkinName();
		switch ( $skinname ) {
			case 'cologneblue' :
			case 'modern' :
			case 'monobook' :
			case 'timeless' :
				$out->addModuleStyles( 'ext.ageclassification.common' );
				$out->addModuleStyles( 'ext.ageclassification.' . $skinname );
			break;
			case 'vector' :
			case 'vector-2022' :
				$out->addModuleStyles( 'ext.ageclassification.common' );
				$out->addModuleStyles( 'ext.ageclassification.vector' );
			break;
			case 'fallback' :
			break;
			default :
				wfLogWarning( 'Skin ' . $skinname . ' not supported by AgeClassification.' . "\n" );
			break;
		}

		// FSM-Altersklassifizierungssystems: www.altersklassifizierung.de
		global $wmAgeClassificationMetaName, $wmAgeClassificationMetaContent;
		if ( !empty( $wmAgeClassificationMetaName ) && !empty( $wmAgeClassificationMetaContent ) ) {
			$out->addMeta( $wmAgeClassificationMetaName, $wmAgeClassificationMetaContent );
		}
	}

	/**
	 * This hook is called when generating portlets.
	 * It allows injecting custom HTML after the portlet.
	 *
	 * @param Skin $skin
	 * @param string $portletName
	 * @param string &$html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinAfterPortlet( $skin, $portletName, &$html ) {

		if ( !self::isActive() )  return;

		global $wmAgeClassificationButtonURL;

		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'main' );
		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = '';
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" />';

		if ( !empty( $wmAgeClassificationButtonURL ) ) {
			$url_site = $wmAgeClassificationButtonURL;
			$img_element = '<a href="' . $url_site . '">' . $img_element . '</a>';
		}

		$sidebar_element['ageclassification'] = $img_element;

		switch ( $skin->getSkinName() ) {
			case 'cologneblue' :
				$img_element = Html::rawElement( 'div', [ 'class' => 'body' ], $img_element );
				$sidebar_element['ageclassification'] = $img_element;
			case 'modern' :
			case 'monobook' :
			case 'timeless' :
			case 'vector' :
			case 'vector-2022' :
				if ( array_key_exists( $portletName, $sidebar_element ) ) {
					$element = $sidebar_element[$portletName];
					if ( !empty( $element ) ) {
						$html = $element;
						return true;
					}
				}
			break;
		}
	}

	/**
	 * https://www.mediawiki.org/wiki/Manual:Hooks/SkinBuildSidebar
	 *
	 * @param Skin $skin
	 * @param array &$bar Sidebar contents. Modify $bar to add or modify sidebar portlets.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinBuildSidebar( $skin, &$bar ) {

		if ( !self::isActive() )  return;

		global $wmAgeClassificationButtonURL;

		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = '';

		if ( !empty( $wmAgeClassificationButtonURL ) ) {
			$url_site = $wmAgeClassificationButtonURL;
		}

		$txt_item = [
			'text'   => $txt_site,
			'href'   => $url_site,
			'id'     => 'n-ageclassification',
			'active' => true
		];
		$empty_item = [
			'text'   => '',
			'id'     => 'n-ageclassification',
			'active' => true
		];

		$sidebar_element = [];

		switch ( $skin->getSkinName() ) {
			case 'cologneblue' :
			case 'modern' :
			case 'monobook' :
			case 'vector' :
			case 'vector-2022' :
			break;
			case 'timeless' :
				// Dirty hack for skin Timeless
				$sidebar_element = [ $empty_item ];
			break;
			default :
				$sidebar_element = [ $txt_item ];
			break;
		}

		$bar['ageclassification'] = $sidebar_element;
	}

	private static function isActive() {
		global $wmAgeClassificationButton;

		return ( isset( $wmAgeClassificationButton ) && ( ( $wmAgeClassificationButton === true ) || ( $wmAgeClassificationButton === 'true' ) ) );
	}

	private static function isSupported( $skinname ) {
		return in_array( $skinname, [ 'cologneblue', 'modern', 'monobook', 'timeless', 'vector', 'vector-2022' ] );
	}
}
