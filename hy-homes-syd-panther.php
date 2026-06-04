<?php
/**
 * Plugin Name: HY Homes Syd Panther Landing
 * Plugin URI: https://thepanthersoft.com/
 * Description: Landing page elements for HY Homes Syd properties. Includes a property search filter compatible with Elementor, WPBakery and shortcodes.
 * Version: 1.1.0
 * Author: The Panther Soft - Vaira Maria Lujan
 * Text Domain: hy-homes-syd-panther
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HY_HOMES_SYD_PANTHER_VERSION', '1.1.0' );
define( 'HY_HOMES_SYD_PANTHER_FILE', __FILE__ );
define( 'HY_HOMES_SYD_PANTHER_PATH', plugin_dir_path( __FILE__ ) );
define( 'HY_HOMES_SYD_PANTHER_URL', plugin_dir_url( __FILE__ ) );

require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-properties.php';
require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-admin.php';
require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-renderer.php';

HY_Homes_Syd_Panther_Properties::init();
HY_Homes_Syd_Panther_Admin::init();

register_activation_hook( __FILE__, array( 'HY_Homes_Syd_Panther_Properties', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'HY_Homes_Syd_Panther_Properties', 'deactivate' ) );

/**
 * Main plugin bootstrap.
 */
final class HY_Homes_Syd_Panther_Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var HY_Homes_Syd_Panther_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return HY_Homes_Syd_Panther_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hooks.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_assets' ) );
		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
		add_action( 'vc_before_init', array( $this, 'register_wpbakery_elements' ) );
		add_filter( 'the_content', array( $this, 'maybe_render_auto_property_detail' ) );
	}

	/**
	 * Register frontend assets.
	 */
	public function register_assets() {
		if ( ! wp_style_is( 'hy-homes-syd-panther', 'registered' ) ) {
			wp_register_style(
				'hy-homes-syd-panther',
				HY_HOMES_SYD_PANTHER_URL . 'assets/css/hy-homes-syd-panther.css',
				array(),
				HY_HOMES_SYD_PANTHER_VERSION
			);
		}

		if ( ! wp_script_is( 'hy-homes-syd-panther', 'registered' ) ) {
			wp_register_script(
				'hy-homes-syd-panther',
				HY_HOMES_SYD_PANTHER_URL . 'assets/js/hy-homes-syd-panther.js',
				array(),
				HY_HOMES_SYD_PANTHER_VERSION,
				true
			);
		}
	}

	/**
	 * Enqueue frontend assets.
	 */
	public function enqueue_assets() {
		$this->register_assets();
		wp_enqueue_style( 'hy-homes-syd-panther' );
		wp_enqueue_script( 'hy-homes-syd-panther' );
	}

	/**
	 * Register shortcodes.
	 */
	public function register_shortcodes() {
		add_shortcode( 'hy_homes_search_filter', array( $this, 'render_search_filter_shortcode' ) );
		add_shortcode( 'panther_hy_homes_search', array( $this, 'render_search_filter_shortcode' ) );
		add_shortcode( 'hy_homes_property_results', array( $this, 'render_property_results_shortcode' ) );
		add_shortcode( 'panther_hy_homes_results', array( $this, 'render_property_results_shortcode' ) );
		add_shortcode( 'hy_homes_recent_properties_carousel', array( $this, 'render_recent_properties_carousel_shortcode' ) );
		add_shortcode( 'panther_hy_homes_recent_carousel', array( $this, 'render_recent_properties_carousel_shortcode' ) );
		add_shortcode( 'hy_homes_property_detail', array( $this, 'render_property_detail_shortcode' ) );
		add_shortcode( 'panther_hy_homes_property_detail', array( $this, 'render_property_detail_shortcode' ) );
		add_shortcode( 'hy_homes_random_banners', array( $this, 'render_random_banners_shortcode' ) );
		add_shortcode( 'panther_hy_homes_random_banners', array( $this, 'render_random_banners_shortcode' ) );
	}

	/**
	 * Render the search filter shortcode.
	 *
	 * @param array<string,string> $atts Shortcode attributes.
	 * @return string
	 */
	public function render_search_filter_shortcode( $atts ) {
		return $this->render_search_filter( $atts );
	}

	/**
	 * Render the search filter element.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public function render_search_filter( $atts = array() ) {
		$this->enqueue_assets();

		return HY_Homes_Syd_Panther_Renderer::search_filter( $atts );
	}

	/**
	 * Render the filtered property results shortcode.
	 *
	 * @param array<string,string> $atts Shortcode attributes.
	 * @return string
	 */
	public function render_property_results_shortcode( $atts ) {
		return $this->render_property_results( $atts );
	}

	/**
	 * Render the filtered property results element.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public function render_property_results( $atts = array() ) {
		$this->enqueue_assets();

		return HY_Homes_Syd_Panther_Renderer::property_results( $atts );
	}

	/**
	 * Render the recent properties carousel shortcode.
	 *
	 * @param array<string,string> $atts Shortcode attributes.
	 * @return string
	 */
	public function render_recent_properties_carousel_shortcode( $atts ) {
		return $this->render_recent_properties_carousel( $atts );
	}

	/**
	 * Render the recent properties carousel element.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public function render_recent_properties_carousel( $atts = array() ) {
		$this->enqueue_assets();

		return HY_Homes_Syd_Panther_Renderer::recent_properties_carousel( $atts );
	}

	/**
	 * Render a property detail shortcode.
	 *
	 * @param array<string,string> $atts Shortcode attributes.
	 * @return string
	 */
	public function render_property_detail_shortcode( $atts ) {
		return $this->render_property_detail( $atts );
	}

	/**
	 * Render a property detail element.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public function render_property_detail( $atts = array() ) {
		$this->enqueue_assets();

		return HY_Homes_Syd_Panther_Renderer::property_detail( $atts );
	}

	/**
	 * Render the random banners shortcode.
	 *
	 * @param array<string,string> $atts Shortcode attributes.
	 * @return string
	 */
	public function render_random_banners_shortcode( $atts ) {
		return $this->render_random_banners( $atts );
	}

	/**
	 * Render the random banners element.
	 *
	 * @param array<string,mixed> $atts Element attributes.
	 * @return string
	 */
	public function render_random_banners( $atts = array() ) {
		$this->enqueue_assets();

		return HY_Homes_Syd_Panther_Renderer::random_banners_carousel( $atts );
	}

	/**
	 * Render the detail layout automatically on single property pages.
	 *
	 * @param string $content Original post content.
	 * @return string
	 */
	public function maybe_render_auto_property_detail( $content ) {
		if ( is_admin() || ! is_singular( HY_Homes_Syd_Panther_Properties::POST_TYPE ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( has_shortcode( $content, 'hy_homes_property_detail' ) || has_shortcode( $content, 'panther_hy_homes_property_detail' ) ) {
			return $content;
		}

		return $this->render_property_detail();
	}

	/**
	 * Register Elementor category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function register_elementor_category( $elements_manager ) {
		if ( ! method_exists( $elements_manager, 'add_category' ) ) {
			return;
		}

		$elements_manager->add_category(
			'hy-homes-syd-panther',
			array(
				'title' => __( 'HY Homes Syd', 'hy-homes-syd-panther' ),
				'icon'  => 'fa fa-home',
			)
		);
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_elementor_widgets( $widgets_manager ) {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-elementor-search-filter.php';
		require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-elementor-property-results.php';
		require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-elementor-recent-properties-carousel.php';
		require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-elementor-property-detail.php';
		require_once HY_HOMES_SYD_PANTHER_PATH . 'includes/class-hy-homes-syd-panther-elementor-random-banners.php';

		$widgets = array(
			new HY_Homes_Syd_Panther_Elementor_Search_Filter(),
			new HY_Homes_Syd_Panther_Elementor_Property_Results(),
			new HY_Homes_Syd_Panther_Elementor_Recent_Properties_Carousel(),
			new HY_Homes_Syd_Panther_Elementor_Property_Detail(),
			new HY_Homes_Syd_Panther_Elementor_Random_Banners(),
		);

		if ( method_exists( $widgets_manager, 'register' ) ) {
			foreach ( $widgets as $widget ) {
				$widgets_manager->register( $widget );
			}
			return;
		}

		if ( method_exists( $widgets_manager, 'register_widget_type' ) ) {
			foreach ( $widgets as $widget ) {
				$widgets_manager->register_widget_type( $widget );
			}
		}
	}

	/**
	 * Register WPBakery elements.
	 */
	public function register_wpbakery_elements() {
		if ( ! function_exists( 'vc_map' ) ) {
			return;
		}

		vc_map(
			array(
				'name'        => __( 'HY Homes Search Filter', 'hy-homes-syd-panther' ),
				'base'        => 'hy_homes_search_filter',
				'description' => __( 'Property search bar with neighborhood, rooms and move-in date fields.', 'hy-homes-syd-panther' ),
				'category'    => __( 'HY Homes Syd', 'hy-homes-syd-panther' ),
				'icon'        => 'dashicons dashicons-search',
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Results URL', 'hy-homes-syd-panther' ),
						'param_name'  => 'results_url',
						'description' => __( 'Leave empty to search on the current page.', 'hy-homes-syd-panther' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Neighborhood label', 'hy-homes-syd-panther' ),
						'param_name' => 'neighborhood_label',
						'value'      => 'NEIGHBORHOOD',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Neighborhood options', 'hy-homes-syd-panther' ),
						'param_name' => 'neighborhood_options',
						'value'      => 'Waterloo & Zetland|Waterloo|Eastgardens|Zetland|Rosebery|Mascot|Kingsford|Kensington',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Room label', 'hy-homes-syd-panther' ),
						'param_name' => 'room_label',
						'value'      => 'ROOM TYPE',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Move-in label', 'hy-homes-syd-panther' ),
						'param_name' => 'move_in_label',
						'value'      => 'MOVE-IN DATE, SEARCH',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Move-in options', 'hy-homes-syd-panther' ),
						'param_name' => 'move_in_options',
						'value'      => 'Immediate|Next 2 weeks|Next month',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Search button label', 'hy-homes-syd-panther' ),
						'param_name' => 'search_label',
						'value'      => 'SEARCH PROPERTIES',
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => __( 'HY Homes Property Results', 'hy-homes-syd-panther' ),
				'base'        => 'hy_homes_property_results',
				'description' => __( 'Filtered property results with search bar, breadcrumbs, cards and pagination.', 'hy-homes-syd-panther' ),
				'category'    => __( 'HY Homes Syd', 'hy-homes-syd-panther' ),
				'icon'        => 'dashicons dashicons-building',
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Results URL', 'hy-homes-syd-panther' ),
						'param_name'  => 'results_url',
						'description' => __( 'Leave empty to keep the search on this page.', 'hy-homes-syd-panther' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Properties per page', 'hy-homes-syd-panther' ),
						'param_name' => 'posts_per_page',
						'value'      => '8',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Neighborhood options', 'hy-homes-syd-panther' ),
						'param_name' => 'neighborhood_options',
						'value'      => 'auto',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Move-in options', 'hy-homes-syd-panther' ),
						'param_name' => 'move_in_options',
						'value'      => 'Immediate|Next 2 weeks|Next month',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button label', 'hy-homes-syd-panther' ),
						'param_name' => 'search_label',
						'value'      => 'NEXT SEARCH',
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => __( 'HY Homes Recent Properties Carousel', 'hy-homes-syd-panther' ),
				'base'        => 'hy_homes_recent_properties_carousel',
				'description' => __( 'Carousel with the most recent property cards.', 'hy-homes-syd-panther' ),
				'category'    => __( 'HY Homes Syd', 'hy-homes-syd-panther' ),
				'icon'        => 'dashicons dashicons-images-alt2',
				'params'      => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'hy-homes-syd-panther' ),
						'param_name' => 'title',
						'value'      => 'Explore Our Available Places',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Properties to load', 'hy-homes-syd-panther' ),
						'param_name' => 'posts_per_page',
						'value'      => '12',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Columns', 'hy-homes-syd-panther' ),
						'param_name' => 'columns',
						'value'      => '4',
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => __( 'HY Homes Property Detail', 'hy-homes-syd-panther' ),
				'base'        => 'hy_homes_property_detail',
				'description' => __( 'Single property detail layout with gallery, WhatsApp, map, related properties and location banners.', 'hy-homes-syd-panther' ),
				'category'    => __( 'HY Homes Syd', 'hy-homes-syd-panther' ),
				'icon'        => 'dashicons dashicons-admin-home',
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Property ID', 'hy-homes-syd-panther' ),
						'param_name'  => 'post_id',
						'description' => __( 'Leave empty on a single property page.', 'hy-homes-syd-panther' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Results URL', 'hy-homes-syd-panther' ),
						'param_name' => 'results_url',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Related per page', 'hy-homes-syd-panther' ),
						'param_name' => 'related_per_page',
						'value'      => '4',
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => __( 'HY Homes Random Banners', 'hy-homes-syd-panther' ),
				'base'        => 'hy_homes_random_banners',
				'description' => __( 'Random carousel with all location banners from the HY Homes Syd admin panel.', 'hy-homes-syd-panther' ),
				'category'    => __( 'HY Homes Syd', 'hy-homes-syd-panther' ),
				'icon'        => 'dashicons dashicons-format-gallery',
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Limit', 'hy-homes-syd-panther' ),
						'param_name'  => 'limit',
						'value'       => '0',
						'description' => __( 'Use 0 to show all banners.', 'hy-homes-syd-panther' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Label', 'hy-homes-syd-panther' ),
						'param_name' => 'banner_button_label',
						'value'      => 'EXPLORE OUR LOCATIONS',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button URL', 'hy-homes-syd-panther' ),
						'param_name' => 'banner_button_url',
						'value'      => 'https://hyhomessyd.com/#locatios',
					),
				),
			)
		);
	}
}

HY_Homes_Syd_Panther_Plugin::instance();
