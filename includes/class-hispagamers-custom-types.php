<?php 

class Hispagamers_custom_types{

	private $hispagamers;

	private $version;

	public function __construct($hispagamers, $version){
		$this->hispagamers = $hispagamers;
		$this->version = $version;
	}


	public function streamer_meta_box(){
		add_meta_box( 
			$this->hispagamers.'_meta_box',
			__('Informacion del Streamer','hg'),
			array($this,'streamer_meta_fields'),
			'hg_streamer',
			'normal',
			'default');
	}


	public function streamer_meta_fields($post){
		wp_nonce_field( $this->hispagamers, 'hg_custom_meta_noncename' );

		$twitch_user = get_post_meta( $post->ID, 'hg_streamer_twitch_user', true );
		?>

		<p>
			<label for="hg_streamer_twitch_user">Usuario de Twitch</label> <br>
			<input type="text" class="all-options" name="hg_streamer_twitch_user" id="hg_streamer_twitch_user" value="<?php echo esc_attr( $twich_user ); ?>">
			<span class="description">Ingresa el usuario de twitch del Streamer</span>
		</p>

		<?php
	}

	public function change_defaul_title($title){
		$screen = get_current_screen();
		if($screen->post_type === 'hg_streamer'){
			return 'Ingresa el nombre del Streamer.';
		}
	}

	public function streamer_meta_save($post_id){
		//verify if this is an auto save routine.
		//if it is the post has not been updated, so we dont want to do anything
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return $post_id;
		}
		//verify this came from the screen and with proper authorization,
		//because save_post can be triggered at other times.
		if(!isset($_POST['hg_custom_meta_noncename'])||!wp_verify_nonce( $_POST['hg_custom_meta_noncename'], $this->hispagamers)){
			return $post_id;
		}

		global $post;

		$post_type = get_post_type_object( $post->post_type );

		if(!current_user_can( $post_type->cap->edit_post, $post_id )){
			return $post_id;
		}

		$metadata['hg_streamer_twitch_user'] = (isset($_POST['hg_streamer_twitch_user'])?$_POST['hg_streamer_twitch_user']:'');

		foreach ($metadata as $key => $value) {
			$current_value = get_post_meta($post_id, $key, true);
			if($value && '' == $current_value){
				add_post_meta($post_id, $key, $value, true);
			}elseif($value && $value != $current_value){
				update_post_meta( $post_id, $key, $value );
			}elseif('' == $value && $current_value ){
				delete_post_meta( $post_id, $key, $current_value );
			}
		}
	}


	public function streamers_taxonomies(){
		$taxs = array(
			'streamer_console' => array(
				'menu_title'	=> 'Streamer Consola',
				'plural'		=> 'Consolas',
				'singular'		=> 'Consola',
				'hierarchical'  => true,
				'post_type'     => 'hg_streamer'
			),
			'streamer_country' => array(
				'menu_title'    => 'Pais del Streamer',
				'plural'        => 'Paises',
				'singular'      => 'Pais',
				'hierarchical'  => false,
				'post_type'     => 'hg_streamer'
			)
		);

		foreach ($taxs as $tax => $args) {
			$labels = array(
				'name'=>_x('Item '.$args['plural'],'taxonomy general name'),
				'singular_name'=>_x('Item '.$args['singular'],'taxonomy singular name'),
				'search_items'=>__('Search '.$args['plural']),
				'all_items'=>__('All '.$args['plural']),
				'parent_item'=>__('Parent '.$args['plural']),
				'parent_item_colon'=>__('Parent '.$args['singular'].':'),
				'edit_item'=>__('Edit '.$args['singular']),
				'update_item'=>__('Update '.$args['singular']),
				'add_new_item'=>__('Add New '.$args['singular'].'Name'),
				'menu_name'=>__($args['menu_title']));
			$tax_args = array(
				'hierarchical'=>$args['hierarchical'],
				'labels'=>$labels,
				'public'=>true,
				'rewrite'=>array('slug' => $args['slug']),
			);
		register_taxonomy( $tax, $args['post_type'], $tax_args );
		}
	}


	public function register_streamer_type(){
		$labels = array(
		'name'                  => _x( 'Streamers', 'Post Type General Name', 'hg' ),
		'singular_name'         => _x( 'Streamer', 'Post Type Singular Name', 'hg' ),
		'menu_name'             => __( 'Streamers', 'hg' ),
		'name_admin_bar'        => __( 'Streamer', 'hg' ),
		'archives'              => __( 'Estreamer archivados', 'hg' ),
		'attributes'            => __( 'Atributos de Streamer', 'hg' ),
		'parent_item_colon'     => __( 'Parent Item:', 'hg' ),
		'all_items'             => __( 'Todos los Streamers', 'hg' ),
		'add_new_item'          => __( 'Añadir Nuevo Streamer', 'hg' ),
		'add_new'               => __( 'Añadir nuevo streamer', 'hg' ),
		'new_item'              => __( 'Nuevo streamer', 'hg' ),
		'edit_item'             => __( 'Editar streamer', 'hg' ),
		'update_item'           => __( 'Actualizar Streamer', 'hg' ),
		'view_item'             => __( 'Ver Streamer', 'hg' ),
		'view_items'            => __( 'Ver todos los Streamers', 'hg' ),
		'search_items'          => __( 'Buscar Streamer', 'hg' ),
		'not_found'             => __( 'Streamer no encontrado', 'hg' ),
		'not_found_in_trash'    => __( 'Streamer no encontrado en la papelera', 'hg' ),
		'featured_image'        => __( 'Imagen del Streamer', 'hg' ),
		'set_featured_image'    => __( 'Set featured image', 'hg' ),
		'remove_featured_image' => __( 'Remove featured image', 'hg' ),
		'use_featured_image'    => __( 'Use as featured image', 'hg' ),
		'insert_into_item'      => __( 'Insert into item', 'hg' ),
		'uploaded_to_this_item' => __( 'Streamer actualizado', 'hg' ),
		'items_list'            => __( 'Lista de streamers', 'hg' ),
		'items_list_navigation' => __( 'Navegacion Lista Streamer', 'hg' ),
		'filter_items_list'     => __( 'Filtro de Estreamer', 'hg' ),
	);
	$rewrite = array(
		'slug'                  => 'streamers',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Streamer', 'hg' ),
		'description'           => __( 'Streamer de HispaGamers', 'hg' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'query_var'             => 'hg_streamer',
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'hg_streamer', $args );
	}
}