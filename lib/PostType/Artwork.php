<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\PostType;

use Closure;
use TMS\Theme\Base\Interfaces\PostType;

/**
 * Artwork CPT
 *
 * @package TMS\Theme\Base\PostType
 */
class Artwork implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'artwork';

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
    private $icon = 'dashicons-art';

    /**
     * Constructor
     */
    public function __construct() {
        $this->url_slug    = 'artwork';
        $this->description = _x( 'Artwork', 'theme CPT', 'tms-theme-muumimuseo' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', Closure::fromCallable( [ $this, 'register' ] ), 15 );
        add_filter( 'tms/gutenberg/blocks', Closure::fromCallable( [ $this, 'allowed_blocks' ] ), 10, 1 );

        add_filter(
            'tms/base/breadcrumbs/before_prepare',
            Closure::fromCallable( [ $this, 'format_single_breadcrumbs' ] ),
            10,
            3
        );

        add_filter(
            'tms/base/breadcrumbs/after_prepare',
            Closure::fromCallable( [ $this, 'format_archive_breadcrumbs' ] ),
        );
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
            'name'                  => 'Taideteokset',
            'singular_name'         => 'Taideteos',
            'menu_name'             => 'Taideteokset',
            'name_admin_bar'        => 'Taideteokset',
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
            'pages'      => true,
            'feeds'      => true,
        ];

        $args = [
            'label'           => $labels['name'],
            'description'     => '',
            'labels'          => $labels,
            'supports'        => [
                'title',
                'thumbnail',
                'excerpt',
                'editor',
            ],
            'hierarchical'    => false,
            'public'          => true,
            'menu_position'   => $this->menu_order,
            'menu_icon'       => $this->icon,
            'show_in_menu'    => true,
            'show_ui'         => true,
            'can_export'      => true,
            'has_archive'     => true,
            'rewrite'         => $rewrite,
            'show_in_rest'    => true,
            'capability_type' => 'artwork',
            'map_meta_cap'    => true,
        ];

        register_post_type( static::SLUG, $args );
    }

    /**
     * Set allowed blocks.
     *
     * @param array $blocks Block list.
     */
    public function allowed_blocks( $blocks ) {
        $allowed_blocks = [
            'acf/image',
            'acf/video',
            'acf/material',
            'acf/quote',
            'acf/map',
        ];

        foreach ( $allowed_blocks as $block ) {
            $blocks[ $block ]['post_types'][] = self::SLUG;
        }

        return $blocks;
    }

    /**
     * Get archive breadcrumbs base.
     *
     * @param false $is_cpt_archive Defines if cpt archive link is active.
     *
     * @return array[]
     */
    private function get_breadcrumbs_base( $is_cpt_archive = false ) : array {
        return [
            'home' => [
                'title'     => _x( 'Home', 'Breadcrumbs', 'tms-theme-muumimuseo' ),
                'permalink' => trailingslashit( get_home_url() ),
                'icon'      => '',
            ],
            [
                'title'     => _x( 'Artwork', 'Breadcrumb text', 'tms-theme-muumimuseo' ),
                'permalink' => get_post_type_archive_link( self::SLUG ),
                'icon'      => false,
                'is_active' => $is_cpt_archive,
            ],
        ];
    }

    /**
     * Format single view breadcrumbs.
     *
     * @param array  $breadcrumbs  Default breadcrumbs.
     * @param string $current_type Post type.
     * @param string $current_id   Current post ID.
     *
     * @return array[]
     */
    public function format_single_breadcrumbs( $breadcrumbs, $current_type, $current_id ) {
        if ( $current_type !== self::SLUG ) {
            return $breadcrumbs;
        }

        $breadcrumbs   = $this->get_breadcrumbs_base();
        $breadcrumbs[] = [
            'title'     => get_the_title( $current_id ),
            'permalink' => false,
            'icon'      => false,
            'is_active' => true,
        ];

        return $breadcrumbs;
    }

    /**
     * Format archive view breadcrumbs.
     *
     * @param array $breadcrumbs Default breadcrumbs.
     *
     * @return array[]
     */
    public function format_archive_breadcrumbs( $breadcrumbs ) {
        if ( ! is_post_type_archive( self::SLUG ) ) {
            return $breadcrumbs;
        }

        return $this->get_breadcrumbs_base( true );
    }
}
