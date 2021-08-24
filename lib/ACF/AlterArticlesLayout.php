<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
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
        $strings = [
            'accent_color' => [
                'label'        => 'Taustaväri',
                'instructions' => '',
            ],
        ];

        try {
            $accent_color_field = ( new Field\Select( $strings['accent_color']['label'] ) )
                ->set_key( "${key}_accent_color" )
                ->set_name( 'accent_color' )
                ->use_ui()
                ->set_choices( apply_filters( 'tms/theme/accent_colors', [] ) )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['accent_color']['instructions'] );

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
