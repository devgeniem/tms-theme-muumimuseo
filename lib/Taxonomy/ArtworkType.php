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
class ArtworkType implements Taxonomy {

    use Categories;

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'artwork-type';

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
            'name'                       => 'Luokittelut',
            'singular_name'              => 'Luokittelu',
            'menu_name'                  => 'Luokittelut',
            'all_items'                  => 'Kaikki luokittelut',
            'new_item_name'              => 'Lisää uusi luokittelu',
            'add_new_item'               => 'Lisää uusi luokittelu',
            'edit_item'                  => 'Muokkaa luokittelua',
            'update_item'                => 'Päivitä luokittelu',
            'view_item'                  => 'Näytä luokittelu',
            'separate_items_with_commas' => \__( 'Erottele luokittelut pilkulla', 'tms-theme-base' ),
            'add_or_remove_items'        => \__( 'Lisää tai poista luokittelu', 'tms-theme-base' ),
            'choose_from_most_used'      => \__( 'Suositut luokittelut', 'tms-theme-base' ),
            'popular_items'              => \__( 'Suositut luokittelut', 'tms-theme-base' ),
            'search_items'               => 'Etsi luokittelu',
            'not_found'                  => 'Ei tuloksia',
            'no_terms'                   => 'Ei tuloksia',
            'items_list'                 => 'Luokittelut',
            'items_list_navigation'      => 'Luokittelut',
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
