<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\PostType;

use TMS\Theme\Base\Interfaces\PostType;

/**
 * Artist CPT
 *
 * @package TMS\Theme\Base\PostType
 */
class Artist implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'artist';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the post type.
     *
     * @var string
     */
    private $url_slug = '';

    /**
     * Define the CPT description
     *
     * @var string
     */
    private $description = '';

    /**
     * This is used to position the post type menu in admin.
     *
     * @var int
     */
    private $menu_order = 5;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-buddicons-topics';

    /**
     * Constructor
     */
    public function __construct() {
        $this->url_slug    = _x( 'artist', 'theme CPT slugs', 'tms-theme-base' );
        $this->description = _x( 'Artists', 'theme CPT', 'tms-theme-base' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * Get post type slug
     *
     * @return string
     */
    public function get_post_type() : string {
        return static::SLUG;
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Artistit',
            'singular_name'         => 'Artisti',
            'menu_name'             => 'Artistit',
            'name_admin_bar'        => 'Artistit',
            'archives'              => 'Arkistot',
            'attributes'            => 'Ominaisuudet',
            'parent_item_colon'     => 'Vanhempi:',
            'all_items'             => 'Kaikki',
            'add_new_item'          => 'Lisää uusi',
            'add_new'               => 'Lisää uusi',
            'new_item'              => 'Uusi',
            'edit_item'             => 'Muokkaa',
            'update_item'           => 'Päivitä',
            'view_item'             => 'Näytä',
            'view_items'            => 'Näytä kaikki',
            'search_items'          => 'Etsi',
            'not_found'             => 'Ei löytynyt',
            'not_found_in_trash'    => 'Ei löytynyt roskakorista',
            'featured_image'        => 'Kuva',
            'set_featured_image'    => 'Aseta kuva',
            'remove_featured_image' => 'Poista kuva',
            'use_featured_image'    => 'Käytä kuvana',
            'insert_into_item'      => 'Aseta julkaisuun',
            'uploaded_to_this_item' => 'Lisätty tähän julkaisuun',
            'items_list'            => 'Listaus',
            'items_list_navigation' => 'Listauksen navigaatio',
            'filter_items_list'     => 'Suodata listaa',
        ];

        $rewrite = [
            'slug'       => static::SLUG,
            'with_front' => false,
            'pages'      => false,
            'feeds'      => false,
        ];

        $args = [
            'label'         => $labels['name'],
            'description'   => '',
            'labels'        => $labels,
            'supports'      => [
                'title',
                'thumbnail',
                'excerpt',
            ],
            'hierarchical'  => false,
            'public'        => false,
            'menu_position' => $this->menu_order,
            'menu_icon'     => $this->icon,
            'show_in_menu'  => true,
            'show_ui'       => true,
            'can_export'    => false,
            'has_archive'   => false,
            'rewrite'       => $rewrite,
            'show_in_rest'  => false,
        ];

        register_post_type( static::SLUG, $args );
    }
}
