<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use Closure;
use \TMS\Theme\Base;
use Geniem\ACF\Field\Textarea;
/**
 * Class ACFController
 *
 * @package TMS\Theme\Base
 */
class ACFController extends Base\ACFController implements Base\Interfaces\Controller {

    /**
     * Get ACF base dir
     *
     * @return string
     */
    protected function get_base_dir() : string {
        return __DIR__ . '/ACF';
    }

}
