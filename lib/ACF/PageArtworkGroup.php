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
use TMS\Theme\Muumimuseo\Taxonomy\ArtworkType;

/**
 * Class PageGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class PageArtworkGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );

        add_filter(
            'tms/acf/group/fg_page_components/rules',
            \Closure::fromCallable( [ $this, 'alter_component_rules' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $group_title = 'Arkiston asetukset';

            $field_group = ( new Group( $group_title ) )
                ->set_key( 'fg_page_artwork_settings' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'page_template', '==', \PageArtwork::TEMPLATE );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $key = $field_group->get_key();

            $strings = [
                'description'   => [
                    'title'        => 'Kuvaus',
                    'instructions' => '',
                ],
                'artwork_types' => [
                    'title'        => 'Teosten kategoriat',
                    'instructions' => '',
                ],
            ];

            $description_field = ( new Field\Wysiwyg( $strings['description']['title'] ) )
                ->set_key( "${key}_description" )
                ->set_name( 'description' )
                ->disable_media_upload()
                ->set_tabs( 'visual' )
                ->set_instructions( $strings['description']['instructions'] );

            $artwork_types_field = ( new Field\Taxonomy( $strings['artwork_types']['title'] ) )
                ->set_key( "${key}_artwork_types" )
                ->set_name( 'artwork_types' )
                ->set_taxonomy( ArtworkType::SLUG )
                ->set_return_format( 'object' )
                ->allow_null()
                ->set_instructions( $strings['artwork_types']['instructions'] );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $description_field,
                        $artwork_types_field,
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
     * Hide components from PageArtwork
     *
     * @param array $rules ACF group rules.
     *
     * @return array
     */
    protected function alter_component_rules( array $rules ) : array {
        $rules[] = [
            'param'    => 'page_template',
            'operator' => '!=',
            'value'    => \PageArtwork::TEMPLATE,
        ];

        return $rules;
    }
}

( new PageArtworkGroup() );


