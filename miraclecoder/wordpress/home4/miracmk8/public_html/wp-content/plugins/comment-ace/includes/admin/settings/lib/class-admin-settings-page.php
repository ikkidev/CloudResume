<?php
/**
 * Settings Page class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

class Admin_Settings_Page {
	/**
	 * Admin capability
	 *
	 * @var string
	 */
	public $capability;

	/**
	 * Admin settings page
	 *
	 * @var string
	 */
	public $page;

    /**
     * Settings configuration
     *
     * @var array
     */
	protected $config;

	public function __construct( $config ) {
	    $this->config     = $config;
        $this->page       = 'options-general.php';
        $this->capability = is_multisite() ? 'manage_network_options' : 'manage_options';

		$this->setup_hooks();
	}

	/**
	 * Define all hooks
	 */
	private function setup_hooks() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_head', array( $this, 'hide_subpages' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_page() {
		$hooks = array();

		foreach( $this->config as $page_id => $page_config ) {


			$hooks[] = add_options_page(
				__( 'CommentAce', 'cace' ),
				__( 'CommentAce', 'cace' ),
				$this->capability,
				$page_id,
				function () use( $page_id, $page_config ) {
				    $this->render_page( $page_id, $page_config );
                }
			);
		}

		// Highlight Settings > CommentAce menu item regardless of current tab.
		foreach ( $hooks as $hook ) {
			add_action( "admin_head-$hook", array( $this, 'menu_highlight' ) );
		}
	}

    public function render_page( $page_id, $page_config ) {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CommentAce Settings', 'cace' ); ?></h1>

            <h2 class="nav-tab-wrapper"><?php $this->redner_tabs( $page_config['title'] ); ?></h2>
            <form action="options.php" method="post">

                <?php settings_fields( $page_id ); ?>
                <?php do_settings_sections( $page_id ); ?>

                <p class="submit">
                    <input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'cace' ); ?>" />
                </p>

            </form>
        </div>
        <?php
    }

    /**
     * Output the tabs in the admin area.
     *
     * @param string $active_tab        Name of the tab that is active. Optional.
     */
    function redner_tabs( $active_tab = '' ) {
        $tabs_html    = '';
        $idle_class   = 'nav-tab';
        $active_class = 'nav-tab nav-tab-active';

        /**
         * Filters the admin tabs to be displayed.
         *
         * @param array $value      Array of tabs to output to the admin area.
         */
        $tabs = apply_filters( 'cace_admin_settings_tabs', $this->get_tabs( $active_tab ) );

        // Loop through tabs and build navigation.
        foreach ( array_values( $tabs ) as $tab_data ) {
            $is_current = (bool) ( $tab_data['name'] === $active_tab );
            $tab_class  = $is_current ? $active_class : $idle_class;
            $tabs_html .= '<a href="' . esc_url( $tab_data['href'] ) . '" class="' . esc_attr( $tab_class ) . '">' . esc_html( $tab_data['name'] ) . '</a>';
        }

        echo filter_var( $tabs_html );

        do_action( 'cace_admin_tabs' );
    }

    /**
     * Get tabs in the admin settings area.
     *
     * @param string $active_tab        Name of the tab that is active. Optional.
     *
     * @return string
     */
    function get_tabs( $active_tab = '' ) {
        $tabs           = array();

        foreach( $this->config as $page_id => $page_config ) {
            $tabs[] = array(
                'href' => get_admin_url( add_query_arg( array( 'page' => $page_id ), 'admin.php' ) ),
                'name' => $page_config['title'],
            );
        }

        return apply_filters( 'cace_get_admin_settings_tabs', $tabs, $active_tab );
    }

    /**
     * Highlight the Settings > CommentAce main menu item regardless of which actual tab we are on.
     */
    function menu_highlight() {
        global $plugin_page, $submenu_file;

        $page_ids = array_keys( $this->config );

        if ( in_array( $plugin_page, $page_ids, true ) ) {
            // We want to map all subpages to one settings page (in main menu).
            $submenu_file = $page_ids[0];
        }
    }

    /**
	 * Hide submenu items under the Settings section
	 */
	public function hide_subpages() {
		$index = 0;

		foreach( $this->config as $page_id => $page_config ) {
			if ( 0 === $index++ ) {
				continue;
			}

			remove_submenu_page( $this->page, $page_id );
		}
	}

	/**
	 * Register settings
	 *
	 * @return void
	 */
	public function page_init() {
		// Loop through sections.
		foreach ( $this->config as $page_id => $page_config ) {

			// Add the section.
			add_settings_section(
				$page_id,
				'',
				! empty( $page_config['description_callback'] ) ? $page_config['description_callback'] : '',
				$page_id
			);

			if ( empty( $page_config['fields'] ) ) {
			    continue;
            }

			// Loop through fields for this section.
			foreach ( $page_config['fields'] as $field_id => $field ) {

				// Add the field.
                add_settings_field(
                    $field_id,
                    $field['title'],
                    function () use( $field_id, $field ) {
                        if ( isset( $field['callback'] ) ) {
                            call_user_func( 'Commentace\\' . $field['callback'], $field_id, $field );
                            return;
                        }

                        $field_class_name = sprintf( 'Commentace\\%s_Field', ucfirst( $field['type'] ) );

                        /**
                         * var Base_Field $field
                         */
                        $field = new $field_class_name( $field_id, $field );
                        $field->render();
                    },
                    $page_id,
                    $page_id,
                    array()
                );

                $sanitize_callback = ! empty( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : 'sanitize_text_field';

				// Register the setting.
				register_setting( $page_id, $field_id, $sanitize_callback );
			}
		}
	}
}
