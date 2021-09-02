<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

/**
 * Class FormatterController
 *
 * @package TMS\Theme\Base
 */
class FormatterController extends \TMS\Theme\Base\FormatterController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        parent::hooks();

        add_filter( 'tms/acf/formatter/Hero/disable', '__return_true' );
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
}
