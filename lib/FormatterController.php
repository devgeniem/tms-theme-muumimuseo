<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use TMS\Theme\Base\Formatters\HeroFormatter;

/**
 * Class FormatterController
 *
 * @package TMS\Theme\Muumimuseo
 */
class FormatterController extends \TMS\Theme\Base\FormatterController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_formatters' ] )
        );

        add_filter( 'tms/theme/base/formatters', [ $this, 'remove_formatters' ] );
    }

    /**
     * Get namespace for formatter instances
     *
     * @return string
     */
    protected function get_namespace() : string {
        return __NAMESPACE__;
    }

    /**
     * Get formatter files
     *
     * @return array
     */
    protected function get_formatter_files() : array {
        return array_diff( scandir( __DIR__ . '/Formatters' ), [ '.', '..' ] );
    }

    /**
     * Remove formatters.
     *
     * @param array $classes Formatter classes.
     *
     * @return array
     */
    public function remove_formatters( array $classes ) : array {
        $formatters_to_remove = [
            HeroFormatter::class,
        ];

        return array_filter(
            $classes,
            fn( $class ) => ! in_array( $class, $formatters_to_remove, true )
        );
    }
}
