<?php
/**
 * Plugin Name: Haricot Example
 * Plugin URI:  https://github.com/caercam/haricot
 * Description: Example plugin using Haricot.  Adds a manager to the edit category screen.
 * Version:     1.0.0
 * Author:      Justin Tadlock, Charlie Merland
 * Author URI:  http://themehybrid.com
 * Author URI:  http://caercam.org
 *
 * @package    HaricotExample
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @author     Charlie Merland <charlie@caercam.org>
 * @copyright  Copyright (c) 2016, Justin Tadlock, Charlie Merland
 * @link       https://github.com/caercam/haricot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( ! class_exists( 'Haricot_Example' ) ) {

	/**
	 * Main Haricot class.  Runs the show.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class Haricot_Example {

		/**
		 * Sets up initial actions.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function setup_actions() {

			// Register managers, sections, settings, and controls.
			// I'm separating these out into their own methods so that the code
			// is cleaner and easier to follow.  This is just an example, so feel
			// free to experiment.
			add_action( 'haricot_register', array( $this, 'register_managers' ), 10, 2 );
			add_action( 'haricot_register', array( $this, 'register_sections' ), 10, 2 );
			add_action( 'haricot_register', array( $this, 'register_settings' ), 10, 2 );
			add_action( 'haricot_register', array( $this, 'register_controls' ), 10, 2 );
		}

		/**
		 * Registers managers.  In this example, we're registering a single manager.
		 * A manager is essentially our "tabbed meta box".  It needs to have
		 * sections and controls added to it.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object  $haricot  Instance of the `Haricot` object.
		 * @param  string  $taxonomy
		 * @return void
		 */
		public function register_managers( $haricot, $taxonomy ) {

			if ( 'category' !== $taxonomy )
				return;

			$haricot->register_manager(
				'hrct_example',
				array(
					'label'     => 'Haricot Example',
					'post_type' => array( 'category' )
				)
			);
		}

		/**
		 * Registers sections.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object  $haricot  Instance of the `Haricot` object.
		 * @param  string  $taxonomy
		 * @return void
		 */
		public function register_sections( $haricot, $taxonomy ) {

			if ( 'category' !== $taxonomy )
				return;

			// Gets the manager object we want to add sections to.
			$manager = $haricot->get_manager( 'hrct_example' );

			$manager->register_section(
				'hrct_text_fields',
				array(
					'label' => 'Text Fields',
					'icon'  => 'dashicons-edit'
				)
			);

			$manager->register_section(
				'hrct_common_fields',
				array(
					'label' => 'Common Fields',
					'icon'  => 'dashicons-admin-generic'
				)
			);

			$manager->register_section(
				'hrct_color_fields',
				array(
					'label' => 'Color Fields',
					'icon'  => 'dashicons-art'
				)
			);

			$manager->register_section(
				'hrct_radio_fields',
				array(
					'label' => 'Radio Fields',
					'icon'  => 'dashicons-carrot'
				)
			);

			$manager->register_section(
				'hrct_special_fields',
				array(
					'label' => 'Special Fields',
					'icon'  => 'dashicons-star-filled'
				)
			);
		}

		/**
		 * Registers settings.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object  $haricot  Instance of the `Haricot` object.
		 * @param  string  $taxonomy
		 * @return void
		 */
		public function register_settings( $haricot, $taxonomy ) {

			if ( 'category' !== $taxonomy )
				return;

			// Gets the manager object we want to add settings to.
			$manager = $haricot->get_manager( 'hrct_example' );

			// Text field setting.
			$manager->register_setting(
				'hrct_text_a',
				array( 'sanitize_callback' => 'wp_filter_nohtml_kses', 'default' => 'yay' )
			);

			// Textarea setting.
			$manager->register_setting(
				'hrct_textarea_a',
				array( 'sanitize_callback' => 'wp_kses_post', 'default' => 'Hello world' )
			);

			// Checkbox setting.
			$manager->register_setting(
				'hrct_checkbox_a',
				array( 'sanitize_callback' => 'haricot_validate_boolean' )
			);

			// Multiple checkboxes array.
			$manager->register_setting(
				'hrct_checkboxes_a',
				array( 'type' => 'array', 'sanitize_callback' => 'sanitize_key' )
			);

			// Radio input.
			$manager->register_setting(
				'hrct_radio_a',
				array( 'sanitize_callback' => 'sanitize_key' )
			);

			// Radio image.
			$manager->register_setting(
				'hrct_radio_image_a',
				array( 'sanitize_callback' => 'sanitize_key', 'default' => 'planet-burst' )
			);

			// Select input.
			$manager->register_setting(
				'hrct_select_a',
				array( 'sanitize_callback' => 'sanitize_key' )
			);

			// Datetime.  Note the `datetime` setting type sanitizes on its own.
			$manager->register_setting(
				'hrct_date_a',
				array( 'type' => 'datetime' )
			);

			// Color picker setting.
			$manager->register_setting(
				'hrct_color_a',
				array( 'sanitize_callback' => 'sanitize_hex_color_no_hash', 'default' => '#232323' )
			);

			// Color palette.
			$manager->register_setting(
				'hrct_palette_a',
				array( 'sanitize_callback' => 'sanitize_key' )
			);

			// Image upload.
			$manager->register_setting(
				'hrct_image_a',
				array( 'sanitize_callback' => array( $this, 'sanitize_absint' ) )
			);
		}

		/**
		 * Registers controls.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object  $haricot  Instance of the `Haricot` object.
		 * @param  string  $taxonomy
		 * @return void
		 */
		public function register_controls( $haricot, $taxonomy ) {

			if ( 'category' !== $taxonomy )
				return;

			// Gets the manager object we want to add controls to.
			$manager = $haricot->get_manager( 'hrct_example' );

			// Basic text input control (the default).
			$manager->register_control(
				'hrct_text_a',
				array(
					'type'        => 'text',
					'section'     => 'hrct_text_fields',
					'attr'        => array( 'class' => 'widefat' ),
					'label'       => 'Example Text',
					'description' => 'Example description.'
				)
			);

			// Textarea control.
			$manager->register_control(
				'hrct_textarea_a',
				array(
					'type'        => 'textarea',
					'section'     => 'hrct_text_fields',
					'attr'        => array( 'class' => 'widefat' ),
					'label'       => 'Example Textarea',
					'description' => 'Example description.'
				)
			);

			// Single boolean checkbox.
			$manager->register_control(
				'hrct_checkbox_a',
				array(
					'type'        => 'checkbox',
					'section'     => 'hrct_common_fields',
					'label'       => 'Example Checkbox',
					'description' => 'Example description.'
				)
			);

			// Multiple checkboxes.
			$manager->register_control(
				'hrct_checkboxes_a',
				array(
					'type'        => 'checkboxes',
					'section'     => 'hrct_common_fields',
					'label'       => 'Example Checkbox',
					'description' => 'Example description.',
					'choices'     => array(
						'choice_d' => 'Choice D',
						'choice_e' => 'Choice E',
						'choice_f' => 'Choice F',
					)
				)
			);

			// Radio input fields.
			$manager->register_control(
				'hrct_radio_a',
				array(
					'type'        => 'radio',
					'section'     => 'hrct_radio_fields',
					'label'       => 'Example Radio',
					'description' => 'Example description.',
					'choices'     => array(
						''         => 'None',
						'choice_a' => 'Choice A',
						'choice_b' => 'Choice B',
						'choice_c' => 'Choice C',
					)
				)
			);

			// Select control.
			$manager->register_control(
				'hrct_select_a',
				array(
					'type'        => 'select',
					'section'     => 'hrct_common_fields',
					'label'       => 'Example Select',
					'description' => 'Example description.',
					'choices'     => array(
						''         => '',
						'choice_x' => 'Choice X',
						'choice_y' => 'Choice Y',
						'choice_z' => 'Choice Z'
					)
				)
			);

			// Select control with optgroup.
			$manager->register_control(
				'hrct_select_b',
				array(
					'type'        => 'select-group',
					'section'     => 'hrct_common_fields',
					'label'       => 'Example Select B',
					'description' => 'Example description.',
					'choices'  => array(
						'' => '',
						array(
							'label' => 'Citrus',
							'choices' => array(
								'grapefruit' => 'Grapefruit',
								'lemon'      => 'Lemon',
								'lime'       => 'Lime',
								'orange'     => 'Orange',
							)
						),
						array(
							'label'   => 'Melons',
							'choices' => array(
								'banana-melon' => 'Banana',
								'cantaloupe'   => 'Cantaloupe',
								'honeydew'     => 'Honeydew',
								'watermelon'   => 'Watermelon'
							)
						)
					)
				)
			);

			// Color picker control.
			$manager->register_control(
				'hrct_color_a',
				array(
					'type'        => 'color',
					'section'     => 'hrct_color_fields',
					'label'       => 'Pick a color',
					'description' => 'Example description.'
				)
			);

			// Color palette control.
			$manager->register_control(
				'hrct_palette_a',
				array(
					'type'        => 'palette',
					'section'     => 'hrct_color_fields',
					'label'       => 'Pick a color palette',
					'description' => 'Example description.',
					'choices'     => array(
						'cilantro' => array(
							'label'  => 'Cilantro',
							'colors' => array( '99ce15', '389113', 'BDE066', 'DB412C' )
						),
						'quench' => array(
							'label'  => 'Quench',
							'colors' => array( '#ffffff', '#7cc7dc', '#60A4B9', '#a07096' )
						),
						'cloudy-days' => array(
							'label'  => 'Cloudy Days',
							'colors' => array( '#E2735F', '#eaa16e', '#FBDF8B', '#ffe249' )
						)
					)
				)
			);

			// Radio image control.

			$uri = trailingslashit( plugin_dir_url(  __FILE__ ) );

			$manager->register_control(
				'hrct_radio_image_a',
				array(
					'type'        => 'radio-image',
					'section'     => 'hrct_radio_fields',
					'label'       => 'Example Radio Image',
					'description' => 'Example description.',
					'choices' => array(
						'horizon' => array(
							'url'   => $uri . 'images/horizon-thumb.jpg',
							'label' => 'Horizon'
						),
						'orange-burn' => array(
							'url'   => $uri . 'images/orange-burn-thumb.jpg',
							'label' => 'Orange Burn'
						),
						'planet-burst' => array(
							'url'   => $uri . 'images/planet-burst-thumb.jpg',
							'label' => 'Planet Burst'
						),
						'planets-blue' => array(
							'url'   => $uri . 'images/planets-blue-thumb.jpg',
							'label' => 'Blue Planets'
						),
						'space-splatters' => array(
							'url'   => $uri . 'images/space-splatters-thumb.jpg',
							'label' => 'Space Splatters'
						)
					)
				)
			);

			// Image upload control.
			$manager->register_control(
				'hrct_image_a',
				array(
					'type'        => 'image',
					'section'     => 'hrct_special_fields',
					'label'       => 'Example Image',
					'description' => 'Example description.',
					'size'        => 'thumbnail'
				)
			);

			// Datetime control.
			$manager->register_control(
				'hrct_date_a',
				array(
					'type'        => 'datetime',
					'section'     => 'hrct_special_fields',
					'label'       => 'Example Date',
					'description' => 'Example description.'
				)
			);
		}

		/**
		 * Sanitize function for integers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  int     $value
		 * @return int|string
		 */
		public function sanitize_absint( $value ) {

			return $value && is_numeric( $value ) ? absint( $value ) : '';
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			static $instance = null;

			if ( is_null( $instance ) ) {
				$instance = new self;
				$instance->setup_actions();
			}

			return $instance;
		}

		/**
		 * Constructor method.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function __construct() {}
	}

	Haricot_Example::get_instance();
}
