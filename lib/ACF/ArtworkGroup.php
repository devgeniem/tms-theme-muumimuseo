<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Muumimuseo\PostType;

/**
 * Class ArtworkGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class ArtworkGroup {

    /**
     * ArtworkGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Taideteoksen lisätiedot' ) )
                ->set_key( 'fg_artwork_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Artwork::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_details_tab( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get writer tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_details_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'                    => 'Lisätiedot',
            'images'                 => [
                'title'        => 'Kuvat',
                'instructions' => '',
            ],
            'year'                   => [
                'title'        => 'Valmistumisvuosi',
                'instructions' => '',
            ],
            'additional_information' => [
                'title'        => 'Lisätiedot',
                'instructions' => '',
                'button'       => 'Lisää rivi',
                'group'        => [
                    'title'        => 'Otsikko',
                    'instructions' => '',
                ],
                'item'         => [
                    'label' => [
                        'title'        => 'Otsikko',
                        'instructions' => '',
                    ],
                    'value' => [
                        'title'        => 'Teksti',
                        'instructions' => '',
                    ],
                ],
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $images_field = ( new Field\Gallery( $strings['images']['title'] ) )
            ->set_key( "${key}_images" )
            ->set_name( 'images' )
            ->set_instructions( $strings['images']['instructions'] );

        $artists_field = ( new Field\PostObject( $strings['artists']['title'] ) )
            ->set_key( "${key}_artists" )
            ->set_name( 'artists' )
            ->set_post_types( [ PostType\Artist::SLUG ] )
            ->allow_multiple()
            ->allow_null()
            ->set_return_format( 'id' )
            ->set_instructions( $strings['artists']['instructions'] );

        $additional_info_repeater = ( new Field\Repeater( $strings['additional_information']['title'] ) )
            ->set_key( "${key}_additional_information" )
            ->set_name( 'additional_information' )
            ->set_layout( 'block' )
            ->set_button_label( $strings['additional_information']['button'] );

        $additional_info_group = ( new Field\Group( $strings['additional_information']['group']['title'] ) )
            ->set_key( "${key}_additional_information_group" )
            ->set_name( 'additional_information_group' )
            ->set_instructions( $strings['additional_information']['group']['instructions'] );

        $additional_info_title = ( new Field\Text(
            $strings['additional_information']['item']['label']['title']
        ) )
            ->set_key( "${key}_additional_information_title" )
            ->set_name( 'additional_information_title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['item']['label']['instructions'] );

        $additional_info_text = ( new Field\Text( $strings['additional_information']['item']['value']['title'] ) )
            ->set_key( "${key}_additional_information_text" )
            ->set_name( 'additional_information_text' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['item']['value']['instructions'] );

        $additional_info_group->add_fields( [
            $additional_info_title,
            $additional_info_text,
        ] );

        $additional_info_repeater->add_field(
            $additional_info_group
        );

        $tab->add_fields( [
            $images_field,
            $artists_field,
            $additional_info_repeater,
        ] );

        return $tab;
    }
}

( new ArtworkGroup() );
