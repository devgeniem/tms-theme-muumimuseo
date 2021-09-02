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
        add_filter( 'tms/acf/formatter/Hero/disable', '__return_true' );

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
        $layout['columns'] = [
            [
                'title' => $layout['opening_times']['opening_times_title'] ?? false,
                'text'  => $layout['opening_times']['opening_times_text'] ?? false,
                'link'  => $layout['opening_times']['opening_times_button'] ?? false,
            ],
            [
                'title' => $layout['tickets']['tickets_title'] ?? false,
                'text'  => $layout['tickets']['tickets_text'] ?? false,
                'logo'  => $layout['tickets']['tickets_image'] ?? false,
                'link'  => $layout['tickets']['tickets_button'] ?? false,
            ],
            [
                'title' => $layout['find_us']['find_us_title'] ?? false,
                'text'  => $layout['find_us']['find_us_text'] ?? false,
                'link'  => $layout['find_us']['find_us_button'] ?? false,
            ],
        ];

        return $layout;
    }
}
