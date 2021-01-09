<?php

class AgeClassificationHooks extends Hooks {

	/**
	 * Hook: BeforePageDisplay
	 * @param OutputPage $out
	 * @param Skin $skin
	 * https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 */
	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {

		if ( !self::isActive() )  return;

		$skinname = $skin->getSkinName();
		$out->addModuleStyles( 'ext.ageclassification.common' );
		if ( self::isSupported( $skinname ) ) {
			$out->addModuleStyles( 'ext.ageclassification.' . $skinname );
		} else {
			wfLogWarning( 'Skin ' . $skinname . ' not supported by AgeClassification.' . "\n" );
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

		global $wgAgeClassificationButton, $wgAgeClassificationButtonIMG, $wgAgeClassificationButtonURL;

		if ( !empty( $wgAgeClassificationButton ) &&
			!empty( $wgAgeClassificationButtonIMG ) &&
			( ( $wgAgeClassificationButton === 'true' ) || ( $wgAgeClassificationButton === true ) )
			) {

			$html = '<img alt="AgeClassification-Button" title="' .
						$skin->msg( 'ageclassification-msg' )->text() .
						'"src="//' . $wgAgeClassificationButtonIMG . '" />';
			if ( !empty( $wgAgeClassificationButtonURL ) ) {
				$html = '<a href="//' . $wgAgeClassificationButtonURL . '">' . $html . '</a>';
			}

			switch ( $skin->getSkinName() ) {
				case 'cologneblue' :
					$html = Html::rawElement( 'div', [ 'class' => 'body' ], $html );
				break;
				case 'modern' :
				break;
				case 'monobook' :
				break;
				case 'vector' :
				break;
			}

			$bar['ageclassification'] = $html;
		}
	}

	private static function isActive() {
		global $wgAgeClassificationButton;

		return ( isset( $wgAgeClassificationButton ) && ( $wgAgeClassificationButton === true ) );
	}

	private static function isSupported( $skinname ) {
		return in_array( $skinname, [ 'cologneblue', 'modern', 'monobook', 'vector' ] );
	}
}
