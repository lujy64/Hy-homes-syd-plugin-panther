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
			'results_url'         => '',
			'related_per_page'    => '4',
			'related_title'       => 'PROPIEDADES RELACIONADAS',
			'banner_button_label' => 'EXPLORE OUR LOCATIONS',
			'banner_button_url'   => 'https://hyhomessyd.com/#locatios',
			'show_search'         => 'true',
			'show_breadcrumbs'    => 'true',
		);

		$atts    = shortcode_atts( $defaults, (array) $atts, 'hy_homes_property_detail' );
		$post_id = self::resolve_property_id( $atts['post_id'] );

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
		$url = self::get_whatsapp_url( $post_id );

		if ( '' === $url ) {
			return '';
		}

		return '<a class="hy-homes-detail__whatsapp" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . self::whatsapp_icon() . '<span>' . esc_html__( 'INQUIRE ABOUT THIS PROPERTY', 'hy-homes-syd-panther' ) . '</span></a>';
	}

	/**
	 * Render the property Google map.
	 *
	 * @param int    $post_id Property post ID.
	 * @param string $address_line Address line.
	 * @return string
	 */
	private static function detail_map( $post_id, $address_line ) {
		$map_url = self::property_meta( $post_id, 'map_embed_url', '' );

		if ( '' === $map_url && '' !== $address_line ) {
			$map_url = 'https://www.google.com/maps?q=' . rawurlencode( $address_line ) . '&output=embed';
		}

		if ( '' === $map_url ) {
			return '';
		}

		return '<div class="hy-homes-detail-map"><iframe src="' . esc_url( $map_url ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>';
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
			<?php echo self::related_pagination( $query, get_permalink( $post_id ) ); ?>
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
		<section class="<?php echo esc_attr( $classes ); ?>" data-hy-homes-carousel style="--hy-homes-carousel-columns:1;">
			<div class="hy-homes-carousel__header hy-homes-location-banner__header">
				<div class="hy-homes-carousel__nav">
					<button class="hy-homes-carousel__button hy-homes-carousel__button--prev" type="button" data-hy-homes-carousel-prev aria-label="<?php esc_attr_e( 'Previous banner', 'hy-homes-syd-panther' ); ?>">&lt;</button>
					<button class="hy-homes-carousel__button hy-homes-carousel__button--next" type="button" data-hy-homes-carousel-next aria-label="<?php esc_attr_e( 'Next banner', 'hy-homes-syd-panther' ); ?>">&gt;</button>
				</div>
			</div>
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
		$detail_url   = self::property_meta( $post_id, 'detail_url', '' );
		$detail_url   = '' !== $detail_url ? $detail_url : get_permalink( $post_id );
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
				<?php esc_html_e( 'VIEW DETAILS', 'hy-homes-syd-panther' ); ?> <span aria-hidden="true">-></span>
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
	private static function resolve_property_id( $post_id_attr ) {
		$post_id = absint( $post_id_attr );

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id || HY_Homes_Syd_Panther_Properties::POST_TYPE !== get_post_type( $post_id ) ) {
			return 0;
		}

		return $post_id;
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
	 * Return WhatsApp inquiry URL.
	 *
	 * @param int $post_id Property post ID.
	 * @return string
	 */
	private static function get_whatsapp_url( $post_id ) {
		$phone = preg_replace( '/\D+/', '', self::property_meta( $post_id, 'whatsapp_phone', '' ) );

		if ( '' === $phone ) {
			return '';
		}

		$message = sprintf(
			__( 'Hello, I am interested in this property: %1$s - %2$s', 'hy-homes-syd-panther' ),
			get_the_title( $post_id ),
			get_permalink( $post_id )
		);

		return 'https://wa.me/' . $phone . '?text=' . rawurlencode( $message );
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
	 * @return string
	 */
	private static function get_results_base_url( $results_url ) {
		$base_url = trim( (string) $results_url );

		if ( '' === $base_url ) {
			$base_url = get_permalink();
		}

		if ( ! $base_url ) {
			$base_url = home_url( '/' );
		}

		return remove_query_arg( 'hy_results_page', $base_url );
	}

	/**
	 * Return results URL for property detail searches.
	 *
	 * @param string $results_url Optional configured URL.
	 * @return string
	 */
	private static function get_property_detail_results_url( $results_url ) {
		$base_url = trim( (string) $results_url );

		if ( '' !== $base_url ) {
			return remove_query_arg( 'hy_results_page', $base_url );
		}

		$archive_url = get_post_type_archive_link( HY_Homes_Syd_Panther_Properties::POST_TYPE );

		if ( $archive_url ) {
			return $archive_url;
		}

		return home_url( '/properties/' );
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
			return '<svg class="hy-homes-property-card__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7 5a3 3 0 0 1 6 0v2h6a2 2 0 0 1 2 2v5a5 5 0 0 1-3 4.58V21h-2v-2H8v2H6v-2.42A5 5 0 0 1 3 14V9h8V5a1 1 0 0 0-2 0v1H7V5Zm-2 6v3a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-3H5Z"/></svg>';
		}

		return '<svg class="hy-homes-property-card__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 5h2v6h13a3 3 0 0 1 3 3v5h-2v-2H4v2H2V5h2Zm2 8H4v2h16v-1a1 1 0 0 0-1-1H6Zm2-7h5a3 3 0 0 1 3 3v2H8V6Zm2 2v1h4a1 1 0 0 0-1-1h-3Z"/></svg>';
	}

	/**
	 * Return WhatsApp icon SVG.
	 *
	 * @return string
	 */
	private static function whatsapp_icon() {
		return '<svg class="hy-homes-whatsapp-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12.04 3a8.78 8.78 0 0 0-7.52 13.31L3.3 21l4.82-1.18A8.76 8.76 0 1 0 12.04 3Zm0 1.7a7.06 7.06 0 0 1 5.98 10.82 7.08 7.08 0 0 1-8.94 2.58l-.32-.17-2.88.7.73-2.8-.2-.34A7.08 7.08 0 0 1 12.04 4.7Zm-3.1 3.62c-.15 0-.39.05-.6.28-.2.23-.78.76-.78 1.86s.8 2.16.91 2.31c.11.15 1.55 2.48 3.84 3.38 1.9.75 2.3.6 2.72.56.42-.04 1.35-.55 1.54-1.08.19-.53.19-.99.13-1.08-.06-.09-.21-.15-.45-.27-.24-.12-1.36-.68-1.57-.75-.21-.08-.36-.12-.51.12-.15.23-.59.75-.72.9-.13.15-.27.17-.5.06-.24-.12-1-.37-1.9-1.18-.7-.63-1.18-1.4-1.32-1.63-.14-.23-.01-.36.1-.48.11-.11.24-.28.36-.42.12-.15.16-.24.24-.4.08-.15.04-.3-.02-.42-.06-.12-.52-1.29-.72-1.77-.19-.46-.38-.47-.52-.47h-.23Z"/></svg>';
	}
}
