<?php
/**
 * Define the generic Page class.
 */

use DustPress\Query;
use TMS\Theme\Base\Images;
use TMS\Theme\Base\Traits;
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkType;

/**
 * The SingleArtist class.
 */
class SingleArtist extends BaseModel {

    use Traits\Sharing;

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        $single = Query::get_acf_post();

        return $single;
    }


    /**
     * Get related artwork
     *
     * @return array|null
     */
    public function artwork() : ?array {
        $artwork_field = get_field( 'artwork' );

        if ( empty( $artwork_field ) ) {
            return null;
        }

        return array_map( function ( $item ) {
            $types = wp_get_post_terms( $item->ID, ArtworkType::SLUG );

            if ( ! empty( $types ) ) {
                $item->artwork_type      = $types[0]->name;
                $item->artwork_type_link = get_category_link( $types[0]->ID );
            }

            $item->image_id = has_post_thumbnail( $item->ID )
                ? get_post_thumbnail_id( $item->ID )
                : Images::get_default_image_id();

            if ( ! has_excerpt( $item->ID ) ) {
                $item->post_excerpt = $this->get_artwork_excerpt( $item );
            }

            return $item;
        }, $artwork_field );
    }

    /**
     * Get artwork excerpt.
     *
     * @param WP_Post $item           Related post item.
     * @param int     $excerpt_length Target excerpt length.
     */
    protected function get_artwork_excerpt( WP_Post $item, int $excerpt_length = 10 ) : string {
        $item_excerpt = get_the_excerpt( $item->ID );

        return strlen( $item_excerpt ) > $excerpt_length
            ? wp_trim_words( $item_excerpt, $excerpt_length, '...' )
            : $item_excerpt;
    }
}
