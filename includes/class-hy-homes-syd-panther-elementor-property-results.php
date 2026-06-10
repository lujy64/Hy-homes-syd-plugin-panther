<?php
/**
 * Elementor property results widget.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filtered property results widget for Elementor.
 */
final class HY_Homes_Syd_Panther_Elementor_Property_Results extends \Elementor\Widget_Base {
	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hy_homes_syd_property_results';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'HY Homes Property Results', 'hy-homes-syd-panther' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Widget categories.
	 *
	 * @return array<int,string>
	 */
	public function get_categories() {
		return array( 'hy-homes-syd-panther' );
	}

	/**
	 * Widget keywords.
	 *
	 * @return array<int,string>
	 */
	public function get_keywords() {
		return array( 'property', 'results', 'search', 'filter', 'hy homes', 'panther' );
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Property Results', 'hy-homes-syd-panther' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'results_url',
			array(
				'label'       => __( 'Results URL', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => home_url( '/properties/' ),
				'description' => __( 'Leave empty to keep searches on /properties/. Do not use /hy-properties/.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Properties per page', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 48,
			)
		);

		$this->add_control(
			'results_banner_image',
			array(
				'label'       => __( 'Results banner image URL', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://example.com/results-banner.jpg',
				'description' => __( 'Fixed banner image shown above the results filter. It does not change by selected neighborhood.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'neighborhood_source',
			array(
				'label'       => __( 'Neighborhood source', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => __( 'Auto from Localidades', 'hy-homes-syd-panther' ),
					'manual' => __( 'Manual list', 'hy-homes-syd-panther' ),
				),
				'description' => __( 'Auto reads the neighborhoods created in HY Homes Syd > Localidades.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'neighborhood_options',
			array(
				'label'       => __( 'Neighborhood options', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => 'auto',
				'description' => __( 'Use auto to read property neighborhoods, or separate custom options with pipes.', 'hy-homes-syd-panther' ),
				'condition'   => array(
					'neighborhood_source' => 'manual',
				),
			)
		);

		$this->add_control(
			'move_in_options',
			array(
				'label'       => __( 'Move-in options', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => 'Immediate|Next 2 weeks|Next month',
				'description' => __( 'Separate options with pipes, commas or new lines.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'search_label',
			array(
				'label'   => __( 'Search button label', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'NEXT SEARCH',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$results_url = '';

		if ( ! empty( $settings['results_url']['url'] ) ) {
			$results_url = $settings['results_url']['url'];
		}

		$neighborhood_source  = isset( $settings['neighborhood_source'] ) ? $settings['neighborhood_source'] : 'auto';
		$neighborhood_options = isset( $settings['neighborhood_options'] ) ? $settings['neighborhood_options'] : 'auto';

		echo HY_Homes_Syd_Panther_Plugin::instance()->render_property_results(
			array(
				'results_url'          => $results_url,
				'posts_per_page'       => $settings['posts_per_page'],
				'results_banner_image' => isset( $settings['results_banner_image'] ) ? $settings['results_banner_image'] : '',
				'neighborhood_source'  => $neighborhood_source,
				'neighborhood_options' => $neighborhood_options,
				'move_in_options'      => $settings['move_in_options'],
				'search_label'         => $settings['search_label'],
			)
		);
	}
}
