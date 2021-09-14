<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use TMS\Theme\Base\Logger;

/**
 * Class ArtworkSettingsTab
 *
 * @package TMS\Theme\Muumimuseo\ACF\Tab
 */
class ArtworkSettingsTab extends Tab {

    /**
     * Where should the tab switcher be located
     *
     * @var string
     */
    protected $placement = 'left';

    /**
     * Tab strings.
     *
     * @var array
     */
    protected $strings = [
        'tab'                              => 'Teokset',
        'artwork_archive_display_location' => [
            'title'        => 'Näytä sijainti arkistossa',
            'instructions' => '',
        ],
        'artwork_archive_display_artist'   => [
            'title'        => 'Näytä taiteilija arkistossa',
            'instructions' => '',
        ],
        'artwork_additional_info'      => [
            'title'        => 'Esitäytetyt lisätiedot',
            'instructions' => '',
        ],
        'artwork_additional_info_text' => [
            'title'        => 'Lisätiedon teksti',
            'instructions' => '',
        ],
    ];

    /**
     * The constructor for tab.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) { // phpcs:ignore
        $label = $this->strings['tab'];

        parent::__construct( $label );

        $this->sub_fields( $key );
    }

    /**
     * Register sub fields.
     *
     * @param string $key Field tab key.
     */
    public function sub_fields( $key ) {
        $strings = $this->strings;

        try {
            $display_location_field = ( new Field\TrueFalse( $strings['artwork_archive_display_location']['title'] ) )
                ->set_key( "${key}_artwork_archive_display_location" )
                ->set_name( 'artwork_archive_display_location' )
                ->set_default_value( true )
                ->use_ui()
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['artwork_archive_display_location']['instructions'] );

            $display_artist_field = ( new Field\TrueFalse( $strings['artwork_archive_display_artist']['title'] ) )
                ->set_key( "${key}_artwork_archive_display_artist" )
                ->set_name( 'artwork_archive_display_artist' )
                ->set_default_value( true )
                ->use_ui()
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['artwork_archive_display_artist']['instructions'] );

            $info_repeater_field = ( new Field\Repeater( $strings['artwork_additional_info']['title'] ) )
                ->set_key( "${key}_artwork_additional_info" )
                ->set_name( 'artwork_additional_info' )
                ->set_instructions( $strings['artwork_additional_info']['instructions'] );

            $text_field = ( new Field\Text( $strings['artwork_additional_info_text']['title'] ) )
                ->set_key( "${key}_artwork_additional_info_text" )
                ->set_name( 'artwork_additional_info_text' )
                ->set_instructions( $strings['artwork_additional_info_text']['instructions'] );

            $info_repeater_field->add_field( $text_field );

            $this->add_fields( [
                $display_location_field,
                $display_artist_field,
                $info_repeater_field,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
