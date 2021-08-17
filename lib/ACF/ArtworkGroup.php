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
            'artists'                => [
                'title'        => 'Taiteilijat',
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

        $year_field = ( new Field\Text( $strings['year']['title'] ) )
            ->set_key( "${key}_year" )
            ->set_name( 'year' )
            ->set_instructions( $strings['year']['instructions'] );

        $additional_information_repeater = ( new Field\Repeater( $strings['additional_information']['title'] ) )
            ->set_key( "${key}_additional_information" )
            ->set_name( 'additional_information' )
            ->set_layout( 'block' )
            ->set_button_label( $strings['additional_information']['button'] );

        $additional_information_group = ( new Field\Group( $strings['additional_information']['group']['title'] ) )
            ->set_key( "${key}_additional_information_group" )
            ->set_name( 'additional_information_group' )
            ->set_instructions( $strings['additional_information']['group']['instructions'] );

        $additional_information_title = ( new Field\Text(
            $strings['additional_information']['item']['label']['title']
        ) )
            ->set_key( "${key}_additional_information_title" )
            ->set_name( 'additional_information_title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['item']['label']['instructions'] );

        $additional_information_text = ( new Field\Text( $strings['additional_information']['item']['value']['title'] ) )
            ->set_key( "${key}_additional_information_text" )
            ->set_name( 'additional_information_text' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['item']['value']['instructions'] );

        $additional_information_group->add_fields( [
            $additional_information_title,
            $additional_information_text,
        ] );

        $additional_information_repeater->add_field(
            $additional_information_group
        );

        $tab->add_fields( [
            $year_field,
            $images_field,
            $additional_information_repeater,
        ] );

        return $tab;
    }

    /**
     * Get writer tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_artwork_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'     => 'Teokset',
            'artwork' => [
                'title'        => 'Taideteokset',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $artwork_field = ( new Field\PostObject( $strings['artwork']['title'] ) )
            ->set_key( "${key}_artwork" )
            ->set_name( 'artwork' )
            ->set_post_types( [ PostType\Artwork::SLUG ] )
            ->allow_multiple()
            ->allow_null()
            ->set_instructions( $strings['artwork']['instructions'] );

        $tab->add_fields( [
            $artwork_field,
        ] );

        return $tab;
    }
}

( new ArtworkGroup() );
