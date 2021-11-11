<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Logger;

/**
 * Alter Grid Fields block
 */
class AlterGridFields {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/block/grid/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );
        add_filter(
            'tms/layout/grid/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );
    }

    /**
     * Alter fields
     *
     * @param array $fields Array of ACF fields.
     *
     * @return array
     */
    public function alter_fields( array $fields ) : array {
        try {
            $fields['repeater']->sub_fields['grid_item_custom']->sub_fields['description']->set_maxlength( 300 );
            $fields['repeater']->sub_fields['grid_item_custom']->sub_fields['description']->set_new_lines( 'br' );

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterGridFields() );
