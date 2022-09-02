<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Base\Muumimuseo;

use \Geniem\ACF\Exception;
use TMS\Theme\Base\Logger;
use TMS\Theme\Muumimuseo\ACF\Fields\Settings\ArtistSettingsTab;
use TMS\Theme\Muumimuseo\ACF\Fields\Settings\ArtworkSettingsTab;
use TMS\Theme\Muumimuseo\ACF\Fields\Settings\HeaderSettingsMainMenuTab;

/**
 * Class SettingsGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class SettingsGroup {

    /**
     * SettingsGroup constructor.
     */
    public function __construct() {
        add_filter(
            'tms/acf/group/fg_site_settings/fields',
            \Closure::fromCallable( [ $this, 'register_fields' ] ),
            10,
            2
        );
    }

    /**
     * Register fields
     *
     * @param array  $fields Field group fields.
     * @param string $key    Field group key.
     *
     * @return array
     */
    protected function register_fields( array $fields, string $key ) : array {
        try {
            $fields[] = new ArtworkSettingsTab( '', $key );
            $fields[] = new ArtistSettingsTab( '', $key );
            $fields[] = new HeaderSettingsMainMenuTab( '', $key );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }

        return $fields;
    }
}

( new SettingsGroup() );
