<?php
/*
Plugin Name: WP Scripts Customizer
Plugin URI: http://themehall.com
Description: WP Scripts Customizer allows to enter scripts you would like output to head and footer of your WordPress theme page via WordPress Theme customizer.
Version: 1.0.0
Author: ThemeHall 
Author URI: http://themehall.com

Text Domain: wsc
*/

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'wsc_script_register' );

/**
 * Registers custom sections, settings, and controls for the $wp_customize instance.
 *
 * @since 0.3.2
 * @access private
 * @param object $wp_customize
 */
function wsc_script_register( $wp_customize ) {

	class WP_Script_Control_Textarea extends WP_Customize_Control {

		/**
		 * The type of customize control being rendered.
		 *
		 * @since 1.0.0
		 */
		public $type = 'textarea';
		public $extra = ''; // we add this for the extra description

		/**
		 * Displays the textarea on the customize screen.
		 *
		 * @since 1.0.0
		 */
		public function render_content() { ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div class="customize-control-content">
					<textarea class="widefat" cols="45" rows="5" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
					<p class="description"><?php echo esc_html( $this->extra ); ?></p>
				</div>
			</label>
		<?php }
	}

	/* Add the Script section. */
	$wp_customize->add_section(
		'script_section',
		array(
			'title'      => esc_html__( 'Scripts', 'wsc' ),
			'priority'   => 151,
			'capability' => 'unfiltered_html'
		)
	);

	/* Add the 'header_scripts' setting. */
	$wp_customize->add_setting(
		"wsc_options[header_scripts]",
		array(
			'default'              => '',
			'type'                 => 'option',
			'capability'           => 'unfiltered_html',
		)
	);

	/* Add the checkbox control for the 'header_scripts' setting. */
	$wp_customize->add_control( 
		new WP_Script_Control_Textarea(
			$wp_customize,
			'header_scripts',
			array(
				'label'    => esc_html__( 'Header Scripts', 'wsc' ),
				'section'  => 'script_section',
				'settings' => 'wsc_options[header_scripts]',
				'extra'	=> esc_html__( 'Insert scripts or code before the closing </head> tag in the document source:', 'wsc' )
			)
		)
	);

	/* Add the 'footer_scripts' setting. */
	$wp_customize->add_setting(
		"wsc_options[footer_scripts]",
		array(
			'default'              => '',
			'type'                 => 'option',
			'capability'           => 'unfiltered_html',
		)
	);

	/* Add the checkbox control for the 'footer_scripts' setting. */
	$wp_customize->add_control( 
		new WP_Script_Control_Textarea(
			$wp_customize,
			'footer_scripts',
			array(
				'label'    => esc_html__( 'Footer Scripts', 'wsc' ),
				'section'  => 'script_section',
				'settings' => 'wsc_options[footer_scripts]',
				'extra'	=> esc_html__( 'Insert scripts or code before the closing </body> tag in the document source:', 'wsc' )
			)
		)
	);

}

add_action( 'wp_head', 'wsc_print_header_scripts' );
add_action( 'wp_footer', 'wsc_print_footer_scripts' );

/**
 * Echo header scripts in to wp_head().
 */
function wsc_print_header_scripts() {
	$wsc_options = get_option('wsc_options');
	echo $wsc_options['header_scripts'];

}

/**
 * Echo the footer scripts, defined in Theme Settings.
 */
function wsc_print_footer_scripts() {
	$wsc_options = get_option('wsc_options');
	echo $wsc_options['footer_scripts'];
}

?>