<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Muumimuseo\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use Geniem\ACF\ConditionalLogicGroup;
use TMS\Theme\Base\Logger;

/**
 * Class HeaderSettingsMainMenuTab
 *
 * @package TMS\Theme\Muumimuseo\ACF\Tab
 */
class HeaderSettingsMainMenuTab extends Tab {

    /**
     * Where should the tab switcher be located
     *
     * @var string
     */
    protected $placement = 'left';

    /**
     * Tab strings.
     *
     * @var array
     */
    protected $strings = [

        'tab'           => 'Ylätunniste - päävalikko',

        'main_links'    => [
            'label'        => 'Päävalikon linkit',
            'instructions' => 'Lisää tähän päävalikon päätason linkit. Linkki voi viedä suoraan haluttuun sivuun tai se voi avata alavalikon, josta löytyy alatason linkkejä ja oikopolkulinkkejä.',
            'button'       => 'Lisää päätason linkki',
        ],

        'main_link'     => [
            'label'        => 'Päätason linkki',
            'instructions' => '',
        ],

        'use_submenu'   => [
            'label'        => 'Lisää alavalikko',
            'instructions' => 'Lisää tästä päätason linkin alle alavalikko. Jos et lisää alavalikkoa, päätason linkin klikkaaminen vie käyttäjän suoraan linkin osoitteeseen.',
        ],

        'submenu_links' => [
            'label'        => 'Alatason linkit',
            'instructions' => 'Lisää tähän päätason linkin alle tulevat alatason linkit.',
            'button'       => 'Lisää alatason linkki',
        ],

        'submenu_link'  => [
            'label'        => 'Alatason linkki',
            'instructions' => '',
        ],
    ];

    /**
     * The constructor for tab.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) { // phpcs:ignore

        $label = $this->strings['tab'];

        parent::__construct( $label );

        $this->sub_fields( $key );
    }

    /**
     * Register sub fields.
     *
     * @param string $key Field tab key.
     */
    public function sub_fields( $key ) {

        $strings = $this->strings;

        try {

            $main_links = ( new Field\Repeater( $strings['main_links']['label'] ) )
                ->set_key( "${key}_main_links" )
                ->set_name( 'main_links' )
                ->set_min( 1 )
                ->set_max( 8 )
                ->set_layout( 'block' )
                ->set_button_label( $strings['main_links']['button'] )
                ->set_instructions( $strings['main_links']['instructions'] );

            $main_link = ( new Field\Link( $strings['main_link']['label'] ) )
                ->set_key( "${key}_main_link" )
                ->set_name( 'main_link' )
                ->set_required()
                ->set_instructions( $strings['main_link']['instructions'] );

            $use_submenu = ( new Field\TrueFalse( $this->strings['use_submenu']['label'] ) )
                ->set_key( "${key}_use_submenu" )
                ->set_name( 'use_submenu' )
                ->set_default_value( false )
                ->use_ui()
                ->set_instructions( $this->strings['use_submenu']['instructions'] );

            $use_submenu_rule = ( new ConditionalLogicGroup() )
                ->add_rule( $use_submenu->get_key(), '==', '1' );

            $submenu_links = ( new Field\Repeater( $strings['submenu_links']['label'] ) )
                ->set_key( "${key}_submenu_links" )
                ->set_name( 'submenu_links' )
                ->set_button_label( $strings['submenu_links']['button'] )
                ->set_layout( 'table' )
                ->set_instructions( $strings['submenu_links']['instructions'] )
                ->add_conditional_logic( $use_submenu_rule );

            $submenu_link = ( new Field\Link( $strings['submenu_link']['label'] ) )
                ->set_key( "${key}_submenu_link" )
                ->set_name( 'submenu_link' )
                ->set_required()
                ->set_instructions( $strings['submenu_link']['instructions'] );

            $submenu_links->add_field( $submenu_link );

            $main_links->add_fields( [
                $main_link,
                $use_submenu,
                $submenu_links,
            ] );

            $main_links->set_collapsed( $main_link );

            $main_menu = ( new Field\Group( 'Päävalikko' ) )
                ->set_key( "${key}_main_menu" )
                ->set_name( 'main_menu' )
                ->hide_label()
                ->set_wrapper_classes( 'seamless no-label' );

            $main_menu->add_fields( [
                $main_links,
            ] );

            $this->add_fields( [
                $main_menu,
            ] );

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
