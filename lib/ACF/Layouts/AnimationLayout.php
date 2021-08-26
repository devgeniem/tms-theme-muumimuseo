<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF\Layouts;

use Geniem\ACF\Exception;
use Geniem\ACF\Field\Flexible\Layout;
use Geniem\ACF\Field\Image;
use TMS\Theme\Base\Logger;

/**
 * Class AnimationLayout
 *
 * @package TMS\Theme\Muumimuseo\ACF\Layouts
 */
class AnimationLayout extends Layout {

    /**
     * Layout key
     */
    const KEY = '_animation';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Animaatio',
            $key . self::KEY,
            'animation'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $key = $this->get_key();

        $strings = [
            'image' => [
                'label'        => 'Animoitu gif-tiedosto',
                'instructions' => '',
            ],
        ];

        $image_field = ( new Image( $strings['image']['label'] ) )
            ->set_key( "${key}_image" )
            ->set_name( 'image' )
            ->set_return_format( 'id' )
            ->set_required()
            ->set_instructions( $strings['image']['instructions'] );

        try {
            $this->add_fields(
                apply_filters(
                    'tms/acf/layout/' . $this->get_key() . '/fields',
                    [ $image_field ]
                )
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
