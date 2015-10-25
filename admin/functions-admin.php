<?php

add_action( 'admin_enqueue_scripts', 'ccp_admin_register_scripts', 0 );
add_action( 'admin_enqueue_scripts', 'ccp_admin_register_styles',  0 );

function ccp_admin_register_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'ccp-edit-project', ccp_plugin()->js_uri . "edit-project{$min}.js", array( 'jquery' ), '', true );
}

function ccp_admin_register_styles() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_style( 'ccp-admin', ccp_plugin()->css_uri . "admin{$min}.css" );
}

# Registers default groups.
add_action( 'ccp_project_details_manager_register', 'ccp_project_details_register', 5 );

/**
 * Registers the default cap groups.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_project_details_register( $manager ) {

	/* === Register Sections === */

	// General section.
	$manager->register_section( 'general',
		array(
			'label' => esc_html__( 'General', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-admin-generic'
		)
	);

	// Date section.
	$manager->register_section( 'date',
		array(
			'label' => esc_html__( 'Date', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-clock'
		)
	);

	// Description section.
	$manager->register_section( 'description',
		array(
			'label' => esc_html__( 'Description', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-edit'
		)
	);

	/* === Register Controls === */

	$url_args = array(
		'section'     => 'general',
		'label'       => esc_html__( 'URL', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the URL of the project Web page.', 'custom-content-portfolio' )
	);

	$client_args = array(
		'section'     => 'general',
		'label'       => esc_html__( 'Client', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the name of the client for the project.', 'custom-content-portfolio' )
	);

	$location_args = array(
		'section'     => 'general',
		'label'       => esc_html__( 'Location', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the physical location of the project.', 'custom-content-portfolio' )
	);

	$start_date_args = array(
		'section'     => 'date',
		'label'       => esc_html__( 'Start Date', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Select the date the project began.', 'custom-content-portfolio' )
	);

	$end_date_args = array(
		'section'     => 'date',
		'label'       => esc_html__( 'End Date', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Select the date the project was completed.', 'custom-content-portfolio' )
	);

	$excerpt_args = array(
		'section'     => 'description',
		'label'       => esc_html__( 'Description', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Write a short description (excerpt) of the project.', 'custom-content-portfolio' )
	);

	$manager->register_control( new CCP_Fields_Control(      $manager, 'url',        $url_args        ) );
	$manager->register_control( new CCP_Fields_Control(      $manager, 'client',     $client_args     ) );
	$manager->register_control( new CCP_Fields_Control(      $manager, 'location',   $location_args   ) );
	$manager->register_control( new CCP_Fields_Control_Date( $manager, 'start_date', $start_date_args ) );
	$manager->register_control( new CCP_Fields_Control_Date( $manager, 'end_date',   $end_date_args   ) );

	if ( ! post_type_supports( ccp_get_project_post_type(), 'excerpt' ) )
		$manager->register_control( new CCP_Fields_Control_Excerpt( $manager, 'excerpt', $excerpt_args ) );

	/* === Register Settings === */

	$manager->register_setting( 'url',      array( 'sanitize_callback' => 'esc_url_raw'       ) );
	$manager->register_setting( 'client',   array( 'sanitize_callback' => 'wp_strip_all_tags' ) );
	$manager->register_setting( 'location', array( 'sanitize_callback' => 'wp_strip_all_tags' ) );

	$manager->register_setting( new CCP_Fields_Setting_Date( $manager, 'start_date' ) );
	$manager->register_setting( new CCP_Fields_Setting_Date( $manager, 'end_date' ) );
}

/**
 * Help sidebar for all of the help tabs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_help_sidebar_text() {

	// Get docs and help links.
	$docs_link = sprintf( '<li><a href="https://github.com/justintadlock/custom-content-portfolio/blob/master/readme.md">%s</a></li>', esc_html__( 'Documentation', 'custom-cotent-portfolio' ) );
	$help_link = sprintf( '<li><a href="http://themehybrid.com/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'custom-content-portfolio' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'custom-content-portfolio' ),
		$docs_link,
		$help_link
	);
}
