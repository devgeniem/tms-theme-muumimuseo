<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF\Layouts;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;

/**
 * Class LayoutInfoBadge
 *
 * @package TMS\Theme\Muumimuseo\ACF
 */
class LayoutInfoBadge {

    /**
     * LayoutInfoBadge constructor.
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_call_to_action/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/call_to_action/data',
            [ $this, 'alter_format' ],
            25
        );

        add_filter(
            'tms/acf/layout/_image_banner/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/image_banner/data',
            [ $this, 'alter_format' ],
            10
        );

        add_filter(
            'tms/block/image/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/block/image/data',
            [ $this, 'alter_format' ],
            10
        );

        add_filter(
            'tms/block/link_list/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/block/link_list/data',
            [ $this, 'alter_format' ],
            10
        );

        add_filter(
            'tms/block/video/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/block/video/data',
            [ $this, 'alter_format' ],
            10
        );
    }

    /**
     * Add badge fields.
     *
     * @param string $key Layout key.
     */
    public function get_fields( string $key ) : ?Field\Group {
        $group   = null;
        $strings = [
            'group' => [
                'label'        => 'Pyöreä nostobanneri',
                'instructions' => '',
            ],
            'align' => [
                'label'        => 'Asemointi',
                'instructions' => '',
            ],
            'text'  => [
                'label'        => 'Teksti',
                'instructions' => '',
            ],
        ];

        try {
            $group = ( new Field\Group( $strings['group']['label'] ) )
                ->set_key( "${key}_layout_badge" )
                ->set_name( 'layout_badge' );

            $align_field = ( new Field\Select( $strings['align']['label'] ) )
                ->set_key( "${key}_layout_badge_align" )
                ->set_name( 'layout_badge_align' )
                ->set_choices( [
                    'before' => 'Vasen',
                    'after'  => 'Oikea',
                ] )
                ->set_default_value( 'left' )
                ->set_wrapper_width( 30 )
                ->set_instructions( $strings['align']['instructions'] );

            $text_field = ( new Field\Textarea( $strings['text']['label'] ) )
                ->set_key( "${key}_layout_badge_text" )
                ->set_name( 'layout_badge_text' )
                ->set_rows( 2 )
                ->set_maxlength( 15 )
                ->set_wrapper_width( 70 )
                ->set_instructions( $strings['text']['instructions'] );

            $group->add_fields( [ $align_field, $text_field ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $group;
    }

    /**
     * Add badge fields.
     *
     * @param array  $fields Array of ACF fields.
     * @param string $key    Layout key.
     */
    public function alter_fields( array $fields, string $key ) : array {
        try {
            $fields[] = $this->get_fields( $key );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $fields;
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function alter_format( array $layout ) : array {
        if ( ! isset( $layout['layout_badge'] ) || empty( $layout['layout_badge']['layout_badge_text'] ) ) {
            return $layout;
        }

        $align      = $layout['layout_badge']['layout_badge_align'] ?? 'before';
        $badge_html = dustpress()->render( [
            'partial' => 'layout-badge',
            'type'    => 'html',
            'echo'    => false,
            'data'    => [
                'align' => "align-$align",
                'text'  => $layout['layout_badge']['layout_badge_text'],
            ],
        ] );

        $layout[ "${align}_main_content" ] = $badge_html;

        return $layout;
    }
}

( new LayoutInfoBadge() );
