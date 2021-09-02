<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

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
            'tms/theme/layout_events/item_icon_class',
            fn() => 'is-black'
        );

        add_filter(
            'tms/theme/page_events_calendar/item_classes',
            \Closure::fromCallable( [ $this, 'event_item_classes' ] )
        );

        add_filter(
            'tms/theme/event/group_title',
            \Closure::fromCallable( [ $this, 'event_info_group_title_classes' ] )
        );

        add_filter(
            'tms/theme/accent_colors',
            [ $this, 'get_theme_accent_colors' ]
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
}
