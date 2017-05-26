<?php
add_action( 'init', 'rvr_testimonials_create_post_type' );
function rvr_testimonials_create_post_type() {
	$wordSingular = 'Depoimento';
	$wordPlural = 'Depoimentos';
	$wordSingularLow = 'depoimento';
	$wordPluralLow = 'depoimentos';
	$slug = 'deixar-um-depoimento';
	$menu_icon = 'dashicons-testimonial';


	if (get_option('rvr_thumbnail_enabled')) {
		$supportsThumbnail = 'thumbnail';
	}
	$supports = array(
		'title',
		'editor',
//		'author',
		$supportsThumbnail,
//		'excerpt',
//		'trackbacks',
//		'custom-fields',
//		'comments',
		'revisions',
//		'page-attributes',
//		'post-formats'
	);

	$labels = array(
		'name'                  => _x( ''.$wordPlural.'', 'Post type general name' ),
		'singular_name'         => _x( ''.$wordSingular.'', 'Post type singular name' ),
		'menu_name'             => _x( ''.$wordPlural.'', 'Admin Menu text' ),
		'name_admin_bar'        => _x( ''.$wordSingular.'', 'Add New on Toolbar' ),
		'add_new'               => __( 'Adicionar Novo' ),
		'add_new_item'          => __( 'Adicionar Novo '.$wordSingular.'' ),
		'new_item'              => __( 'Novo '.$wordSingular.'' ),
		'edit_item'             => __( 'Editar '.$wordSingular.'' ),
		'view_item'             => __( 'Ver '.$wordSingular.'' ),
		'all_items'             => __( 'Todos os '.$wordPlural.'' ),
		'search_items'          => __( 'Pesquisar '.$wordPlural.'' ),
		'parent_item_colon'     => __( ''.$wordPlural.' Pai:' ),
		'not_found'             => __( 'Nenhum '.$wordSingularLow.' encontrado.' ),
		'not_found_in_trash'    => __( 'Nenhum '.$wordSingularLow.' encontrado na Lixeira.' ),

		'featured_image'        => _x( ''.$wordSingular.' Imagem de Capa', 'Substitui a "Imagem Destaque" para este tipo de postagem. Adicionado em 4.3' ),
		'set_featured_image'    => _x( 'Definir imagem da Capa', 'Substitui a frase "Definir imagem em destaque" para este tipo de postagem. Adicionado em 4.3' ),
		'remove_featured_image' => _x( 'Remover Imagem de Capa', 'Substitui a frase "Remover imagem em destaque" para este tipo de postagem. Adicionado em 4.3' ),
		'use_featured_image'    => _x( 'Usar como imagem de capa', 'Substituir a “Usar como imagem destaque” frase para esta postagem. Adicionado em 4.3' ),
		'archives'              => _x( 'Arquivos do '.$wordSingular.'', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),
		'insert_into_item'      => _x( 'Inserir dentro do '.$wordSingularLow.'', 'Substitui a frase "Inserir na publicação" / "Inserir na página" (usada ao inserir mídia em uma postagem). Adicionado em 4.4' ),
		'uploaded_to_this_item' => _x( 'Carregado para este '.$wordSingularLow.'', 'Substitui a frase "Uploaded to this post" / "Carregado para esta página" (usada ao visualizar mídia anexada a uma postagem). Adicionado em 4.4' ),
		'filter_items_list'     => _x( 'Filtrar lista de '.$wordPluralLow.'', 'Texto do leitor de tela para o cabeçalho dos links de filtro na tela de listagem de tipo de postagem. "Filtrar lista de postagens" / "Filtrar lista de páginas". Adicionado em 4.4' ),
		'items_list_navigation' => _x( ''.$wordPlural.' navegação de lista', 'Texto do leitor de tela para o cabeçalho de paginação na tela de listagem de tipo de postagem. Default "Navegação da lista de postagens" / "Navegação da lista de páginas". Adicionado em 4.4' ),
		'items_list'            => _x( ''.$wordPlural.' lista', 'Texto do leitor de tela para o cabeçalho da lista de itens na tela de listagem de tipo de postagem. Default "Lista de postagens" / "Lista de páginas". Adicionado em 4.4' ),
	);

	$args = array(
		'labels'               => $labels,
		'public'               => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'query_var'            => true,
		'rewrite'              => array( 'slug' => $slug),
		'capability_type'      => 'post',
		'has_archive'          => true,
		'hierarchical'         => false,
		'menu_position'        => 5,
		'menu_icon'            => $menu_icon,
		'supports'             => $supports,
		'show_in_rest'         => true,
		'register_meta_box_cb' => 'rvr_testimonials_add_metaboxes'
		);

	register_post_type( $slug, $args );
}

add_action( 'init', 'rvr_testimonials_create_taxonomies', 0 );
function rvr_testimonials_create_taxonomies() {
	$labels = array(
		'name'              => _x( 'Categorias', 'taxonomy general name' ),
		'singular_name'     => _x( 'Categoria', 'taxonomy singular name' ),
		'search_items'      => __( 'Pesquisar Categorias' ),
		'all_items'         => __( 'Todas as Categorias' ),
		'parent_item'       => __( 'Categoria Pai' ),
		'parent_item_colon' => __( 'Categoria Pai:' ),
		'edit_item'         => __( 'Editar Categoria' ),
		'update_item'       => __( 'Atualizar Categoria' ),
		'add_new_item'      => __( 'Adicionar Nova Categoria' ),
		'new_item_name'     => __( 'Novo Nome da Categoria' ),
		'menu_name'         => __( 'Categorias' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true
	);
	register_taxonomy( 'cat-depoimentos', array( 'deixar-um-depoimento' ), $args );
}



if (get_option('rvr_thumbnail_enabled')) {
	add_image_size('rvr_testimonials_featured_image', 75, 75, false);

	add_filter('manage_deixar-um-depoimento_posts_columns' , 'rvr_testimonials_add_colum_miniatura_thumbnail', 2);
	//add_filter('manage_deixar-um-depoimento_pages_columns' , 'rvr_testimonials_add_colum_miniatura_thumbnail', 2);
	function rvr_testimonials_add_colum_miniatura_thumbnail($rvr_testimonials_columns) {
		$rvr_testimonials_columns['rvr_testimonials_thumb'] = __('Miniatura');
		return $rvr_testimonials_columns;
	}

	add_action('manage_deixar-um-depoimento_posts_custom_column', 'rvr_testimonials_show_post_thumbnail_column', 5, 2);
	//add_action('manage_deixar-um-depoimento_pages_custom_column', 'rvr_testimonials_show_post_thumbnail_column', 5, 2);
	function rvr_testimonials_show_post_thumbnail_column($rvr_testimonials_columns, $rvr_testimonials_id) {
		switch($rvr_testimonials_columns) {
			case 'rvr_testimonials_thumb':
			if(function_exists('the_post_thumbnail'))
				echo the_post_thumbnail( 'rvr_testimonials_featured_image' );
			else
				echo 'Your theme doesn\'t support featured image...';
			break;
		}
	}
}

require plugin_dir_path( __FILE__ ).'/rvr-opcoes.php';
