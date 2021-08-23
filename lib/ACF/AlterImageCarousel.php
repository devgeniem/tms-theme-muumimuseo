<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF;

use TMS\Theme\Base\Logger;

/**
 * Class AlterImageCarousel
 *
 * @package TMS\Theme\Muumimuseo\ACF
 */
class AlterImageCarousel {
    /**
     * AlterImageCarousel constructor.
     */
    public function __construct() {
        add_filter( 'tms/block/image_carousel/fields', [ $this, 'alter_fields' ], 10, 1 );
        add_filter( 'tms/acf/block/image_carousel/data', [ $this, 'alter_data' ], 10, 1 );

        add_filter(
            'tms/acf/layout/fg_onepager_components_image_carousel/fields',
            [ $this, 'alter_fields' ], 10, 1
        );

        add_filter(
            'tms/acf/layout/fg_page_components_image_carousel/fields',
            [ $this, 'alter_fields' ], 10, 1
        );

        add_filter(
            'tms/acf/layout/fg_front_page_components_image_carousel/fields',
            [ $this, 'alter_fields' ], 10, 1
        );

        add_filter(
            'tms/acf/layout/fg_post_fields_components_image_carousel/fields',
            [ $this, 'alter_fields' ], 10, 1
        );

        add_filter(
            'tms/acf/layout/fg_dynamic_event_fields_components_image_carousel/fields',
            [ $this, 'alter_fields' ], 10, 1
        );

        add_filter( 'tms/acf/layout/image_carousel/data', [ $this, 'alter_data' ], 10, 1 );
    }

    /**
     * Adds Shape (Rectangle, Rounded) Select to Images.
     *
     * @param array $fields ImageCarouselBlock Fields.
     */
    public function alter_fields( array $fields = [] ) : array {
        /**
         * Repeater.
         *
         * @var \Geniem\ACF\Field\Repeater|false $repeater
         */
        $repeater = $fields['rows'] ?? false;

        if ( empty( $repeater ) ) {
            return $fields;
        }

        $s = [
            'select' => [
                'title'        => 'Muoto',
                'instructions' => 'Valitse kuvan muoto kuvakarusellissa.',
            ],
        ];

        try {
            $select = ( new \Geniem\ACF\Field\Select( $s['select']['title'] ) )
                ->set_key( $repeater->get_key() . '_shape' )
                ->set_name( 'shape' )
                ->use_ui()
                ->set_choices( [
                    'has-shape-rectangle' => 'Suorakaide',
                    'has-shape-round'     => 'Pyöreä',
                ] );

            $repeater->add_field( $select );

            $fields['rows'] = $repeater;
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }

        return $fields;
    }

    /**
     * ACF Block Data Modification.
     *
     * @param array $data Block's ACF data.
     *
     * @return array
     */
    public function alter_data( array $data = [] ) : array {
        if ( empty( $data['rows'] ?? [] ) ) {
            return $data;
        }

        foreach ( $data['rows'] as $idx => $item ) {
            if ( empty( $item['shape'] ?? '' ) ) {
                $item['shape'] = '';
            }

            $data['rows'][ $idx ] = $item;
        }

        return $data;
    }
}

( new AlterImageCarousel() );
