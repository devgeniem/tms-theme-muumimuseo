<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use TMS\Theme\Base\Interfaces;

/**
 * ThemeController
 */
class ThemeController extends \TMS\Theme\Base\ThemeController {

    /**
     * Init classes
     */
    protected function init_classes() : void {
        $classes = [
            Assets::class,
            PostTypeController::class,
            TaxonomyController::class,
            ThemeCustomizationController::class,
            ACFController::class,
            PostTypeController::class,
            TaxonomyController::class,
            Localization::class,
            ThemeSupports::class,
            FormatterController::class,
            RolesController::class,
            Admin::class,
        ];

        array_walk( $classes, function ( $class ) {
            $instance = new $class();

            if ( $instance instanceof Interfaces\Controller ) {
                $instance->hooks();
            }
        } );

        add_action( 'init', function () {
            \ArchiveArtist::hooks();
            \ArchiveArtwork::hooks();
        } );
    }
}
