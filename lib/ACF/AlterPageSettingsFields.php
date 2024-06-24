<?php
use TMS\Theme\Base\Logger;

/**
 * Alter Page settings -fields
 */
class AlterPageSettingsFields {

    /**
     * Constructor
     */
    public function __construct() {
        \add_filter(
            'tms/acf/group/fg_page_settings',
            [ $this, 'remove_hero_overlay_setting' ],
            100
        );
    }


    /**
     * Remove overlay TrueFalse-field from page settings
     *
     * @param Field\Group[] $fields Array of settings.
     *
     * @return array
     */
    public function remove_hero_overlay_setting( $group ) {
        try {
            if ( $group->get_key() === 'fg_page_settings' ) {
                $group->remove_field( 'fg_page_settings_remove_overlay' );
            }
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $group;
    }
}

( new AlterPageSettingsFields() );
