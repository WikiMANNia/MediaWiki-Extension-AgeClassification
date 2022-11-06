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
				if ( version_compare( $wgVersion, '1.37', '<' ) ) {
					$out->addModuleStyles( 'ext.ageclassification.common' );
					$out->addModuleStyles( 'ext.ageclassification.' . $skinname );
				}
			break;
			case 'vector' :
			case 'vector-2022' :
				if ( version_compare( $wgVersion, '1.37', '<' ) ) {
					$out->addModuleStyles( 'ext.ageclassification.common' );
					$out->addModuleStyles( 'ext.ageclassification.vector' );
				}
			break;
			case 'timeless' :
				$out->addModuleStyles( 'ext.ageclassification.common' );
				$out->addModuleStyles( 'ext.donatebutton.' . $skinname );
			break;
			case 'minerva' :
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
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" />';

		if ( !empty( $wgAgeClassificationButtonURL ) ) {
			$url_site = $wgAgeClassificationButtonURL;
			$img_element = '<a href="' . $url_site . '">' . $img_element . '</a>';
		}

		$txt_element = [
			'text'   => $txt_site,
			'href'   => $url_site,
			'id'     => 'n-ageclassification',
			'active' => true
		];

		$sidebar_element = $img_element;

		switch ( $skin->getSkinName() ) {
			case 'cologneblue' :
				$img_element = Html::rawElement( 'div', [ 'class' => 'body' ], $img_element );
				$sidebar_element = ( version_compare( $wgVersion, '1.37', '>=' ) ) ? [ $txt_element ] : $img_element;
			break;
			case 'modern' :
			case 'monobook' :
			case 'vector' :
			case 'vector-2022' :
				$sidebar_element = ( version_compare( $wgVersion, '1.37', '>=' ) ) ? [ $txt_element ] : $img_element;
			break;
			default :
				$sidebar_element = $img_element;
			break;
		}

		$bar['ageclassification'] = $sidebar_element;
	}

	private static function isActive() {
		global $wgAgeClassificationButton;

		return ( isset( $wgAgeClassificationButton ) && ( ( $wgAgeClassificationButton === true ) || ( $wgAgeClassificationButton === 'true' ) ) );
	}

	private static function isSupported( $skinname ) {
		return in_array( $skinname, [ 'cologneblue', 'minerva', 'modern', 'monobook', 'timeless', 'vector', 'vector-2022' ] );
	}
}
