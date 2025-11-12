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
use MediaWiki\Html\Html;

/**
 * @phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
 */
class AgeClassificationHooks implements
	BeforePageDisplayHook,
	SkinAfterPortletHook,
	SkinBuildSidebarHook
{

	private static $instance;

	private bool $button_active;
	private string $meta_content;
	private string $meta_name;
	private string $url_site;

	/**
	 * @param GlobalVarConfig $config
	 */
	public function __construct() {

		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'ageclassification' );

		$this->button_active = ( $config->get( 'AgeClassificationButton' ) === true );
		$this->meta_content = $config->get( 'AgeClassificationMetaContent' );
		$this->meta_name = $config->get( 'AgeClassificationMetaName' );
		$this->url_site = $config->get( 'AgeClassificationButtonURL' );
	}

	private function __clone() { }

	/**
	 * @return self
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			// Erstelle eine neue Instanz, falls noch keine vorhanden ist.
			self::$instance = new self();
		}

		// Liefere immer die selbe Instanz.
		return self::$instance;
	}

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
			case 'citizen' :
			case 'cologneblue' :
			case 'modern' :
			case 'monaco' :
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
			case 'minerva' :
			case 'fallback' :
			break;
			default :
				wfLogWarning( 'Skin ' . $skinname . ' not supported by AgeClassification.' . "\n" );
			break;
		}

		// FSM-Altersklassifizierungssystems: www.altersklassifizierung.de
		if ( !empty( $this->meta_name ) && !empty( $this->meta_content ) ) {
			$out->addMeta( $this->meta_name, $this->meta_content );
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

		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'main' );

		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = $this->url_site;
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" />';

		if ( !empty( $url_site ) ) {
			$img_element = '<a href="' . $url_site . '">' . $img_element . '</a>';
		}

		$sidebar_element['ageclassification'] = $img_element;

		switch ( $skin->getSkinName() ) {
			case 'cologneblue' :
				$img_element = Html::rawElement( 'div', [ 'class' => 'body' ], $img_element );
				$sidebar_element['ageclassification'] = $img_element;
			case 'citizen' :
			case 'modern' :
			case 'monaco' :
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

		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = $this->url_site;

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
			case 'citizen' :
			case 'cologneblue' :
			case 'modern' :
			case 'monaco' :
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

	/**
	 * Load sidebar ad for Monaco skin.
	 *
	 * @return bool
	 */
	public static function onMonacoSidebarEnd( $skin, &$html ) {

		if ( !self::isActive() )  return;

		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'main' );

		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = wfMessage( 'ageclassification-msg' )->text();
		$url_site = self::getInstance()->url_site;
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" height=auto width=206 />';

		if ( !empty( $url_site ) ) {
			$img_element = '<a href="' . $url_site . '">' . $img_element . '</a>';
		}

		$html .= "<p>$txt_site</p>";
		$html .= $img_element;

		return true;
	}

	private static function isActive() {

		return self::getInstance()->button_active;
	}
}
