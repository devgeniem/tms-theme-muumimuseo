<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

/**
 * Class ThemeSupports
 *
 * @package TMS\Theme\Muumimuseo
 */
class ThemeSupports implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter(
            'query_vars',
            \Closure::fromCallable( [ $this, 'query_vars' ] )
        );
    }

    /**
     * Append custom query vars
     *
     * @param array $vars Registered query vars.
     *
     * @return array
     */
    protected function query_vars( $vars ) {
        $vars[] = \ArchiveArtist::SEARCH_QUERY_VAR;
        $vars[] = \ArchiveArtist::FILTER_QUERY_VAR;
        $vars[] = \ArchiveArtwork::SEARCH_QUERY_VAR;
        $vars[] = \ArchiveArtwork::FILTER_QUERY_VAR;

        return $vars;
    }
}
