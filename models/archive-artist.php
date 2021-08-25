<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Traits\Pagination;
use TMS\Theme\Muumimuseo\PostType\Artist;
use TMS\Theme\Muumimuseo\Taxonomy\ArtistCategory;

/**
 * Archive for Artist CPT
 */
class ArchiveArtist extends BaseModel {

    use Pagination;

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'artist-search';

    /**
     * Artist category filter name.
     */
    const FILTER_QUERY_VAR = 'artist-filter';

    /**
     * Pagination data.
     *
     * @var object
     */
    protected object $pagination;

    /**
     * Hooks
     */
    public static function hooks() : void {
        add_action(
            'pre_get_posts',
            [ __CLASS__, 'modify_query' ]
        );
    }

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
        return post_type_archive_title( '', false );
    }

    /**
     * Return translated strings.
     *
     * @return array[]
     */
    public function strings() : array {
        return [
            'search'         => [
                'label'             => __( 'Search for artist', 'tms-theme-base' ),
                'submit_value'      => __( 'Search', 'tms-theme-base' ),
                'input_placeholder' => __( 'Search query', 'tms-theme-base' ),
            ],
            'terms'          => [
                'show_all' => __( 'Show All', 'tms-theme-base' ),
            ],
            'no_results'     => __( 'No results', 'tms-theme-base' ),
            'filter'         => __( 'Filter', 'tms-theme-base' ),
            'art_categories' => __( 'Categories', 'tms-theme-base' ),

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
        if ( is_admin() || ( ! $wp_query->is_main_query() || ! $wp_query->is_post_type_archive( Artist::SLUG ) ) ) {
            return;
        }

        $wp_query->set( 'orderby', [ 'last_name' => 'ASC' ] );
        $wp_query->set( 'meta_key', 'last_name' );

        $artist_category = self::get_filter_query_var();

        if ( ! empty( $artist_category ) ) {
            $wp_query->set(
                'tax_query',
                [
                    [
                        'taxonomy' => ArtistCategory::SLUG,
                        'terms'    => $artist_category,
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
            'action'            => get_post_type_archive_link( Artist::SLUG ),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {
        $categories = get_terms( [
            'taxonomy'   => ArtistCategory::SLUG,
            'hide_empty' => true,
        ] );

        if ( empty( $categories ) || is_wp_error( $categories ) ) {
            return [];
        }

        $base_url   = get_post_type_archive_link( Artist::SLUG );
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
     * Artists
     *
     * @return array|bool
     */
    public function artists() {
        global $wp_query;

        if ( empty( $wp_query->posts ) ) {
            return [];
        }

        $this->set_pagination_data( $wp_query );

        return array_map( function ( $item ) {
            if ( has_post_thumbnail( $item->ID ) ) {
                $item->image = get_post_thumbnail_id( $item->ID );
            }

            $item->permalink = get_the_permalink( $item->ID );
            $item->fields    = get_fields( $item->ID );

            $categories       = wp_get_post_terms( $item->ID, ArtistCategory::SLUG, [ 'fields' => 'names' ] );
            $item->categories = ! empty( $categories ) && ! is_wp_error( $categories )
                ? implode( ', ', $categories )
                : false;

            $item->link = [
                'url'          => $item->permalink,
                'title'        => __( 'View artist', 'tms-theme-base' ),
                'icon'         => 'chevron-right',
                'icon_classes' => 'icon--medium',
            ];

            return $item;
        }, $wp_query->posts );
    }

    /**
     * Set pagination data
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    protected function set_pagination_data( $wp_query ) : void {
        $per_page = get_option( 'posts_per_page' );
        $paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $this->pagination           = new stdClass();
        $this->pagination->page     = $paged;
        $this->pagination->per_page = $per_page;
        $this->pagination->items    = $wp_query->found_posts;
        $this->pagination->max_page = (int) ceil( $wp_query->found_posts / $per_page );
    }
}
