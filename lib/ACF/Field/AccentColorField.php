<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF\Field;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;

/**
 * AccentColorField
 */
class AccentColorField {
    /**
     * Create accent color select.
     *
     * @param string $key Field key prefix.
     *
     * @return \Geniem\ACF\Field\Select
     */
    public static function field( string $key = '' ) : ?Field\Select {
        $strings = [
            'accent_color' => [
                'label'        => 'Taustaväri',
                'instructions' => '',
            ],
        ];

        try {
            $field = ( new Field\Select( $strings['accent_color']['label'] ) )
                ->set_key( "${key}_accent_color" )
                ->set_name( 'accent_color' )
                ->use_ui()
                ->set_choices( apply_filters( 'tms/theme/accent_colors', [] ) )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['accent_color']['instructions'] );

            return apply_filters(
                'tms/acf/field/' . $field->get_key(),
                $field,
                $field->get_key()
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return null;
    }
}
