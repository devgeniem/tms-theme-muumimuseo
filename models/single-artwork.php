<?php
/**
 * Define the SingleArtwork class.
 */

use DustPress\Query;
use TMS\Theme\Base\Formatters\ImageFormatter;
use TMS\Theme\Base\Traits;
use TMS\Theme\Muumimuseo\PostType\Artist;

/**
 * The SingleArtwork class.
 */
class SingleArtwork extends SingleArtist {

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
     * Return image gallery data.
     *
     * @return array
     */
    public function image_gallery() {
        $gallery_field = get_field( 'images' );

        if ( has_post_thumbnail() ) {
            array_unshift( $gallery_field, $this->get_featured_media_gallery_item() );
        }

        $data['rows'] = array_map( static function ( $item ) {
            $item['meta'] = ImageFormatter::format( [
                'is_clickable' => true,
                'id'           => $item['ID'],
            ] );

            $item['id'] = wp_unique_id( 'image-gallery-item-' );

            return $item;
        }, $gallery_field );

        $data['gallery_id']   = wp_unique_id( 'image-gallery-' );
        $data['translations'] = ( new \Strings() )->s()['gallery'] ?? [];

        return $data;
    }

    /**
     * Get featured media formatted for use in as a gallery item.
     *
     * @return array|WP_Post|null
     */
    protected function get_featured_media_gallery_item() {
        $featured_media_id = get_post_thumbnail_id();
        $img_src           = wp_get_attachment_image_src( $featured_media_id, 'fullhd' );
        $img_post          = get_post( $featured_media_id, ARRAY_A );
        $img_post['sizes'] = [
            'fullhd'        => $img_src[0],
            'fullhd-width'  => $img_src[1],
            'fullhd-height' => $img_src[2],
        ];

        return $img_post;
    }

    /**
     * Returns artist link details.
     */
    public function artist_permalink() {
        $artist_id = $this->get_artist_id();

        return get_permalink( $artist_id );
    }

    /**
     * Get artwork artist ID.
     *
     * @return mixed|null
     */
    protected function get_artist_id() {
        $artists_map = $this->get_artist_map();
        $current_id  = get_the_ID();

        return $artists_map[ $current_id ][0] ?? null;
    }

    /**
     * Get artwork.
     *
     * @return mixed
     */
    protected function get_artwork() {
        $artist_id = $this->get_artist_id();
        $artwork   = get_field( 'artwork', $artist_id );

        if ( empty( $artwork ) ) {
            return [];
        }

        $current_id = get_the_ID();

        return array_filter( $artwork, function ( $artwork_item ) use ( $current_id ) {
            return $artwork_item->ID !== $current_id;
        } );
    }

    /**
     * Get artworks artists map.
     *
     * @return array
     */
    protected function get_artist_map() : array {
        $the_query = new WP_Query( [
            'post_type'      => Artist::SLUG,
            'posts_per_page' => 200, // phpcs:ignore
            'no_found_rows'  => true,
        ] );

        if ( ! $the_query->have_posts() ) {
            return [];
        }

        $map = [];

        foreach ( $the_query->posts as $artist ) {
            $artworks = get_field( 'artwork', $artist->ID );

            if ( empty( $artworks ) ) {
                continue;
            }

            foreach ( $artworks as $artwork ) {
                if ( ! isset( $map[ $artwork->ID ] ) ) {
                    $map[ $artwork->ID ] = [];
                }

                $map[ $artwork->ID ][] = $artist->ID;
            }
        }

        return $map;
    }
}
