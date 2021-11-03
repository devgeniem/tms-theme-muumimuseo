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

     /**
     * Filters for overwriting grid field's properties
     *
     * @return void
     */
    public function hooks() : void{
        add_filter( 'tms/acf/grid_fields', Closure::fromCallable( [ $this, 'modify_acf'] ) );
    }

    /**
     * Functionality for modifying ACF fields for this theme
     *
     * @return mixed
     */
    private function modify_acf( $fields ){
        $description = $fields->sub_fields['description'];
        if( empty($description) || !$description instanceof Textarea ){
            return $fields;
        }
        $description->set_maxlength( 300 );
        $fields->sub_fields['description'] = $description;
        return $fields;
    }
}
