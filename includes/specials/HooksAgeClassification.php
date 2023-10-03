<?php

class AgeClassificationHooks extends Hooks {

	/**
	 * Hook: BeforePageDisplay
	 * @param OutputPage $out
	 * @param Skin $skin
	 * https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 */
	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {

		global $wgVersion;

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
		global $wgAgeClassificationMetaName, $wgAgeClassificationMetaContent;
		if ( !empty( $wgAgeClassificationMetaName ) && !empty( $wgAgeClassificationMetaContent ) ) {
			$out->addMeta( $wgAgeClassificationMetaName, $wgAgeClassificationMetaContent );
		}
	}

	/**
	 * This hook is called when generating portlets.
	 * It allows injecting custom HTML after the portlet.
	 *
	 * @since 1.35
	 *
	 * @param Skin $skin
	 * @param string $portletName
	 * @param string &$html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public static function onSkinAfterPortlet( $skin, $portletName, &$html ) {

		if ( !self::isActive() )  return;

		global $wgAgeClassificationButtonURL;
		global $wgVersion;

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = '';
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" />';

		if ( !empty( $wgAgeClassificationButtonURL ) ) {
			$url_site = $wgAgeClassificationButtonURL;
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
	 * Hook: SkinBuildSidebar
	 * @param Skin $skin
	 * @param array $bar
	 * https://www.mediawiki.org/wiki/Manual:Hooks/SkinBuildSidebar
	 */
	public static function onSkinBuildSidebar(
		Skin $skin,
		array &$bar
	) {

		if ( !self::isActive() )  return;

		global $wgAgeClassificationButtonURL;
		global $wgVersion;

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = '';

		if ( !empty( $wgAgeClassificationButtonURL ) ) {
			$url_site = $wgAgeClassificationButtonURL;
		}

		$txt_element = [
			'text'   => $txt_site,
			'href'   => $url_site,
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
			default :
				$sidebar_element = [ $txt_element ];
			break;
		}

		$bar['ageclassification'] = $sidebar_element;
	}

	private static function isActive() {
		global $wgAgeClassificationButton;

		return ( isset( $wgAgeClassificationButton ) && ( ( $wgAgeClassificationButton === true ) || ( $wgAgeClassificationButton === 'true' ) ) );
	}

	private static function isSupported( $skinname ) {
		return in_array( $skinname, [ 'cologneblue', 'modern', 'monobook', 'timeless', 'vector', 'vector-2022' ] );
	}
}
