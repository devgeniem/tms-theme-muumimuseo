<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;

/**
 * Alter Hero Layout
 */
class AlterHeroLayout {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_hero/fields',
            [ $this, 'alter_fields' ],
            20,
            2
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
            'image'         => [
                'label'        => 'Kuva',
                'instructions' => '',
            ],
            'title'         => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'description'   => [
                'label'        => 'Kuvaus',
                'instructions' => '',
            ],
            'link'          => [
                'label'        => 'Painike',
                'instructions' => '',
            ],
            'align'         => [
                'label'        => 'Tekstin tasaus',
                'instructions' => '',
            ],
            'opening_times' => [
                'label'  => 'Aukioloajat',
                'title'  => [
                    'label'        => 'Otsikko',
                    'instructions' => '',
                ],
                'text'   => [
                    'label'        => 'Teksti',
                    'instructions' => '',
                ],
                'button' => [
                    'label'        => 'Painike',
                    'instructions' => '',
                ],
            ],
            'tickets'       => [
                'label'  => 'Liput',
                'title'  => [
                    'label'        => 'Otsikko',
                    'instructions' => '',
                ],
                'text'   => [
                    'label'        => 'Teksti',
                    'instructions' => '',
                ],
                'button' => [
                    'label'        => 'Painike',
                    'instructions' => '',
                ],
                'image'  => [
                    'label'        => 'Kuva',
                    'instructions' => '',
                ],
            ],
            'find_us'       => [
                'label'  => 'Löydä meille',
                'title'  => [
                    'label'        => 'Otsikko',
                    'instructions' => '',
                ],
                'text'   => [
                    'label'        => 'Teksti',
                    'instructions' => '',
                ],
                'button' => [
                    'label'        => 'Painike',
                    'instructions' => '',
                ],
            ],
        ];

        try {
            $image_field = ( new Field\Image( $strings['image']['label'] ) )
                ->set_key( "${key}_image" )
                ->set_name( 'image' )
                ->set_return_format( 'id' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['image']['instructions'] );

            $title_field = ( new Field\Text( $strings['title']['label'] ) )
                ->set_key( "${key}_title" )
                ->set_name( 'title' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['title']['instructions'] );

            $description_field = ( new Field\Textarea( $strings['description']['label'] ) )
                ->set_key( "${key}_description" )
                ->set_name( 'description' )
                ->set_rows( 4 )
                ->set_new_lines( 'wpautop' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['description']['instructions'] );

            $link_field = ( new Field\Link( $strings['link']['label'] ) )
                ->set_key( "${key}_link" )
                ->set_name( 'link' )
                ->set_wrapper_width( 40 )
                ->set_instructions( $strings['link']['instructions'] );

            $opening_times_tab = ( new Field\Group( $strings['opening_times']['label'] ) )
                ->set_key( "${key}_opening_times" )
                ->set_name( 'opening_times' );

            $opening_times_tab->add_fields(
                $this->get_hero_group_fields( $key, 'opening_times', $strings['opening_times'] )
            );

            $fields[] = $opening_times_tab;

            $ticket_tab = ( new Field\Group( $strings['tickets']['label'] ) )
                ->set_key( "${key}_tickets" )
                ->set_name( 'tickets' );

            $ticket_image_field = ( new Field\Image( $strings['tickets']['image']['label'] ) )
                ->set_key( "${key}_tickets_image" )
                ->set_name( 'tickets_image' )
                ->set_return_format( 'id' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['tickets']['image']['instructions'] );

            $ticket_tab_fields   = $this->get_hero_group_fields( $key, 'tickets', $strings['tickets'] );
            $ticket_tab_fields[] = $ticket_image_field;

            $ticket_tab->add_fields( $ticket_tab_fields );

            $find_us_tab = ( new Field\Group( $strings['find_us']['label'] ) )
                ->set_key( "${key}_find_us" )
                ->set_name( 'find_us' );

            $find_us_tab->add_fields(
                $this->get_hero_group_fields( $key, 'find_us', $strings['find_us'] )
            );

            $fields = [
                $image_field,
                $title_field,
                $description_field,
                $link_field,
                $opening_times_tab,
                $ticket_tab,
                $find_us_tab,
            ];
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $fields;
    }

    /**
     * Get hero group fields.
     *
     * @param string $key     Layout key.
     * @param string $group   Group name.
     * @param array  $strings Field strings.
     *
     * @return array
     * @throws Exception In case of invalid ACF option.
     */
    public function get_hero_group_fields( string $key, string $group, array $strings ) : array {
        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "${key}_${group}_title" )
            ->set_name( "${group}_title" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $text_field = ( new Field\Textarea( $strings['text']['label'] ) )
            ->set_key( "${key}_${group}_text" )
            ->set_name( "${group}_text" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['text']['instructions'] );

        $button_field = ( new Field\Link( $strings['button']['label'] ) )
            ->set_key( "${key}_${group}_button" )
            ->set_name( "${group}_button" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['button']['instructions'] );

        return [
            $title_field,
            $text_field,
            $button_field,
        ];
    }
}

( new AlterHeroLayout() );
