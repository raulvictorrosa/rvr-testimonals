<?php
/**
 * Function to the form.
 */
function rvr_form() {
	global $_POST;

	//Defino a Chave do meu site
	$secret_key = ''.get_option('rvr_key_google_recaptcha_Secret_key');

	//Pego a validação do Captcha feita pelo usuário
	$recaptcha_response = $_POST['g-recaptcha-response'];

	// Verifico se foi feita a postagem do Captcha
	if(isset($recaptcha_response)){

		// Valido se a ação do usuário foi correta junto ao google
		$answer =
			json_decode(
				file_get_contents(
					'https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.
					'&response='.$_POST['g-recaptcha-response']
				)
			);

		// Se a ação do usuário foi correta executo o restante do meu formulário
		if($answer->success) {

			if ($post_id == "") {
				if (isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
					$post_information = array(
						'post_title'   => esc_attr(strip_tags($_POST['postTitle'])),
						'post_content' => esc_attr(strip_tags($_POST['postContent'])),
						'post_type'    => 'deixar-um-depoimento',
						'post_status'  => 'pending'
					);

					$post_information2 = array(
//						'cargo'   => esc_attr(strip_tags($_POST['rvr_testimonial_Cargo'])),
						'email' => esc_attr(strip_tags($_POST['rvr_testimonial_Email'])),
						'cidade' => esc_attr(strip_tags($_POST['rvr_testimonial_Cidade'])),
					);

					$attach_id = wp_insert_attachment($attachment, $uploadfile);

					$post_id = wp_insert_post($post_information);

					if (!function_exists('wp_generate_attachment_metadata')) {
						require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						require_once(ABSPATH . "wp-admin" . '/includes/file.php');
						require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					}
					if ($_FILES) {
						foreach ($_FILES as $file => $array) {
							if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
								return "upload error : " . $_FILES[$file]['error'];
							}
							$attach_id = media_handle_upload($file, $post_id);
						}
					}
					if ($attach_id > 0) {
							//and if you want to set that image as Post  then use:
							update_post_meta($post_id,'_thumbnail_id',$attach_id);
					}

//					rvr_update_post_meta($post_id, 'rvr_testimonial_Cargo', $post_information2['cargo']);
					rvr_update_post_meta($post_id, 'rvr_testimonial_Email', $post_information2['email']);
					rvr_update_post_meta($post_id, 'rvr_testimonial_Cidade', $post_information2['cidade']);
				}
			}


			// senão, ela retornará o erro ocorrido
			if ($post_id) {
				$successVerificacao = true;
			?>
				<script type="text/javascript">
					window.onload = function() {
						var newcontent = document.createElement('p');
						newcontent.id = "messageSuccessCaptcha";
						var myElem = document.getElementById("primaryPostForm");
//						myElem.appendChild(newcontent);
						myElem.parentNode.insertBefore(newcontent, myElem);

						var targetS = document.getElementById("messageSuccessCaptcha");
						targetS.innerHTML = 'Depoimento enviado com sucesso!';
					}
				</script>
			<?php
			}
		}

		// Caso o Captcha não tenha sido validado
		//retorno uma mensagem de erro.
		else {
			$erroVerificacao = true;
			?>
				<script type="text/javascript">
					window.onload = function() {
						var newcontent = document.createElement('p');
						newcontent.id = "messageErrorCaptcha";
						var myElem = document.getElementById("primaryPostForm");
//						myElem.appendChild(newcontent);
						myElem.parentNode.insertBefore(newcontent, myElem);

						var targetS = document.getElementById("messageErrorCaptcha");
						targetS.innerHTML = 'Por favor faça a verificação do captcha abaixo.';
					}
				</script>
			<?php
		}
	}
}

function rvr_update_post_meta($post_id, $field_name, $value = '') {
	if (empty($value) OR !$value) {
		delete_post_meta($post_id, $field_name);
	}
	elseif (!get_post_meta($post_id, $field_name)) {
		add_post_meta( $post_id, $field_name, $value );
	}
	else {
		update_post_meta($post_id, $field_name, $value);
	}
}



/**
 * Implemetn the shortcode to the form.
 */
function rvr_testimonials_shortcode_show_html($atts) {
?>
	<form action="" id="primaryPostForm" method="post" enctype="multipart/form-data">
		<p></p>
		<p class="required-symbol-left hidden">Campos Obrigatórios</p>
		<?php if ($erroVerificacao) { ?>
			<p id="messageErrorCaptcha">Por favor faça a verificação do captcha acima.</p>
		<?php } ?>
		<?php if ($successVerificacao) { ?>
			<p id="messageSuccessCaptcha">Depoimento enviado com sucesso!</p>
		<?php } ?>
		<div class="form-group">
			<label for="postTitle" class="required-symbol-right hidden">Nome Completo</label>
			<input type="text" name="postTitle" id="postTitle" class="form-control postTitle" required="true" placeholder="Nome Completo" value="">
		</div>

		<div class="form-group">
			<label for="rvr_testimonial_Email" class="hidden">Nome do E-mail</label>
			<input type="email" name="rvr_testimonial_Email" id="rvr_testimonial_Email" class="form-control rvr_testimonial_Email" placeholder="E-mail" value="">
		</div>

		<div class="form-group">
			<label for="rvr_testimonial_Cidade" class="required-symbol-right hidden">Nome da Cidade</label>
			<input type="text" name="rvr_testimonial_Cidade" id="rvr_testimonial_Cidade" class="form-control rvr_testimonial_Cidade" required="true" placeholder="Cidade" value="">
		</div>

		<?php if (get_option('rvr_thumbnail_enabled')) { ?>
		<div class="form-group">
			<label for="rvr_testimonial_Thumbnail" class="required-symbol-right hidden">Foto</label>
			<input type="file" name="rvr_testimonial_Thumbnail" id="rvr_testimonial_Thumbnail" class="form-control rvr_testimonial_Thumbnail" required="true" accept="image/*">
		</div>
		<?php } ?>

		<div class="form-group">
			<label for="postContent" class="required-symbol-right hidden">Depoimento</label>
			<textarea name="postContent" id="postContent" class="form-control postContent" required="true" rows="4" placeholder="Digite aqui seu Depoimento"></textarea>
		</div>

		<div class="g-recaptcha" data-sitekey="<?php echo get_option('rvr_key_google_recaptcha_Site_key'); ?>"></div>
		<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
		<input type="hidden" name="submitted" id="submitted" value="true">
		<input type="submit" id="capsubmit" class="btn btn-default" value="Adicionar Depoimento">
	</form>
<?php
}
add_shortcode('rvr_testimonials_shortcode', 'rvr_testimonials_shortcode_show_html');
