<?php

namespace MediaWiki\Extension\AgeClassification;

class Compat {

    public static function init(): void {
        self::aliasCoreClasses();
    }

    private static function aliasCoreClasses(): void {

		// Class aliases for multi-version compatibility.
		// These need to be in global scope so phan can pick up on them,
		// and before any use statements that make use of the namespaced names.

		if ( class_exists( \Html::class ) && /* < 1.40 */
			!class_exists( \MediaWiki\Html\Html::class, false ) ) {
			class_alias(
				\Html::class,
				\MediaWiki\Html\Html::class );
		}
    }
}