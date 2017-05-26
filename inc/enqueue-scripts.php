<?php
/**
 * Enqueue scripts and styles.
 */
function rvr_enqueue_scripts() {
	if (is_page('deixe-seu-depoimento')) {
		wp_enqueue_style('rvr-testimonial-style',                 plugins_url('../css/style.css', __FILE__));

		wp_enqueue_script('rvr-testimonial-jquery-js',            plugins_url('../js/jquery.min.js', __FILE__));

//		wp_enqueue_script('rvr-testimonial-jquery-custom-js',     plugins_url('../js/jquery.custom.js', __FILE__));

//		wp_enqueue_script('rvr-testimonial-validation',           'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js');

//		wp_enqueue_script('rvr-testimonial-additional-methods',   'https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js');

		wp_enqueue_script('rvr-testimonial-google-recaptcha-api', 'https://www.google.com/recaptcha/api.js' );
	}
}
add_action( 'wp_enqueue_scripts', 'rvr_enqueue_scripts' );

add_action( 'admin_enqueue_scripts', 'rvr_enqueue_wp_admin_style_script' );
function rvr_enqueue_wp_admin_style_script() {
	global $post_type;
	$screen = get_current_screen()->id;

	if ($post_type =='deixar-um-depoimento' || $screen == 'deixar-um-depoimento_page_opcoes-rvr-testimonials') {
		wp_enqueue_style('rvr_testimonial_wp_admin', plugins_url('../css/bootstrap.min.css',__FILE__));
		wp_enqueue_style('rvr_testimonial_wp_admin_2', plugins_url('../css/style-adm-page.css',__FILE__));
	}
}
