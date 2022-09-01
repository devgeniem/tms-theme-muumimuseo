<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Settings;
use TMS\Theme\Muumimuseo\PostType\Artist;
use TMS\Theme\Muumimuseo\PostType\Artwork;
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkLocation;
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkType;

/**
 * Archive for Artwork CPT
 */
class ArchiveArtwork extends ArchiveArtist {

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'artwork-search';

    /**
     * Artist category filter name.
     */
    const FILTER_QUERY_VAR = 'artwork-filter';

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get filter query var value
     *
     * @return int|null
     */
    protected static function get_filter_query_var() {
        $value = get_query_var( self::FILTER_QUERY_VAR, false );

        return ! $value
            ? null
            : intval( $value );
    }

    /**
     * Hooks
     */
    public static function hooks() : void {
        add_action(
            'pre_get_posts',
            [ __CLASS__, 'modify_query' ]
        );

        add_filter( 'redipress/ignore_query_vars', \Closure::fromCallable( [ __CLASS__, 'redipress_ignore_query_vars' ] ) );
    }

    /**
     * Add custom query vars to the list of ignored query vars list for RediPress.
     *
     * @param array $vars Ignored query vars.
     *
     * @return array
     */
    private static function redipress_ignore_query_vars( $vars ) {

        $vars[] = self::SEARCH_QUERY_VAR;
        $vars[] = self::FILTER_QUERY_VAR;

        return $vars;
    }

    /**
     * Return translated strings.
     *
     * @return array[]
     */
    public function strings() : array {
        return [
            'search'     => [
                'label'             => __( 'Search for artwork', 'tms-theme-muumimuseo' ),
                'submit_value'      => __( 'Search', 'tms-theme-muumimuseo' ),
                'input_placeholder' => __( 'Search query', 'tms-theme-muumimuseo' ),
            ],
            'terms'      => [
                'show_all' => __( 'Show All', 'tms-theme-muumimuseo' ),
            ],
            'no_results' => __( 'No results', 'tms-theme-muumimuseo' ),
            'filter'     => __( 'Filter', 'tms-theme-muumimuseo' ),
        ];
    }

    /**
     * Modify query
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    public static function modify_query( WP_Query $wp_query ) : void {
        if ( is_admin() || ( ! $wp_query->is_main_query() || ! $wp_query->is_post_type_archive( Artwork::SLUG ) ) ) {
            return;
        }

        $wp_query->set( 'orderby', [ 'title' => 'ASC' ] );

        $category = self::get_filter_query_var();

        if ( ! empty( $category ) ) {
            $wp_query->set(
                'tax_query',
                [
                    [
                        'taxonomy' => ArtworkType::SLUG,
                        'terms'    => $category,
                    ],
                ]
            );
        }

        $s = self::get_search_query_var();

        if ( ! empty( $s ) ) {
            $wp_query->set( 's', $s );
        }
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        $this->search_data        = new stdClass();
        $this->search_data->query = get_query_var( self::SEARCH_QUERY_VAR );

        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'action'            => get_post_type_archive_link( Artwork::SLUG ),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {
        $categories = get_terms( [
            'taxonomy'   => ArtworkType::SLUG,
            'hide_empty' => true,
        ] );

        if ( empty( $categories ) || is_wp_error( $categories ) ) {
            return [];
        }

        $base_url   = get_post_type_archive_link( Artwork::SLUG );
        $categories = array_map( function ( $item ) use ( $base_url ) {
            return [
                'name'      => $item->name,
                'url'       => add_query_arg(
                    [
                        self::FILTER_QUERY_VAR => $item->term_id,
                    ],
                    $base_url
                ),
                'is_active' => $item->term_id === self::get_filter_query_var(),
            ];
        }, $categories );

        array_unshift(
            $categories,
            [
                'name'      => __( 'All', 'tms-theme-muumimuseo' ),
                'url'       => $base_url,
                'is_active' => null === self::get_filter_query_var(),
            ]
        );

        return $categories;
    }

    /**
     * Format posts for view
     *
     * @param array $posts Array of WP_Post instances.
     *
     * @return array
     */
    protected function format_posts( array $posts ) : array {
        $display_location = Settings::get_setting( 'artwork_archive_display_location' );
        $display_artist   = Settings::get_setting( 'artwork_archive_display_artist' );
        $artist_map       = $display_artist ? $this->get_artist_map() : [];

        return array_map( function ( $item ) use ( $artist_map, $display_artist, $display_location ) {
            if ( has_post_thumbnail( $item->ID ) ) {
                $item->image = get_post_thumbnail_id( $item->ID );
            }

            $item->permalink = get_the_permalink( $item->ID );
            $item->fields    = get_fields( $item->ID );

            if ( $display_location ) {
                $locations       = wp_get_post_terms( $item->ID, ArtworkLocation::SLUG, [ 'fields' => 'names' ] );
                $item->locations = ! empty( $locations ) && ! is_wp_error( $locations )
                    ? implode( ', ', $locations )
                    : false;
            }

            $item->types = wp_get_post_terms( $item->ID, ArtworkType::SLUG, [ 'fields' => 'names' ] );

            if ( $display_artist && isset( $artist_map[ $item->ID ] ) ) {
                $item->artist = implode( ', ', $artist_map[ $item->ID ] );
            }

            return $item;
        }, $posts );
    }

    /**
     * Get artworks artists map
     *
     * @return array
     */
    protected function get_artist_map() : array {
        $artists = Artist::get_all();

        if ( empty( $artists ) ) {
            return [];
        }

        $map = [];

        foreach ( $artists as $artist ) {
            $artworks = get_field( 'artwork', $artist->ID );

            if ( empty( $artworks ) ) {
                continue;
            }

            foreach ( $artworks as $artwork ) {
                if ( ! isset( $map[ $artwork->ID ] ) ) {
                    $map[ $artwork->ID ] = [];
                }

                $map[ $artwork->ID ][] = $artist->post_title;
            }
        }

        return $map;
    }
}
