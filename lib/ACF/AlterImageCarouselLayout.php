<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF;

use TMS\Theme\Base\Logger;
use TMS\Theme\Muumimuseo\ACF\Field\AccentColorField;

/**
 * Alter Image Carousel Layout
 */
class AlterImageCarouselLayout {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_image_carousel/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/image_carousel/data',
            [ $this, 'alter_format' ]
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
        try {
            $accent_color_field = AccentColorField::field( $key );
            if ( $accent_color_field !== null ) {
                $accent_color_field->set_wrapper_width( 100 );
                $fields[] = $accent_color_field;
            }
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $fields;
    }

    /**
     * Alter format
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function alter_format( array $layout ) : array {
        $layout['accent_color'] = $layout['accent_color'] ?? '';

        return $layout;
    }
}

( new AlterImageCarouselLayout() );
