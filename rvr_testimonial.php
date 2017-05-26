<?php
/*
	Plugin Name: RVR Testimonials
	Plugin URI:
	Description: Plugin desenvolvido para facilitar a criação e recebimento de depoimentos dos usuários.
	Author: Raul Victor Rosa
	Version: 1.0
	Author URI: https://github.com/raulvictorrosa
	Text Domain: rvr-testimonials
 */

/**
 * Implement the Enqueue Scripts.
 */
require plugin_dir_path( __FILE__ ).'/inc/enqueue-scripts.php';

/**
 * Implement the Custom Post Types.
 */
require plugin_dir_path( __FILE__ ).'/inc/custom-post_types.php';

/**
 * Implement the meta boxes in post form
 */
require plugin_dir_path( __FILE__ ).'/inc/meta-boxes.php';

/**
 * Implement the front-end form to the user
 */
require plugin_dir_path( __FILE__ ).'/inc/front-end-form.php';
