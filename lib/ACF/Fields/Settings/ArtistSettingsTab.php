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
 * Class istSettingsTab
 *
 * @package TMS\Theme\Muumimuseo\ACF\Tab
 */
class ArtistSettingsTab extends Tab {

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
        'tab'                         => 'Taiteilijat',
        'artist_additional_info'      => [
            'title'        => 'Esitäytetyt lisätiedot',
            'instructions' => '',
        ],
        'artist_additional_info_text' => [
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
            $info_repeater_field = ( new Field\Repeater( $strings['artist_additional_info']['title'] ) )
                ->set_key( "${key}_artist_additional_info" )
                ->set_name( 'artist_additional_info' )
                ->set_instructions( $strings['artist_additional_info']['instructions'] );

            $text_field = ( new Field\Text( $strings['artist_additional_info_text']['title'] ) )
                ->set_key( "${key}_artist_additional_info_text" )
                ->set_name( 'artist_additional_info_text' )
                ->set_instructions( $strings['artist_additional_info_text']['instructions'] );

            $info_repeater_field->add_field( $text_field );

            $this->add_fields( [
                $info_repeater_field,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
