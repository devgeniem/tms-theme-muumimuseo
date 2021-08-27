<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo;

use Geniem\Role;
use TMS\Theme\Base\Interfaces\Controller;

/**
 * RolesController
 */
class RolesController implements Controller {

    /**
     * Artist / artist-cpt.
     *
     * @var string[]
     */
    private $artists_all_capabilities = [
        'delete_artist',
        'delete_artists',
        'delete_others_artists',
        'delete_private_artists',
        'delete_published_artists',
        'edit_artist',
        'edit_artists',
        'edit_others_artists',
        'edit_private_artists',
        'edit_published_artists',
        'publish_artists',
        'read',
        'read_artist',
        'read_private_artists',
    ];

    /**
     * Artwork / artwork-cpt.
     *
     * @var string[]
     */
    private $artworks_all_capabilities = [
        'delete_artwork',
        'delete_artworks',
        'delete_others_artworks',
        'delete_private_artworks',
        'delete_published_artworks',
        'edit_artwork',
        'edit_artworks',
        'edit_others_artworks',
        'edit_private_artworks',
        'edit_published_artworks',
        'publish_artworks',
        'read',
        'read_artwork',
        'read_private_artworks',
    ];

    /**
     * Artist Category taxonomy
     *
     * @var string[]
     */
    private $taxonomy_artist_category_all_capabilities = [
        'manage_artist_categories',
        'edit_artist_categories',
        'delete_artist_categories',
        'assign_artist_categories',
    ];

    /**
     * Artwork Type taxonomy
     *
     * @var string[]
     */
    private $taxonomy_artwork_type_all_capabilities = [
        'manage_artwork_types',
        'edit_artwork_types',
        'delete_artwork_types',
        'assign_artwork_types',
    ];

    /**
     * Artwork Location taxonomy
     *
     * @var string[]
     */
    private $taxonomy_artwork_location_all_capabilities = [
        'manage_artwork_locations',
        'edit_artwork_locations',
        'delete_artwork_locations',
        'assign_artwork_locations',
    ];

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'tms/roles/super_administrator', [ $this, 'modify_super_administrator_caps' ] );
        add_filter( 'tms/roles/admin', [ $this, 'modify_admin_caps' ] );
        add_filter( 'tms/roles/editor', [ $this, 'modify_editor_caps' ] );
        add_filter( 'tms/roles/author', [ $this, 'modify_author_caps' ] );
        add_filter( 'tms/roles/contributor', [ $this, 'modify_contributor_caps' ] );
    }

    /**
     * Modify Super Administrator caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_super_administrator_caps( Role $role ) {
        $role->add_caps( $this->artists_all_capabilities );
        $role->add_caps( $this->artworks_all_capabilities );
        $role->add_caps( $this->taxonomy_artist_category_all_capabilities );
        $role->add_caps( $this->taxonomy_artwork_location_all_capabilities );
        $role->add_caps( $this->taxonomy_artwork_type_all_capabilities );

        return $role;
    }

    /**
     * Modify Administrator caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_admin_caps( Role $role ) {
        $role->add_caps( $this->artists_all_capabilities );
        $role->add_caps( $this->artworks_all_capabilities );
        $role->add_caps( $this->taxonomy_artist_category_all_capabilities );
        $role->add_caps( $this->taxonomy_artwork_location_all_capabilities );
        $role->add_caps( $this->taxonomy_artwork_type_all_capabilities );

        return $role;
    }

    /**
     * Modify Editor caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_editor_caps( Role $role ) {
        $role->add_caps( $this->artists_all_capabilities );
        $role->add_caps( $this->artworks_all_capabilities );
        $role->add_caps( $this->taxonomy_artist_category_all_capabilities );
        $role->add_caps( $this->taxonomy_artwork_location_all_capabilities );
        $role->add_caps( $this->taxonomy_artwork_type_all_capabilities );

        return $role;
    }

    /**
     * Modify Author caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_author_caps( Role $role ) {
        $role->add_caps( $this->artists_all_capabilities );
        $role->add_caps( $this->artworks_all_capabilities );

        $role->add_caps( [
            'assign_artwork_locations',
            'assign_artwork_types',
            'assign_artist_categories',
        ] );

        return $role;
    }

    /**
     * Modify Contributor caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_contributor_caps( Role $role ) {
        $role->add_caps( [
            'delete_artist',
            'delete_artists',
            'delete_private_artists',
            'delete_published_artists',
            'edit_artist',
            'edit_artists',
            'edit_private_artists',
            'edit_published_artists',
            'read',
            'read_artist',
            'read_private_artists',
        ] );

        $role->add_caps( [
            'delete_artwork',
            'delete_artworks',
            'delete_private_artworks',
            'delete_published_artworks',
            'edit_artwork',
            'edit_artworks',
            'edit_private_artworks',
            'edit_published_artworks',
            'read',
            'read_artwork',
            'read_private_artworks',
        ] );

        $role->add_caps( [
            'assign_artwork_locations',
            'assign_artwork_types',
            'assign_artist_categories',
        ] );

        return $role;
    }
}
