<?php
/**
 * Copyright (c) 2021. Geniem Oy
 * Template Name: Teosarkisto
 */

use TMS\Theme\Base\Traits\Pagination;
use TMS\Theme\Muumimuseo\PostType\Artwork;
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkType;

/**
 * PageArtwork
 */
class PageArtwork extends ArchiveArtwork {

    use Pagination;

    /**
     * Template
     */
    const TEMPLATE = 'models/page-artwork.php';

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
     * Page title
     *
     * @return string
     */
    public function page_title() : string {
        return get_the_title();
    }

    /**
     * Page description
     *
     * @return string
     */
    public function page_description() : string {
        return get_field( 'description' ) ?? '';
    }

    /**
     * Modify query
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    public static function modify_query( WP_Query $wp_query ) : void {
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        $this->search_data        = new \stdClass();
        $this->search_data->query = get_query_var( self::SEARCH_QUERY_VAR );

        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'action'            => get_the_permalink(),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {
        $categories = get_field( 'artwork_types' );

        if ( empty( $categories ) || is_wp_error( $categories ) || 1 === count( $categories ) ) {
            return [];
        }

        $base_url   = get_the_permalink();
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
                'name'      => __( 'All', 'tms-theme-base' ),
                'url'       => $base_url,
                'is_active' => null === self::get_filter_query_var(),
            ]
        );

        return $categories;
    }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        $args = [
            'post_type' => Artwork::SLUG,
            'orderby'   => 'title',
            'order'     => 'ASC',
            'paged'     => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
        ];

        $categories = self::get_filter_query_var();

        if ( empty( $categories ) ) {
            $categories = get_field( 'artwork_types' );
            $categories = !empty( $categories ) ?? array_map( fn( $c ) => $c->term_id, $categories );
        }

        $args['tax_query'] = [
            [
                'taxonomy' => ArtworkType::SLUG,
                'terms'    => $categories,
            ],
        ];

        $s = self::get_search_query_var();

        if ( ! empty( $s ) ) {
            $args['s'] = $s;
        }

        $the_query = new \WP_Query( $args );

        $this->set_pagination_data( $the_query );

        return $this->format_posts( $the_query->posts );
    }
}
