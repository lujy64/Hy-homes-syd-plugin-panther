<?php
/**
 * Shared frontend renderers.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend markup builder.
 */
final class HY_Homes_Syd_Panther_Renderer {
	/**
	 * Render the property search filter.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public static function search_filter( $atts = array() ) {
		$defaults = array(
			'results_url'              => '',
			'neighborhood_label'       => 'NEIGHBORHOOD',
			'neighborhood_placeholder' => 'seleccionar',
			'neighborhood_source'      => 'auto',
			'neighborhood_options'     => 'auto',
			'room_label'               => 'ROOM TYPE',
			'room_placeholder'         => 'numero',
			'room_min'                 => '1',
			'room_max'                 => '',
			'move_in_label'            => 'MOVE-IN DATE, SEARCH',
			'move_in_placeholder'      => 'seleccionar',
			'move_in_options'          => 'Immediate|Next 2 weeks|Next month',
			'search_label'             => 'SEARCH PROPERTIES',
			'variant'                  => 'default',
			'modifier_class'           => '',
			'selected_neighborhood'    => '',
			'selected_rooms'           => '',
			'selected_move_in'         => '',
		);

		$atts = shortcode_atts( $defaults, (array) $atts, 'hy_homes_search_filter' );

		if ( 'auto' === sanitize_key( $atts['neighborhood_source'] ) ) {
			$atts['neighborhood_options'] = 'auto';
		}

		$atts['results_url'] = self::get_results_base_url( $atts['results_url'], false );

		$neighborhood_options = self::resolve_options( $atts['neighborhood_options'], 'neighborhood' );
		$move_in_options      = self::resolve_options( $atts['move_in_options'], 'move_in' );
		$selected_values      = self::get_selected_values();
		$selected_values      = self::merge_selected_values( $selected_values, $atts );
		$instance_id          = wp_unique_id( 'hy-homes-search-' );
		$variant              = sanitize_html_class( 'hy-homes-search--' . sanitize_key( $atts['variant'] ) );
		$modifier_class       = '' !== $atts['modifier_class'] ? ' ' . sanitize_html_class( $atts['modifier_class'] ) : '';
		$classes              = trim( 'hy-homes-search ' . $variant . $modifier_class );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" data-hy-homes-search>
			<form class="hy-homes-search__form" method="get" action="<?php echo esc_url( $atts['results_url'] ); ?>">
				<div class="hy-homes-search__field hy-homes-search__field--neighborhood">
					<label class="hy-homes-search__label" for="<?php echo esc_attr( $instance_id . '-neighborhood' ); ?>">
						<?php echo esc_html( $atts['neighborhood_label'] ); ?>
					</label>
					<div class="hy-homes-search__control hy-homes-search__select-wrap <?php echo '' !== $selected_values['neighborhood'] ? 'has-value' : ''; ?>">
						<select
							class="hy-homes-search__select"
							id="<?php echo esc_attr( $instance_id . '-neighborhood' ); ?>"
							name="hy_neighborhood"
							data-placeholder="<?php echo esc_attr( $atts['neighborhood_placeholder'] ); ?>"
						>
							<option value=""><?php echo esc_html( $atts['neighborhood_placeholder'] ); ?></option>
							<?php foreach ( $neighborhood_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $selected_values['neighborhood'], $option ); ?>>
									<?php echo esc_html( $option ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<button class="hy-homes-search__clear" type="button" data-hy-homes-clear="hy_neighborhood" aria-label="<?php esc_attr_e( 'Clear neighborhood', 'hy-homes-syd-panther' ); ?>">x</button>
					</div>
				</div>

				<span class="hy-homes-search__divider" aria-hidden="true"></span>

				<div class="hy-homes-search__field hy-homes-search__field--rooms">
					<label class="hy-homes-search__label" for="<?php echo esc_attr( $instance_id . '-rooms' ); ?>">
						<?php echo esc_html( $atts['room_label'] ); ?>
					</label>
					<div class="hy-homes-search__control hy-homes-search__number-wrap <?php echo '' !== $selected_values['rooms'] ? 'has-value' : ''; ?>">
						<input
							class="hy-homes-search__number"
							id="<?php echo esc_attr( $instance_id . '-rooms' ); ?>"
							type="number"
							name="hy_rooms"
							min="<?php echo esc_attr( $atts['room_min'] ); ?>"
							<?php echo '' !== $atts['room_max'] ? 'max="' . esc_attr( $atts['room_max'] ) . '"' : ''; ?>
							placeholder="<?php echo esc_attr( $atts['room_placeholder'] ); ?>"
							value="<?php echo esc_attr( $selected_values['rooms'] ); ?>"
							inputmode="numeric"
						/>
						<button class="hy-homes-search__clear" type="button" data-hy-homes-clear="hy_rooms" aria-label="<?php esc_attr_e( 'Clear room type', 'hy-homes-syd-panther' ); ?>">x</button>
					</div>
				</div>

				<span class="hy-homes-search__divider" aria-hidden="true"></span>

				<div class="hy-homes-search__field hy-homes-search__field--move-in">
					<label class="hy-homes-search__label" for="<?php echo esc_attr( $instance_id . '-move-in' ); ?>">
						<?php echo esc_html( $atts['move_in_label'] ); ?>
					</label>
					<div class="hy-homes-search__control hy-homes-search__select-wrap <?php echo '' !== $selected_values['move_in'] ? 'has-value' : ''; ?>">
						<select
							class="hy-homes-search__select"
							id="<?php echo esc_attr( $instance_id . '-move-in' ); ?>"
							name="hy_move_in"
							data-placeholder="<?php echo esc_attr( $atts['move_in_placeholder'] ); ?>"
						>
							<option value=""><?php echo esc_html( $atts['move_in_placeholder'] ); ?></option>
							<?php foreach ( $move_in_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $selected_values['move_in'], $option ); ?>>
									<?php echo esc_html( $option ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<button class="hy-homes-search__clear" type="button" data-hy-homes-clear="hy_move_in" aria-label="<?php esc_attr_e( 'Clear move-in date', 'hy-homes-syd-panther' ); ?>">x</button>
					</div>
				</div>

				<button class="hy-homes-search__submit" type="submit">
					<?php echo esc_html( $atts['search_label'] ); ?>
				</button>
			</form>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render a complete filtered results block.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public static function property_results( $atts = array() ) {
		$defaults = array(
			'results_url'          => '',
			'posts_per_page'       => '8',
			'neighborhood_source'  => 'auto',
			'neighborhood_options' => 'auto',
			'move_in_options'      => 'Immediate|Next 2 weeks|Next month',
			'search_label'         => 'NEXT SEARCH',
			'empty_title'          => 'Properties',
			'no_results_text'      => 'No properties match this search yet.',
			'show_search'          => 'true',
			'show_breadcrumbs'     => 'true',
			'show_banner'          => 'true',
			'results_banner_image' => '',
		);

		$atts          = shortcode_atts( $defaults, (array) $atts, 'hy_homes_property_results' );
		if ( 'auto' === sanitize_key( $atts['neighborhood_source'] ) ) {
			$atts['neighborhood_options'] = 'auto';
		}

		$selected      = self::get_selected_values();
		$base_url      = self::get_results_base_url( $atts['results_url'] );
		$filtered_url  = add_query_arg( self::selected_query_args( $selected ), $base_url );
		$property_loop = new WP_Query( self::build_property_query_args( $atts, $selected ) );
		$title         = self::get_results_title( $selected, $atts['empty_title'] );

		ob_start();
		?>
		<section class="hy-homes-results">
			<?php echo self::results_banner( $atts ); ?>

			<div class="hy-homes-results__filter-band">
				<div class="hy-homes-results__filter-inner">
					<?php
					if ( self::truthy( $atts['show_search'] ) ) {
						echo self::search_filter(
							array(
								'results_url'          => $base_url,
								'neighborhood_source'  => $atts['neighborhood_source'],
								'neighborhood_options' => $atts['neighborhood_options'],
								'move_in_options'      => $atts['move_in_options'],
								'search_label'         => $atts['search_label'],
								'variant'              => 'results',
							)
						);
					}

					if ( self::truthy( $atts['show_breadcrumbs'] ) ) {
						echo self::results_breadcrumbs( $selected, $base_url );
					}
					?>
				</div>
			</div>

			<div class="hy-homes-results__content">
				<h1 class="hy-homes-results__title"><?php echo esc_html( $title ); ?></h1>

				<?php if ( $property_loop->have_posts() ) : ?>
					<div class="hy-homes-results__grid">
						<?php
						while ( $property_loop->have_posts() ) :
							$property_loop->the_post();
							echo self::property_card( get_the_ID() );
						endwhile;
						?>
					</div>
					<?php echo self::pagination( $property_loop, $filtered_url ); ?>
				<?php else : ?>
					<p class="hy-homes-results__empty"><?php echo esc_html( $atts['no_results_text'] ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render the top banner for the filtered results page.
	 *
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return string
	 */
	private static function results_banner( $atts ) {
		if ( ! self::truthy( $atts['show_banner'] ) ) {
			return '';
		}

		$image = self::get_results_banner_image( $atts );

		if ( '' === $image ) {
			return '';
		}

		return '<div class="hy-homes-results__banner" style="' . esc_attr( 'background-image: url(' . esc_url_raw( $image ) . ');' ) . '" aria-hidden="true"></div>';
	}

