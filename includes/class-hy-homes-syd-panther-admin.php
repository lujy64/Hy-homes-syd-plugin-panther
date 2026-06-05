<?php
/**
 * Admin menu and spreadsheet import tools.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WordPress admin panel for HY Homes Syd content.
 */
final class HY_Homes_Syd_Panther_Admin {
	const MENU_SLUG                = 'hy_homes_syd';
	const IMPORT_SLUG              = 'hy_homes_syd_import';
	const MEDIA_SLUG               = 'hy_homes_syd_media';
	const IMPORT_ACTION            = 'hy_homes_syd_import';
	const MEDIA_SETTINGS_ACTION    = 'hy_homes_syd_media_settings';
	const MEDIA_UPLOAD_ACTION      = 'hy_homes_syd_media_upload';
	const MEDIA_BASE_URL_OPTION    = 'hy_homes_syd_media_base_url';
	const MEDIA_BASE_PATH_OPTION   = 'hy_homes_syd_media_base_path';
	const MEDIA_FFMPEG_PATH_OPTION = 'hy_homes_syd_media_ffmpeg_path';
	const MEDIA_RESULT_TRANSIENT   = 'hy_homes_syd_media_result_';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ), 5 );
		add_action( 'admin_post_' . self::IMPORT_ACTION, array( __CLASS__, 'handle_import' ) );
		add_action( 'admin_post_' . self::MEDIA_SETTINGS_ACTION, array( __CLASS__, 'handle_media_settings' ) );
		add_action( 'admin_post_' . self::MEDIA_UPLOAD_ACTION, array( __CLASS__, 'handle_media_upload' ) );
	}

	/**
	 * Register the sidebar admin panel.
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'HY Homes Syd', 'hy-homes-syd-panther' ),
			__( 'HY Homes Syd', 'hy-homes-syd-panther' ),
			'edit_posts',
			self::MENU_SLUG,
			array( __CLASS__, 'render_dashboard_page' ),
			'dashicons-admin-home',
			26
		);

		add_submenu_page(
			self::MENU_SLUG,
			__( 'Dashboard', 'hy-homes-syd-panther' ),
			__( 'Dashboard', 'hy-homes-syd-panther' ),
			'edit_posts',
			self::MENU_SLUG,
			array( __CLASS__, 'render_dashboard_page' )
		);

		add_submenu_page(
			self::MENU_SLUG,
			__( 'Localidades (/Neighborhoods)', 'hy-homes-syd-panther' ),
			__( 'Localidades (/Neighborhoods)', 'hy-homes-syd-panther' ),
			'manage_categories',
			'edit-tags.php?taxonomy=' . HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD . '&post_type=' . HY_Homes_Syd_Panther_Properties::POST_TYPE
		);

		add_submenu_page(
			self::MENU_SLUG,
			__( 'Import Excel / Sheets', 'hy-homes-syd-panther' ),
			__( 'Import Excel / Sheets', 'hy-homes-syd-panther' ),
			'edit_posts',
			self::IMPORT_SLUG,
			array( __CLASS__, 'render_import_page' )
		);

		add_submenu_page(
			self::MENU_SLUG,
			__( 'Media externo (/External media)', 'hy-homes-syd-panther' ),
			__( 'Media externo (/External media)', 'hy-homes-syd-panther' ),
			'manage_options',
			self::MEDIA_SLUG,
			array( __CLASS__, 'render_media_page' )
		);
	}

	/**
	 * Render dashboard.
	 */
	public static function render_dashboard_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'HY Homes Syd', 'hy-homes-syd-panther' ); ?></h1>
			<p><strong><?php esc_html_e( 'Developed by The Panther Soft - Vaira Maria Lujan', 'hy-homes-syd-panther' ); ?></strong></p>
			<p><?php esc_html_e( 'Administra propiedades, banners por localidad e importaciones desde hojas de calculo (/Manage properties, location banners and spreadsheet imports).', 'hy-homes-syd-panther' ); ?></p>

			<p>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . HY_Homes_Syd_Panther_Properties::POST_TYPE ) ); ?>"><?php esc_html_e( 'Agregar propiedad (/Add property)', 'hy-homes-syd-panther' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . HY_Homes_Syd_Panther_Properties::POST_TYPE ) ); ?>"><?php esc_html_e( 'Ver propiedades (/View properties)', 'hy-homes-syd-panther' ); ?></a>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE ) ); ?>"><?php esc_html_e( 'Agregar banner (/Add banner)', 'hy-homes-syd-panther' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE ) ); ?>"><?php esc_html_e( 'Ver banners (/View banners)', 'hy-homes-syd-panther' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::IMPORT_SLUG ) ); ?>"><?php esc_html_e( 'Importar Excel / Sheets (/Import Excel / Sheets)', 'hy-homes-syd-panther' ); ?></a>
				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::MEDIA_SLUG ) ); ?>"><?php esc_html_e( 'Media externo (/External media)', 'hy-homes-syd-panther' ); ?></a>
				<?php endif; ?>
			</p>

			<h2><?php esc_html_e( 'Shortcodes', 'hy-homes-syd-panther' ); ?></h2>
			<ul>
				<li><code>[hy_homes_search_filter]</code></li>
				<li><code>[hy_homes_property_results]</code></li>
				<li><code>[hy_homes_recent_properties_carousel]</code></li>
				<li><code>[hy_homes_property_detail]</code></li>
				<li><code>[hy_homes_random_banners]</code></li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render import page.
	 */
	public static function render_import_page() {
		$notice = self::get_import_notice();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Import Excel / Google Sheets', 'hy-homes-syd-panther' ); ?></h1>
			<p><strong><?php esc_html_e( 'Developed by The Panther Soft - Vaira Maria Lujan', 'hy-homes-syd-panther' ); ?></strong></p>

			<?php if ( '' !== $notice ) : ?>
				<div class="notice notice-info is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<input type="hidden" name="action" value="<?php echo esc_attr( self::IMPORT_ACTION ); ?>">
				<?php wp_nonce_field( self::IMPORT_ACTION, 'hy_homes_import_nonce' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="hy_homes_import_file"><?php esc_html_e( 'CSV or XLSX file', 'hy-homes-syd-panther' ); ?></label></th>
						<td>
							<input id="hy_homes_import_file" name="hy_homes_import_file" type="file" accept=".csv,.xlsx">
							<p class="description"><?php esc_html_e( 'CSV is recommended. XLSX works when the server has ZipArchive enabled.', 'hy-homes-syd-panther' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hy_homes_google_sheet_url"><?php esc_html_e( 'Google Sheet CSV URL', 'hy-homes-syd-panther' ); ?></label></th>
						<td>
							<input id="hy_homes_google_sheet_url" name="hy_homes_google_sheet_url" type="url" class="regular-text" placeholder="https://docs.google.com/spreadsheets/d/...">
							<p class="description"><?php esc_html_e( 'Paste a published Google Sheets link or CSV export URL. If both file and URL are filled, the URL is used.', 'hy-homes-syd-panther' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hy_homes_import_status"><?php esc_html_e( 'Default status', 'hy-homes-syd-panther' ); ?></label></th>
						<td>
							<select id="hy_homes_import_status" name="hy_homes_import_status">
								<option value="publish"><?php esc_html_e( 'Published', 'hy-homes-syd-panther' ); ?></option>
								<option value="draft"><?php esc_html_e( 'Draft', 'hy-homes-syd-panther' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Import', 'hy-homes-syd-panther' ) ); ?>
			</form>

			<h2><?php esc_html_e( 'Spreadsheet columns', 'hy-homes-syd-panther' ); ?></h2>
			<p><?php esc_html_e( 'Use one row per item. The type column must be property or banner.', 'hy-homes-syd-panther' ); ?></p>
			<textarea readonly rows="8" class="large-text code">action,type,id,slug,title,description,neighborhood,room_type,bedrooms,bathrooms,street,address,price,availability_date,availability,price_suffix,status,move_in,detail_url,featured_image_url,gallery_media,map_embed_url,whatsapp_phone,image_url,button_url
,property,,,Modern Apartment in Zetland,Fully furnished.,Zetland,1,2,1,Calle xx,Full address,1010,2026-06-10,,pw,,,,https://example.com/card.jpg,https://example.com/gallery-1.jpg,,61400000000,,
,banner,,,Private Sauna Room,A tranquil space to unwind.,Zetland,,,,,,,,,,,,,,,,,https://example.com/banner.jpg,https://hyhomessyd.com/#locatios
delete,property,123,modern-apartment-in-zetland,,,,,,,,,,,,,,,,,,,,</textarea>
			<p class="description"><?php esc_html_e( 'Accepted aliases include accion, tipo, titulo, descripcion, localidad, barrio, imagen, precio, fecha_disponible, disponibilidad, banos, dormitorios, telefono_whatsapp and url_boton. Availability date accepts YYYY-MM-DD and automatically calculates the card label/search filter. Use action=delete or accion=eliminar with an id or slug to move an item to Trash.', 'hy-homes-syd-panther' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render external media upload page.
	 */
	public static function render_media_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage HY Homes media.', 'hy-homes-syd-panther' ) );
		}

		$settings = self::get_media_settings();
		$result   = self::get_media_result();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Media externo (/External media)', 'hy-homes-syd-panther' ); ?></h1>
			<p><strong><?php esc_html_e( 'Developed by The Panther Soft - Vaira Maria Lujan', 'hy-homes-syd-panther' ); ?></strong></p>
			<p><?php esc_html_e( 'Subi un ZIP con imagenes y videos al subdominio de media. El plugin descomprime el ZIP y genera las URLs listas para usar en propiedades y banners.', 'hy-homes-syd-panther' ); ?></p>

			<?php if ( ! empty( $result['message'] ) ) : ?>
				<div class="notice <?php echo ! empty( $result['error'] ) ? 'notice-error' : 'notice-success'; ?> is-dismissible">
					<p><?php echo esc_html( $result['message'] ); ?></p>
				</div>
			<?php endif; ?>

			<div class="hy-homes-admin-fields">
				<div class="hy-homes-admin-section">
					<h3><?php esc_html_e( 'Configuracion del subdominio (/Subdomain settings)', 'hy-homes-syd-panther' ); ?></h3>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="<?php echo esc_attr( self::MEDIA_SETTINGS_ACTION ); ?>">
						<?php wp_nonce_field( self::MEDIA_SETTINGS_ACTION, 'hy_homes_media_settings_nonce' ); ?>

						<div class="hy-homes-admin-grid hy-homes-admin-grid--2">
							<div class="hy-homes-admin-field">
								<label for="hy_homes_media_base_url"><?php esc_html_e( 'URL base (/Base URL)', 'hy-homes-syd-panther' ); ?></label>
								<input id="hy_homes_media_base_url" name="hy_homes_media_base_url" type="url" value="<?php echo esc_attr( $settings['base_url'] ); ?>" placeholder="https://media.hyhomessyd.com/">
								<p class="hy-homes-admin-help"><?php esc_html_e( 'Debe ser la URL publica del subdominio donde se veran los archivos.', 'hy-homes-syd-panther' ); ?></p>
							</div>

							<div class="hy-homes-admin-field">
								<label for="hy_homes_media_base_path"><?php esc_html_e( 'Ruta del servidor (/Server path)', 'hy-homes-syd-panther' ); ?></label>
								<input id="hy_homes_media_base_path" name="hy_homes_media_base_path" type="text" value="<?php echo esc_attr( $settings['base_path'] ); ?>" placeholder="/home/usuario/media.hyhomessyd.com">
								<p class="hy-homes-admin-help"><?php esc_html_e( 'Debe ser la carpeta fisica del subdominio en el hosting.', 'hy-homes-syd-panther' ); ?></p>
							</div>

							<div class="hy-homes-admin-field">
								<label for="hy_homes_media_ffmpeg_path"><?php esc_html_e( 'Ruta de FFmpeg (/FFmpeg path)', 'hy-homes-syd-panther' ); ?></label>
								<input id="hy_homes_media_ffmpeg_path" name="hy_homes_media_ffmpeg_path" type="text" value="<?php echo esc_attr( $settings['ffmpeg_path'] ); ?>" placeholder="/usr/bin/ffmpeg">
								<p class="hy-homes-admin-help"><?php esc_html_e( 'Opcional. Se usa para convertir videos a WebM. Si FFmpeg esta en el PATH del servidor, puede quedar vacio.', 'hy-homes-syd-panther' ); ?></p>
							</div>
						</div>

						<?php submit_button( __( 'Guardar configuracion (/Save settings)', 'hy-homes-syd-panther' ) ); ?>
					</form>
				</div>

				<div class="hy-homes-admin-section">
					<h3><?php esc_html_e( 'Subir ZIP de una propiedad (/Upload property ZIP)', 'hy-homes-syd-panther' ); ?></h3>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
						<input type="hidden" name="action" value="<?php echo esc_attr( self::MEDIA_UPLOAD_ACTION ); ?>">
						<?php wp_nonce_field( self::MEDIA_UPLOAD_ACTION, 'hy_homes_media_upload_nonce' ); ?>

						<div class="hy-homes-admin-grid hy-homes-admin-grid--2">
							<div class="hy-homes-admin-field">
								<label for="hy_homes_media_folder"><?php esc_html_e( 'Carpeta destino (/Destination folder)', 'hy-homes-syd-panther' ); ?></label>
								<input id="hy_homes_media_folder" name="hy_homes_media_folder" type="text" placeholder="zetland/modern-apartment">
								<p class="hy-homes-admin-help"><?php esc_html_e( 'Ejemplo: zetland/modern-apartment. Si queda vacio se usa el nombre del ZIP.', 'hy-homes-syd-panther' ); ?></p>
							</div>

							<div class="hy-homes-admin-field">
								<label for="hy_homes_media_zip"><?php esc_html_e( 'Archivo ZIP (/ZIP file)', 'hy-homes-syd-panther' ); ?></label>
								<input id="hy_homes_media_zip" name="hy_homes_media_zip" type="file" accept=".zip,application/zip">
								<p class="hy-homes-admin-help"><?php esc_html_e( 'Acepta imagenes jpg, jpeg, png, webp, gif, avif, heic, heif y videos mp4, webm, ogg, mov, m4v.', 'hy-homes-syd-panther' ); ?></p>
							</div>
						</div>

						<div class="hy-homes-admin-grid hy-homes-admin-grid--2">
							<label class="hy-homes-admin-checkbox">
								<input type="checkbox" name="hy_homes_media_convert_images" value="1" checked>
								<span><?php esc_html_e( 'Convertir imagenes a AVIF (/Convert images to AVIF)', 'hy-homes-syd-panther' ); ?></span>
							</label>

							<label class="hy-homes-admin-checkbox">
								<input type="checkbox" name="hy_homes_media_convert_videos" value="1" checked>
								<span><?php esc_html_e( 'Convertir videos a WebM (/Convert videos to WebM)', 'hy-homes-syd-panther' ); ?></span>
							</label>
						</div>

						<?php submit_button( __( 'Subir y generar URLs (/Upload and generate URLs)', 'hy-homes-syd-panther' ), 'primary' ); ?>
					</form>
				</div>

				<?php if ( ! empty( $result['urls'] ) && is_array( $result['urls'] ) ) : ?>
					<div class="hy-homes-admin-section">
						<h3><?php esc_html_e( 'URLs generadas (/Generated URLs)', 'hy-homes-syd-panther' ); ?></h3>
						<div class="hy-homes-admin-field">
							<label for="hy_homes_media_generated_urls"><?php esc_html_e( 'Copiar para URLs de imagenes y videos (/Copy for image and video URLs)', 'hy-homes-syd-panther' ); ?></label>
							<textarea id="hy_homes_media_generated_urls" rows="10" readonly><?php echo esc_textarea( implode( "\n", $result['urls'] ) ); ?></textarea>
							<p class="hy-homes-admin-help">
								<?php echo esc_html( sprintf( __( 'Archivos generados (/Generated files): %d', 'hy-homes-syd-panther' ), count( $result['urls'] ) ) ); ?>
								<?php if ( ! empty( $result['skipped'] ) ) : ?>
									<br><?php echo esc_html( sprintf( __( 'Archivos omitidos por seguridad o formato no permitido (/Skipped files): %d', 'hy-homes-syd-panther' ), absint( $result['skipped'] ) ) ); ?>
								<?php endif; ?>
								<?php if ( isset( $result['converted_images'] ) || isset( $result['converted_videos'] ) ) : ?>
									<br><?php echo esc_html( sprintf( __( 'Imagenes convertidas a AVIF (/Images converted to AVIF): %d', 'hy-homes-syd-panther' ), isset( $result['converted_images'] ) ? absint( $result['converted_images'] ) : 0 ) ); ?>
									<br><?php echo esc_html( sprintf( __( 'Videos convertidos a WebM (/Videos converted to WebM): %d', 'hy-homes-syd-panther' ), isset( $result['converted_videos'] ) ? absint( $result['converted_videos'] ) : 0 ) ); ?>
								<?php endif; ?>
								<?php if ( ! empty( $result['conversion_failed'] ) ) : ?>
									<br><?php echo esc_html( sprintf( __( 'Conversiones no realizadas, se conservaron originales (/Conversions skipped, originals kept): %d', 'hy-homes-syd-panther' ), absint( $result['conversion_failed'] ) ) ); ?>
								<?php endif; ?>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Save external media settings.
	 */
	public static function handle_media_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage HY Homes media.', 'hy-homes-syd-panther' ) );
		}

		check_admin_referer( self::MEDIA_SETTINGS_ACTION, 'hy_homes_media_settings_nonce' );

		$base_url    = isset( $_POST['hy_homes_media_base_url'] ) ? esc_url_raw( wp_unslash( $_POST['hy_homes_media_base_url'] ) ) : '';
		$base_path   = isset( $_POST['hy_homes_media_base_path'] ) ? self::normalize_server_path( wp_unslash( $_POST['hy_homes_media_base_path'] ) ) : '';
		$ffmpeg_path = isset( $_POST['hy_homes_media_ffmpeg_path'] ) ? self::normalize_command_path( wp_unslash( $_POST['hy_homes_media_ffmpeg_path'] ) ) : '';

		if ( '' === $base_url || '' === $base_path ) {
			self::redirect_media_with_result( array( 'error' => __( 'Complete the base URL and server path.', 'hy-homes-syd-panther' ) ) );
		}

		if ( ! wp_mkdir_p( $base_path ) || ! is_dir( $base_path ) ) {
			self::redirect_media_with_result( array( 'error' => __( 'The server path could not be created or found.', 'hy-homes-syd-panther' ) ) );
		}

		if ( ! wp_is_writable( $base_path ) ) {
			self::redirect_media_with_result( array( 'error' => __( 'The server path is not writable.', 'hy-homes-syd-panther' ) ) );
		}

		update_option( self::MEDIA_BASE_URL_OPTION, trailingslashit( $base_url ) );
		update_option( self::MEDIA_BASE_PATH_OPTION, untrailingslashit( $base_path ) );
		update_option( self::MEDIA_FFMPEG_PATH_OPTION, $ffmpeg_path );

		self::redirect_media_with_result( array( 'message' => __( 'External media settings saved.', 'hy-homes-syd-panther' ) ) );
	}

	/**
	 * Handle ZIP upload to the external media directory.
	 */
	public static function handle_media_upload() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to upload HY Homes media.', 'hy-homes-syd-panther' ) );
		}

		check_admin_referer( self::MEDIA_UPLOAD_ACTION, 'hy_homes_media_upload_nonce' );

		$settings = self::get_media_settings();

		if ( '' === $settings['base_url'] || '' === $settings['base_path'] ) {
			self::redirect_media_with_result( array( 'error' => __( 'Save the external media settings before uploading a ZIP.', 'hy-homes-syd-panther' ) ) );
		}

		if ( ! class_exists( 'ZipArchive' ) ) {
			self::redirect_media_with_result( array( 'error' => __( 'This server cannot extract ZIP files because ZipArchive is not enabled.', 'hy-homes-syd-panther' ) ) );
		}

		if ( ! isset( $_FILES['hy_homes_media_zip'] ) || UPLOAD_ERR_NO_FILE === (int) $_FILES['hy_homes_media_zip']['error'] ) {
			self::redirect_media_with_result( array( 'error' => __( 'Select a ZIP file to upload.', 'hy-homes-syd-panther' ) ) );
		}

		$file = $_FILES['hy_homes_media_zip'];

		if ( ! isset( $file['error'] ) || UPLOAD_ERR_OK !== (int) $file['error'] ) {
			self::redirect_media_with_result( array( 'error' => __( 'The uploaded ZIP could not be read.', 'hy-homes-syd-panther' ) ) );
		}

		$name      = isset( $file['name'] ) ? sanitize_file_name( wp_unslash( $file['name'] ) ) : '';
		$tmp_name  = isset( $file['tmp_name'] ) ? (string) $file['tmp_name'] : '';
		$extension = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

		if ( 'zip' !== $extension || ! is_uploaded_file( $tmp_name ) ) {
			self::redirect_media_with_result( array( 'error' => __( 'Upload a valid .zip file.', 'hy-homes-syd-panther' ) ) );
		}

		$folder = isset( $_POST['hy_homes_media_folder'] ) ? self::normalize_media_subdir( wp_unslash( $_POST['hy_homes_media_folder'] ) ) : '';

		if ( '' === $folder ) {
			$folder = self::normalize_media_subdir( pathinfo( $name, PATHINFO_FILENAME ) );
		}

		if ( '' === $folder ) {
			$folder = 'media-' . gmdate( 'Ymd-His' );
		}

		$options = array(
			'convert_images' => ! empty( $_POST['hy_homes_media_convert_images'] ),
			'convert_videos' => ! empty( $_POST['hy_homes_media_convert_videos'] ),
			'ffmpeg_path'    => $settings['ffmpeg_path'],
		);

		$result = self::extract_media_zip( $tmp_name, $settings['base_path'], $settings['base_url'], $folder, $options );

		if ( is_wp_error( $result ) ) {
			self::redirect_media_with_result( array( 'error' => $result->get_error_message() ) );
		}

		$result['message'] = sprintf(
			/* translators: %d: generated files. */
			__( 'ZIP extracted successfully. Generated URLs: %d.', 'hy-homes-syd-panther' ),
			count( $result['urls'] )
		);

		self::redirect_media_with_result( $result );
	}

	/**
	 * Get saved external media settings.
	 *
	 * @return array{base_url:string,base_path:string,ffmpeg_path:string}
	 */
	private static function get_media_settings() {
		return array(
			'base_url'    => esc_url_raw( (string) get_option( self::MEDIA_BASE_URL_OPTION, '' ) ),
			'base_path'   => self::normalize_server_path( (string) get_option( self::MEDIA_BASE_PATH_OPTION, '' ) ),
			'ffmpeg_path' => self::normalize_command_path( (string) get_option( self::MEDIA_FFMPEG_PATH_OPTION, '' ) ),
		);
	}

	/**
	 * Get the external media result for the current admin user.
	 *
	 * @return array<string,mixed>
	 */
	private static function get_media_result() {
		$key    = self::MEDIA_RESULT_TRANSIENT . get_current_user_id();
		$result = get_transient( $key );

		if ( false === $result || ! is_array( $result ) ) {
			return array();
		}

		delete_transient( $key );

		if ( ! empty( $result['error'] ) && empty( $result['message'] ) ) {
			$result['message'] = wp_strip_all_tags( (string) $result['error'] );
		}

		return $result;
	}

	/**
	 * Redirect back to the external media page with a result notice.
	 *
	 * @param array<string,mixed> $result Upload result.
	 */
	private static function redirect_media_with_result( $result ) {
		if ( ! empty( $result['error'] ) && empty( $result['message'] ) ) {
			$result['message'] = wp_strip_all_tags( (string) $result['error'] );
		}

		set_transient( self::MEDIA_RESULT_TRANSIENT . get_current_user_id(), $result, 10 * MINUTE_IN_SECONDS );
		wp_safe_redirect( admin_url( 'admin.php?page=' . self::MEDIA_SLUG ) );
		exit;
	}

	/**
	 * Normalize a server path from the settings form.
	 *
	 * @param string $path Server path.
	 * @return string
	 */
	private static function normalize_server_path( $path ) {
		return untrailingslashit( wp_normalize_path( sanitize_text_field( (string) $path ) ) );
	}

	/**
	 * Normalize an executable path or command name.
	 *
	 * @param string $path Command path.
	 * @return string
	 */
	private static function normalize_command_path( $path ) {
		return trim( sanitize_text_field( wp_normalize_path( (string) $path ) ) );
	}

	/**
	 * Normalize a user-provided media folder path.
	 *
	 * @param string $path Folder path.
	 * @return string
	 */
	private static function normalize_media_subdir( $path ) {
		$path  = str_replace( '\\', '/', sanitize_text_field( (string) $path ) );
		$parts = array_filter( array_map( 'trim', explode( '/', $path ) ) );
		$clean = array();

		foreach ( $parts as $part ) {
			if ( '.' === $part || '..' === $part ) {
				continue;
			}

			$part = sanitize_title( $part );

			if ( '' !== $part ) {
				$clean[] = $part;
			}
		}

		return implode( '/', $clean );
	}

	/**
	 * Extract a ZIP into the configured external media path.
	 *
	 * @param string              $zip_path  Uploaded ZIP temp path.
	 * @param string              $base_path External media server path.
	 * @param string              $base_url  External media public URL.
	 * @param string              $folder  Destination folder.
	 * @param array<string,mixed> $options Upload processing options.
	 * @return array{urls:array<int,string>,skipped:int,converted_images:int,converted_videos:int,conversion_failed:int}|WP_Error
	 */
	private static function extract_media_zip( $zip_path, $base_path, $base_url, $folder, $options = array() ) {
		$base_path = self::normalize_server_path( $base_path );
		$base_url  = trailingslashit( esc_url_raw( $base_url ) );
		$folder    = self::normalize_media_subdir( $folder );
		$options   = wp_parse_args(
			$options,
			array(
				'convert_images' => false,
				'convert_videos' => false,
				'ffmpeg_path'    => '',
			)
		);

		if ( '' === $base_path || '' === $base_url || '' === $folder ) {
			return new WP_Error( 'hy_homes_media_settings_missing', __( 'External media settings or destination folder are missing.', 'hy-homes-syd-panther' ) );
		}

		if ( ! wp_mkdir_p( $base_path ) || ! is_dir( $base_path ) || ! wp_is_writable( $base_path ) ) {
			return new WP_Error( 'hy_homes_media_base_unwritable', __( 'The configured external media folder is not writable.', 'hy-homes-syd-panther' ) );
		}

		$target_dir = trailingslashit( $base_path ) . $folder;

		if ( ! wp_mkdir_p( $target_dir ) || ! is_dir( $target_dir ) || ! self::path_inside_directory( $target_dir, $base_path ) ) {
			return new WP_Error( 'hy_homes_media_target_invalid', __( 'The destination folder could not be created safely.', 'hy-homes-syd-panther' ) );
		}

		$zip    = new ZipArchive();
		$opened = $zip->open( $zip_path );

		if ( true !== $opened ) {
			return new WP_Error( 'hy_homes_media_zip_invalid', __( 'The ZIP file could not be opened.', 'hy-homes-syd-panther' ) );
		}

		$urls              = array();
		$skipped           = 0;
		$converted_images  = 0;
		$converted_videos  = 0;
		$conversion_failed = 0;

		for ( $index = 0; $index < $zip->numFiles; $index++ ) {
			$entry = (string) $zip->getNameIndex( $index );

			if ( '' === $entry || '/' === substr( str_replace( '\\', '/', $entry ), -1 ) ) {
				continue;
			}

			$relative = self::sanitize_zip_entry_name( $entry );

			if ( '' === $relative || ! self::is_allowed_media_file( $relative ) ) {
				$skipped++;
				continue;
			}

			$destination     = trailingslashit( $target_dir ) . $relative;
			$destination_dir = dirname( $destination );

			if ( ! wp_mkdir_p( $destination_dir ) || ! self::path_inside_directory( $destination, $target_dir ) ) {
				$skipped++;
				continue;
			}

			$source = $zip->getStream( $entry );

			if ( false === $source ) {
				$skipped++;
				continue;
			}

			$target = @fopen( $destination, 'wb' );

			if ( false === $target ) {
				fclose( $source );
				$skipped++;
				continue;
			}

			$copied = stream_copy_to_stream( $source, $target );
			fclose( $source );
			fclose( $target );

			if ( false === $copied ) {
				@unlink( $destination );
				$skipped++;
				continue;
			}

			$final_relative = $relative;

			if ( ! empty( $options['convert_images'] ) && self::is_image_media_file( $relative ) && 'avif' !== strtolower( pathinfo( $relative, PATHINFO_EXTENSION ) ) ) {
				$converted = self::convert_image_to_avif( $destination );

				if ( is_wp_error( $converted ) ) {
					$conversion_failed++;
				} else {
					@unlink( $destination );
					$final_relative = self::replace_media_extension( $relative, 'avif' );
					$converted_images++;
				}
			} elseif ( ! empty( $options['convert_videos'] ) && self::is_video_media_file( $relative ) && 'webm' !== strtolower( pathinfo( $relative, PATHINFO_EXTENSION ) ) ) {
				$converted = self::convert_video_to_webm( $destination, (string) $options['ffmpeg_path'] );

				if ( is_wp_error( $converted ) ) {
					$conversion_failed++;
				} else {
					@unlink( $destination );
					$final_relative = self::replace_media_extension( $relative, 'webm' );
					$converted_videos++;
				}
			}

			$urls[] = self::media_relative_to_url( trailingslashit( $folder ) . $final_relative, $base_url );
		}

		$zip->close();

		if ( empty( $urls ) ) {
			return new WP_Error( 'hy_homes_media_zip_empty', __( 'No valid media files were found inside the ZIP.', 'hy-homes-syd-panther' ) );
		}

		return array(
			'urls'              => $urls,
			'skipped'           => $skipped,
			'converted_images'  => $converted_images,
			'converted_videos'  => $converted_videos,
			'conversion_failed' => $conversion_failed,
		);
	}

	/**
	 * Sanitize one ZIP entry path while preserving safe subfolders.
	 *
	 * @param string $entry ZIP entry name.
	 * @return string
	 */
	private static function sanitize_zip_entry_name( $entry ) {
		$entry = str_replace( '\\', '/', (string) $entry );
		$parts = array_filter( array_map( 'trim', explode( '/', $entry ) ) );
		$clean = array();

		foreach ( $parts as $part ) {
			if ( '.' === $part || '..' === $part ) {
				return '';
			}

			$part = sanitize_file_name( $part );

			if ( '' !== $part ) {
				$clean[] = $part;
			}
		}

		return implode( '/', $clean );
	}

	/**
	 * Check if a media filename is allowed for public use.
	 *
	 * @param string $path Relative media path.
	 * @return bool
	 */
	private static function is_allowed_media_file( $path ) {
		return self::is_image_media_file( $path ) || self::is_video_media_file( $path );
	}

	/**
	 * Check if a media filename is an allowed image.
	 *
	 * @param string $path Relative media path.
	 * @return bool
	 */
	private static function is_image_media_file( $path ) {
		$extension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );

		return in_array( $extension, array( 'jpg', 'jpeg', 'png', 'webp', 'gif', 'avif', 'heic', 'heif' ), true );
	}

	/**
	 * Check if a media filename is an allowed video.
	 *
	 * @param string $path Relative media path.
	 * @return bool
	 */
	private static function is_video_media_file( $path ) {
		$extension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );

		return in_array( $extension, array( 'mp4', 'webm', 'ogg', 'mov', 'm4v' ), true );
	}

	/**
	 * Convert an image to AVIF when the server supports it.
	 *
	 * @param string $source_path Image path.
	 * @return string|WP_Error
	 */
	private static function convert_image_to_avif( $source_path ) {
		if ( ! function_exists( 'wp_get_image_editor' ) && defined( 'ABSPATH' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		if ( ! function_exists( 'wp_get_image_editor' ) ) {
			return new WP_Error( 'hy_homes_media_image_editor_missing', __( 'WordPress image editor is not available.', 'hy-homes-syd-panther' ) );
		}

		$destination = self::replace_media_extension( $source_path, 'avif' );
		$editor      = wp_get_image_editor( $source_path );

		if ( is_wp_error( $editor ) ) {
			return $editor;
		}

		if ( method_exists( $editor, 'set_quality' ) ) {
			$editor->set_quality( 72 );
		}

		$saved = $editor->save( $destination, 'image/avif' );

		if ( is_wp_error( $saved ) || ! file_exists( $destination ) || 0 === filesize( $destination ) ) {
			if ( file_exists( $destination ) ) {
				@unlink( $destination );
			}

			return is_wp_error( $saved ) ? $saved : new WP_Error( 'hy_homes_media_avif_failed', __( 'The image could not be converted to AVIF.', 'hy-homes-syd-panther' ) );
		}

		return $destination;
	}

	/**
	 * Convert a video to WebM when FFmpeg is available.
	 *
	 * @param string $source_path Video path.
	 * @param string $ffmpeg_path Optional FFmpeg path.
	 * @return string|WP_Error
	 */
	private static function convert_video_to_webm( $source_path, $ffmpeg_path = '' ) {
		if ( ! function_exists( 'exec' ) ) {
			return new WP_Error( 'hy_homes_media_exec_disabled', __( 'PHP exec is disabled, so videos cannot be converted.', 'hy-homes-syd-panther' ) );
		}

		$ffmpeg      = self::resolve_ffmpeg_path( $ffmpeg_path );
		$destination = self::replace_media_extension( $source_path, 'webm' );
		$command     = escapeshellarg( $ffmpeg ) . ' -y -i ' . escapeshellarg( $source_path ) . ' -c:v libvpx-vp9 -b:v 0 -crf 32 -c:a libopus ' . escapeshellarg( $destination ) . ' 2>&1';
		$output      = array();
		$exit_code   = 0;

		exec( $command, $output, $exit_code );

		if ( 0 !== $exit_code || ! file_exists( $destination ) || 0 === filesize( $destination ) ) {
			if ( file_exists( $destination ) ) {
				@unlink( $destination );
			}

			return new WP_Error( 'hy_homes_media_webm_failed', __( 'The video could not be converted to WebM. Check the FFmpeg path and server permissions.', 'hy-homes-syd-panther' ) );
		}

		return $destination;
	}

	/**
	 * Resolve the configured FFmpeg command.
	 *
	 * @param string $ffmpeg_path Optional command path.
	 * @return string
	 */
	private static function resolve_ffmpeg_path( $ffmpeg_path ) {
		$ffmpeg_path = self::normalize_command_path( $ffmpeg_path );

		return '' !== $ffmpeg_path ? $ffmpeg_path : 'ffmpeg';
	}

	/**
	 * Replace a path extension.
	 *
	 * @param string $path      File path.
	 * @param string $extension New extension without dot.
	 * @return string
	 */
	private static function replace_media_extension( $path, $extension ) {
		$path_info = pathinfo( $path );
		$dirname   = isset( $path_info['dirname'] ) && '.' !== $path_info['dirname'] ? trailingslashit( $path_info['dirname'] ) : '';
		$filename  = isset( $path_info['filename'] ) ? $path_info['filename'] : sanitize_file_name( (string) $path );

		return $dirname . $filename . '.' . ltrim( strtolower( $extension ), '.' );
	}

	/**
	 * Make sure a path resolves inside a base directory.
	 *
	 * @param string $path Path to check.
	 * @param string $base Base directory.
	 * @return bool
	 */
	private static function path_inside_directory( $path, $base ) {
		$base_real = realpath( $base );

		if ( false === $base_real ) {
			return false;
		}

		$path_real = file_exists( $path ) ? realpath( $path ) : realpath( dirname( $path ) );

		if ( false === $path_real ) {
			return false;
		}

		$base_real = untrailingslashit( wp_normalize_path( $base_real ) );
		$path_real = untrailingslashit( wp_normalize_path( $path_real ) );

		return $path_real === $base_real || 0 === strpos( trailingslashit( $path_real ), trailingslashit( $base_real ) );
	}

	/**
	 * Convert a relative media path to its public URL.
	 *
	 * @param string $relative Relative media path.
	 * @param string $base_url External media public URL.
	 * @return string
	 */
	private static function media_relative_to_url( $relative, $base_url ) {
		$parts = array_map( 'rawurlencode', explode( '/', str_replace( '\\', '/', $relative ) ) );

		return trailingslashit( $base_url ) . implode( '/', $parts );
	}

	/**
	 * Handle spreadsheet import submission.
	 */
	public static function handle_import() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You do not have permission to import HY Homes content.', 'hy-homes-syd-panther' ) );
		}

		check_admin_referer( self::IMPORT_ACTION, 'hy_homes_import_nonce' );

		$status = isset( $_POST['hy_homes_import_status'] ) ? sanitize_key( wp_unslash( $_POST['hy_homes_import_status'] ) ) : 'publish';
		$status = in_array( $status, array( 'publish', 'draft' ), true ) ? $status : 'publish';
		$url    = isset( $_POST['hy_homes_google_sheet_url'] ) ? esc_url_raw( wp_unslash( $_POST['hy_homes_google_sheet_url'] ) ) : '';

		if ( '' !== $url ) {
			$rows = self::load_rows_from_google_sheet( $url );
		} elseif ( isset( $_FILES['hy_homes_import_file'] ) && UPLOAD_ERR_NO_FILE !== (int) $_FILES['hy_homes_import_file']['error'] ) {
			$rows = self::load_rows_from_upload( $_FILES['hy_homes_import_file'] );
		} else {
			self::redirect_with_result( array( 'error' => __( 'Upload a CSV/XLSX file or paste a Google Sheets URL.', 'hy-homes-syd-panther' ) ) );
		}

		if ( is_wp_error( $rows ) ) {
			self::redirect_with_result( array( 'error' => $rows->get_error_message() ) );
		}

		$result = self::import_rows( $rows, $status );
		self::redirect_with_result( $result );
	}

	/**
	 * Return an import status message from query args.
	 *
	 * @return string
	 */
	private static function get_import_notice() {
		if ( isset( $_GET['hy_import_error'] ) ) {
			return sanitize_text_field( rawurldecode( wp_unslash( $_GET['hy_import_error'] ) ) );
		}

		if ( isset( $_GET['hy_created'], $_GET['hy_updated'], $_GET['hy_deleted'], $_GET['hy_skipped'] ) ) {
			return sprintf(
				/* translators: 1: created rows, 2: updated rows, 3: deleted rows, 4: skipped rows */
				__( 'Import finished. Created: %1$d. Updated: %2$d. Deleted: %3$d. Skipped: %4$d.', 'hy-homes-syd-panther' ),
				absint( $_GET['hy_created'] ),
				absint( $_GET['hy_updated'] ),
				absint( $_GET['hy_deleted'] ),
				absint( $_GET['hy_skipped'] )
			);
		}

		return '';
	}

	/**
	 * Redirect back to the import page.
	 *
	 * @param array<string,mixed> $result Import result.
	 */
	private static function redirect_with_result( $result ) {
		$args = array();

		if ( ! empty( $result['error'] ) ) {
			$args['hy_import_error'] = wp_strip_all_tags( (string) $result['error'] );
		} else {
			$args['hy_created'] = isset( $result['created'] ) ? absint( $result['created'] ) : 0;
			$args['hy_updated'] = isset( $result['updated'] ) ? absint( $result['updated'] ) : 0;
			$args['hy_deleted'] = isset( $result['deleted'] ) ? absint( $result['deleted'] ) : 0;
			$args['hy_skipped'] = isset( $result['skipped'] ) ? absint( $result['skipped'] ) : 0;
		}

		wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php?page=' . self::IMPORT_SLUG ) ) );
		exit;
	}

	/**
	 * Load rows from an uploaded CSV/XLSX file.
	 *
	 * @param array<string,mixed> $file Uploaded file data.
	 * @return array<int,array<string,string>>|WP_Error
	 */
	private static function load_rows_from_upload( $file ) {
		if ( ! isset( $file['error'] ) || UPLOAD_ERR_OK !== (int) $file['error'] ) {
			return new WP_Error( 'hy_import_upload', __( 'The uploaded file could not be read.', 'hy-homes-syd-panther' ) );
		}

		$name      = isset( $file['name'] ) ? sanitize_file_name( wp_unslash( $file['name'] ) ) : '';
		$tmp_name  = isset( $file['tmp_name'] ) ? (string) $file['tmp_name'] : '';
		$extension = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

		if ( ! is_uploaded_file( $tmp_name ) ) {
			return new WP_Error( 'hy_import_upload', __( 'The uploaded file is invalid.', 'hy-homes-syd-panther' ) );
		}

		if ( 'csv' === $extension ) {
			$content = file_get_contents( $tmp_name );
			return false === $content ? new WP_Error( 'hy_import_csv', __( 'The CSV file could not be opened.', 'hy-homes-syd-panther' ) ) : self::parse_csv_string( $content );
		}

		if ( 'xlsx' === $extension ) {
			return self::parse_xlsx_file( $tmp_name );
		}

		return new WP_Error( 'hy_import_type', __( 'Use a .csv or .xlsx file.', 'hy-homes-syd-panther' ) );
	}

	/**
	 * Load rows from a Google Sheet URL.
	 *
	 * @param string $url Google Sheets or CSV URL.
	 * @return array<int,array<string,string>>|WP_Error
	 */
	private static function load_rows_from_google_sheet( $url ) {
		$csv_url  = self::normalize_google_sheet_url( $url );
		$response = wp_remote_get(
			$csv_url,
			array(
				'timeout' => 20,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== (int) $code ) {
			return new WP_Error( 'hy_import_google', __( 'Google Sheets did not return a readable CSV file. Publish the sheet or use File > Share > Publish to web.', 'hy-homes-syd-panther' ) );
		}

		return self::parse_csv_string( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * Convert normal Google Sheets links into CSV export links.
	 *
	 * @param string $url User URL.
	 * @return string
	 */
	private static function normalize_google_sheet_url( $url ) {
		if ( preg_match( '#docs\.google\.com/spreadsheets/d/([^/]+)#', $url, $matches ) ) {
			$gid = '0';
			$parts = wp_parse_url( $url );

			if ( ! empty( $parts['query'] ) ) {
				parse_str( $parts['query'], $query_args );

				if ( ! empty( $query_args['gid'] ) ) {
					$gid = preg_replace( '/[^0-9]/', '', (string) $query_args['gid'] );
				}
			}

			if ( preg_match( '/[#&]gid=([0-9]+)/', $url, $gid_matches ) ) {
				$gid = $gid_matches[1];
			}

			return 'https://docs.google.com/spreadsheets/d/' . rawurlencode( $matches[1] ) . '/export?format=csv&gid=' . rawurlencode( $gid );
		}

		return $url;
	}

	/**
	 * Parse CSV content.
	 *
	 * @param string $content CSV content.
	 * @return array<int,array<string,string>>|WP_Error
	 */
	private static function parse_csv_string( $content ) {
		$content = preg_replace( '/^\xEF\xBB\xBF/', '', (string) $content );
		$delimiter = self::detect_csv_delimiter( $content );
		$handle = fopen( 'php://temp', 'r+' );

		if ( false === $handle ) {
			return new WP_Error( 'hy_import_csv', __( 'The CSV data could not be parsed.', 'hy-homes-syd-panther' ) );
		}

		fwrite( $handle, $content );
		rewind( $handle );

		$matrix = array();

		while ( false !== ( $row = fgetcsv( $handle, 0, $delimiter ) ) ) {
			$matrix[] = $row;
		}

		fclose( $handle );

		return self::rows_from_matrix( $matrix );
	}

	/**
	 * Detect CSV delimiter from the first line.
	 *
	 * @param string $content CSV content.
	 * @return string
	 */
	private static function detect_csv_delimiter( $content ) {
		$first_line = strtok( (string) $content, "\r\n" );
		$counts = array(
			','  => substr_count( (string) $first_line, ',' ),
			';'  => substr_count( (string) $first_line, ';' ),
			"\t" => substr_count( (string) $first_line, "\t" ),
		);

		arsort( $counts );
		$delimiter = key( $counts );

		return $delimiter ? $delimiter : ',';
	}

	/**
	 * Parse basic XLSX files.
	 *
	 * @param string $path Uploaded file path.
	 * @return array<int,array<string,string>>|WP_Error
	 */
	private static function parse_xlsx_file( $path ) {
		if ( ! class_exists( 'ZipArchive' ) || ! function_exists( 'simplexml_load_string' ) ) {
			return new WP_Error( 'hy_import_xlsx_support', __( 'This server cannot read XLSX files directly. Export the spreadsheet as CSV and import that file.', 'hy-homes-syd-panther' ) );
		}

		$zip = new ZipArchive();

		if ( true !== $zip->open( $path ) ) {
			return new WP_Error( 'hy_import_xlsx', __( 'The XLSX file could not be opened.', 'hy-homes-syd-panther' ) );
		}

		$shared_strings = self::get_xlsx_shared_strings( $zip );
		$sheet_xml      = $zip->getFromName( 'xl/worksheets/sheet1.xml' );
		$zip->close();

		if ( false === $sheet_xml ) {
			return new WP_Error( 'hy_import_xlsx', __( 'The first worksheet could not be found.', 'hy-homes-syd-panther' ) );
		}

		$sheet = simplexml_load_string( $sheet_xml );

		if ( false === $sheet ) {
			return new WP_Error( 'hy_import_xlsx', __( 'The worksheet could not be parsed.', 'hy-homes-syd-panther' ) );
		}

		$matrix = array();

		foreach ( $sheet->sheetData->row as $row ) {
			$row_data = array();

			foreach ( $row->c as $cell ) {
				$reference = isset( $cell['r'] ) ? (string) $cell['r'] : '';
				$index     = self::xlsx_column_index( $reference );

				if ( 0 <= $index ) {
					$row_data[ $index ] = self::xlsx_cell_value( $cell, $shared_strings );
				}
			}

			if ( ! empty( $row_data ) ) {
				ksort( $row_data );
				$matrix[] = $row_data;
			}
		}

		return self::rows_from_matrix( $matrix );
	}

	/**
	 * Return XLSX shared strings.
	 *
	 * @param ZipArchive $zip XLSX archive.
	 * @return array<int,string>
	 */
	private static function get_xlsx_shared_strings( $zip ) {
		$strings = array();
		$xml     = $zip->getFromName( 'xl/sharedStrings.xml' );

		if ( false === $xml ) {
			return $strings;
		}

		$shared = simplexml_load_string( $xml );

		if ( false === $shared ) {
			return $strings;
		}

		foreach ( $shared->si as $item ) {
			if ( isset( $item->t ) ) {
				$strings[] = (string) $item->t;
				continue;
			}

			$text = '';

			foreach ( $item->r as $run ) {
				$text .= isset( $run->t ) ? (string) $run->t : '';
			}

			$strings[] = $text;
		}

		return $strings;
	}

	/**
	 * Return an XLSX cell value.
	 *
	 * @param SimpleXMLElement $cell Cell node.
	 * @param array<int,string> $shared_strings Shared strings.
	 * @return string
	 */
	private static function xlsx_cell_value( $cell, $shared_strings ) {
		$type = isset( $cell['t'] ) ? (string) $cell['t'] : '';

		if ( 's' === $type ) {
			$index = isset( $cell->v ) ? absint( (string) $cell->v ) : 0;
			return isset( $shared_strings[ $index ] ) ? $shared_strings[ $index ] : '';
		}

		if ( 'inlineStr' === $type && isset( $cell->is->t ) ) {
			return (string) $cell->is->t;
		}

		return isset( $cell->v ) ? (string) $cell->v : '';
	}

	/**
	 * Convert XLSX cell reference to zero-based column index.
	 *
	 * @param string $reference Cell reference.
	 * @return int
	 */
	private static function xlsx_column_index( $reference ) {
		$letters = preg_replace( '/[^A-Z]/', '', strtoupper( $reference ) );

		if ( '' === $letters ) {
			return -1;
		}

		$index = 0;
		$length = strlen( $letters );

		for ( $i = 0; $i < $length; $i++ ) {
			$index = ( $index * 26 ) + ( ord( $letters[ $i ] ) - 64 );
		}

		return $index - 1;
	}

	/**
	 * Convert a row matrix to associative rows.
	 *
	 * @param array<int,array<int,string>> $matrix Raw rows.
	 * @return array<int,array<string,string>>|WP_Error
	 */
	private static function rows_from_matrix( $matrix ) {
		if ( empty( $matrix ) ) {
			return new WP_Error( 'hy_import_empty', __( 'The spreadsheet is empty.', 'hy-homes-syd-panther' ) );
		}

		$header_row = array_shift( $matrix );
		$headers    = array();

		foreach ( $header_row as $index => $header ) {
			$key = self::normalize_key( $header );

			if ( '' !== $key ) {
				$headers[ $index ] = $key;
			}
		}

		if ( empty( $headers ) ) {
			return new WP_Error( 'hy_import_headers', __( 'The spreadsheet needs a header row.', 'hy-homes-syd-panther' ) );
		}

		$rows = array();

		foreach ( $matrix as $matrix_row ) {
			$row       = array();
			$has_value = false;

			foreach ( $headers as $index => $key ) {
				$value = isset( $matrix_row[ $index ] ) ? trim( (string) $matrix_row[ $index ] ) : '';
				$row[ $key ] = $value;

				if ( '' !== $value ) {
					$has_value = true;
				}
			}

			if ( $has_value ) {
				$rows[] = $row;
			}
		}

		if ( empty( $rows ) ) {
			return new WP_Error( 'hy_import_empty_rows', __( 'No data rows were found.', 'hy-homes-syd-panther' ) );
		}

		return $rows;
	}

	/**
	 * Import normalized rows.
	 *
	 * @param array<int,array<string,string>> $rows Rows.
	 * @param string                          $default_status Default post status.
	 * @return array<string,mixed>
	 */
	private static function import_rows( $rows, $default_status ) {
		$result = array(
			'created' => 0,
			'updated' => 0,
			'deleted' => 0,
			'skipped' => 0,
		);

		foreach ( $rows as $row ) {
			$type = strtolower( self::row_value( $row, array( 'type', 'tipo', 'content_type' ) ) );
			$action = strtolower( self::row_value( $row, array( 'action', 'accion', 'operation', 'operacion' ) ) );
			$is_banner = in_array( $type, array( 'banner', 'banners', 'carrusel', 'carousel' ), true );

			if ( in_array( $action, array( 'delete', 'trash', 'eliminar', 'borrar' ), true ) ) {
				$status = self::delete_import_row( $row, $is_banner ? HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE : HY_Homes_Syd_Panther_Properties::POST_TYPE );
			} elseif ( $is_banner ) {
				$status = self::import_banner_row( $row, $default_status );
			} else {
				$status = self::import_property_row( $row, $default_status );
			}

			if ( isset( $result[ $status ] ) ) {
				$result[ $status ]++;
			} else {
				$result['skipped']++;
			}
		}

		return $result;
	}

	/**
	 * Move an imported row target to Trash.
	 *
	 * @param array<string,string> $row Row data.
	 * @param string               $post_type Post type.
	 * @return string deleted|skipped
	 */
	private static function delete_import_row( $row, $post_type ) {
		$post_id = self::resolve_existing_post_id( $row, $post_type );

		if ( ! $post_id || ! current_user_can( 'delete_post', $post_id ) ) {
			return 'skipped';
		}

		$deleted = wp_trash_post( $post_id );

		return $deleted ? 'deleted' : 'skipped';
	}

	/**
	 * Import one property row.
	 *
	 * @param array<string,string> $row Row data.
	 * @param string               $default_status Default post status.
	 * @return string created|updated|skipped
	 */
	private static function import_property_row( $row, $default_status ) {
		$title = self::row_value( $row, array( 'title', 'titulo', 'name', 'nombre' ) );

		if ( '' === $title ) {
			return 'skipped';
		}

		$existing_id = self::resolve_existing_post_id( $row, HY_Homes_Syd_Panther_Properties::POST_TYPE );
		$description = self::row_value( $row, array( 'content', 'description', 'descripcion', 'descripcion_propiedad' ) );
		$post_data   = array(
			'post_type'    => HY_Homes_Syd_Panther_Properties::POST_TYPE,
			'post_title'   => sanitize_text_field( $title ),
		);

		if ( ! $existing_id || '' !== $description ) {
			$post_data['post_content'] = wp_kses_post( self::normalize_multiline( $description ) );
		}

		if ( ! $existing_id || self::row_has_status( $row ) ) {
			$post_data['post_status'] = self::row_status( $row, $default_status );
		}

		$slug = self::row_value( $row, array( 'slug', 'post_name' ) );

		if ( '' !== $slug ) {
			$post_data['post_name'] = sanitize_title( $slug );
		}

		if ( $existing_id ) {
			$post_data['ID'] = $existing_id;
		}

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 'skipped';
		}

		$excerpt = self::row_value( $row, array( 'excerpt', 'resumen' ) );

		if ( '' !== $excerpt ) {
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_excerpt' => sanitize_textarea_field( $excerpt ),
				)
			);
		}

		self::assign_neighborhoods( $post_id, $row );
		self::update_property_meta_from_row( $post_id, $row );

		return $existing_id ? 'updated' : 'created';
	}

	/**
	 * Import one banner row.
	 *
	 * @param array<string,string> $row Row data.
	 * @param string               $default_status Default post status.
	 * @return string created|updated|skipped
	 */
	private static function import_banner_row( $row, $default_status ) {
		$title = self::row_value( $row, array( 'title', 'titulo', 'name', 'nombre' ) );

		if ( '' === $title ) {
			return 'skipped';
		}

		$existing_id = self::resolve_existing_post_id( $row, HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE );
		$order       = self::row_value( $row, array( 'order', 'orden', 'menu_order' ) );
		$post_data   = array(
			'post_type'   => HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE,
			'post_title'  => sanitize_text_field( $title ),
		);

		if ( ! $existing_id || self::row_has_status( $row ) ) {
			$post_data['post_status'] = self::row_status( $row, $default_status );
		}

		if ( ! $existing_id || '' !== $order ) {
			$post_data['menu_order'] = absint( $order );
		}

		$slug = self::row_value( $row, array( 'slug', 'post_name' ) );

		if ( '' !== $slug ) {
			$post_data['post_name'] = sanitize_title( $slug );
		}

		if ( $existing_id ) {
			$post_data['ID'] = $existing_id;
		}

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 'skipped';
		}

		self::assign_neighborhoods( $post_id, $row );
		self::update_text_meta( $post_id, HY_Homes_Syd_Panther_Properties::BANNER_META_PREFIX . 'description', self::row_value( $row, array( 'description', 'descripcion', 'content' ) ), true );
		self::update_text_meta( $post_id, HY_Homes_Syd_Panther_Properties::BANNER_META_PREFIX . 'image_url', self::row_value( $row, array( 'image_url', 'banner_image', 'imagen', 'url_imagen', 'featured_image_url' ) ), false, true );
		self::update_text_meta( $post_id, HY_Homes_Syd_Panther_Properties::BANNER_META_PREFIX . 'button_url', self::row_value( $row, array( 'button_url', 'url_boton', 'boton_url', 'link' ) ), false, true );

		return $existing_id ? 'updated' : 'created';
	}

	/**
	 * Update property meta from a row.
	 *
	 * @param int                  $post_id Post ID.
	 * @param array<string,string> $row Row data.
	 */
	private static function update_property_meta_from_row( $post_id, $row ) {
		$availability_date = HY_Homes_Syd_Panther_Properties::normalize_availability_date(
			self::row_value(
				$row,
				array(
					'availability_date',
					'available_date',
					'available_from',
					'fecha_disponible',
					'fecha_disponibilidad',
					'disponible_desde',
					'fecha_ingreso',
					'move_in_date',
				)
			)
		);
		$availability      = self::row_value( $row, array( 'availability', 'disponibilidad' ) );

		if ( '' !== $availability_date ) {
			update_post_meta( $post_id, HY_Homes_Syd_Panther_Properties::META_PREFIX . 'availability_date', $availability_date );
			update_post_meta( $post_id, HY_Homes_Syd_Panther_Properties::META_PREFIX . 'status', HY_Homes_Syd_Panther_Properties::availability_date_to_status_label( $availability_date ) );
			update_post_meta( $post_id, HY_Homes_Syd_Panther_Properties::META_PREFIX . 'move_in', HY_Homes_Syd_Panther_Properties::availability_date_to_filter_value( $availability_date ) );
		} elseif ( '' !== $availability ) {
			update_post_meta( $post_id, HY_Homes_Syd_Panther_Properties::META_PREFIX . 'status', self::availability_to_status_label( $availability ) );
			update_post_meta( $post_id, HY_Homes_Syd_Panther_Properties::META_PREFIX . 'move_in', self::availability_to_filter_value( $availability ) );
		}

		$map = array(
			'room_type'          => array( 'room_type', 'rooms', 'habitaciones', 'tipo_habitacion' ),
			'bedrooms'           => array( 'bedrooms', 'dormitorios', 'camas' ),
			'bathrooms'          => array( 'bathrooms', 'banos', 'duchas' ),
			'street'             => array( 'street', 'calle' ),
			'address'            => array( 'address', 'direccion', 'full_address' ),
			'price'              => array( 'price', 'precio' ),
			'price_suffix'       => array( 'price_suffix', 'sufijo_precio' ),
			'status'             => array( 'status', 'estado' ),
			'move_in'            => array( 'move_in', 'ingreso' ),
			'availability_date'  => array( 'availability_date', 'available_date', 'available_from', 'fecha_disponible', 'fecha_disponibilidad', 'disponible_desde', 'fecha_ingreso', 'move_in_date' ),
			'detail_url'         => array( 'detail_url', 'url_detalle' ),
			'featured_image_url' => array( 'featured_image_url', 'image_url', 'card_image', 'imagen_principal', 'imagen' ),
			'gallery_media'      => array( 'gallery_media', 'gallery', 'galeria', 'media_urls', 'multimedia' ),
			'map_embed_url'      => array( 'map_embed_url', 'mapa', 'google_map' ),
			'whatsapp_phone'     => array( 'whatsapp_phone', 'whatsapp', 'telefono_whatsapp' ),
			'location_banners'   => array( 'location_banners', 'banners_localidad' ),
		);

		foreach ( $map as $field => $aliases ) {
			if ( '' !== $availability_date && in_array( $field, array( 'status', 'move_in' ), true ) ) {
				continue;
			}

			$value = self::row_value( $row, $aliases );

			if ( '' === $value ) {
				continue;
			}

			$meta_key = HY_Homes_Syd_Panther_Properties::META_PREFIX . $field;

			if ( 'availability_date' === $field ) {
				update_post_meta( $post_id, $meta_key, HY_Homes_Syd_Panther_Properties::normalize_availability_date( $value ) );
			} elseif ( in_array( $field, array( 'room_type', 'bedrooms', 'bathrooms' ), true ) ) {
				update_post_meta( $post_id, $meta_key, absint( $value ) );
			} elseif ( in_array( $field, array( 'detail_url', 'featured_image_url', 'map_embed_url' ), true ) ) {
				update_post_meta( $post_id, $meta_key, esc_url_raw( $value ) );
			} elseif ( in_array( $field, array( 'gallery_media', 'location_banners' ), true ) ) {
				update_post_meta( $post_id, $meta_key, sanitize_textarea_field( self::normalize_multiline( $value ) ) );
			} else {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $value ) );
			}
		}
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
	 * Assign localities/neighborhoods from a row.
	 *
	 * @param int                  $post_id Post ID.
	 * @param array<string,string> $row Row data.
	 */
	private static function assign_neighborhoods( $post_id, $row ) {
		$raw = self::row_value( $row, array( 'neighborhood', 'neighborhoods', 'locality', 'localidad', 'localidades', 'barrio' ) );

		if ( '' === $raw ) {
			return;
		}

		$terms = array_filter( array_map( 'trim', preg_split( '/[|;,]+/', $raw ) ) );

		if ( ! empty( $terms ) ) {
			wp_set_object_terms( $post_id, $terms, HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD );
		}
	}

	/**
	 * Update text/url meta only when there is a value.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $meta_key Meta key.
	 * @param string $value Raw value.
	 * @param bool   $textarea Whether value is multiline text.
	 * @param bool   $url Whether value is a URL.
	 */
	private static function update_text_meta( $post_id, $meta_key, $value, $textarea = false, $url = false ) {
		if ( '' === $value ) {
			return;
		}

		if ( $url ) {
			$value = esc_url_raw( $value );
		} elseif ( $textarea ) {
			$value = sanitize_textarea_field( self::normalize_multiline( $value ) );
		} else {
			$value = sanitize_text_field( $value );
		}

		update_post_meta( $post_id, $meta_key, $value );
	}

	/**
	 * Resolve an existing post by ID or slug.
	 *
	 * @param array<string,string> $row Row data.
	 * @param string               $post_type Post type.
	 * @return int
	 */
	private static function resolve_existing_post_id( $row, $post_type ) {
		$id = absint( self::row_value( $row, array( 'id', 'post_id', 'wp_id' ) ) );

		if ( $id && $post_type === get_post_type( $id ) ) {
			return $id;
		}

		$slug = self::row_value( $row, array( 'slug', 'post_name' ) );

		if ( '' !== $slug ) {
			$post = get_page_by_path( sanitize_title( $slug ), OBJECT, $post_type );

			if ( $post ) {
				return (int) $post->ID;
			}
		}

		return 0;
	}

	/**
	 * Return a row value by aliases.
	 *
	 * @param array<string,string> $row Row data.
	 * @param array<int,string>    $aliases Possible keys.
	 * @return string
	 */
	private static function row_value( $row, $aliases ) {
		foreach ( $aliases as $alias ) {
			$key = self::normalize_key( $alias );

			if ( isset( $row[ $key ] ) && '' !== trim( (string) $row[ $key ] ) ) {
				return trim( (string) $row[ $key ] );
			}
		}

		return '';
	}

	/**
	 * Return post status from row or default.
	 *
	 * @param array<string,string> $row Row data.
	 * @param string               $default_status Default status.
	 * @return string
	 */
	private static function row_status( $row, $default_status ) {
		$status = sanitize_key( self::row_value( $row, array( 'post_status', 'status_wp', 'estado_wp' ) ) );

		if ( in_array( $status, array( 'publish', 'draft', 'pending', 'private' ), true ) ) {
			return $status;
		}

		return $default_status;
	}

	/**
	 * Check whether a row includes an explicit WordPress status.
	 *
	 * @param array<string,string> $row Row data.
	 * @return bool
	 */
	private static function row_has_status( $row ) {
		return '' !== self::row_value( $row, array( 'post_status', 'status_wp', 'estado_wp' ) );
	}

	/**
	 * Normalize a spreadsheet header.
	 *
	 * @param string $key Header.
	 * @return string
	 */
	private static function normalize_key( $key ) {
		$key = strtolower( remove_accents( trim( (string) $key ) ) );
		$key = preg_replace( '/[^a-z0-9]+/', '_', $key );
		$key = trim( (string) $key, '_' );

		return $key;
	}

	/**
	 * Convert literal \n into real line breaks.
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	private static function normalize_multiline( $value ) {
		return str_replace( array( '\\r\\n', '\\n', '\\r' ), "\n", (string) $value );
	}
}
