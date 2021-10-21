<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

/**
 * Class Assets
 *
 * @package TMS\Theme\Base
 */
class Assets extends \TMS\Theme\Base\Assets implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter( 'tms/theme/theme_default_color', [ $this, 'theme_name' ] );
        add_filter( 'tms/theme/theme_selected', [ $this, 'theme_name' ] );

        add_filter( 'tms/theme/theme_css_path', [ $this, 'theme_asset_path' ], 10, 2 );
        add_filter( 'tms/theme/theme_js_path', [ $this, 'theme_asset_path' ], 10, 2 );
        add_filter( 'tms/theme/admin_js_path', [ $this, 'base_theme_asset_path' ], 10, 2 );

        add_filter( 'tms/theme/asset_mod_time', function ( $mod_time, $filename ) {
            if ( false !== strpos( $filename, 'muumimuseo' ) ) {
                $dist_path = get_stylesheet_directory() . '/assets/dist/' . $filename;

                if ( file_exists( $dist_path ) ) {
                    return filemtime( $dist_path );
                }
            }

            return $mod_time;

        }, 10, 2 );

        \add_action(
            'wp_enqueue_scripts',
            \Closure::fromCallable( [ $this, 'enqueue_language_assets' ] ),
            100
        );
    }

    /**
     * Get theme name.
     *
     * @return string
     */
    public function theme_name() : string {
        return 'muumimuseo';
    }

    /**
     * Get theme asset path.
     *
     * @param string $full_path Asset path.
     * @param string $file      File name.
     *
     * @return string
     */
    public function theme_asset_path( $full_path, $file ) : string { // // phpcs:ignore
        return get_stylesheet_directory_uri() . '/assets/dist/' . $file;
    }

    /**
     * Get base theme asset path.
     *
     * @param string $full_path Asset path.
     * @param string $file      File name.
     *
     * @return string
     */
    public function base_theme_asset_path( $full_path, $file ) : string { // // phpcs:ignore
        return get_template_directory_uri() . '/assets/dist/' . $file;
    }

    /**
     * Language style assets.
     */
    private function enqueue_language_assets() : void {
        $assets_for_langs = [ 'ja', 'ko', 'ru', 'zh' ];
        $current_language = function_exists( 'pll_current_language' ) ? \pll_current_language() : \get_locale();

        if ( in_array( $current_language, $assets_for_langs, true ) ) {
            // Load Typekit for custom fonts
            \wp_enqueue_script(
                'typekit-js',
                DPT_ASSET_URI . '/typekit.js',
                [],
                static::get_theme_asset_mod_time( 'typekit.js' ),
                true
            );

            // Load language specific CSS file
            $language_stylesheet = "/lang_{$current_language}.css";
            \wp_enqueue_style(
                $language_stylesheet,
                DPT_ASSET_URI . $language_stylesheet,
                [],
                static::get_theme_asset_mod_time( $language_stylesheet ),
                false
            );
        }
    }
}
