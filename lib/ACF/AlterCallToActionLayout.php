<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Muumimuseo\ACF\Field\AccentColorField;

/**
 * Alter Call to Action Layout
 */
class AlterCallToActionLayout {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_call_to_action/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/call_to_action/data',
            [ $this, 'alter_format' ],
            20
        );
    }

    /**
     * Alter fields
     *
     * @param array  $fields Array of ACF fields.
     * @param string $key    Layout key.
     *
     * @return array
     */
    public function alter_fields( array $fields, string $key ) : array {
        $strings = [
            'round_image' => [
                'label'        => 'Pyöreä kuva',
                'instructions' => '',
            ],
        ];

        try {
            $round_image_field = ( new Field\TrueFalse( $strings['round_image']['label'] ) )
                ->set_key( "${key}_round_image" )
                ->set_name( 'round_image' )
                ->use_ui()
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['round_image']['instructions'] );

            $fields['rows']->add_field( $round_image_field );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        try {
            $accent_color_field = AccentColorField::field( $key );
            array_unshift( $fields, $accent_color_field );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $fields;
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function alter_format( array $layout ) : array {
        $layout['accent_color'] = $layout['accent_color'] ?? '';

        if ( empty( $layout['rows'] ) ) {
            return $layout;
        }

        foreach ( $layout['rows'] as $key => $row ) {
            if ( isset( $row['round_image'] ) && true === $row['round_image'] ) {
                $layout['rows'][ $key ]['image_class'] = 'has-round-mask';
            }
        }

        return $layout;
    }
}

( new AlterCallToActionLayout() );
