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
use TMS\Theme\Base\PostType;

/**
 * Class PageGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class PageGroup {

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
            $group_title = 'Siuvn asetukset';

            $field_group = ( new Group( $group_title ) )
                ->set_key( 'fg_page_settings' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Page::SLUG )
                ->add_rule( 'page_template', '!=', \PageFrontPage::TEMPLATE )
                ->add_rule( 'page_template', '!=', \PageEventsCalendar::TEMPLATE )
                ->add_rule( 'page_template', '!=', \PageOnepager::TEMPLATE )
                ->add_rule( 'page_template', '!=', \PageEventsCalendar::TEMPLATE )
                ->add_rule( 'page_type', '!=', 'posts_page' );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'side' );

            $key = $field_group->get_key();

            $strings = [
                'use_overlay' => [
                    'title'        => 'Heron tummennus käytössä',
                    'instructions' => '',
                ],
            ];

            $use_overlay_field = ( new Field\TrueFalse( $strings['use_overlay']['title'] ) )
                ->set_key( "${key}_use_overlay" )
                ->set_name( 'use_overlay' )
                ->use_ui()
                ->set_default_value( false )
                ->set_instructions( $strings['use_overlay']['instructions'] );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $use_overlay_field,
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
}

( new PageGroup() );


