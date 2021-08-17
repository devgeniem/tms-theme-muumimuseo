<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Base\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Base\PostType;
use TMS\Theme\Base\Taxonomy\ArtworkLocation;

/**
 * Class ArtworkLocationGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class ArtworkLocationGroup {

    /**
     * PageGroup constructor.
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
            $field_group = ( new Group( 'Sijainnin lisätiedot' ) )
                ->set_key( 'fg_artwork_location_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'taxonomy', '==', ArtworkLocation::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_map_url_field( $field_group->get_key() ),
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
     * Get map_url field
     *
     * @param string $key Field group key.
     *
     * @return Field\Text
     */
    protected function get_map_url_field( string $key ) : Field\Text {
        $strings = [
            'map_url' => [
                'title'        => 'Kartan url',
                'instructions' => '',
            ],
        ];

        return ( new Field\Text( $strings['map_url']['title'] ) )
            ->set_key( "${key}_map_url" )
            ->set_name( 'map_url' )
            ->set_instructions( $strings['map_url']['instructions'] );
    }
}

( new ArtworkLocationGroup() );
