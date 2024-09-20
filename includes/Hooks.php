<?php

class AgeClassificationHooks extends Hooks {

	private static $instance;

	private $button_active;
	private $meta_content;
	private $meta_name;
	private $url_site;

	/**
	 * @param GlobalVarConfig $config
	 */
	public function __construct() {

		global $wgAgeClassificationButton, $wgAgeClassificationButtonURL;
		global $wgAgeClassificationMetaContent, $wgAgeClassificationMetaName;

		$this->button_active = ( isset( $wgAgeClassificationButton ) && ( $wgAgeClassificationButton === true ) );
		$this->meta_content = $wgAgeClassificationMetaContent;
		$this->meta_name = $wgAgeClassificationMetaName;
		$this->url_site = $wgAgeClassificationButtonURL;
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
	 */
	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {

		if ( !self::isActive() )  return;

		$skinname = $skin->getSkinName();
		switch ( $skinname ) {
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
		if ( !empty( self::getInstance()->meta_name ) && !empty( self::getInstance()->meta_content ) ) {
			$out->addMeta( self::getInstance()->meta_name, self::getInstance()->meta_content );
		}
	}

	/**
	 * https://www.mediawiki.org/wiki/Manual:Hooks/SkinBuildSidebar
	 *
	 * @param Skin $skin
	 * @param array $bar
	 */
	public static function onSkinBuildSidebar(
		Skin $skin,
		array &$bar
	) {

		if ( !self::isActive() )  return;

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );

		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = self::getInstance()->url_site;
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" />';

		if ( !empty( $url_site ) ) {
			$img_element = '<a href="' . $url_site . '">' . $img_element . '</a>';
		}

		switch ( $skin->getSkinName() ) {
			case 'cologneblue' :
				$img_element = Html::rawElement( 'div', [ 'class' => 'body' ], $img_element );
			break;
		}

		$bar['ageclassification'] = $img_element;
	}

	/**
	 * Load sidebar ad for Monaco skin.
	 *
	 * @return bool
	 */
	public static function onMonacoSidebarEnd( $skin, &$html ) {

		if ( !self::isActive() )  return;

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );

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

	private static function isSupported( $skinname ) {
		return in_array( $skinname, [ 'cologneblue', 'minerva', 'modern', 'monaco', 'monobook', 'timeless', 'vector', 'vector-2022' ] );
	}
}
