<?php
/**
 * Property content type and fields.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the editable property inventory used by the results shortcode.
 */
final class HY_Homes_Syd_Panther_Properties {
	const POST_TYPE          = 'hy_home_property';
	const BANNER_POST_TYPE   = 'hy_location_banner';
	const TAX_NEIGHBORHOOD   = 'hy_property_neighborhood';
	const META_PREFIX        = '_hy_property_';
	const BANNER_META_PREFIX = '_hy_banner_';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_content_types' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_property' ) );
		add_action( 'save_post_' . self::BANNER_POST_TYPE, array( __CLASS__, 'save_banner' ) );
		add_filter( 'gettext', array( __CLASS__, 'filter_neighborhood_admin_text' ), 20, 3 );
	}

	/**
	 * Activation tasks.
	 */
	public static function activate() {
		self::register_content_types();
		flush_rewrite_rules();
	}

	/**
	 * Deactivation tasks.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Register the property post type and neighborhood taxonomy.
	 */
	public static function register_content_types() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'               => __( 'Propiedades (/Properties)', 'hy-homes-syd-panther' ),
					'singular_name'      => __( 'Propiedad (/Property)', 'hy-homes-syd-panther' ),
					'add_new_item'       => __( 'Agregar nueva propiedad (/Add new property)', 'hy-homes-syd-panther' ),
					'edit_item'          => __( 'Editar propiedad (/Edit property)', 'hy-homes-syd-panther' ),
					'new_item'           => __( 'Nueva propiedad (/New property)', 'hy-homes-syd-panther' ),
					'view_item'          => __( 'Ver propiedad (/View property)', 'hy-homes-syd-panther' ),
					'search_items'       => __( 'Buscar propiedades (/Search properties)', 'hy-homes-syd-panther' ),
					'not_found'          => __( 'No se encontraron propiedades (/No properties found).', 'hy-homes-syd-panther' ),
					'not_found_in_trash' => __( 'No se encontraron propiedades en la papelera (/No properties found in Trash).', 'hy-homes-syd-panther' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => 'dashicons-building',
				'show_in_menu' => 'hy_homes_syd',
				'rewrite'      => array( 'slug' => 'hy-properties' ),
				'show_in_rest' => true,
				'supports'     => array( 'thumbnail' ),
			)
		);

		register_post_type(
			self::BANNER_POST_TYPE,
			array(
				'labels'              => array(
					'name'               => __( 'Banners de localidad (/Location banners)', 'hy-homes-syd-panther' ),
					'singular_name'      => __( 'Banner de localidad (/Location banner)', 'hy-homes-syd-panther' ),
					'add_new_item'       => __( 'Agregar nuevo banner (/Add new banner)', 'hy-homes-syd-panther' ),
					'edit_item'          => __( 'Editar banner (/Edit banner)', 'hy-homes-syd-panther' ),
					'new_item'           => __( 'Nuevo banner (/New banner)', 'hy-homes-syd-panther' ),
					'view_item'          => __( 'Ver banner (/View banner)', 'hy-homes-syd-panther' ),
					'search_items'       => __( 'Buscar banners (/Search banners)', 'hy-homes-syd-panther' ),
					'not_found'          => __( 'No se encontraron banners (/No banners found).', 'hy-homes-syd-panther' ),
					'not_found_in_trash' => __( 'No se encontraron banners en la papelera (/No banners found in Trash).', 'hy-homes-syd-panther' ),
				),
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => 'hy_homes_syd',
				'menu_icon'           => 'dashicons-format-image',
				'show_in_rest'        => true,
				'supports'            => array( 'thumbnail', 'page-attributes' ),
			)
		);

		register_taxonomy(
			self::TAX_NEIGHBORHOOD,
			array( self::POST_TYPE, self::BANNER_POST_TYPE ),
			array(
				'labels'            => array(
					'name'                     => __( 'Localidades (/Neighborhoods)', 'hy-homes-syd-panther' ),
					'singular_name'            => __( 'Localidad (/Neighborhood)', 'hy-homes-syd-panther' ),
					'search_items'             => __( 'Buscar localidades (/Search neighborhoods)', 'hy-homes-syd-panther' ),
					'all_items'                => __( 'Todas las localidades (/All neighborhoods)', 'hy-homes-syd-panther' ),
					'edit_item'                => __( 'Editar localidad (/Edit neighborhood)', 'hy-homes-syd-panther' ),
					'update_item'              => __( 'Actualizar localidad (/Update neighborhood)', 'hy-homes-syd-panther' ),
					'add_new_item'             => __( 'Agregar nueva localidad (/Add new neighborhood)', 'hy-homes-syd-panther' ),
					'new_item_name'            => __( 'Nombre de nueva localidad (/New neighborhood name)', 'hy-homes-syd-panther' ),
					'parent_item'              => __( 'Localidad superior (/Parent neighborhood)', 'hy-homes-syd-panther' ),
					'parent_item_colon'        => __( 'Localidad superior (/Parent neighborhood):', 'hy-homes-syd-panther' ),
					'menu_name'                => __( 'Localidades (/Neighborhoods)', 'hy-homes-syd-panther' ),
					'back_to_items'            => __( 'Volver a localidades (/Back to neighborhoods)', 'hy-homes-syd-panther' ),
					'name_field_description'   => __( 'Nombre visible de la localidad (/Visible neighborhood name).', 'hy-homes-syd-panther' ),
					'slug_field_description'   => __( 'Version amigable para URL (/URL-friendly version).', 'hy-homes-syd-panther' ),
					'parent_field_description' => __( 'Opcional: usar si una localidad depende de otra (/Optional: use if a neighborhood belongs under another).', 'hy-homes-syd-panther' ),
					'desc_field_description'   => __( 'Notas internas o descripcion publica de la localidad (/Internal notes or public neighborhood description).', 'hy-homes-syd-panther' ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'hy-neighborhood' ),
			)
		);

		self::register_meta_fields();
		self::register_banner_meta_fields();
	}

	/**
	 * Register property meta fields.
	 */
	private static function register_meta_fields() {
		$fields = array(
			'room_type'          => 'number',
			'bedrooms'           => 'number',
			'bathrooms'          => 'number',
			'street'             => 'string',
			'address'            => 'string',
			'price'              => 'string',
			'price_suffix'       => 'string',
			'status'             => 'string',
			'move_in'            => 'string',
			'detail_url'         => 'string',
			'featured_image_url' => 'string',
			'gallery_media'      => 'string',
			'map_embed_url'      => 'string',
			'whatsapp_phone'     => 'string',
			'location_banners' => 'string',
		);

		foreach ( $fields as $field => $type ) {
			$sanitize_callback = 'number' === $type ? 'absint' : 'sanitize_text_field';

			if ( in_array( $field, array( 'detail_url', 'featured_image_url', 'map_embed_url' ), true ) ) {
				$sanitize_callback = 'esc_url_raw';
			}

			if ( in_array( $field, array( 'gallery_media', 'location_banners' ), true ) ) {
				$sanitize_callback = 'sanitize_textarea_field';
			}

			register_post_meta(
				self::POST_TYPE,
				self::META_PREFIX . $field,
				array(
					'type'              => $type,
					'single'            => true,
					'sanitize_callback' => $sanitize_callback,
					'show_in_rest'      => true,
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}

	/**
	 * Register banner meta fields.
	 */
	private static function register_banner_meta_fields() {
		$fields = array(
			'description' => 'sanitize_textarea_field',
			'image_url'   => 'esc_url_raw',
			'button_url'  => 'esc_url_raw',
		);

		foreach ( $fields as $field => $sanitize_callback ) {
			register_post_meta(
				self::BANNER_POST_TYPE,
				self::BANNER_META_PREFIX . $field,
				array(
					'type'              => 'string',
					'single'            => true,
					'sanitize_callback' => $sanitize_callback,
					'show_in_rest'      => true,
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}

	/**
	 * Add property details meta box.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'hy_homes_property_details',
			__( 'Datos de la propiedad (/Property data)', 'hy-homes-syd-panther' ),
			array( __CLASS__, 'render_details_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'hy_homes_banner_details',
			__( 'Datos del banner de localidad (/Location banner data)', 'hy-homes-syd-panther' ),
			array( __CLASS__, 'render_banner_meta_box' ),
			self::BANNER_POST_TYPE,
			'normal',
			'high'
		);

		remove_meta_box( self::TAX_NEIGHBORHOOD . 'div', self::POST_TYPE, 'side' );
		remove_meta_box( self::TAX_NEIGHBORHOOD . 'div', self::BANNER_POST_TYPE, 'side' );
	}

	/**
	 * Render property details fields.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_details_meta_box( $post ) {
		wp_nonce_field( 'hy_homes_save_property', 'hy_homes_property_nonce' );

		$terms           = get_terms(
			array(
				'taxonomy'   => self::TAX_NEIGHBORHOOD,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);
		$current_terms   = wp_get_object_terms( $post->ID, self::TAX_NEIGHBORHOOD, array( 'fields' => 'ids' ) );
		$current_term_id = ! is_wp_error( $current_terms ) && ! empty( $current_terms ) ? absint( $current_terms[0] ) : 0;
		$title           = 'auto-draft' === $post->post_status ? '' : get_the_title( $post );
		$address         = get_post_meta( $post->ID, self::META_PREFIX . 'address', true );
		$price           = get_post_meta( $post->ID, self::META_PREFIX . 'price', true );
		$availability    = get_post_meta( $post->ID, self::META_PREFIX . 'move_in', true );
		$availability    = '' !== $availability ? $availability : get_post_meta( $post->ID, self::META_PREFIX . 'status', true );
		$bedrooms        = get_post_meta( $post->ID, self::META_PREFIX . 'bedrooms', true );
		$bathrooms       = get_post_meta( $post->ID, self::META_PREFIX . 'bathrooms', true );
		$description     = get_post_field( 'post_content', $post->ID );
		$map_url         = get_post_meta( $post->ID, self::META_PREFIX . 'map_embed_url', true );
		$gallery_media   = get_post_meta( $post->ID, self::META_PREFIX . 'gallery_media', true );
		?>
		<div class="hy-homes-admin-fields hy-homes-admin-fields--property">
			<div class="hy-homes-admin-section">
				<h3><?php esc_html_e( 'Ubicacion (/Location)', 'hy-homes-syd-panther' ); ?></h3>
				<div class="hy-homes-admin-grid hy-homes-admin-grid--3">
					<div class="hy-homes-admin-field">
						<label for="hy_property_locality_existing"><?php esc_html_e( 'Localidad existente (/Existing neighborhood)', 'hy-homes-syd-panther' ); ?></label>
						<select id="hy_property_locality_existing" name="hy_property_locality_existing">
							<option value=""><?php esc_html_e( 'Seleccionar localidad (/Select neighborhood)', 'hy-homes-syd-panther' ); ?></option>
							<?php if ( ! is_wp_error( $terms ) ) : ?>
								<?php foreach ( $terms as $term ) : ?>
									<option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $current_term_id, $term->term_id ); ?>>
										<?php echo esc_html( $term->name ); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Usar una localidad ya cargada (/Use an existing neighborhood).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_locality_new"><?php esc_html_e( 'Nueva localidad (/New neighborhood)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_locality_new" name="hy_property_locality_new" type="text" value="" placeholder="<?php esc_attr_e( 'Ej: Zetland', 'hy-homes-syd-panther' ); ?>">
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Si se completa, reemplaza la localidad seleccionada (/If filled, it replaces the selected neighborhood).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_address"><?php esc_html_e( 'Direccion (/Address)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_address" name="hy_property_address" type="text" value="<?php echo esc_attr( $address ); ?>" placeholder="<?php esc_attr_e( 'Calle, numero, ciudad', 'hy-homes-syd-panther' ); ?>">
					</div>
				</div>
			</div>

			<div class="hy-homes-admin-section">
				<h3><?php esc_html_e( 'Datos principales (/Main details)', 'hy-homes-syd-panther' ); ?></h3>
				<div class="hy-homes-admin-grid hy-homes-admin-grid--3">
					<div class="hy-homes-admin-field hy-homes-admin-field--wide">
						<label for="hy_property_title"><?php esc_html_e( 'Nombre de la propiedad (/Property name)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_title" name="hy_property_title" type="text" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e( 'Ej: Modern Apartment in Zetland', 'hy-homes-syd-panther' ); ?>">
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_price"><?php esc_html_e( 'Precio (/Price)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_price" name="hy_property_price" type="text" value="<?php echo esc_attr( $price ); ?>" placeholder="1010">
						<p class="hy-homes-admin-help"><?php esc_html_e( 'El sufijo por defecto es pw (/Default suffix is pw).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_availability"><?php esc_html_e( 'Disponibilidad (/Availability)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_availability" name="hy_property_availability" type="text" list="hy_property_availability_options" value="<?php echo esc_attr( $availability ); ?>" placeholder="Immediate">
						<datalist id="hy_property_availability_options">
							<option value="Immediate">
							<option value="Next 2 weeks">
							<option value="Next month">
							<option value="Available Now">
						</datalist>
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Controla etiqueta de card y filtro del buscador (/Controls the card label and search filter).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_bedrooms"><?php esc_html_e( 'Habitaciones (/Bedrooms)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_bedrooms" name="hy_property_bedrooms" type="number" min="0" value="<?php echo esc_attr( $bedrooms ); ?>">
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Tambien se usa en el filtro Room Type (/Also used by the Room Type filter).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_bathrooms"><?php esc_html_e( 'Banos (/Bathrooms)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_bathrooms" name="hy_property_bathrooms" type="number" min="0" value="<?php echo esc_attr( $bathrooms ); ?>">
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_map_embed_url"><?php esc_html_e( 'URL de Maps (/Maps URL)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_property_map_embed_url" name="hy_property_map_embed_url" type="url" value="<?php echo esc_attr( $map_url ); ?>" placeholder="https://www.google.com/maps?...">
					</div>
				</div>
			</div>

			<div class="hy-homes-admin-section">
				<h3><?php esc_html_e( 'Descripcion y medios (/Description and media)', 'hy-homes-syd-panther' ); ?></h3>
				<div class="hy-homes-admin-grid hy-homes-admin-grid--1">
					<div class="hy-homes-admin-field">
						<label for="hy_property_description"><?php esc_html_e( 'Descripcion (/Description)', 'hy-homes-syd-panther' ); ?></label>
						<textarea id="hy_property_description" name="hy_property_description" rows="7" placeholder="<?php esc_attr_e( 'Caracteristicas, comodidades y detalles de la propiedad.', 'hy-homes-syd-panther' ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_property_gallery_media"><?php esc_html_e( 'URLs de imagenes y videos (/Image and video URLs)', 'hy-homes-syd-panther' ); ?></label>
						<textarea id="hy_property_gallery_media" name="hy_property_gallery_media" rows="7" placeholder="https://example.com/imagen-1.jpg&#10;https://example.com/video.mp4"><?php echo esc_textarea( $gallery_media ); ?></textarea>
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Colocar una URL por linea. Acepta imagenes, videos directos y enlaces embebibles (/Add one URL per line. Accepts images, direct videos and embeddable links).', 'hy-homes-syd-panther' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render banner fields.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_banner_meta_box( $post ) {
		wp_nonce_field( 'hy_homes_save_banner', 'hy_homes_banner_nonce' );

		$description = get_post_meta( $post->ID, self::BANNER_META_PREFIX . 'description', true );
		$image_url   = get_post_meta( $post->ID, self::BANNER_META_PREFIX . 'image_url', true );
		$button_url  = get_post_meta( $post->ID, self::BANNER_META_PREFIX . 'button_url', true );
		$terms       = get_terms(
			array(
				'taxonomy'   => self::TAX_NEIGHBORHOOD,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);
		$current_terms   = wp_get_object_terms( $post->ID, self::TAX_NEIGHBORHOOD, array( 'fields' => 'ids' ) );
		$current_term_id = ! is_wp_error( $current_terms ) && ! empty( $current_terms ) ? absint( $current_terms[0] ) : 0;
		$title           = 'auto-draft' === $post->post_status ? '' : get_the_title( $post );

		if ( '' === $button_url ) {
			$button_url = 'https://hyhomessyd.com/#locatios';
		}
		?>
		<div class="hy-homes-admin-fields hy-homes-admin-fields--banner">
			<div class="hy-homes-admin-section">
				<h3><?php esc_html_e( 'Identificacion y localidad (/Identification and neighborhood)', 'hy-homes-syd-panther' ); ?></h3>
				<div class="hy-homes-admin-grid hy-homes-admin-grid--3">
					<div class="hy-homes-admin-field">
						<label for="hy_banner_locality_existing"><?php esc_html_e( 'Localidad existente (/Existing neighborhood)', 'hy-homes-syd-panther' ); ?></label>
						<select id="hy_banner_locality_existing" name="hy_banner_locality_existing">
							<option value=""><?php esc_html_e( 'Seleccionar localidad (/Select neighborhood)', 'hy-homes-syd-panther' ); ?></option>
							<?php if ( ! is_wp_error( $terms ) ) : ?>
								<?php foreach ( $terms as $term ) : ?>
									<option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $current_term_id, $term->term_id ); ?>>
										<?php echo esc_html( $term->name ); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Define en que ficha por localidad se muestra (/Defines which neighborhood detail can show it).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_banner_locality_new"><?php esc_html_e( 'Nueva localidad (/New neighborhood)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_banner_locality_new" name="hy_banner_locality_new" type="text" value="" placeholder="<?php esc_attr_e( 'Ej: Waterloo', 'hy-homes-syd-panther' ); ?>">
						<p class="hy-homes-admin-help"><?php esc_html_e( 'Si se completa, crea o usa esa localidad (/If filled, it creates or uses that neighborhood).', 'hy-homes-syd-panther' ); ?></p>
					</div>

					<div class="hy-homes-admin-field">
						<label for="hy_banner_title"><?php esc_html_e( 'Titulo del banner (/Banner title)', 'hy-homes-syd-panther' ); ?></label>
						<input id="hy_banner_title" name="hy_banner_title" type="text" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e( 'Ej: Private Sauna Room', 'hy-homes-syd-panther' ); ?>">
					</div>
				</div>
			</div>

			<div class="hy-homes-admin-section">
				<h3><?php esc_html_e( 'Contenido del banner (/Banner content)', 'hy-homes-syd-panther' ); ?></h3>
				<div class="hy-homes-admin-grid hy-homes-admin-grid--1">
					<div class="hy-homes-admin-field">
						<label for="hy_banner_description"><?php esc_html_e( 'Descripcion (/Description)', 'hy-homes-syd-panther' ); ?></label>
						<textarea id="hy_banner_description" name="hy_banner_description" rows="5" placeholder="<?php esc_attr_e( 'Texto breve que acompana el banner.', 'hy-homes-syd-panther' ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
					</div>

					<div class="hy-homes-admin-grid hy-homes-admin-grid--2">
						<div class="hy-homes-admin-field">
							<label for="hy_banner_image_url"><?php esc_html_e( 'URL de imagen externa (/External image URL)', 'hy-homes-syd-panther' ); ?></label>
							<input id="hy_banner_image_url" name="hy_banner_image_url" type="url" value="<?php echo esc_attr( $image_url ); ?>" placeholder="https://example.com/banner.jpg">
							<p class="hy-homes-admin-help"><?php esc_html_e( 'Usar para OneDrive, WeDrive o imagenes externas. La imagen destacada de WordPress tiene prioridad (/Use for OneDrive, WeDrive or external images. The WordPress featured image has priority).', 'hy-homes-syd-panther' ); ?></p>
						</div>

						<div class="hy-homes-admin-field">
							<label for="hy_banner_button_url"><?php esc_html_e( 'URL del boton (/Button URL)', 'hy-homes-syd-panther' ); ?></label>
							<input id="hy_banner_button_url" name="hy_banner_button_url" type="url" value="<?php echo esc_attr( $button_url ); ?>" placeholder="https://hyhomessyd.com/#locatios">
							<p class="hy-homes-admin-help"><?php esc_html_e( 'El carrusel aleatorio usa todos los banners; la ficha de propiedad usa la localidad (/The random carousel uses all banners; the property detail uses the neighborhood).', 'hy-homes-syd-panther' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save property fields.
	 *
	 * @param int $post_id Current post ID.
	 */
	public static function save_property( $post_id ) {
		if ( ! isset( $_POST['hy_homes_property_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hy_homes_property_nonce'] ) ), 'hy_homes_save_property' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$title       = isset( $_POST['hy_property_title'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_property_title'] ) ) : '';
		$description = isset( $_POST['hy_property_description'] ) ? wp_kses_post( wp_unslash( $_POST['hy_property_description'] ) ) : '';

		$post_update = array(
			'ID'           => $post_id,
			'post_content' => $description,
		);

		if ( '' !== $title ) {
			$post_update['post_title'] = $title;
		}

		remove_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_property' ) );
		wp_update_post( $post_update );
		add_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_property' ) );

		$address       = isset( $_POST['hy_property_address'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_property_address'] ) ) : '';
		$price         = isset( $_POST['hy_property_price'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_property_price'] ) ) : '';
		$availability  = isset( $_POST['hy_property_availability'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_property_availability'] ) ) : '';
		$bedrooms_raw  = isset( $_POST['hy_property_bedrooms'] ) ? wp_unslash( $_POST['hy_property_bedrooms'] ) : '';
		$bathrooms_raw = isset( $_POST['hy_property_bathrooms'] ) ? wp_unslash( $_POST['hy_property_bathrooms'] ) : '';
		$bedrooms      = '' === $bedrooms_raw ? '' : absint( $bedrooms_raw );
		$bathrooms     = '' === $bathrooms_raw ? '' : absint( $bathrooms_raw );
		$map_url       = isset( $_POST['hy_property_map_embed_url'] ) ? esc_url_raw( wp_unslash( $_POST['hy_property_map_embed_url'] ) ) : '';
		$gallery_media = isset( $_POST['hy_property_gallery_media'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hy_property_gallery_media'] ) ) : '';

		self::update_property_meta_value( $post_id, 'address', $address );
		self::update_property_meta_value( $post_id, 'street', $address );
		self::update_property_meta_value( $post_id, 'price', $price );
		self::update_property_meta_value( $post_id, 'status', self::availability_to_status_label( $availability ) );
		self::update_property_meta_value( $post_id, 'move_in', self::availability_to_filter_value( $availability ) );
		self::update_property_meta_value( $post_id, 'bedrooms', $bedrooms );
		self::update_property_meta_value( $post_id, 'room_type', $bedrooms );
		self::update_property_meta_value( $post_id, 'bathrooms', $bathrooms );
		self::update_property_meta_value( $post_id, 'map_embed_url', $map_url );
		self::update_property_meta_value( $post_id, 'gallery_media', $gallery_media );

		if ( '' !== $price && '' === get_post_meta( $post_id, self::META_PREFIX . 'price_suffix', true ) ) {
			update_post_meta( $post_id, self::META_PREFIX . 'price_suffix', 'pw' );
		}

		$existing_term_id = isset( $_POST['hy_property_locality_existing'] ) ? absint( wp_unslash( $_POST['hy_property_locality_existing'] ) ) : 0;
		$new_locality     = isset( $_POST['hy_property_locality_new'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_property_locality_new'] ) ) : '';

		if ( '' !== $new_locality ) {
			wp_set_object_terms( $post_id, array( $new_locality ), self::TAX_NEIGHBORHOOD, false );
		} elseif ( 0 < $existing_term_id ) {
			wp_set_object_terms( $post_id, array( $existing_term_id ), self::TAX_NEIGHBORHOOD, false );
		} else {
			wp_set_object_terms( $post_id, array(), self::TAX_NEIGHBORHOOD, false );
		}
	}

	/**
	 * Update or delete a property meta value.
	 *
	 * @param int    $post_id Property ID.
	 * @param string $field Meta field without prefix.
	 * @param mixed  $value Meta value.
	 */
	private static function update_property_meta_value( $post_id, $field, $value ) {
		$meta_key = self::META_PREFIX . $field;

		if ( '' === $value || null === $value ) {
			delete_post_meta( $post_id, $meta_key );
			return;
		}

		update_post_meta( $post_id, $meta_key, $value );
	}

	/**
	 * Convert availability value into the card label.
	 *
	 * @param string $availability Availability value.
	 * @return string
	 */
	private static function availability_to_status_label( $availability ) {
		$availability = trim( (string) $availability );

		if ( '' === $availability ) {
			return '';
		}

		if ( 'immediate' === strtolower( $availability ) ) {
			return 'Available Now';
		}

		return $availability;
	}

	/**
	 * Convert availability value into the search filter value.
	 *
	 * @param string $availability Availability value.
	 * @return string
	 */
	private static function availability_to_filter_value( $availability ) {
		$availability = trim( (string) $availability );

		if ( '' === $availability ) {
			return '';
		}

		if ( in_array( strtolower( $availability ), array( 'available now', 'disponible ahora' ), true ) ) {
			return 'Immediate';
		}

		return $availability;
	}

	/**
	 * Save banner fields.
	 *
	 * @param int $post_id Current post ID.
	 */
	public static function save_banner( $post_id ) {
		if ( ! isset( $_POST['hy_homes_banner_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hy_homes_banner_nonce'] ) ), 'hy_homes_save_banner' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$title = isset( $_POST['hy_banner_title'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_banner_title'] ) ) : '';

		if ( '' !== $title ) {
			remove_action( 'save_post_' . self::BANNER_POST_TYPE, array( __CLASS__, 'save_banner' ) );
			wp_update_post(
				array(
					'ID'         => $post_id,
					'post_title' => $title,
				)
			);
			add_action( 'save_post_' . self::BANNER_POST_TYPE, array( __CLASS__, 'save_banner' ) );
		}

		$fields = array(
			'description' => 'textarea',
			'image_url'   => 'url',
			'button_url'  => 'url',
		);

		foreach ( $fields as $field => $type ) {
			$post_key = 'hy_banner_' . $field;
			$meta_key = self::BANNER_META_PREFIX . $field;

			if ( ! isset( $_POST[ $post_key ] ) ) {
				delete_post_meta( $post_id, $meta_key );
				continue;
			}

			$value = wp_unslash( $_POST[ $post_key ] );
			$value = 'url' === $type ? esc_url_raw( $value ) : sanitize_textarea_field( $value );

			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, $value );
			}
		}

		$existing_term_id = isset( $_POST['hy_banner_locality_existing'] ) ? absint( wp_unslash( $_POST['hy_banner_locality_existing'] ) ) : 0;
		$new_locality     = isset( $_POST['hy_banner_locality_new'] ) ? sanitize_text_field( wp_unslash( $_POST['hy_banner_locality_new'] ) ) : '';

		if ( '' !== $new_locality ) {
			wp_set_object_terms( $post_id, array( $new_locality ), self::TAX_NEIGHBORHOOD, false );
		} elseif ( 0 < $existing_term_id ) {
			wp_set_object_terms( $post_id, array( $existing_term_id ), self::TAX_NEIGHBORHOOD, false );
		} else {
			wp_set_object_terms( $post_id, array(), self::TAX_NEIGHBORHOOD, false );
		}
	}

	/**
	 * Add bilingual wording to the native WordPress neighborhood fields.
	 *
	 * @param string $translation Current translated text.
	 * @param string $text Source text.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function filter_neighborhood_admin_text( $translation, $text, $domain ) {
		if ( 'default' !== $domain || ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
			return $translation;
		}

		$screen = get_current_screen();

		if ( ! $screen || ! isset( $screen->taxonomy ) || self::TAX_NEIGHBORHOOD !== $screen->taxonomy ) {
			return $translation;
		}

		$map = array(
			'Name'                                                                 => 'Nombre (/Name)',
			'Slug'                                                                 => 'Slug (/Slug)',
			'Description'                                                          => 'Descripcion (/Description)',
			'Parent Category'                                                      => 'Localidad superior (/Parent neighborhood)',
			'None'                                                                 => 'Ninguna (/None)',
			'Count'                                                                => 'Cantidad (/Count)',
			'The name is how it appears on your site.'                             => 'El nombre es como aparece en el sitio (/The name is how it appears on your site).',
			'The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.' => 'El slug es la version amigable para URL del nombre (/The slug is the URL-friendly version of the name).',
			'The description is not prominent by default; however, some themes may show it.' => 'La descripcion no se muestra por defecto, aunque algunos temas pueden mostrarla (/The description is not prominent by default; some themes may show it).',
		);

		return isset( $map[ $text ] ) ? $map[ $text ] : $translation;
	}

	/**
	 * Return available neighborhood names.
	 *
	 * @return array<int,string>
	 */
	public static function get_neighborhood_options() {
		$terms = get_terms(
			array(
				'taxonomy'   => self::TAX_NEIGHBORHOOD,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		return wp_list_pluck( $terms, 'name' );
	}
}
