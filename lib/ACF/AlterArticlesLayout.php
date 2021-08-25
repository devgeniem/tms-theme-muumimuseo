<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Logger;
use TMS\Theme\Muumimuseo\ACF\Field\AccentColorField;
use TMS\Theme\Muumimuseo\ThemeCustomizationController;

/**
 * Alter Articles Layout
 */
class AlterArticlesLayout {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_articles/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/articles/data',
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

            $fields[] = $accent_color_field;
        }
        catch ( Exception $e ) {
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
        $layout['accent_color'] = ( new ThemeCustomizationController() )->get_theme_accent_color_by_key(
            $layout['accent_color']
        );

        return $layout;
    }
}

( new AlterArticlesLayout() );
