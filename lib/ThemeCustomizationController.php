<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use WP_post;

/**
 * Class ThemeCustomizationController
 *
 * @package TMS\Theme\Base
 */
class ThemeCustomizationController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'tms/theme/error404/search_link',
            \Closure::fromCallable( [ $this, 'error404_search_link' ] )
        );

        add_filter(
            'tms/theme/error404/home_link',
            \Closure::fromCallable( [ $this, 'error404_home_link' ] )
        );

        add_filter(
            'tms/theme/event/hero_info_classes',
            fn() => 'has-background-white has-text-secondary-invert'
        );

        add_filter(
            'tms/theme/event/hero_icon_classes',
            fn() => 'is-primary'
        );

        add_filter(
            'tms/theme/event/info_group_classes',
            fn() => 'has-background-light has-text-secondary-invert'
        );

        add_filter(
            'tms/theme/event/info_button_classes',
            fn() => 'is-primary'
        );

        add_filter(
            'tms/plugin-materials/block_materials/title_classes',
            fn() => 'has-text-primary-invert'
        );

        add_filter(
            'tms/plugin-materials/page_materials/submit_button_classes',
            fn() => 'is-primary is-borderless'
        );

        add_filter(
            'tms/plugin-materials/page_materials/material_page_item_classes',
            fn() => ''
        );

        add_filter(
            'tms/plugin-materials/page_materials/material_page_item_text_classes',
            fn() => 'has-text-white'
        );

        add_filter(
            'tms/plugin-materials/page_materials/material_page_item_button_classes',
            fn() => 'is-primary'
        );

        add_filter(
            'tms/theme/share_links/link_class',
            fn() => 'has-background-accent'
        );

        add_filter(
            'tms/theme/share_links/icon_class',
            fn() => 'is-black'
        );

        add_filter(
            'tms/theme/layout_events/item_bg_class',
            fn() => 'has-background-light'
        );

        add_filter(
            'tms/theme/layout_events/item_text_class',
            fn() => 'has-text-black'
        );

        add_filter(
            'tms/theme/page_events_calendar/item_classes',
            \Closure::fromCallable( [ $this, 'event_item_classes' ] )
        );

        add_filter(
            'tms/theme/page_events_search/item_classes',
            \Closure::fromCallable( [ $this, 'event_item_classes' ] )
        );

        add_filter(
            'tms/theme/event/group_title',
            \Closure::fromCallable( [ $this, 'event_info_group_title_classes' ] )
        );

        add_filter(
            'tms/theme/search/search_item',
            [ $this, 'search_classes' ]
        );

        add_filter(
            'tms/theme/accent_colors',
            [ $this, 'get_theme_accent_colors' ]
        );

        add_filter(
            'tms/theme/header/colors',
            [ $this, 'header_colors' ]
        );

        add_filter(
            'tms/theme/base/search_result_item',
            [ $this, 'alter_search_result_item' ]
        );

        add_filter(
            'tms/acf/block/quote/data',
            [ $this, 'alter_block_quote_data' ],
            30
        );

        add_filter(
            'tms/acf/block/subpages/data',
            [ $this, 'alter_block_subpages_data' ],
            30
        );

        add_filter(
            'tms/theme/layout_events/all_events_link',
            fn() => 'is-size-6 has-text-primary-invert is-family-secondary',
        );

        add_filter(
            'tms/theme/layout_events/event_item',
            fn() => '',
        );

        add_filter(
            'tms/theme/layout_events/event_icon',
            fn() => '',
        );

        add_filter(
            'tms/single/related_display_categories',
            '__return_false',
        );
    }

    /**
     * Override error404 search link class.
     *
     * @param array $link Link details.
     *
     * @return array
     */
    public function error404_search_link( $link ) : array {
        $link['classes'] = 'is-primary-invert is-outlined';

        return $link;
    }

    /**
     * Override error404 home link class.
     *
     * @param array $link Link details.
     *
     * @return array
     */
    public function error404_home_link( $link ) : array {
        $link['classes'] = 'is-primary';

        return $link;
    }

    /**
     * Search classes.
     *
     * @param array $classes Search view classes.
     *
     * @return array
     */
    public function search_classes( $classes ) : array {
        $classes['search_item']          = 'has-border-1 has-border-divider-invert';
        $classes['search_item_excerpt']  = 'has-text-small';
        $classes['search_form']          = 'has-colors-accent-secondary';
        $classes['search_filter_button'] = 'has-background-primary-invert has-text-accent-secondary-invert';
        $classes['event_search_section'] = 'has-border-bottom-1 has-border-divider-invert';

        return $classes;
    }

    /**
     * Override event item classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function event_item_classes( $classes ) : array {
        $classes['list'] = [
            'item'        => 'has-background-light',
            'meta_label'  => 'has-text-black',
            'icon'        => 'is-primary',
            'description' => 'has-text-black',
        ];

        $classes['grid'] = [
            'item'       => 'has-background-light',
            'item_inner' => 'has-text-black',
            'icon'       => 'is-primary',
        ];

        return $classes;
    }

    /**
     * Override event item classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function event_info_group_title_classes( $classes ) : array {
        $classes['title'] = 'has-background-gray';
        $classes['icon']  = 'is-primary';

        return $classes;
    }

    /**
     * Theme accent colors for layouts
     *
     * @return string[]
     */
    public function get_theme_accent_colors() : array {
        return [
            'has-colors-transparent'      => 'Ei taustaväriä (valkoinen teksti)',
            'has-colors-primary'          => 'Tumman vihreä (valkoinen teksti)',
            'has-colors-accent-tertiary'  => 'Keltaoranssi (musta teksti)',
            'has-colors-accent-secondary' => 'Turkoosi (musta teksti)',
            'has-colors-light'            => 'Muumipeikon valkoinen (musta teksti)',
        ];
    }

    /**
     * Get theme accent color by key
     *
     * @param string $key Accent color key.
     *
     * @return string|null
     */
    public function get_theme_accent_color_by_key( string $key ) : ?string {
        $map = $this->get_theme_accent_colors();

        return $map[ $key ] ?? null;
    }

    /**
     * Header colors
     *
     * @param array $colors Header colors config.
     *
     * @return array
     */
    public function header_colors( $colors ) {
        $colors['nav']['container'] = 'is-family-primary';

        return $colors;
    }

    /**
     * Alter search result item
     *
     * @param WP_Post $post_item Instance of \WP_Post.
     *
     * @return WP_post
     */
    public function alter_search_result_item( $post_item ) {
        $post_item->content_type = false;

        return $post_item;
    }

    /**
     * Alter Quote block data.
     *
     * @param array $data Block data.
     *
     * @return array
     */
    public function alter_block_quote_data( $data ) {
        $data['classes']['container'] = [];
        $data['classes']['quote']     = [
            'is-size-1',
            'has-line-height-tight',
            'is-family-tovescript',
        ];
        $data['classes']['author']    = '';

        if ( ! empty( $data['is_wide'] ) ) {
            $data['classes']['container'][] = 'is-align-wide';
        }

        return $data;
    }

    /**
     * Alter Subpages block data.
     *
     * @param array $data Block data.
     *
     * @return array
     */
    public function alter_block_subpages_data( $data ) {
        if ( empty( $data['subpages'] ) ) {
            return $data;
        }

        foreach ( $data['subpages'] as $key => $item ) {
            $data['subpages'][ $key ]['classes'] .= ' has-border-1 has-border-divider-invert';
        }

        $icon_colors_map = [
            'black'     => 'is-accent-tertiary',
            'white'     => 'is-primary',
            'primary'   => 'is-primary-invert',
            'secondary' => 'is-secondary-invert',
        ];

        $icon_color_key = $data['background_color'] ?? 'black';

        $data['icon_classes'] = $icon_colors_map[ $icon_color_key ];

        return $data;
    }
}
