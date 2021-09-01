<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF;

use Geniem\ACF\Field;

/**
 * Class AlterPageFrontPageGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class AlterPageFrontPageGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_filter( 'tms/acf/field/fg_front_page_components_components/layouts',
            [ $this, 'replace_base_theme_hero' ],
            10,
            1
        );
    }

    /**
     * Replace base theme hero with Muumimuseo hero.
     *
     * @param array $layouts Front page layouts.
     *
     * @return mixed
     */
    public function replace_base_theme_hero( $layouts ) {
        $layouts = array_filter( $layouts, function ( $layout ) {
            return $layout !== 'TMS\Theme\Base\ACF\Layouts\HeroLayout';
        } );

        $layouts[] = 'TMS\Theme\Muumimuseo\ACF\Layouts\HeroLayout';

        return $layouts;
    }
}

( new AlterPageFrontPageGroup() );
