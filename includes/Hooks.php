<?php

class AgeClassificationHooks extends Hooks {

	/**
	 * https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 */
	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {

		global $wgVersion;

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
		global $wmAgeClassificationMetaName, $wmAgeClassificationMetaContent;
		if ( !empty( $wmAgeClassificationMetaName ) && !empty( $wmAgeClassificationMetaContent ) ) {
			$out->addMeta( $wmAgeClassificationMetaName, $wmAgeClassificationMetaContent );
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

		global $wmAgeClassificationButtonURL;
		global $wgVersion;

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = $skin->msg( 'ageclassification-msg' )->text();
		$url_site = '';
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" />';

		if ( !empty( $wmAgeClassificationButtonURL ) ) {
			$url_site = $wmAgeClassificationButtonURL;
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

		global $wmAgeClassificationButtonURL;

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );

		$url_file = $config->get( 'ExtensionAssetsPath' ) . '/AgeClassification/resources/images/fsm-aks.svg';
		$txt_site = wfMessage( 'ageclassification-msg' )->text();
		$url_site = '';
		$img_element = '<img alt="AgeClassification-Button" title="' . $txt_site .
					'" src="' . $url_file . '" height=auto width=206 />';

		if ( !empty( $wmAgeClassificationButtonURL ) ) {
			$url_site = $wmAgeClassificationButtonURL;
			$img_element = '<a href="' . $url_site . '">' . $img_element . '</a>';
		}

		$html .= "<p>$txt_site</p>";
		$html .= $img_element;

		return true;
	}

	private static function isActive() {
		global $wmAgeClassificationButton;

		return ( isset( $wmAgeClassificationButton ) && ( ( $wmAgeClassificationButton === true ) || ( $wmAgeClassificationButton === 'true' ) ) );
	}

	private static function isSupported( $skinname ) {
		return in_array( $skinname, [ 'cologneblue', 'minerva', 'modern', 'monaco', 'monobook', 'timeless', 'vector', 'vector-2022' ] );
	}
}
