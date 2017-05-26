<?php
// Add the Events Meta Boxes
add_action( 'add_meta_boxes', 'rvr_testimonials_add_metaboxes');
function rvr_testimonials_add_metaboxes() {
	add_meta_box('rvr_post_options', __('Detalhes Depoimento'), 'rvr_post_options_code', 'deixar-um-depoimento', 'normal', 'high');
}

// The Event Location Metabox
function rvr_post_options_code() {
	global $post;

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="rvr_testimonial_meta_noncename" id="rvr_testimonial_meta_noncename" value="'.wp_create_nonce(plugin_basename(__FILE__)).'">';

	// Get the location data if its already been entered
//	$seuCargo = get_post_meta($post->ID, 'rvr_testimonial_Cargo', true);
	$suaCidade = get_post_meta($post->ID, 'rvr_testimonial_Cidade', true);
	$seuEmail = get_post_meta($post->ID, 'rvr_testimonial_Email', true);

	// Echo out the field ?>
	<form>
		<div class="form-group hidden">
			<label for="rvr_testimonial_Cargo">
				Nome do Cargo<br>
				<small>Qual é o seu Cargo?</small>
			</label>
			<input type="text" name="rvr_testimonial_Cargo" id="rvr_testimonial_Cargo" class="form-control" placeholder="Cargo" value="<?php echo $seuCargo; ?>">
		</div>
		<div class="form-group">
			<label for="rvr_testimonial_Email">
				E-mail<br>
				<small>Qual é o seu endereço de Email?</small>
			</label>
			<input type="email" name="rvr_testimonial_Email" id="rvr_testimonial_Email" class="form-control" placeholder="E-mail" value="<?php echo $seuEmail; ?>">
		</div>
		<div class="form-group">
			<label for="rvr_testimonial_Cidade">
				Cidade<br>
				<small>Qual é a sua Cidade?</small>
			</label>
			<input type="text" name="rvr_testimonial_Cidade" id="rvr_testimonial_Cidade" class="form-control" placeholder="Cidade" value="<?php echo $suaCidade; ?>">
		</div>
	</form>
<?php
}

// Save the Metabox Data
function rvr_testimonals_save_meta($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if (!wp_verify_nonce($_POST['rvr_testimonial_meta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID )) {
		return $post->ID;
	}

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
//	$events_meta['rvr_testimonial_Cargo'] = $_POST['rvr_testimonial_Cargo'];
	$events_meta['rvr_testimonial_Cidade'] = $_POST['rvr_testimonial_Cidade'];
	$events_meta['rvr_testimonial_Email'] = $_POST['rvr_testimonial_Email'];

	// Add values of $events_meta as custom fields

	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if ($post->post_type == 'revision') return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)

		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if (!$value) { // Delete if blank
			delete_post_meta($post->ID, $key);
		}
	}
}
add_action('save_post', 'rvr_testimonals_save_meta', 1, 2); // save the custom fields
