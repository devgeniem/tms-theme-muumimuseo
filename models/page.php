<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

/**
 * The Page class.
 */
class Page extends PageExtend {

    /**
     * Use overlay
     *
     * @return bool|mixed
     */
    public function use_overlay() {
        return get_field( 'use_overlay' ) ?? false;
    }
}
