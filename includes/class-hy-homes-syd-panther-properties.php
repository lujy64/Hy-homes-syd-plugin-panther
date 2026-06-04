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
	const POST_TYPE        = 'hy_home_property';
	const BANNER_POST_TYPE = 'hy_location_banner';
	const TAX_NEIGHBORHOOD = 'hy_property_neighborhood';
	const META_PREFIX      = '_hy_property_';
	const BANNER_META_PREFIX = '_hy_banner_';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_content_types' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_property' ) );
		add_action( 'save_post_' . self::BANNER_POST_TYPE, array( __CLASS__, 'save_banner' ) );
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
					'name'               => __( 'Properties', 'hy-homes-syd-panther' ),
					'singular_name'      => __( 'Property', 'hy-homes-syd-panther' ),
					'add_new_item'       => __( 'Add New Property', 'hy-homes-syd-panther' ),
					'edit_item'          => __( 'Edit Property', 'hy-homes-syd-panther' ),
					'new_item'           => __( 'New Property', 'hy-homes-syd-panther' ),
					'view_item'          => __( 'View Property', 'hy-homes-syd-panther' ),
					'search_items'       => __( 'Search Properties', 'hy-homes-syd-panther' ),
					'not_found'          => __( 'No properties found.', 'hy-homes-syd-panther' ),
					'not_found_in_trash' => __( 'No properties found in Trash.', 'hy-homes-syd-panther' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => 'dashicons-building',
				'show_in_menu' => 'hy_homes_syd',
				'rewrite'      => array( 'slug' => 'hy-properties' ),
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			)
		);

		register_post_type(
			self::BANNER_POST_TYPE,
			array(
				'labels'              => array(
					'name'               => __( 'Location Banners', 'hy-homes-syd-panther' ),
					'singular_name'      => __( 'Location Banner', 'hy-homes-syd-panther' ),
					'add_new_item'       => __( 'Add New Banner', 'hy-homes-syd-panther' ),
					'edit_item'          => __( 'Edit Banner', 'hy-homes-syd-panther' ),
					'new_item'           => __( 'New Banner', 'hy-homes-syd-panther' ),
					'view_item'          => __( 'View Banner', 'hy-homes-syd-panther' ),
					'search_items'       => __( 'Search Banners', 'hy-homes-syd-panther' ),
					'not_found'          => __( 'No banners found.', 'hy-homes-syd-panther' ),
					'not_found_in_trash' => __( 'No banners found in Trash.', 'hy-homes-syd-panther' ),
				),
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => 'hy_homes_syd',
				'menu_icon'           => 'dashicons-format-image',
				'show_in_rest'        => true,
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		);

		register_taxonomy(
			self::TAX_NEIGHBORHOOD,
			array( self::POST_TYPE, self::BANNER_POST_TYPE ),
			array(
				'labels'            => array(
					'name'          => __( 'Neighborhoods', 'hy-homes-syd-panther' ),
					'singular_name' => __( 'Neighborhood', 'hy-homes-syd-panther' ),
					'search_items'  => __( 'Search Neighborhoods', 'hy-homes-syd-panther' ),
					'all_items'     => __( 'All Neighborhoods', 'hy-homes-syd-panther' ),
					'edit_item'     => __( 'Edit Neighborhood', 'hy-homes-syd-panther' ),
					'add_new_item'  => __( 'Add New Neighborhood', 'hy-homes-syd-panther' ),
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
			__( 'Property Details', 'hy-homes-syd-panther' ),
			array( __CLASS__, 'render_details_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'hy_homes_banner_details',
			__( 'Banner Details', 'hy-homes-syd-panther' ),
			array( __CLASS__, 'render_banner_meta_box' ),
			self::BANNER_POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render property details fields.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_details_meta_box( $post ) {
		wp_nonce_field( 'hy_homes_save_property', 'hy_homes_property_nonce' );

		$fields = array(
			'room_type'    => array(
				'label' => __( 'Room Type Filter Number', 'hy-homes-syd-panther' ),
				'type'  => 'number',
			),
			'bedrooms'     => array(
				'label' => __( 'Bedrooms', 'hy-homes-syd-panther' ),
				'type'  => 'number',
			),
			'bathrooms'    => array(
				'label' => __( 'Bathrooms', 'hy-homes-syd-panther' ),
				'type'  => 'number',
			),
			'street'       => array(
				'label' => __( 'Street Line', 'hy-homes-syd-panther' ),
				'type'  => 'text',
			),
			'address'      => array(
				'label' => __( 'Full Address', 'hy-homes-syd-panther' ),
				'type'  => 'text',
			),
			'price'        => array(
				'label' => __( 'Price', 'hy-homes-syd-panther' ),
				'type'  => 'text',
			),
			'price_suffix' => array(
				'label' => __( 'Price Suffix', 'hy-homes-syd-panther' ),
				'type'  => 'text',
			),
			'status'       => array(
				'label' => __( 'Availability Label', 'hy-homes-syd-panther' ),
				'type'  => 'text',
			),
			'move_in'      => array(
				'label' => __( 'Move-in Filter Value', 'hy-homes-syd-panther' ),
				'type'  => 'text',
			),
			'detail_url'   => array(
				'label' => __( 'Detail URL', 'hy-homes-syd-panther' ),
				'type'  => 'url',
			),
			'featured_image_url' => array(
				'label'       => __( 'External Card Image URL', 'hy-homes-syd-panther' ),
				'type'        => 'url',
				'description' => __( 'Optional fallback image URL for imports or external media. Featured image has priority.', 'hy-homes-syd-panther' ),
			),
			'gallery_media' => array(
				'label'       => __( 'Gallery Media URLs', 'hy-homes-syd-panther' ),
				'type'        => 'textarea',
				'description' => __( 'One URL per line. Supports images, direct video files, and embeddable iframe URLs.', 'hy-homes-syd-panther' ),
			),
			'map_embed_url' => array(
				'label'       => __( 'Google Map Embed URL', 'hy-homes-syd-panther' ),
				'type'        => 'url',
				'description' => __( 'Paste a Google Maps embed URL. If empty, the full address will be used.', 'hy-homes-syd-panther' ),
			),
			'whatsapp_phone' => array(
				'label'       => __( 'WhatsApp Phone', 'hy-homes-syd-panther' ),
				'type'        => 'text',
				'description' => __( 'Use international format without plus sign, for example 61400000000.', 'hy-homes-syd-panther' ),
			),
			'location_banners' => array(
				'label'       => __( 'Legacy Location Banners', 'hy-homes-syd-panther' ),
				'type'        => 'textarea',
				'description' => __( 'Fallback only. Prefer HY Homes Syd > Location Banners. Format: image URL | title | description | optional button URL.', 'hy-homes-syd-panther' ),
			),
		);

		echo '<div class="hy-homes-admin-fields">';

		foreach ( $fields as $field => $field_args ) {
			$value = get_post_meta( $post->ID, self::META_PREFIX . $field, true );
			?>
			<p>
				<label for="<?php echo esc_attr( 'hy_property_' . $field ); ?>">
					<strong><?php echo esc_html( $field_args['label'] ); ?></strong>
				</label>
				<?php if ( 'textarea' === $field_args['type'] ) : ?>
					<textarea
						id="<?php echo esc_attr( 'hy_property_' . $field ); ?>"
						name="<?php echo esc_attr( 'hy_property_' . $field ); ?>"
						rows="5"
						style="width:100%;max-width:760px;"
					><?php echo esc_textarea( $value ); ?></textarea>
				<?php else : ?>
					<input
						id="<?php echo esc_attr( 'hy_property_' . $field ); ?>"
						name="<?php echo esc_attr( 'hy_property_' . $field ); ?>"
						type="<?php echo esc_attr( $field_args['type'] ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						style="width:100%;max-width:520px;"
					/>
				<?php endif; ?>
				<?php if ( ! empty( $field_args['description'] ) ) : ?>
					<br><small><?php echo esc_html( $field_args['description'] ); ?></small>
				<?php endif; ?>
			</p>
			<?php
		}

		echo '</div>';
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

		if ( '' === $button_url ) {
			$button_url = 'https://hyhomessyd.com/#locatios';
		}
		?>
		<div class="hy-homes-admin-fields">
			<p>
				<label for="hy_banner_description"><strong><?php esc_html_e( 'Description', 'hy-homes-syd-panther' ); ?></strong></label>
				<textarea id="hy_banner_description" name="hy_banner_description" rows="4" style="width:100%;max-width:760px;"><?php echo esc_textarea( $description ); ?></textarea>
			</p>
			<p>
				<label for="hy_banner_image_url"><strong><?php esc_html_e( 'External Image URL', 'hy-homes-syd-panther' ); ?></strong></label>
				<input id="hy_banner_image_url" name="hy_banner_image_url" type="url" value="<?php echo esc_attr( $image_url ); ?>" style="width:100%;max-width:760px;" />
				<br><small><?php esc_html_e( 'Use this for OneDrive/WeDrive or external images. The WordPress featured image has priority.', 'hy-homes-syd-panther' ); ?></small>
			</p>
			<p>
				<label for="hy_banner_button_url"><strong><?php esc_html_e( 'Button URL', 'hy-homes-syd-panther' ); ?></strong></label>
				<input id="hy_banner_button_url" name="hy_banner_button_url" type="url" value="<?php echo esc_attr( $button_url ); ?>" style="width:100%;max-width:520px;" />
			</p>
			<p><small><?php esc_html_e( 'Select the locality in the Neighborhoods box. Random banner shortcodes ignore locality; property detail banners use it.', 'hy-homes-syd-panther' ); ?></small></p>
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

		$fields = array( 'room_type', 'bedrooms', 'bathrooms', 'street', 'address', 'price', 'price_suffix', 'status', 'move_in', 'detail_url', 'featured_image_url', 'gallery_media', 'map_embed_url', 'whatsapp_phone', 'location_banners' );

		foreach ( $fields as $field ) {
			$post_key = 'hy_property_' . $field;
			$meta_key = self::META_PREFIX . $field;

			if ( ! isset( $_POST[ $post_key ] ) ) {
				delete_post_meta( $post_id, $meta_key );
				continue;
			}

			$value = wp_unslash( $_POST[ $post_key ] );

			if ( in_array( $field, array( 'room_type', 'bedrooms', 'bathrooms' ), true ) ) {
				$value = absint( $value );
			} elseif ( in_array( $field, array( 'detail_url', 'featured_image_url', 'map_embed_url' ), true ) ) {
				$value = esc_url_raw( $value );
			} elseif ( in_array( $field, array( 'gallery_media', 'location_banners' ), true ) ) {
				$value = sanitize_textarea_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}

			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
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
