<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use TMS\Theme\Muumimuseo\Taxonomy\ArtistCategory;
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkLocation;
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkType;

/**
 * Class Localization
 *
 * @package TMS\Theme\Muumimuseo
 */
class Localization extends \TMS\Theme\Base\Localization implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * This adds the CPTs that are not public to Polylang translation.
     *
     * @param array   $post_types  The post type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified post_types -array.
     */
    protected function add_cpts_to_polylang( $post_types, $is_settings ) { // phpcs:ignore
        if ( ! DPT_PLL_ACTIVE ) {
            return $post_types;
        }

        $post_types[ PostType\Artist::SLUG ]  = PostType\Artist::SLUG;
        $post_types[ PostType\Artwork::SLUG ] = PostType\Artwork::SLUG;

        return $post_types;
    }

    /**
     * This adds the taxonomies that are not public to Polylang translation.
     *
     * @param array   $tax_types   The taxonomy type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified tax_types -array.
     */
    protected function add_tax_to_polylang( $tax_types, $is_settings ) : array { // phpcs:ignore
        $tax_types[ ArtistCategory::SLUG ]  = ArtistCategory::SLUG;
        $tax_types[ ArtworkLocation::SLUG ] = ArtworkLocation::SLUG;
        $tax_types[ ArtworkType::SLUG ]     = ArtworkType::SLUG;

        return $tax_types;
    }
}
