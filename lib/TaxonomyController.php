<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use TMS\Theme\Base\Interfaces\Controller;

/**
 * Class PostTypeController
 *
 * @package TMS\Theme\Base
 */
class TaxonomyController extends \TMS\Theme\Base\TaxonomyController implements Controller {

    /**
     * Get namespace for CPT instances
     *
     * @return string
     */
    protected function get_namespace() : string {
        return __NAMESPACE__;
    }

    /**
     * Get custom post type files
     *
     * @return array
     */
    protected function get_files() : array {
        return array_diff( scandir( __DIR__ . '/Taxonomy' ), [ '.', '..' ] );
    }
}
