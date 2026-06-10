<?php
/**
 * Elementor locations widget.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Locations carousel widget for Elementor.
 */
final class HY_Homes_Syd_Panther_Elementor_Locations extends \Elementor\Widget_Base {
	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hy_homes_syd_locations';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'HY Homes Locations', 'hy-homes-syd-panther' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-map-pin';
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
	 * Register controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Locations', 'hy-homes-syd-panther' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => "Find Your Place in Sydney's Best Neighborhoods",
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => __( 'Side Label', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'LOCATIONS',
			)
		);

		$this->add_control(
			'locations',
			array(
				'label'       => __( 'Locations', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => 'auto',
				'description' => __( 'Use auto for all Localidades, or list names/slugs separated by | to control order.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'results_url',
			array(
				'label'       => __( 'Results URL', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => '/properties/',
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'   => __( 'Button Label', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'EXPLORE PROPERTIES',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'       => __( 'Limit', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'max'         => 100,
				'description' => __( 'Use 0 to show all locations.', 'hy-homes-syd-panther' ),
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

		echo HY_Homes_Syd_Panther_Plugin::instance()->render_locations(
			array(
				'title'        => $settings['title'],
				'eyebrow'      => $settings['eyebrow'],
				'locations'    => $settings['locations'],
				'results_url'  => $results_url,
				'button_label' => $settings['button_label'],
				'limit'        => $settings['limit'],
			)
		);
	}
}
