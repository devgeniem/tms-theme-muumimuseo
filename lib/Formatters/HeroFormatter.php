<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\Formatters;

/**
 * Class HeroFormatter
 *
 * @package TMS\Theme\Base\Formatters
 */
class HeroFormatter implements \TMS\Theme\Base\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'Hero';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/hero/data',
            [ $this, 'format' ],
            30
        );
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function format( array $layout ) : array {
        $opening_times = [
            'title' => $layout['opening_times']['opening_times_title'] ?? false,
            'text'  => $layout['opening_times']['opening_times_text'] ?? false,
            'link'  => $layout['opening_times']['opening_times_button'] ?? false,
        ];

        if ( ! empty( $opening_times['title'] ) || ! empty( $opening_times['text'] ) ) {
            $layout['columns'][] = $opening_times;
        }

        $tickets = [
            'title' => $layout['tickets']['tickets_title'] ?? false,
            'text'  => $layout['tickets']['tickets_text'] ?? false,
            'logo'  => $layout['tickets']['tickets_image'] ?? false,
            'link'  => $layout['tickets']['tickets_button'] ?? false,
        ];

        if ( ! empty( $tickets['title'] ) || ! empty( $tickets['text'] ) ) {
            $layout['columns'][] = $tickets;
        }

        $find_us = [
            'title' => $layout['find_us']['find_us_title'] ?? false,
            'text'  => $layout['find_us']['find_us_text'] ?? false,
            'link'  => $layout['find_us']['find_us_button'] ?? false,
        ];

        if ( ! empty( $find_us['title'] ) || ! empty( $find_us['text'] ) ) {
            $layout['columns'][] = $find_us;
        }

        return $layout;
    }
}
