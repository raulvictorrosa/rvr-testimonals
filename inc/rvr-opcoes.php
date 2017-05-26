<?php
/**
 * Adds a submenu page under a custom post type parent.
 */
add_action('admin_menu', 'rvr_testimonials_register_ref_page');
function rvr_testimonials_register_ref_page() {
		add_submenu_page(
				'edit.php?post_type=deixar-um-depoimento',
				__( 'Opções RVR Testimonials', 'rvr-testimonials' ),
				__( 'Opções', 'rvr-testimonials' ),
				'manage_options',
				'opcoes-rvr-testimonials',
				'rvr_testimonials_settings_page'
		);

		add_action('admin_init', 'rvr_testimonials_register_my_setting');
}

function rvr_testimonials_register_my_setting() {
	register_setting('rvr-testimonials-settings-group', 'rvr_thumbnail_enabled');
	register_setting('rvr-testimonials-settings-group', 'rvr_key_google_recaptcha_Site_key');
	register_setting('rvr-testimonials-settings-group', 'rvr_key_google_recaptcha_Secret_key');
}

/**
 * Display callback for the submenu page.
 */
function rvr_testimonials_settings_page() {
?>
	<div id="rvr_post_options" class="rvr_post_options wrap">
		<h3><?php _e( 'Opções - RVR Testimonials', 'rvr-testimonials' ); ?></h3>

		<form action="options.php" method="post">
			<?php settings_fields('rvr-testimonials-settings-group'); ?>
			<?php do_settings_sections('rvr-testimonials-settings-group'); ?>

			<br>
			<div class="form-group">
				<h4>Thumbnail</h4>
				<div class="input-group">
					<span class="input-group-addon">
						<?php
						$checked = '';
						if (get_option('rvr_thumbnail_enabled')) {
							$checked = 'checked="checked"';
						}
						?>
						<input type="checkbox" name="rvr_thumbnail_enabled" id="rvr_thumbnail_enabled" value="true" <?php echo $checked; ?>>
					</span>
					<input type="text" class="form-control" value="Thumbnail em depoimentos" disabled>
				</div>
			</div>
			<hr>
			<h4>Chaves do Google reCAPTCHA</h4>
			<div class="form-group">
				<label for="rvr_key_google_recaptcha_Site_key">Google reCAPTCHA - Site key</label>
				<input type="text" name="rvr_key_google_recaptcha_Site_key" class="form-control" id="rvr_key_google_recaptcha_Site_key" value="<?php echo esc_attr(get_option('rvr_key_google_recaptcha_Site_key')); ?>" placeholder="Google reCAPTCHA - Site key">
			</div>
			<div class="form-group">
				<label for="rvr_key_google_recaptcha_Secret_key">Google reCAPTCHA - Secret key</label>
				<input type="text" name="rvr_key_google_recaptcha_Secret_key" class="form-control" id="rvr_key_google_recaptcha_Secret_key" value="<?php echo esc_attr(get_option('rvr_key_google_recaptcha_Secret_key')); ?>" placeholder="Google reCAPTCHA - Secret key">
				<p class="help-block">Pegue suas chaves aqui - <a href="https://www.google.com/recaptcha/intro/invisible.html" target="_blank">https://www.google.com/recaptcha/intro/invisible.html</a></p>
			</div>

			<?php submit_button(); ?>
		</form>

		<p><?php _e( 'Plugin - RVR Testimonials', 'rvr-testimonials' ); ?></p>
	</div>
<?php }