	/**
	 * Render a carousel with the most recent properties.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public static function recent_properties_carousel( $atts = array() ) {
		$defaults = array(
			'title'          => 'Explore Our Available Places',
			'posts_per_page' => '12',
			'columns'        => '4',
			'empty_text'     => 'No properties are available yet.',
		);

		$atts           = shortcode_atts( $defaults, (array) $atts, 'hy_homes_recent_properties_carousel' );
		$posts_per_page = max( 1, min( 48, absint( $atts['posts_per_page'] ) ) );
		$columns        = max( 1, min( 4, absint( $atts['columns'] ) ) );
		$carousel_id    = wp_unique_id( 'hy-homes-carousel-' );
		$property_loop  = new WP_Query(
			array(
				'post_type'           => HY_Homes_Syd_Panther_Properties::POST_TYPE,
				'post_status'         => 'publish',
				'posts_per_page'      => $posts_per_page,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		ob_start();
		?>
		<section class="hy-homes-carousel" data-hy-homes-carousel style="<?php echo esc_attr( '--hy-homes-carousel-columns:' . $columns . ';' ); ?>">
			<div class="hy-homes-carousel__header">
				<?php if ( '' !== trim( (string) $atts['title'] ) ) : ?>
					<h2 class="hy-homes-carousel__title"><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php endif; ?>

				<?php if ( $property_loop->have_posts() ) : ?>
					<div class="hy-homes-carousel__nav">
						<button class="hy-homes-carousel__button hy-homes-carousel__button--prev" type="button" data-hy-homes-carousel-prev aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Previous properties', 'hy-homes-syd-panther' ); ?>">&lt;</button>
						<button class="hy-homes-carousel__button hy-homes-carousel__button--next" type="button" data-hy-homes-carousel-next aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Next properties', 'hy-homes-syd-panther' ); ?>">&gt;</button>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $property_loop->have_posts() ) : ?>
				<div class="hy-homes-carousel__viewport" id="<?php echo esc_attr( $carousel_id ); ?>" tabindex="0">
					<div class="hy-homes-carousel__track">
						<?php
						while ( $property_loop->have_posts() ) :
							$property_loop->the_post();
							?>
							<div class="hy-homes-carousel__slide">
								<?php echo self::property_card( get_the_ID() ); ?>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
			<?php else : ?>
				<p class="hy-homes-carousel__empty"><?php echo esc_html( $atts['empty_text'] ); ?></p>
			<?php endif; ?>
		</section>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render a single property detail page.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public static function property_detail( $atts = array() ) {
		$defaults = array(
			'post_id'             => '',
			'slug'                => '',
			'results_url'         => '',
			'related_per_page'    => '4',
			'related_title'       => 'RELATED PROPERTIES',
			'banner_button_label' => 'EXPLORE OUR LOCATIONS',
			'banner_button_url'   => 'https://hyhomessyd.com/#locatios',
			'show_search'         => 'true',
			'show_breadcrumbs'    => 'true',
		);

		$atts    = shortcode_atts( $defaults, (array) $atts, 'hy_homes_property_detail' );
		$post_id = self::resolve_property_id( $atts['post_id'], $atts['slug'] );

		if ( ! $post_id ) {
			return '';
		}

		$selected     = self::get_property_selected_values( $post_id );
		$base_url     = self::get_property_detail_results_url( $atts['results_url'] );
		$location     = self::get_property_neighborhood_label( $post_id );
		$street       = self::property_meta( $post_id, 'street', '' );
		$address      = self::property_meta( $post_id, 'address', '' );
		$address_line = '' !== $address ? $address : $street;
		$description  = get_post_field( 'post_content', $post_id );
		$price        = self::format_price( self::property_meta( $post_id, 'price', '' ) );
		$price_suffix = self::property_meta( $post_id, 'price_suffix', 'pw' );
		$price_line   = trim( $price . ' ' . $price_suffix );

		ob_start();
		?>
		<section class="hy-homes-detail">
			<div class="hy-homes-results__filter-band">
				<div class="hy-homes-results__filter-inner">
					<?php
					if ( self::truthy( $atts['show_search'] ) ) {
						echo self::search_filter(
							array(
								'results_url'           => $base_url,
								'neighborhood_options'  => 'auto',
								'move_in_options'       => 'Immediate|Next 2 weeks|Next month',
								'search_label'          => 'NEXT SEARCH',
								'variant'               => 'results',
								'selected_neighborhood' => $selected['neighborhood'],
								'selected_rooms'        => $selected['rooms'],
								'selected_move_in'      => $selected['move_in'],
							)
						);
					}

					if ( self::truthy( $atts['show_breadcrumbs'] ) ) {
						echo self::results_breadcrumbs( $selected, $base_url );
					}
					?>
				</div>
			</div>

			<div class="hy-homes-detail__content">
				<div class="hy-homes-detail__main">
					<div class="hy-homes-detail__media-column">
						<?php echo self::detail_gallery( $post_id, $price_line ); ?>
						<?php echo self::detail_property_strip( $post_id, $address_line ); ?>
					</div>

					<div class="hy-homes-detail__info">
						<h1 class="hy-homes-detail__title"><?php echo esc_html( '' !== $location ? $location : get_the_title( $post_id ) ); ?></h1>
						<?php if ( '' !== $street ) : ?>
							<p class="hy-homes-detail__street"><?php echo esc_html( $street ); ?></p>
						<?php endif; ?>
						<div class="hy-homes-detail__rule" aria-hidden="true"></div>
						<div class="hy-homes-detail__description">
							<?php if ( '' !== trim( $description ) ) : ?>
								<?php echo wp_kses_post( wpautop( $description ) ); ?>
							<?php endif; ?>
						</div>
						<?php echo self::whatsapp_button( $post_id ); ?>
					</div>
				</div>

				<?php echo self::detail_map( $post_id, $address_line ); ?>
				<?php echo self::related_properties( $post_id, $atts ); ?>
				<?php echo self::location_banners( $post_id, $atts ); ?>
			</div>
		</section>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render all stored location banners in random order.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public static function random_banners_carousel( $atts = array() ) {
		$defaults = array(
			'limit'               => '0',
			'banner_button_label' => 'EXPLORE OUR LOCATIONS',
			'banner_button_url'   => 'https://hyhomessyd.com/#locatios',
			'empty_text'          => '',
		);

		$atts    = shortcode_atts( $defaults, (array) $atts, 'hy_homes_random_banners' );
		$limit   = absint( $atts['limit'] );
		$banners = self::get_all_location_banners();

		if ( empty( $banners ) ) {
			return '' !== $atts['empty_text'] ? '<p class="hy-homes-carousel__empty">' . esc_html( $atts['empty_text'] ) . '</p>' : '';
		}

		shuffle( $banners );

		if ( 0 < $limit ) {
			$banners = array_slice( $banners, 0, $limit );
		}

		return self::render_location_banner_carousel( $banners, $atts, 'hy-homes-location-banner--random' );
	}

	/**
	 * Render the locations carousel from the Neighborhoods taxonomy.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public static function locations_carousel( $atts = array() ) {
		$defaults = array(
			'title'        => "Find Your Place in Sydney's Best Neighborhoods",
			'eyebrow'      => 'LOCATIONS',
			'locations'    => 'auto',
			'limit'        => '0',
			'columns'      => '3',
			'orderby'      => 'name',
			'order'        => 'ASC',
			'results_url'  => '',
			'button_label' => 'EXPLORE PROPERTIES',
			'empty_text'   => '',
		);

		$atts      = shortcode_atts( $defaults, (array) $atts, 'hy_homes_locations' );
		$locations = self::get_locations_for_carousel( $atts );

		if ( empty( $locations ) ) {
			return '' !== $atts['empty_text'] ? '<p class="hy-homes-carousel__empty">' . esc_html( $atts['empty_text'] ) . '</p>' : '';
		}

		$columns     = max( 1, min( 3, absint( $atts['columns'] ) ) );
		$carousel_id = wp_unique_id( 'hy-homes-locations-' );

		ob_start();
		?>
		<section class="hy-homes-locations" data-hy-homes-carousel style="<?php echo esc_attr( '--hy-homes-carousel-columns:' . $columns . ';' ); ?>">
			<div class="hy-homes-locations__inner">
				<div class="hy-homes-locations__rail" aria-hidden="true">
					<span><?php echo esc_html( $atts['eyebrow'] ); ?></span>
				</div>

				<div class="hy-homes-locations__body">
					<div class="hy-homes-carousel__viewport hy-homes-locations__viewport" id="<?php echo esc_attr( $carousel_id ); ?>" tabindex="0">
						<div class="hy-homes-carousel__track hy-homes-locations__track">
							<?php foreach ( $locations as $location ) : ?>
								<div class="hy-homes-carousel__slide hy-homes-locations__slide">
									<?php echo self::location_card( $location, $atts ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="hy-homes-locations__footer">
						<?php if ( '' !== trim( (string) $atts['title'] ) ) : ?>
							<h2 class="hy-homes-locations__title"><?php echo esc_html( $atts['title'] ); ?></h2>
						<?php endif; ?>

						<div class="hy-homes-carousel__nav hy-homes-locations__nav">
							<button class="hy-homes-carousel__button hy-homes-carousel__button--prev" type="button" data-hy-homes-carousel-prev aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Previous locations', 'hy-homes-syd-panther' ); ?>">&lt;</button>
							<button class="hy-homes-carousel__button hy-homes-carousel__button--next" type="button" data-hy-homes-carousel-next aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Next locations', 'hy-homes-syd-panther' ); ?>">&gt;</button>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php

		return ob_get_clean();
	}

	/**
	 * Check if there are WhatsApp agents ready to show.
	 *
	 * @return bool
	 */
	public static function has_whatsapp_agents() {
		return ! empty( self::get_whatsapp_agents() );
	}

	/**
	 * Render the global floating WhatsApp selector.
	 *
	 * @return string
	 */
	public static function floating_whatsapp() {
		$message = __( 'Hello, I would like to make an inquiry.', 'hy-homes-syd-panther' );

		return self::whatsapp_agent_picker( $message, 'floating' );
	}

