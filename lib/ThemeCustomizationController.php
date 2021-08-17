<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

/**
 * Class PostTypeController
 *
 * @package TMS\Theme\Base
 */
class ThemeCustomizationController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'tms/theme/error404/search_link',
            \Closure::fromCallable( [ $this, 'error404_search_link' ] )
        );

        add_filter(
            'tms/theme/error404/home_link',
            \Closure::fromCallable( [ $this, 'error404_home_link' ] )
        );
    }

    /**
     * Override error404 search link class.
     *
     * @param array $link Link details.
     *
     * @return array
     */
    public function error404_search_link( $link ) {
        $link['classes'] = 'is-primary-invert is-outlined';

        return $link;
    }

    /**
     * Override error404 home link class.
     *
     * @param array $link Link details.
     *
     * @return array
     */
    public function error404_home_link( $link ) {
        $link['classes'] = 'is-primary';

        return $link;
    }
}
