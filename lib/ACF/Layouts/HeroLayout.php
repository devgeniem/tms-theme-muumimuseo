<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF\Layouts;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Flexible\Layout;
use TMS\Theme\Base\Logger;

/**
 * Class HeroLayout
 *
 * @package TMS\Theme\Muumimuseo\ACF\Layouts
 */
class HeroLayout extends Layout {

    /**
     * Layout key
     */
    const KEY = '_hero';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Hero',
            $key . self::KEY,
            'hero'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields.
     *
     * @return array
     * @throws Exception In case of invalid option.
     */
    public function add_layout_fields() : array {
        $key = $this->get_key();

        $strings = [
            'image'         => [
                'label'        => 'Kuva',
                'instructions' => '',
            ],
            'video'          => [
                'label'        => 'Videotiedosto',
                'instructions' => '',
            ],
            'video_caption'  => [
                'label'        => 'Videon tekstivastine',
                'instructions' => 'Tarkoitettu ruudunlukijoille, ei näytetä sivustolla.',
            ],
            'autoplay_video' => [
                'label'        => 'Käynnistä video heti sivunlatauksessa',
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

        $image_field = ( new Field\Image( $strings['image']['label'] ) )
            ->set_key( "{$key}_image" )
            ->set_name( 'image' )
            ->set_return_format( 'id' )
            ->set_required()
            ->set_instructions( $strings['image']['instructions'] );

        $video_field = ( new Field\File( $strings['video']['label'] ) )
            ->set_key( "{$key}_video_file" )
            ->set_name( 'video_file' )
            ->set_mime_types( [ 'mp4' ] )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['video']['instructions'] );

        $video_caption_field = ( new Field\Textarea( $strings['video_caption']['label'] ) )
            ->set_key( "{$key}_video_caption" )
            ->set_name( 'video_caption' )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['video_caption']['instructions'] );

        $autoplay_video_field = ( new Field\TrueFalse( $strings['autoplay_video']['label'] ) )
            ->set_key( "{$key}_autoplay_video" )
            ->set_name( 'autoplay_video' )
            ->use_ui()
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['autoplay_video']['instructions'] );

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new Field\Textarea( $strings['description']['label'] ) )
            ->set_key( "{$key}_description" )
            ->set_name( 'description' )
            ->set_rows( 4 )
            ->set_new_lines( 'wpautop' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['description']['instructions'] );

        $link_field = ( new Field\Link( $strings['link']['label'] ) )
            ->set_key( "{$key}_link" )
            ->set_name( 'link' )
            ->set_wrapper_width( 40 )
            ->set_instructions( $strings['link']['instructions'] );

        $opening_times_tab = ( new Field\Group( $strings['opening_times']['label'] ) )
            ->set_key( "{$key}_opening_times" )
            ->set_name( 'opening_times' );

        $opening_times_tab->add_fields(
            $this->get_hero_group_fields( $key, 'opening_times', $strings['opening_times'] )
        );

        $fields[] = $opening_times_tab;

        $ticket_tab = ( new Field\Group( $strings['tickets']['label'] ) )
            ->set_key( "{$key}_tickets" )
            ->set_name( 'tickets' );

        $ticket_image_field = ( new Field\Image( $strings['tickets']['image']['label'] ) )
            ->set_key( "{$key}_tickets_image" )
            ->set_name( 'tickets_image' )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['tickets']['image']['instructions'] );

        $ticket_tab_fields   = $this->get_hero_group_fields( $key, 'tickets', $strings['tickets'] );
        $ticket_tab_fields[] = $ticket_image_field;

        $ticket_tab->add_fields( $ticket_tab_fields );

        $find_us_tab = ( new Field\Group( $strings['find_us']['label'] ) )
            ->set_key( "{$key}_find_us" )
            ->set_name( 'find_us' );

        $find_us_tab->add_fields(
            $this->get_hero_group_fields( $key, 'find_us', $strings['find_us'] )
        );

        try {
            $this->add_fields(
                apply_filters(
                    'tms/acf/layout/hero--muumimuseo/fields',
                    [
                        $image_field,
                        $video_field,
                        $video_caption_field,
                        $autoplay_video_field,
                        $title_field,
                        $description_field,
                        $link_field,
                        $opening_times_tab,
                        $ticket_tab,
                        $find_us_tab,
                    ],
                    $key
                )
            );
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
            ->set_key( "{$key}_{$group}_title" )
            ->set_name( "{$group}_title" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $text_field = ( new Field\Textarea( $strings['text']['label'] ) )
            ->set_key( "{$key}_{$group}_text" )
            ->set_name( "{$group}_text" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['text']['instructions'] );

        $button_field = ( new Field\Link( $strings['button']['label'] ) )
            ->set_key( "{$key}_{$group}_button" )
            ->set_name( "{$group}_button" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['button']['instructions'] );

        return [
            $title_field,
            $text_field,
            $button_field,
        ];
    }
}