	/**
	 * Render the property media gallery.
	 *
	 * @param int    $post_id Property post ID.
	 * @param string $price_line Formatted price.
	 * @return string
	 */
	private static function detail_gallery( $post_id, $price_line ) {
		$items = self::get_property_gallery_items( $post_id );

		ob_start();
		?>
		<div class="hy-homes-detail-gallery" data-hy-homes-detail-gallery>
			<div class="hy-homes-detail-gallery__stage">
				<?php if ( '' !== self::property_meta( $post_id, 'status', '' ) ) : ?>
					<span class="hy-homes-detail-gallery__status"><?php echo esc_html( strtoupper( self::property_meta( $post_id, 'status', '' ) ) ); ?></span>
				<?php endif; ?>

				<?php foreach ( $items as $index => $item ) : ?>
					<div class="hy-homes-detail-gallery__item <?php echo 0 === $index ? 'is-active' : ''; ?>" data-hy-homes-detail-media="<?php echo esc_attr( $index ); ?>">
						<?php echo self::render_media_item( $item, 'hy-homes-detail-gallery__media' ); ?>
					</div>
				<?php endforeach; ?>

				<?php if ( count( $items ) > 1 ) : ?>
					<button class="hy-homes-detail-gallery__arrow hy-homes-detail-gallery__arrow--prev" type="button" data-hy-homes-detail-prev aria-label="<?php esc_attr_e( 'Previous media', 'hy-homes-syd-panther' ); ?>">&lt;</button>
					<button class="hy-homes-detail-gallery__arrow hy-homes-detail-gallery__arrow--next" type="button" data-hy-homes-detail-next aria-label="<?php esc_attr_e( 'Next media', 'hy-homes-syd-panther' ); ?>">&gt;</button>
				<?php endif; ?>

				<?php if ( '' !== $price_line ) : ?>
					<span class="hy-homes-detail-gallery__price"><?php echo esc_html( $price_line ); ?></span>
				<?php endif; ?>
			</div>

			<?php if ( count( $items ) > 1 ) : ?>
				<div class="hy-homes-detail-gallery__thumbs">
					<?php foreach ( $items as $index => $item ) : ?>
						<button class="hy-homes-detail-gallery__thumb <?php echo 0 === $index ? 'is-active' : ''; ?>" type="button" data-hy-homes-detail-thumb="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show media %d', 'hy-homes-syd-panther' ), $index + 1 ) ); ?>">
							<?php echo self::render_media_thumb( $item ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render the small property strip below the gallery.
	 *
	 * @param int    $post_id Property post ID.
	 * @param string $address_line Address line.
	 * @return string
	 */
	private static function detail_property_strip( $post_id, $address_line ) {
		$bedrooms  = self::property_meta( $post_id, 'bedrooms', '2' );
		$bathrooms = self::property_meta( $post_id, 'bathrooms', '1' );

		ob_start();
		?>
		<div class="hy-homes-detail-strip">
			<div class="hy-homes-detail-strip__text">
				<strong><?php echo esc_html( get_the_title( $post_id ) ); ?></strong>
				<?php if ( '' !== $address_line ) : ?>
					<span><?php echo esc_html( $address_line ); ?></span>
				<?php endif; ?>
			</div>
			<div class="hy-homes-detail-strip__specs">
				<span><?php echo self::icon( 'bed' ); ?> <?php echo esc_html( $bedrooms ); ?></span>
				<span><?php echo self::icon( 'bath' ); ?> <?php echo esc_html( $bathrooms ); ?></span>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render the WhatsApp inquiry button.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function whatsapp_button( $post_id ) {
		return self::whatsapp_agent_picker( self::get_property_whatsapp_message( $post_id ), 'detail' );
	}

	/**
	 * Render the property Google map.
	 *
	 * @param int    $post_id Property post ID.
	 * @param string $address_line Address line.
	 * @return string
	 */
	private static function detail_map( $post_id, $address_line ) {
		$map_url = self::normalize_map_embed_url( self::property_meta( $post_id, 'map_embed_url', '' ), $address_line );

		if ( '' === $map_url ) {
			return '';
		}

		return '<div class="hy-homes-detail-map"><iframe src="' . esc_url( $map_url ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>';
	}

	/**
	 * Return an embeddable Google Maps URL from a raw Maps value.
	 *
	 * @param string $raw_map_url Raw map URL or iframe.
	 * @param string $address_line Property address fallback.
	 * @return string
	 */
	private static function normalize_map_embed_url( $raw_map_url, $address_line ) {
		$raw_map_url = trim( (string) $raw_map_url );

		if ( '' !== $raw_map_url && false !== stripos( $raw_map_url, '<iframe' ) ) {
			$raw_map_url = self::extract_iframe_src( $raw_map_url );
		}

		if ( self::is_google_maps_embed_url( $raw_map_url ) ) {
			return $raw_map_url;
		}

		$query = self::extract_google_maps_query( $raw_map_url );

		if ( '' === $query && '' !== trim( (string) $address_line ) ) {
			$query = trim( (string) $address_line );
		}

		if ( '' === $query ) {
			return '';
		}

		return 'https://www.google.com/maps?q=' . rawurlencode( $query ) . '&output=embed';
	}

	/**
	 * Extract src from an iframe snippet.
	 *
	 * @param string $iframe HTML iframe.
	 * @return string
	 */
	private static function extract_iframe_src( $iframe ) {
		if ( preg_match( '/src=["\']([^"\']+)["\']/i', $iframe, $matches ) ) {
			return esc_url_raw( html_entity_decode( $matches[1], ENT_QUOTES ) );
		}

		return '';
	}

	/**
	 * Check whether a URL is already a Google Maps embed URL.
	 *
	 * @param string $url Map URL.
	 * @return bool
	 */
	private static function is_google_maps_embed_url( $url ) {
		if ( '' === $url ) {
			return false;
		}

		$host = wp_parse_url( $url, PHP_URL_HOST );

		if ( ! is_string( $host ) || false === stripos( $host, 'google.' ) ) {
			return false;
		}

		return false !== stripos( $url, '/maps/embed' ) || false !== stripos( $url, 'output=embed' );
	}

	/**
	 * Extract a search query from a regular Google Maps URL.
	 *
	 * @param string $url Map URL.
	 * @return string
	 */
	private static function extract_google_maps_query( $url ) {
		if ( '' === $url ) {
			return '';
		}

		$host = wp_parse_url( $url, PHP_URL_HOST );

		if ( ! is_string( $host ) ) {
			return '';
		}

		$is_google_maps = false !== stripos( $host, 'google.' ) || false !== stripos( $host, 'goo.gl' ) || false !== stripos( $host, 'maps.app.goo.gl' );

		if ( ! $is_google_maps ) {
			return '';
		}

		$query_string = wp_parse_url( $url, PHP_URL_QUERY );

		if ( is_string( $query_string ) && '' !== $query_string ) {
			parse_str( $query_string, $query_args );

			foreach ( array( 'q', 'query', 'destination', 'daddr' ) as $key ) {
				if ( ! empty( $query_args[ $key ] ) && ! is_array( $query_args[ $key ] ) ) {
					return sanitize_text_field( wp_unslash( $query_args[ $key ] ) );
				}
			}
		}

		$path = wp_parse_url( $url, PHP_URL_PATH );

		if ( is_string( $path ) && preg_match( '#/place/([^/]+)#', $path, $matches ) ) {
			return sanitize_text_field( rawurldecode( str_replace( '+', ' ', $matches[1] ) ) );
		}

		return '';
	}

	/**
	 * Render related properties by neighborhood.
	 *
	 * @param int                 $post_id Property post ID.
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return string
	 */
	private static function related_properties( $post_id, $atts ) {
		$neighborhoods = self::get_property_neighborhood_terms( $post_id );
		$per_page      = max( 1, min( 24, absint( $atts['related_per_page'] ) ) );
		$current_page  = isset( $_GET['hy_related_page'] ) ? max( 1, absint( wp_unslash( $_GET['hy_related_page'] ) ) ) : 1;

		if ( empty( $neighborhoods ) ) {
			return '';
		}

		$query = new WP_Query(
			array(
				'post_type'      => HY_Homes_Syd_Panther_Properties::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => $per_page,
				'paged'          => $current_page,
				'post__not_in'   => array( $post_id ),
				'tax_query'      => array(
					array(
						'taxonomy' => HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD,
						'field'    => 'term_id',
						'terms'    => wp_list_pluck( $neighborhoods, 'term_id' ),
					),
				),
			)
		);

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();
			return '';
		}

		ob_start();
		?>
		<section class="hy-homes-detail-related">
			<h2 class="hy-homes-detail-related__title"><?php echo esc_html( $atts['related_title'] ); ?></h2>
			<div class="hy-homes-results__grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					echo self::property_card( get_the_ID() );
				endwhile;
				?>
			</div>
			<?php echo self::related_pagination( $query, self::get_current_property_detail_url( $post_id ) ); ?>
		</section>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render location-specific banner carousel.
	 *
	 * @param int                 $post_id Property post ID.
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return string
	 */
	private static function location_banners( $post_id, $atts ) {
		$banners = self::get_location_banners_for_property( $post_id );

		if ( empty( $banners ) ) {
			return '';
		}

		return self::render_location_banner_carousel( $banners, $atts, 'hy-homes-location-banner--property' );
	}

	/**
	 * Render a location banner carousel.
	 *
	 * @param array<int,array<string,string>> $banners Banner data.
	 * @param array<string,mixed>             $atts Element attributes.
	 * @param string                          $modifier_class Additional class.
	 * @return string
	 */
	private static function render_location_banner_carousel( $banners, $atts, $modifier_class = '' ) {
		$classes = trim( 'hy-homes-location-banner ' . sanitize_html_class( $modifier_class ) );

		ob_start();
		?>
		<section class="<?php echo esc_attr( $classes ); ?>" data-hy-homes-carousel data-hy-homes-carousel-autoplay="true" data-hy-homes-carousel-interval="5200" style="--hy-homes-carousel-columns:1;">
			<div class="hy-homes-carousel__viewport">
				<div class="hy-homes-carousel__track">
					<?php foreach ( $banners as $banner ) : ?>
						<?php $button_url = ! empty( $banner['button_url'] ) ? $banner['button_url'] : $atts['banner_button_url']; ?>
						<div class="hy-homes-carousel__slide">
							<div class="hy-homes-location-banner__item" style="<?php echo esc_attr( 'background-image: url(' . esc_url_raw( $banner['image'] ) . ');' ); ?>">
								<div class="hy-homes-location-banner__content">
									<h2><?php echo esc_html( $banner['title'] ); ?></h2>
									<p><?php echo esc_html( $banner['description'] ); ?></p>
									<a href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $atts['banner_button_label'] ); ?></a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render one property card.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function property_card( $post_id ) {
		$status       = self::property_meta( $post_id, 'status', 'Available Now' );
		$bedrooms     = self::property_meta( $post_id, 'bedrooms', '2' );
		$bathrooms    = self::property_meta( $post_id, 'bathrooms', '1' );
		$price        = self::format_price( self::property_meta( $post_id, 'price', '' ) );
		$price_suffix = self::property_meta( $post_id, 'price_suffix', 'pw' );
		$detail_url   = self::get_property_detail_url( $post_id );
		$external_image = self::property_meta( $post_id, 'featured_image_url', '' );
		$external_image = '' !== $external_image ? $external_image : self::get_first_gallery_image_url( $post_id );
		$image        = get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'class'   => 'hy-homes-property-card__image',
				'loading' => 'lazy',
			)
		);

		ob_start();
		?>
		<article class="hy-homes-property-card">
			<a class="hy-homes-property-card__media" href="<?php echo esc_url( $detail_url ); ?>">
				<?php if ( '' !== $status ) : ?>
					<span class="hy-homes-property-card__status"><?php echo esc_html( strtoupper( $status ) ); ?></span>
				<?php endif; ?>
				<?php
				if ( '' !== $image ) {
					echo $image;
				} elseif ( '' !== $external_image ) {
					echo '<img class="hy-homes-property-card__image" src="' . esc_url( $external_image ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '" loading="lazy">';
				} else {
					echo '<span class="hy-homes-property-card__image-placeholder" aria-hidden="true"></span>';
				}
				?>
			</a>
			<div class="hy-homes-property-card__body">
				<h2 class="hy-homes-property-card__title">
					<a href="<?php echo esc_url( $detail_url ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
				</h2>
				<div class="hy-homes-property-card__meta">
					<span class="hy-homes-property-card__spec">
						<?php echo self::icon( 'bed' ); ?>
						<?php echo esc_html( $bedrooms ); ?>
					</span>
					<span class="hy-homes-property-card__separator" aria-hidden="true">|</span>
					<span class="hy-homes-property-card__spec">
						<?php echo self::icon( 'bath' ); ?>
						<?php echo esc_html( $bathrooms ); ?>
					</span>
					<?php if ( '' !== $price ) : ?>
						<span class="hy-homes-property-card__price">
							<?php echo esc_html( trim( $price . ' ' . $price_suffix ) ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
			<a class="hy-homes-property-card__button" href="<?php echo esc_url( $detail_url ); ?>">
				<span><?php esc_html_e( 'VIEW DETAILS', 'hy-homes-syd-panther' ); ?></span>
				<?php echo self::arrow_long_right_icon(); ?>
			</a>
		</article>
		<?php

		return ob_get_clean();
	}

	/**
	 * Build the property query from selected filters.
	 *
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @param array<string,string> $selected Selected filters.
	 * @return array<string,mixed>
	 */
	private static function build_property_query_args( $atts, $selected ) {
		$posts_per_page = max( 1, min( 48, absint( $atts['posts_per_page'] ) ) );
		$current_page   = isset( $_GET['hy_results_page'] ) ? max( 1, absint( wp_unslash( $_GET['hy_results_page'] ) ) ) : 1;
		$meta_query     = array( 'relation' => 'AND' );
		$tax_query      = array( 'relation' => 'AND' );

		if ( '' !== $selected['neighborhood'] ) {
			$tax_query[] = array(
				'taxonomy' => HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD,
				'field'    => 'name',
				'terms'    => self::expand_neighborhood_terms( $selected['neighborhood'] ),
				'operator' => 'IN',
			);
		}

		if ( '' !== $selected['rooms'] ) {
			$meta_query[] = array(
				'key'     => HY_Homes_Syd_Panther_Properties::META_PREFIX . 'room_type',
				'value'   => absint( $selected['rooms'] ),
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
		}

		if ( '' !== $selected['move_in'] ) {
			$meta_query[] = array(
				'key'     => HY_Homes_Syd_Panther_Properties::META_PREFIX . 'move_in',
				'value'   => $selected['move_in'],
				'compare' => '=',
			);
		}

		$args = array(
			'post_type'      => HY_Homes_Syd_Panther_Properties::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'paged'          => $current_page,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( count( $tax_query ) > 1 ) {
			$args['tax_query'] = $tax_query;
		}

		if ( count( $meta_query ) > 1 ) {
			$args['meta_query'] = $meta_query;
		}

		return $args;
	}

	/**
	 * Render cumulative breadcrumb links for selected filters.
	 *
	 * @param array<string,string> $selected Selected filters.
	 * @param string               $base_url Results page URL.
	 * @return string
	 */
	private static function results_breadcrumbs( $selected, $base_url ) {
		$query = array();
		$items = array();

		if ( '' !== $selected['neighborhood'] ) {
			$query['hy_neighborhood'] = $selected['neighborhood'];
			$items[]                  = array(
				'label' => $selected['neighborhood'],
				'url'   => add_query_arg( $query, $base_url ),
			);
		}

		if ( '' !== $selected['rooms'] ) {
			$query['hy_rooms'] = $selected['rooms'];
			$items[]           = array(
				'label' => $selected['rooms'],
				'url'   => add_query_arg( $query, $base_url ),
			);
		}

		if ( '' !== $selected['move_in'] ) {
			$query['hy_move_in'] = $selected['move_in'];
			$items[]             = array(
				'label' => $selected['move_in'],
				'url'   => add_query_arg( $query, $base_url ),
			);
		}

		if ( empty( $items ) ) {
			return '';
		}

		$items[] = array(
			'label' => __( 'Search', 'hy-homes-syd-panther' ),
			'url'   => add_query_arg( $query, $base_url ),
		);

		ob_start();
		?>
		<nav class="hy-homes-results__breadcrumbs" aria-label="<?php esc_attr_e( 'Search filters', 'hy-homes-syd-panther' ); ?>">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php if ( 0 < $index ) : ?>
					<span class="hy-homes-results__breadcrumb-separator" aria-hidden="true">&gt;</span>
				<?php endif; ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render pagination links.
	 *
	 * @param WP_Query $property_loop Property query.
	 * @param string   $base_url Results page URL.
	 * @return string
	 */
	private static function pagination( $property_loop, $base_url ) {
		if ( $property_loop->max_num_pages < 2 ) {
			return '';
		}

		$current = isset( $_GET['hy_results_page'] ) ? max( 1, absint( wp_unslash( $_GET['hy_results_page'] ) ) ) : 1;
		$base    = str_replace( 999999999, '%#%', esc_url( add_query_arg( 'hy_results_page', 999999999, $base_url ) ) );
		$links   = paginate_links(
			array(
				'base'      => $base,
				'format'    => '',
				'current'   => $current,
				'total'     => $property_loop->max_num_pages,
				'type'      => 'list',
				'prev_text' => __( 'Previous', 'hy-homes-syd-panther' ),
				'next_text' => __( 'Next', 'hy-homes-syd-panther' ),
			)
		);

		if ( ! $links ) {
			return '';
		}

		return '<nav class="hy-homes-results__pagination" aria-label="' . esc_attr__( 'Property results pages', 'hy-homes-syd-panther' ) . '">' . $links . '</nav>';
	}

	/**
	 * Convert an option list string into clean labels.
	 *
	 * @param string $raw_options Raw options divided by pipes, commas or new lines.
	 * @return array<int,string>
	 */
	private static function parse_options( $raw_options ) {
		$raw_options = str_replace( array( "\r\n", "\r", "\n", ',' ), '|', (string) $raw_options );
		$options     = array_filter(
			array_map(
				'trim',
				explode( '|', $raw_options )
			)
		);

		return array_values( array_unique( $options ) );
	}

	/**
	 * Resolve option lists, including auto-generated neighborhoods.
	 *
	 * @param string $raw_options Raw options.
	 * @param string $type Option type.
	 * @return array<int,string>
	 */
	private static function resolve_options( $raw_options, $type ) {
		if ( 'auto' === strtolower( trim( (string) $raw_options ) ) && 'neighborhood' === $type ) {
			$options = HY_Homes_Syd_Panther_Properties::get_neighborhood_options();

			if ( ! empty( $options ) ) {
				return $options;
			}

			return self::parse_options( 'Waterloo & Zetland|Waterloo|Eastgardens|Zetland|Rosebery|Mascot|Kingsford|Kensington' );
		}

		return self::parse_options( $raw_options );
	}

	/**
	 * Resolve a property ID from shortcode attrs or current post.
	 *
	 * @param mixed $post_id_attr Post ID attribute.
	 * @return int
	 */
	private static function resolve_property_id( $post_id_attr, $slug_attr = '' ) {
		$post_id = absint( $post_id_attr );

		if ( ! $post_id && isset( $_GET['hy_property_id'] ) && ! is_array( $_GET['hy_property_id'] ) ) {
			$post_id = absint( wp_unslash( $_GET['hy_property_id'] ) );
		}

		if ( ! $post_id ) {
			$slug = sanitize_title( $slug_attr );

			if ( '' === $slug && isset( $_GET['hy_property'] ) && ! is_array( $_GET['hy_property'] ) ) {
				$slug = sanitize_title( wp_unslash( $_GET['hy_property'] ) );
			}

			if ( '' !== $slug ) {
				$post = get_page_by_path( $slug, OBJECT, HY_Homes_Syd_Panther_Properties::POST_TYPE );

				if ( $post instanceof WP_Post ) {
					$post_id = $post->ID;
				}
			}
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id || HY_Homes_Syd_Panther_Properties::POST_TYPE !== get_post_type( $post_id ) ) {
			return 0;
		}

		return $post_id;
	}

	/**
	 * Return the URL used by property cards to open a detail view.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function get_property_detail_url( $post_id ) {
		$custom_url = self::property_meta( $post_id, 'detail_url', '' );

		if ( '' === $custom_url ) {
			$custom_url = HY_Homes_Syd_Panther_Properties::DEFAULT_DETAIL_URL;
		}

		$slug = get_post_field( 'post_name', $post_id );

		if ( '' === $slug ) {
			return $custom_url;
		}

		return add_query_arg( 'hy_property', $slug, $custom_url );
	}

	/**
	 * Return the active detail page URL for buttons and pagination.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function get_current_property_detail_url( $post_id ) {
		if ( is_singular( HY_Homes_Syd_Panther_Properties::POST_TYPE ) ) {
			return get_permalink( $post_id );
		}

		$base_url = get_permalink();

		if ( ! $base_url ) {
			$base_url = self::get_property_detail_url( $post_id );
		}

		$slug = get_post_field( 'post_name', $post_id );

		if ( '' !== $slug ) {
			$base_url = add_query_arg( 'hy_property', $slug, $base_url );
		}

		return remove_query_arg( 'hy_related_page', $base_url );
	}

	/**
	 * Return selected filters from a property.
	 *
	 * @param int $post_id Property post ID.
	 * @return array<string,string>
	 */
	private static function get_property_selected_values( $post_id ) {
		return array(
			'neighborhood' => self::get_property_neighborhood_label( $post_id ),
			'rooms'        => self::property_meta( $post_id, 'room_type', '' ),
			'move_in'      => self::property_meta( $post_id, 'move_in', '' ),
		);
	}

	/**
	 * Return property neighborhood label.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function get_property_neighborhood_label( $post_id ) {
		$terms = self::get_property_neighborhood_terms( $post_id );

		if ( empty( $terms ) ) {
			return '';
		}

		return implode( ' & ', wp_list_pluck( $terms, 'name' ) );
	}

	/**
	 * Return property neighborhood terms.
	 *
	 * @param int $post_id Property post ID.
	 * @return array<int,WP_Term>
	 */
	private static function get_property_neighborhood_terms( $post_id ) {
		$terms = get_the_terms( $post_id, HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		return array_values( $terms );
	}

	/**
	 * Merge forced selected values into query selected values.
	 *
	 * @param array<string,string> $selected Existing selected values.
	 * @param array<string,mixed>  $atts Shortcode attributes.
	 * @return array<string,string>
	 */
	private static function merge_selected_values( $selected, $atts ) {
		if ( '' !== $atts['selected_neighborhood'] ) {
			$selected['neighborhood'] = sanitize_text_field( $atts['selected_neighborhood'] );
		}

		if ( '' !== $atts['selected_rooms'] ) {
			$selected['rooms'] = (string) absint( $atts['selected_rooms'] );
		}

		if ( '' !== $atts['selected_move_in'] ) {
			$selected['move_in'] = sanitize_text_field( $atts['selected_move_in'] );
		}

		return $selected;
	}

	/**
	 * Return gallery items from meta or featured image.
	 *
	 * @param int $post_id Property post ID.
	 * @return array<int,array<string,string>>
	 */
	private static function get_property_gallery_items( $post_id ) {
		$raw   = self::property_meta( $post_id, 'gallery_media', '' );
		$items = array();

		if ( '' !== $raw ) {
			foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
				$url = trim( $line );

				if ( '' === $url ) {
					continue;
				}

				$items[] = array(
					'url'  => esc_url_raw( $url ),
					'type' => self::get_media_type( $url ),
				);
			}
		}

		if ( empty( $items ) && has_post_thumbnail( $post_id ) ) {
			$items[] = array(
				'url'  => get_the_post_thumbnail_url( $post_id, 'large' ),
				'type' => 'image',
			);
		}

		if ( empty( $items ) && '' !== self::property_meta( $post_id, 'featured_image_url', '' ) ) {
			$items[] = array(
				'url'  => self::property_meta( $post_id, 'featured_image_url', '' ),
				'type' => 'image',
			);
		}

		if ( empty( $items ) ) {
			$items[] = array(
				'url'  => '',
				'type' => 'placeholder',
			);
		}

		return $items;
	}

	/**
	 * Return the first image URL from the property gallery.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function get_first_gallery_image_url( $post_id ) {
		$raw = self::property_meta( $post_id, 'gallery_media', '' );

		if ( '' === $raw ) {
			return '';
		}

		foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
			$url = trim( $line );

			if ( '' !== $url && 'image' === self::get_media_type( $url ) ) {
				return esc_url_raw( $url );
			}
		}

		return '';
	}

	/**
	 * Return media type by URL.
	 *
	 * @param string $url Media URL.
	 * @return string
	 */
	private static function get_media_type( $url ) {
		$path = wp_parse_url( $url, PHP_URL_PATH );
		$ext  = strtolower( pathinfo( (string) $path, PATHINFO_EXTENSION ) );

		if ( in_array( $ext, array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif' ), true ) ) {
			return 'image';
		}

		if ( in_array( $ext, array( 'mp4', 'webm', 'ogg', 'mov' ), true ) ) {
			return 'video';
		}

		return 'embed';
	}

	/**
	 * Render a media item.
	 *
	 * @param array<string,string> $item Media item.
	 * @param string               $class CSS class.
	 * @return string
	 */
	private static function render_media_item( $item, $class ) {
		if ( 'image' === $item['type'] ) {
			return '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $item['url'] ) . '" alt="" loading="lazy">';
		}

		if ( 'video' === $item['type'] ) {
			return '<video class="' . esc_attr( $class ) . '" src="' . esc_url( $item['url'] ) . '" controls playsinline></video>';
		}

		if ( 'embed' === $item['type'] ) {
			return '<iframe class="' . esc_attr( $class ) . '" src="' . esc_url( $item['url'] ) . '" loading="lazy" allowfullscreen></iframe>';
		}

		return '<span class="' . esc_attr( $class ) . ' hy-homes-detail-gallery__placeholder" aria-hidden="true"></span>';
	}

	/**
	 * Render gallery thumbnail.
	 *
	 * @param array<string,string> $item Media item.
	 * @return string
	 */
	private static function render_media_thumb( $item ) {
		if ( 'image' === $item['type'] ) {
			return '<img src="' . esc_url( $item['url'] ) . '" alt="" loading="lazy">';
		}

		if ( 'video' === $item['type'] ) {
			return '<span class="hy-homes-detail-gallery__thumb-label">VIDEO</span>';
		}

		if ( 'embed' === $item['type'] ) {
			return '<span class="hy-homes-detail-gallery__thumb-label">MEDIA</span>';
		}

		return '<span class="hy-homes-detail-gallery__thumb-label"></span>';
	}

	/**
	 * Render a WhatsApp agent picker.
	 *
	 * @param string $message WhatsApp message.
	 * @param string $context Picker context.
	 * @return string
	 */
	private static function whatsapp_agent_picker( $message, $context = 'detail' ) {
		$agents = self::get_whatsapp_agents();

		if ( empty( $agents ) ) {
			return '';
		}

		$is_floating = 'floating' === $context;
		$panel_id    = wp_unique_id( 'hy-homes-whatsapp-panel-' );
		$classes     = $is_floating ? 'hy-homes-whatsapp hy-homes-whatsapp--floating' : 'hy-homes-whatsapp hy-homes-whatsapp--detail';

		ob_start();
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" data-hy-homes-whatsapp>
			<button
				class="<?php echo esc_attr( $is_floating ? 'hy-homes-whatsapp-floating__button' : 'hy-homes-detail__whatsapp' ); ?>"
				type="button"
				data-hy-homes-whatsapp-toggle
				aria-controls="<?php echo esc_attr( $panel_id ); ?>"
				aria-expanded="false"
				aria-label="<?php echo esc_attr( $is_floating ? __( 'Chat with an agent', 'hy-homes-syd-panther' ) : __( 'Inquire about this property', 'hy-homes-syd-panther' ) ); ?>"
			>
				<?php echo self::whatsapp_icon(); ?>
				<?php if ( ! $is_floating ) : ?>
					<span><?php esc_html_e( 'INQUIRE ABOUT THIS PROPERTY', 'hy-homes-syd-panther' ); ?></span>
				<?php endif; ?>
			</button>

			<div class="hy-homes-whatsapp-panel" id="<?php echo esc_attr( $panel_id ); ?>" data-hy-homes-whatsapp-panel hidden>
				<h2 class="hy-homes-whatsapp-panel__title"><?php esc_html_e( 'Chat with an Agent', 'hy-homes-syd-panther' ); ?></h2>
				<p class="hy-homes-whatsapp-panel__text"><?php esc_html_e( 'Select a representative to start your inquiry.', 'hy-homes-syd-panther' ); ?></p>
				<div class="hy-homes-whatsapp-panel__agents">
					<?php foreach ( $agents as $agent ) : ?>
						<a class="hy-homes-whatsapp-agent" href="<?php echo esc_url( self::build_whatsapp_agent_url( $agent['phone'], $message ) ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo self::whatsapp_icon(); ?>
							<span><?php echo esc_html( $agent['label'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Return configured WhatsApp agents.
	 *
	 * @return array<int,array{label:string,phone:string}>
	 */
	private static function get_whatsapp_agents() {
		if ( ! class_exists( 'HY_Homes_Syd_Panther_Admin' ) ) {
			return array();
		}

		return HY_Homes_Syd_Panther_Admin::get_whatsapp_agents();
	}

	/**
	 * Build a WhatsApp URL for one agent.
	 *
	 * @param string $phone Agent phone.
	 * @param string $message Message text.
	 * @return string
	 */
	private static function build_whatsapp_agent_url( $phone, $message ) {
		$phone = (string) preg_replace( '/\D+/', '', (string) $phone );

		if ( '' === $phone ) {
			return '';
		}

		return 'https://wa.me/' . $phone . '?text=' . rawurlencode( $message );
	}

	/**
	 * Return the property-specific WhatsApp message.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function get_property_whatsapp_message( $post_id ) {
		return sprintf(
			__( 'Hello, I am interested in this property: %1$s - %2$s', 'hy-homes-syd-panther' ),
			get_the_title( $post_id ),
			self::get_current_property_detail_url( $post_id )
		);
	}

	/**
	 * Render related pagination.
	 *
	 * @param WP_Query $query Related query.
	 * @param string   $base_url Base URL.
	 * @return string
	 */
	private static function related_pagination( $query, $base_url ) {
		if ( $query->max_num_pages < 2 ) {
			return '';
		}

		$current = isset( $_GET['hy_related_page'] ) ? max( 1, absint( wp_unslash( $_GET['hy_related_page'] ) ) ) : 1;
		$base    = str_replace( 999999999, '%#%', esc_url( add_query_arg( 'hy_related_page', 999999999, $base_url ) ) );
		$links   = paginate_links(
			array(
				'base'      => $base,
				'format'    => '',
				'current'   => $current,
				'total'     => $query->max_num_pages,
				'type'      => 'list',
				'prev_text' => '>',
				'next_text' => '>>',
			)
		);

		if ( ! $links ) {
			return '';
		}

		return '<nav class="hy-homes-results__pagination hy-homes-detail-related__pagination" aria-label="' . esc_attr__( 'Related property pages', 'hy-homes-syd-panther' ) . '">' . $links . '</nav>';
	}

	/**
	 * Parse banner rows.
	 *
	 * @param string $raw Raw banner text.
	 * @return array<int,array<string,string>>
	 */
	private static function parse_location_banners( $raw ) {
		$banners = array();

		foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
			$parts = array_map( 'trim', explode( '|', $line ) );

			if ( count( $parts ) < 3 || '' === $parts[0] ) {
				continue;
			}

			$banners[] = array(
				'image'       => esc_url_raw( $parts[0] ),
				'title'       => sanitize_text_field( $parts[1] ),
				'description' => sanitize_text_field( $parts[2] ),
				'button_url'  => ! empty( $parts[3] ) ? esc_url_raw( $parts[3] ) : '',
			);
		}

		return $banners;
	}

	/**
	 * Return banners matching a property location.
	 *
	 * @param int $post_id Property post ID.
	 * @return array<int,array<string,string>>
	 */
	private static function get_location_banners_for_property( $post_id ) {
		$banners       = array();
		$neighborhoods = self::get_property_neighborhood_terms( $post_id );

		if ( ! empty( $neighborhoods ) ) {
			$query = new WP_Query(
				array(
					'post_type'           => HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE,
					'post_status'         => 'publish',
					'posts_per_page'      => -1,
					'fields'              => 'ids',
					'orderby'             => array(
						'menu_order' => 'ASC',
						'title'      => 'ASC',
					),
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
					'tax_query'           => array(
						array(
							'taxonomy' => HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD,
							'field'    => 'term_id',
							'terms'    => wp_list_pluck( $neighborhoods, 'term_id' ),
						),
					),
				)
			);

			foreach ( $query->posts as $banner_id ) {
				$banner = self::get_banner_post_data( $banner_id );

				if ( ! empty( $banner ) ) {
					$banners[] = $banner;
				}
			}
		}

		if ( empty( $banners ) ) {
			$banners = self::parse_location_banners( self::property_meta( $post_id, 'location_banners', '' ) );
		}

		return $banners;
	}

	/**
	 * Return all location banners.
	 *
	 * @return array<int,array<string,string>>
	 */
	private static function get_all_location_banners() {
		$banners = array();

		$banner_query = new WP_Query(
			array(
				'post_type'           => HY_Homes_Syd_Panther_Properties::BANNER_POST_TYPE,
				'post_status'         => 'publish',
				'posts_per_page'      => -1,
				'fields'              => 'ids',
				'orderby'             => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			)
		);

		foreach ( $banner_query->posts as $banner_id ) {
			$banner = self::get_banner_post_data( $banner_id );

			if ( ! empty( $banner ) ) {
				$banners[] = $banner;
			}
		}

		if ( ! empty( $banners ) ) {
			return $banners;
		}

		$query   = new WP_Query(
			array(
				'post_type'           => HY_Homes_Syd_Panther_Properties::POST_TYPE,
				'post_status'         => 'publish',
				'posts_per_page'      => -1,
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => HY_Homes_Syd_Panther_Properties::META_PREFIX . 'location_banners',
						'compare' => 'EXISTS',
					),
				),
			)
		);

		foreach ( $query->posts as $post_id ) {
			$raw     = self::property_meta( $post_id, 'location_banners', '' );
			$banners = array_merge( $banners, self::parse_location_banners( $raw ) );
		}

		wp_reset_postdata();

		return $banners;
	}

	/**
	 * Return the fixed image configured for the results page banner.
	 *
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return string
	 */
	private static function get_results_banner_image( $atts ) {
		return isset( $atts['results_banner_image'] ) ? esc_url_raw( trim( (string) $atts['results_banner_image'] ) ) : '';
	}

	/**
	 * Return banner data from a banner post.
	 *
	 * @param int $banner_id Banner post ID.
	 * @return array<string,string>
	 */
	private static function get_banner_post_data( $banner_id ) {
		$image = get_the_post_thumbnail_url( $banner_id, 'full' );

		if ( ! $image ) {
			$image = get_post_meta( $banner_id, HY_Homes_Syd_Panther_Properties::BANNER_META_PREFIX . 'image_url', true );
		}

		if ( '' === $image ) {
			return array();
		}

		return array(
			'image'       => esc_url_raw( $image ),
			'title'       => sanitize_text_field( get_the_title( $banner_id ) ),
			'description' => sanitize_textarea_field( get_post_meta( $banner_id, HY_Homes_Syd_Panther_Properties::BANNER_META_PREFIX . 'description', true ) ),
			'button_url'  => esc_url_raw( get_post_meta( $banner_id, HY_Homes_Syd_Panther_Properties::BANNER_META_PREFIX . 'button_url', true ) ),
		);
	}

	/**
	 * Render one location card.
	 *
	 * @param array<string,mixed> $location Location data.
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return string
	 */
	private static function location_card( $location, $atts ) {
		$name        = isset( $location['name'] ) ? (string) $location['name'] : '';
		$title       = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $name ) : strtoupper( $name );
		$highlight   = isset( $location['highlight'] ) ? (string) $location['highlight'] : '';
		$description = isset( $location['description'] ) ? wp_strip_all_tags( (string) $location['description'] ) : '';
		$image_url   = isset( $location['image_url'] ) ? (string) $location['image_url'] : '';
		$button_url  = self::get_location_results_url( $name, $atts['results_url'] );

		ob_start();
		?>
		<article class="hy-homes-location-card">
			<a class="hy-homes-location-card__media" href="<?php echo esc_url( $button_url ); ?>">
				<?php if ( '' !== $image_url ) : ?>
					<img class="hy-homes-location-card__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
				<?php else : ?>
					<span class="hy-homes-location-card__placeholder" aria-hidden="true"></span>
				<?php endif; ?>

				<span class="hy-homes-location-card__shade" aria-hidden="true"></span>

				<span class="hy-homes-location-card__overlay">
					<span class="hy-homes-location-card__title"><?php echo esc_html( $title ); ?></span>
					<?php if ( '' !== $highlight ) : ?>
						<span class="hy-homes-location-card__highlight"><?php echo esc_html( $highlight ); ?></span>
					<?php endif; ?>
				</span>
			</a>

			<div class="hy-homes-location-card__content">
				<?php if ( '' !== $description ) : ?>
					<p class="hy-homes-location-card__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<a class="hy-homes-location-card__button" href="<?php echo esc_url( $button_url ); ?>">
					<span><?php echo esc_html( $atts['button_label'] ); ?></span>
					<?php echo self::arrow_long_right_icon(); ?>
				</a>
			</div>
		</article>
		<?php

		return ob_get_clean();
	}

	/**
	 * Return locality cards from the taxonomy.
	 *
	 * @param array<string,mixed> $atts Shortcode attributes.
	 * @return array<int,array<string,mixed>>
	 */
	private static function get_locations_for_carousel( $atts ) {
		$limit     = absint( $atts['limit'] );
		$requested = self::parse_options( $atts['locations'] );
		$locations = array();

		if ( empty( $requested ) || ( 1 === count( $requested ) && 'auto' === strtolower( $requested[0] ) ) ) {
			$orderby = sanitize_key( $atts['orderby'] );
			$order   = 'DESC' === strtoupper( (string) $atts['order'] ) ? 'DESC' : 'ASC';

			if ( ! in_array( $orderby, array( 'name', 'slug', 'id', 'term_id', 'count', 'description' ), true ) ) {
				$orderby = 'name';
			}

			$terms = get_terms(
				array(
					'taxonomy'   => HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD,
					'hide_empty' => false,
					'orderby'    => $orderby,
					'order'      => $order,
					'number'     => 0,
				)
			);
		} else {
			$terms = array();

			foreach ( $requested as $identifier ) {
				$term = self::get_location_term_by_identifier( $identifier );

				if ( $term instanceof WP_Term ) {
					$terms[ $term->term_id ] = $term;
				}
			}

		}

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		foreach ( $terms as $term ) {
			if ( $term instanceof WP_Term ) {
				$locations[] = self::term_to_location_card_data( $term );
			}
		}

		return self::group_location_cards( $locations, $limit );
	}

	/**
	 * Group localities that share the same description, image and highlight.
	 *
	 * @param array<int,array<string,mixed>> $locations Location card data.
	 * @param int                            $limit Maximum cards to return.
	 * @return array<int,array<string,mixed>>
	 */
	private static function group_location_cards( $locations, $limit = 0 ) {
		$groups = array();

		foreach ( $locations as $location ) {
			$key = self::location_group_key( $location );

			if ( ! isset( $groups[ $key ] ) ) {
				$location['names']      = array();
				$location['names_seen'] = array();
				$groups[ $key ]         = $location;
			}

			foreach ( self::split_location_card_name( $location['name'] ) as $name ) {
				$name_key = self::normalize_location_group_value( $name );

				if ( '' === $name_key || isset( $groups[ $key ]['names_seen'][ $name_key ] ) ) {
					continue;
				}

				$groups[ $key ]['names'][]                  = $name;
				$groups[ $key ]['names_seen'][ $name_key ] = true;
			}
		}

		$grouped = array();

		foreach ( $groups as $location ) {
			if ( ! empty( $location['names'] ) ) {
				$location['name'] = implode( ' & ', $location['names'] );
			}

			unset( $location['names'], $location['names_seen'] );

			$grouped[] = $location;
		}

		if ( 0 < $limit ) {
			$grouped = array_slice( $grouped, 0, $limit );
		}

		return $grouped;
	}

	/**
	 * Return a stable grouping key for location cards.
	 *
	 * @param array<string,mixed> $location Location card data.
	 * @return string
	 */
	private static function location_group_key( $location ) {
		$description = self::normalize_location_group_value( isset( $location['description'] ) ? $location['description'] : '' );
		$highlight   = self::normalize_location_group_value( isset( $location['highlight'] ) ? $location['highlight'] : '' );
		$image_url   = trim( (string) ( isset( $location['image_url'] ) ? $location['image_url'] : '' ) );

		if ( '' === $description || '' === $highlight || '' === $image_url ) {
			return 'single-' . absint( isset( $location['id'] ) ? $location['id'] : 0 );
		}

		return md5( $image_url . '|' . $description . '|' . $highlight );
	}

	/**
	 * Split combined location names into unique filter parts.
	 *
	 * @param string $name Location name.
	 * @return array<int,string>
	 */
	private static function split_location_card_name( $name ) {
		return array_values(
			array_filter(
				array_map(
					'trim',
					explode( '&', (string) $name )
				)
			)
		);
	}

	/**
	 * Normalize grouping text values.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	private static function normalize_location_group_value( $value ) {
		$value = wp_strip_all_tags( (string) $value );
		$value = preg_replace( '/\s+/', ' ', trim( $value ) );

		if ( null === $value ) {
			return '';
		}

		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $value ) : strtolower( $value );
	}

	/**
	 * Find a locality term by ID, slug or name.
	 *
	 * @param string $identifier Term ID, slug or name.
	 * @return WP_Term|null
	 */
	private static function get_location_term_by_identifier( $identifier ) {
		$identifier = trim( (string) $identifier );

		if ( '' === $identifier ) {
			return null;
		}

		if ( ctype_digit( $identifier ) ) {
			$term = get_term( absint( $identifier ), HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD );

			if ( $term instanceof WP_Term ) {
				return $term;
			}
		}

		$term = get_term_by( 'slug', sanitize_title( $identifier ), HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD );

		if ( $term instanceof WP_Term ) {
			return $term;
		}

		$term = get_term_by( 'name', $identifier, HY_Homes_Syd_Panther_Properties::TAX_NEIGHBORHOOD );

		return $term instanceof WP_Term ? $term : null;
	}

	/**
	 * Convert a term into location card data.
	 *
	 * @param WP_Term $term Locality term.
	 * @return array<string,mixed>
	 */
	private static function term_to_location_card_data( $term ) {
		return array(
			'id'          => absint( $term->term_id ),
			'name'        => $term->name,
			'slug'        => $term->slug,
			'description' => $term->description,
			'highlight'   => get_term_meta( $term->term_id, HY_Homes_Syd_Panther_Properties::NEIGHBORHOOD_META_PREFIX . 'highlight', true ),
			'image_url'   => get_term_meta( $term->term_id, HY_Homes_Syd_Panther_Properties::NEIGHBORHOOD_META_PREFIX . 'image_url', true ),
		);
	}

	/**
	 * Build the results URL for a locality card.
	 *
	 * @param string $name Locality name.
	 * @param string $results_url Optional configured results URL.
	 * @return string
	 */
	private static function get_location_results_url( $name, $results_url ) {
		return add_query_arg(
			array(
				'hy_neighborhood' => $name,
			),
			self::get_results_base_url( $results_url, false )
		);
	}

	/**
	 * Read current selected values from the query string.
	 *
	 * @return array<string,string>
	 */
	private static function get_selected_values() {
		$rooms = isset( $_GET['hy_rooms'] ) ? absint( wp_unslash( $_GET['hy_rooms'] ) ) : 0;

		return array(
			'neighborhood' => isset( $_GET['hy_neighborhood'] ) ? sanitize_text_field( wp_unslash( $_GET['hy_neighborhood'] ) ) : '',
			'rooms'        => 0 < $rooms ? (string) $rooms : '',
			'move_in'      => isset( $_GET['hy_move_in'] ) ? sanitize_text_field( wp_unslash( $_GET['hy_move_in'] ) ) : '',
		);
	}

	/**
	 * Return query args for active filters.
	 *
	 * @param array<string,string> $selected Selected filters.
	 * @return array<string,string>
	 */
	private static function selected_query_args( $selected ) {
		$query = array();

		if ( '' !== $selected['neighborhood'] ) {
			$query['hy_neighborhood'] = $selected['neighborhood'];
		}

		if ( '' !== $selected['rooms'] ) {
			$query['hy_rooms'] = $selected['rooms'];
		}

		if ( '' !== $selected['move_in'] ) {
			$query['hy_move_in'] = $selected['move_in'];
		}

		return $query;
	}

	/**
	 * Return the results page URL.
	 *
	 * @param string $results_url Optional configured URL.
	 * @param bool   $allow_current Whether the current page can be used as fallback.
	 * @return string
	 */
	private static function get_results_base_url( $results_url, $allow_current = true ) {
		$base_url = trim( (string) $results_url );

		if ( '' !== $base_url && ! self::is_property_archive_url( $base_url ) ) {
			return self::clean_results_url( $base_url );
		}

		if ( $allow_current && self::current_page_has_results_shortcode() ) {
			$current_url = get_permalink();

			if ( $current_url && ! self::is_property_archive_url( $current_url ) ) {
				return self::clean_results_url( $current_url );
			}
		}

		$results_page_url = self::find_results_page_url();

		if ( '' !== $results_page_url ) {
			return self::clean_results_url( $results_page_url );
		}

		if ( $allow_current ) {
			$current_url = get_permalink();

			if ( $current_url && ! self::is_property_archive_url( $current_url ) ) {
				return self::clean_results_url( $current_url );
			}
		}

		return self::clean_results_url( home_url( '/properties/' ) );
	}

	/**
	 * Return results URL for property detail searches.
	 *
	 * @param string $results_url Optional configured URL.
	 * @return string
	 */
	private static function get_property_detail_results_url( $results_url ) {
		$base_url = trim( (string) $results_url );

		if ( '' !== $base_url && ! self::is_property_archive_url( $base_url ) ) {
			return self::clean_results_url( $base_url );
		}

		return self::get_results_base_url( '', false );
	}

	/**
	 * Remove plugin query parameters from a results base URL.
	 *
	 * @param string $url URL to clean.
	 * @return string
	 */
	private static function clean_results_url( $url ) {
		return remove_query_arg(
			array(
				'hy_results_page',
				'hy_neighborhood',
				'hy_rooms',
				'hy_move_in',
				'hy_property',
				'hy_property_id',
			),
			$url
		);
	}

	/**
	 * Check whether a URL points to the internal property archive.
	 *
	 * @param string $url URL to inspect.
	 * @return bool
	 */
	private static function is_property_archive_url( $url ) {
		$archive_url  = get_post_type_archive_link( HY_Homes_Syd_Panther_Properties::POST_TYPE );
		$archive_path = $archive_url ? wp_parse_url( $archive_url, PHP_URL_PATH ) : '/hy-properties/';
		$url_path     = wp_parse_url( $url, PHP_URL_PATH );

		if ( ! $archive_path || ! $url_path ) {
			return false;
		}

		return untrailingslashit( $archive_path ) === untrailingslashit( $url_path );
	}

	/**
	 * Check whether the current page contains the results shortcode.
	 *
	 * @return bool
	 */
	private static function current_page_has_results_shortcode() {
		$post = get_post();

		return $post instanceof WP_Post && self::content_has_results_shortcode( $post->post_content );
	}

	/**
	 * Find a published page that contains the property results shortcode/widget.
	 *
	 * @return string
	 */
	private static function find_results_page_url() {
		static $cached_url = null;

		if ( null !== $cached_url ) {
			return $cached_url;
		}

		$cached_url = '';
		$pages      = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => 'modified',
				'order'          => 'DESC',
			)
		);

		foreach ( $pages as $page ) {
			if ( self::content_has_results_shortcode( $page->post_content ) ) {
				$cached_url = (string) get_permalink( $page );
				return $cached_url;
			}
		}

		$elementor_pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_elementor_data',
				'meta_value'     => 'hy_homes_syd_property_results',
				'meta_compare'   => 'LIKE',
			)
		);

		if ( ! empty( $elementor_pages ) ) {
			$cached_url = (string) get_permalink( $elementor_pages[0] );
		}

		return $cached_url;
	}

	/**
	 * Check content for either property results shortcode.
	 *
	 * @param string $content Post content.
	 * @return bool
	 */
	private static function content_has_results_shortcode( $content ) {
		return has_shortcode( $content, 'hy_homes_property_results' )
			|| has_shortcode( $content, 'panther_hy_homes_results' )
			|| false !== strpos( $content, 'hy_homes_property_results' )
			|| false !== strpos( $content, 'panther_hy_homes_results' );
	}

	/**
	 * Return a readable title for the results block.
	 *
	 * @param array<string,string> $selected Selected filters.
	 * @param string               $empty_title Title when no location exists.
	 * @return string
	 */
	private static function get_results_title( $selected, $empty_title ) {
		if ( '' !== $selected['neighborhood'] ) {
			return $selected['neighborhood'];
		}

		if ( '' !== $selected['rooms'] || '' !== $selected['move_in'] ) {
			return __( 'Search Results', 'hy-homes-syd-panther' );
		}

		return $empty_title;
	}

	/**
	 * Expand combined neighborhood labels into possible taxonomy terms.
	 *
	 * @param string $neighborhood Selected neighborhood.
	 * @return array<int,string>
	 */
	private static function expand_neighborhood_terms( $neighborhood ) {
		$terms = array( $neighborhood );

		if ( false !== strpos( $neighborhood, '&' ) ) {
			$parts = array_map( 'trim', explode( '&', $neighborhood ) );
			$terms = array_merge( $terms, $parts );
		}

		return array_values( array_filter( array_unique( $terms ) ) );
	}

	/**
	 * Return property meta with fallback.
	 *
	 * @param int    $post_id Property post ID.
	 * @param string $field Meta field without prefix.
	 * @param string $default Fallback value.
	 * @return string
	 */
	private static function property_meta( $post_id, $field, $default = '' ) {
		$value = get_post_meta( $post_id, HY_Homes_Syd_Panther_Properties::META_PREFIX . $field, true );

		if ( '' === $value || null === $value ) {
			return $default;
		}

		return (string) $value;
	}

	/**
	 * Format a property price.
	 *
	 * @param string $price Raw price.
	 * @return string
	 */
	private static function format_price( $price ) {
		$price = trim( (string) $price );

		if ( '' === $price ) {
			return '';
		}

		if ( is_numeric( $price ) ) {
			return '$' . number_format_i18n( (float) $price, 0 );
		}

		return $price;
	}

	/**
	 * Convert text-like booleans.
	 *
	 * @param mixed $value Value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on' ), true );
	}

	/**
	 * Return a small inline icon.
	 *
	 * @param string $name Icon name.
	 * @return string
	 */
	private static function icon( $name ) {
		if ( 'bath' === $name ) {
			return '<svg class="hy-homes-property-card__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.875 19.524" aria-hidden="true" focusable="false"><g transform="translate(-375.918 -1725.789)" fill="currentColor"><path d="M143.385,22.448c-.27-.188-.307-.293-.212-.607.047-.157.089-.316.143-.47a.386.386,0,1,1,.735.231c-.038.139-.093.275-.124.417a.657.657,0,0,1-.274.43Z" transform="translate(235.862 1722.865)"/><path d="M150.127,22.447a.58.58,0,0,1-.286-.4c-.041-.171-.1-.337-.143-.509a.38.38,0,0,1,.72-.233,6.837,6.837,0,0,1,.2.688c.052.209-.076.341-.229.455Z" transform="translate(236.443 1722.866)"/><path d="M152.936,22.444a2,2,0,0,1-.516-.883.38.38,0,0,1,.68-.288,5.8,5.8,0,0,1,.346.693c.072.172-.017.319-.244.478Z" transform="translate(236.683 1722.869)"/><path d="M140.547,22.444c-.278-.231-.3-.354-.139-.681.076-.151.148-.307.233-.454a.362.362,0,0,1,.455-.169.332.332,0,0,1,.244.414,1.6,1.6,0,0,1-.527.89Z" transform="translate(235.612 1722.869)"/><path d="M146.749,22.45a.5.5,0,0,1-.267-.521c.012-.158,0-.318,0-.477a.394.394,0,0,1,.379-.4.386.386,0,0,1,.388.388c.008.158-.007.318.005.476a.533.533,0,0,1-.243.534Z" transform="translate(236.159 1722.863)"/><path d="M148.6,7.674a.452.452,0,0,1,.318.514c-.01.362,0,.725-.005,1.086,0,.146.04.2.184.245a4.256,4.256,0,0,1,2.9,2.689,6.652,6.652,0,0,1,.251,1.07.27.27,0,0,0,.134.221,1.291,1.291,0,0,1,.632,1.36,1.306,1.306,0,0,1-1.022,1.034,1.17,1.17,0,0,1-.246.023q-4.433,0-8.866,0a1.3,1.3,0,0,1-1.245-.94,1.284,1.284,0,0,1,.563-1.453.33.33,0,0,0,.182-.281,4.318,4.318,0,0,1,3.088-3.7c.188-.056.249-.125.238-.319-.018-.348-.005-.7,0-1.048,0-.319.088-.432.4-.509a3.594,3.594,0,0,1,.808-1.913,3.527,3.527,0,0,1,2.786-1.344c2.308-.011,4.614,0,6.922,0,.3,0,.466.153.448.411a.366.366,0,0,1-.34.348c-.057.005-.114,0-.171,0-2.25,0-4.5,0-6.75,0a2.865,2.865,0,0,0-2.414,1.226,2.62,2.62,0,0,0-.5,1.282h.889c.015-.04.037-.084.05-.131a1.97,1.97,0,0,1,1.91-1.463q3.45,0,6.9,0a.375.375,0,0,1,.417.3.367.367,0,0,1-.234.429.75.75,0,0,1-.243.026q-3.355,0-6.712,0a1.742,1.742,0,0,0-.469.057,1.192,1.192,0,0,0-.808.773m-5.451,5.651h.182c.75,0,1.5,0,2.249,0a.381.381,0,1,1,0,.761c-.2,0-.394,0-.591,0-.68,0-1.36,0-2.04,0a.533.533,0,0,0-.566.672.561.561,0,0,0,.619.4h8.615a1.021,1.021,0,0,0,.263-.032.52.52,0,0,0-.008-1,1.011,1.011,0,0,0-.262-.029c-.839,0-5.755,0-6.593,0-.273,0-.451-.15-.452-.379s.177-.382.448-.382c.724,0,5.525,0,6.25,0h.2c0-.085,0-.143-.011-.2a3.541,3.541,0,0,0-2.838-2.918,12.61,12.61,0,0,0-1.86-.079,4.036,4.036,0,0,0-.942.113,3.569,3.569,0,0,0-2.668,3.084m4.987-4.868h-1.644v.888h1.644Z" transform="translate(235.725 1721.39)"/><path d="M141.893,20.181a.414.414,0,0,1-.382-.554c.167-.368.351-.728.541-1.085a.363.363,0,0,1,.492-.149.371.371,0,0,1,.183.5c-.167.354-.336.709-.53,1.049-.06.1-.2.162-.3.243" transform="translate(235.716 1722.624)"/><path d="M152.3,19.741a.363.363,0,0,1-.246.377.341.341,0,0,1-.439-.131c-.2-.366-.392-.74-.567-1.12a.369.369,0,0,1,.2-.479.358.358,0,0,1,.48.146c.194.356.369.722.549,1.085a.382.382,0,0,1,.024.122" transform="translate(236.561 1722.624)"/><path d="M149.936,19.658c0,.271-.113.426-.282.468a.365.365,0,0,1-.44-.212c-.126-.37-.237-.746-.339-1.123a.371.371,0,0,1,.247-.4.352.352,0,0,1,.457.167c.144.389.256.79.356,1.106" transform="translate(236.37 1722.625)"/><path d="M144.891,18.789c-.012.051-.032.144-.058.234-.085.284-.164.57-.259.851a.379.379,0,0,1-.472.248.385.385,0,0,1-.257-.481c.1-.34.195-.679.306-1.015a.36.36,0,0,1,.43-.255.381.381,0,0,1,.31.417" transform="translate(235.924 1722.626)"/><path d="M147.253,19.252c0,.171.008.342,0,.513a.37.37,0,0,1-.381.366.386.386,0,0,1-.381-.373c-.009-.342-.01-.684,0-1.025a.381.381,0,0,1,.762.008c.01.171,0,.342,0,.513h0" transform="translate(236.159 1722.626)"/><path d="M150.32,16.013a1.166,1.166,0,0,1,.259.228,3.61,3.61,0,0,1,.278.54.384.384,0,1,1-.684.348,6.039,6.039,0,0,1-.292-.6c-.1-.25.086-.519.439-.519" transform="translate(236.458 1722.418)"/><path d="M143.913,16.422a.76.76,0,0,1-.042.153c-.089.188-.18.378-.28.56a.383.383,0,0,1-.505.173.379.379,0,0,1-.186-.5c.091-.208.2-.411.307-.61a.36.36,0,0,1,.443-.164.377.377,0,0,1,.263.386" transform="translate(235.839 1722.418)"/><path d="M145.585,16.481c-.062.189-.133.459-.237.715a.356.356,0,0,1-.458.164.372.372,0,0,1-.238-.426c.057-.228.122-.453.2-.673a.358.358,0,0,1,.423-.238c.189.037.31.183.31.457" transform="translate(235.996 1722.418)"/><path d="M149.116,16.918c0,.267-.118.413-.3.454a.352.352,0,0,1-.426-.2,6.252,6.252,0,0,1-.222-.725.373.373,0,0,1,.246-.407.354.354,0,0,1,.448.15c.11.26.184.536.248.729" transform="translate(236.308 1722.418)"/><path d="M147.253,16.718c0,.108.007.216,0,.323a.365.365,0,0,1-.381.365.373.373,0,0,1-.382-.367,6.476,6.476,0,0,1,0-.666.381.381,0,0,1,.393-.36.377.377,0,0,1,.368.364c.009.113,0,.228,0,.342h0" transform="translate(236.159 1722.418)"/><path d="M146.87,13.36a.381.381,0,1,1,.013-.762.381.381,0,0,1-.013.762" transform="translate(236.159 1722.115)"/></g></svg>';
		}

		return '<svg class="hy-homes-property-card__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.061 13.565" aria-hidden="true" focusable="false"><g transform="translate(-295.391 -1728.771)" fill="currentColor"><path d="M83.478,13.6A1.229,1.229,0,0,1,84.7,14.91v2.36c.1,0,.19,0,.278,0a.3.3,0,0,1,.332.325c0,.388,0,.776,0,1.164a.3.3,0,0,1-.339.339c-.082,0-.163,0-.271,0V20.17c0,.382-.072.454-.453.454-.235,0-.47,0-.705,0s-.321-.064-.37-.293c-.078-.365-.153-.729-.222-1.1-.019-.1-.058-.147-.161-.134a.916.916,0,0,1-.095,0q-6.843,0-13.687,0c-.173,0-.239.044-.267.22-.052.344-.132.684-.206,1.024a.3.3,0,0,1-.357.285c-.273,0-.546,0-.82,0-.242,0-.349-.106-.351-.346,0-.385,0-.773,0-1.179-.1,0-.19,0-.278,0a.3.3,0,0,1-.332-.325c0-.388,0-.776,0-1.164a.3.3,0,0,1,.337-.339c.082,0,.164,0,.272,0v-.211c0-.705.024-1.412-.008-2.115A1.267,1.267,0,0,1,68.226,13.6v-.223q0-2.448,0-4.9a1.21,1.21,0,0,1,1.281-1.272H82.2a1.209,1.209,0,0,1,1.276,1.277V13.6Zm-7.321,0c0-.419,0-.824,0-1.229a1.206,1.206,0,0,1,1.2-1.2q1.848,0,3.7,0a1.2,1.2,0,0,1,1.159.9,1.75,1.75,0,0,1,.04.414c.005.372,0,.745,0,1.115h.611V8.546a.644.644,0,0,0-.738-.733H69.567a.643.643,0,0,0-.731.738V13.6h.611c0-.4,0-.788,0-1.175a1.209,1.209,0,0,1,1.245-1.252h3.585a1.209,1.209,0,0,1,1.27,1.265c0,.385,0,.771,0,1.166Zm7.932,3.664V14.981c0-.537-.226-.762-.765-.762H68.382c-.05,0-.1,0-.152,0a.613.613,0,0,0-.611.608c-.008.8,0,1.6,0,2.4,0,.01.012.021.023.036ZM67.013,18.478h17.67V17.89H67.013ZM74.931,13.6c0-.431.019-.857-.007-1.278a.58.58,0,0,0-.591-.54q-1.837-.008-3.675,0a.584.584,0,0,0-.592.54c-.024.421-.005.847-.005,1.278Zm6.715,0c0-.425.012-.831,0-1.233a.6.6,0,0,0-.607-.588q-1.829-.008-3.656,0a.57.57,0,0,0-.593.513c-.03.432-.008.869-.008,1.308Zm-14.016,5.5v.909a1.145,1.145,0,0,0,.223,0c.048-.009.121-.039.128-.071.062-.273.112-.551.167-.834Zm16.451.008h-.527a6.685,6.685,0,0,1,.127.667c.02.213.122.268.309.234.028-.005.059-.01.09-.013Z" transform="translate(229.069 1721.638)"/><path d="M67.773,20.7h-.41a.372.372,0,0,1-.422-.418c0-.257,0-.513,0-.775v-.332h-.021c-.067,0-.125,0-.187,0a.37.37,0,0,1-.4-.4c0-.388,0-.776,0-1.164a.368.368,0,0,1,.409-.411h.2v-.14c0-.218,0-.434,0-.652.005-.479.011-.974-.011-1.46a1.328,1.328,0,0,1,1.227-1.415v-.156q0-2.449,0-4.9a1.277,1.277,0,0,1,1.353-1.344h12.7a1.277,1.277,0,0,1,1.348,1.35v5.053a1.291,1.291,0,0,1,1.22,1.375V17.2h.207a.368.368,0,0,1,.4.4c0,.389,0,.776,0,1.165a.373.373,0,0,1-.409.411c-.058,0-.116,0-.182,0h-.019v1c0,.423-.1.527-.525.527-.236,0-.474,0-.705,0-.274,0-.385-.089-.441-.349-.064-.3-.148-.7-.222-1.1-.015-.083-.034-.083-.081-.076a.611.611,0,0,1-.077,0H80.743q-5.867,0-11.732,0c-.146,0-.174.024-.2.158-.044.292-.108.585-.171.868l-.036.161a.373.373,0,0,1-.427.343h-.409m-.761-1.671h.072v.477c0,.261,0,.518,0,.774,0,.2.077.273.28.274.272,0,.544,0,.819,0,.19,0,.247-.046.286-.23l.035-.16c.062-.282.126-.573.17-.859.037-.235.152-.281.339-.281q5.867.008,11.732,0H82.73a.286.286,0,0,0,.054,0l.04,0a.2.2,0,0,1,.2.195c.074.4.156.8.22,1.1.041.193.1.234.3.235.239,0,.471,0,.705,0,.341,0,.38-.039.38-.382V19.033h.343c.188,0,.266-.081.267-.268q0-.581,0-1.163c0-.174-.079-.25-.26-.255-.058,0-.113,0-.174,0h-.175V14.916a1.154,1.154,0,0,0-1.154-1.239l-.066-.005V8.489a1.14,1.14,0,0,0-1.2-1.206h-12.7a1.141,1.141,0,0,0-1.209,1.2q0,2.449,0,4.9v.29l-.067,0a1.191,1.191,0,0,0-1.158,1.27c.022.49.015.987.01,1.468,0,.217,0,.433,0,.65v.284H66.9c-.061,0-.113,0-.164,0-.192,0-.266.079-.267.268,0,.388,0,.775,0,1.163,0,.174.079.25.261.255h.276ZM83.9,20.1a.27.27,0,0,1-.174-.052.346.346,0,0,1-.114-.256,3.388,3.388,0,0,0-.081-.446c-.016-.07-.033-.14-.047-.212l-.017-.087h.688v1.023l-.063.009-.088.012a.611.611,0,0,1-.1.01m-.256-.909c.009.041.019.082.027.123a3.771,3.771,0,0,1,.085.465.219.219,0,0,0,.057.155.225.225,0,0,0,.167.014l.03,0v-.752Zm-15.871.9c-.045,0-.088,0-.143,0h-.072V19.041h.678l-.046.238c-.045.236-.086.457-.138.683-.022.094-.167.124-.184.127a.577.577,0,0,1-.1.008m-.07-.146c.02,0,.049,0,.07,0a.357.357,0,0,0,.07,0,.2.2,0,0,0,.076-.033c.045-.208.086-.428.131-.662l.013-.069h-.359Zm17.054-1.4H66.947v-.731H84.762Zm-17.671-.145H84.617v-.443H67.091Zm17.077-1.068H67.611l-.022-.033a.12.12,0,0,1-.036-.076l0-.631c0-.58-.005-1.18,0-1.77a.688.688,0,0,1,.68-.679c.038,0,.076,0,.111,0H83.329c.579,0,.838.257.839.834ZM67.7,17.2H84.023V14.987c0-.5-.2-.689-.694-.689H68.342c-.036,0-.071,0-.1,0a.539.539,0,0,0-.542.536c-.005.589,0,1.189,0,1.768Zm14.027-3.516H76.711V13.61c0-.131,0-.26,0-.391,0-.3-.009-.617.013-.925a.644.644,0,0,1,.665-.578c1.355-.005,2.553-.005,3.656,0a.673.673,0,0,1,.68.656c.01.281.007.557.005.85,0,.127,0,.257,0,.388Zm-4.869-.145H81.58c0-.106,0-.211,0-.317,0-.291.005-.565-.005-.843a.524.524,0,0,0-.536-.517c-1.107-.005-2.3-.008-3.656,0a.494.494,0,0,0-.52.445c-.022.3-.017.612-.013.912,0,.108,0,.213,0,.32m6.091.142h-.757v-.416c0-.257,0-.514,0-.772a1.724,1.724,0,0,0-.039-.4,1.121,1.121,0,0,0-1.089-.847q-1.848,0-3.7,0a1.135,1.135,0,0,0-1.128,1.129c0,.288,0,.577,0,.871v.431H75.48V12.44a1.134,1.134,0,0,0-1.2-1.192q-1.793,0-3.585,0a1.14,1.14,0,0,0-1.173,1.179v1.247h-.755V8.558a.716.716,0,0,1,.8-.811H82.136a.717.717,0,0,1,.812.8Zm-.612-.145H82.8V8.552c0-.456-.207-.66-.667-.66H69.572c-.455,0-.659.206-.659.666V13.53h.466v-1.1A1.281,1.281,0,0,1,70.7,11.1h3.585a1.285,1.285,0,0,1,1.343,1.337v1.094h.466v-.286c0-.294,0-.582,0-.872A1.279,1.279,0,0,1,77.365,11.1q1.852,0,3.7,0a1.266,1.266,0,0,1,1.229.955,1.875,1.875,0,0,1,.044.431c0,.258,0,.516,0,.773Zm-7.327.145H69.994v-.073q0-.193,0-.385c0-.295-.008-.6.01-.9a.651.651,0,0,1,.664-.606c1.219-.007,2.457-.007,3.675,0a.653.653,0,0,1,.664.606c.016.295.012.6.009.888,0,.131,0,.262,0,.394Zm-4.87-.145h4.725l0-.323c0-.3.009-.589-.009-.878a.509.509,0,0,0-.519-.471c-1.219-.007-2.455-.007-3.675,0a.507.507,0,0,0-.519.471c-.017.293-.014.581-.01.886l0,.316" transform="translate(229.063 1721.632)"/></g></svg>';
	}

	/**
	 * Return the long right arrow icon for property card CTAs.
	 *
	 * @return string
	 */
	private static function arrow_long_right_icon() {
		return '<svg class="hy-homes-property-card__button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m18 8l4 4m0 0l-4 4m4-4H2"/></svg>';
	}

	/**
	 * Return WhatsApp icon image.
	 *
	 * @return string
	 */
	private static function whatsapp_icon() {
		return '<img class="hy-homes-whatsapp-icon" src="' . esc_url( HY_HOMES_SYD_PANTHER_URL . 'assets/images/whatsapp-icon.png' ) . '" alt="" aria-hidden="true" loading="lazy">';
	}
}
