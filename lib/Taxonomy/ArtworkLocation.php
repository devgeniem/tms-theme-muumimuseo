<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Base\Taxonomy;

use \TMS\Theme\Base\Interfaces\Taxonomy;
use TMS\Theme\Base\PostType\Artwork;
use TMS\Theme\Base\Traits\Categories;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Base\Taxonomy
 */
class ArtworkLocation implements Taxonomy {

    use Categories;

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'artwork-location';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                       => 'Sijainnit',
            'singular_name'              => 'Sijainti',
            'menu_name'                  => 'Sijainnit',
            'all_items'                  => 'Kaikki Sijainnit',
            'new_item_name'              => 'Lisää uusi sijainti',
            'add_new_item'               => 'Lisää uusi sijainti',
            'edit_item'                  => 'Muokkaa sijaintia',
            'update_item'                => 'Päivitä sijainti',
            'view_item'                  => 'Näytä sijainti',
            'separate_items_with_commas' => \__( 'Erottele sijainnit pilkulla', 'tms-theme-base' ),
            'add_or_remove_items'        => \__( 'Lisää tai poista sijainti', 'tms-theme-base' ),
            'choose_from_most_used'      => \__( 'Suositut sijainnit', 'tms-theme-base' ),
            'popular_items'              => \__( 'Suositut sijainnit', 'tms-theme-base' ),
            'search_items'               => 'Etsi sijainti',
            'not_found'                  => 'Ei tuloksia',
            'no_terms'                   => 'Ei tuloksia',
            'items_list'                 => 'Sijainnit',
            'items_list_navigation'      => 'Sijainnit',
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ];

        register_taxonomy( self::SLUG, [ Artwork::SLUG ], $args );
    }
}
