<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

/**
 * Class Admin
 *
 * @package TMS\Theme\Muumimuseo
 */
class Admin implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'tms/theme/gutenberg/excluded_templates',
            \Closure::fromCallable( [ $this, 'remove_gutenberg_from_templates' ] )
        );
    }

    /**
     * Remove Gutenberg from page templates
     *
     * @param array $templates Array of template slugs.
     *
     * @return array
     */
    public function remove_gutenberg_from_templates( array $templates ) : array {
        $templates[] = \PageArtwork::TEMPLATE;

        return $templates;
    }
}
