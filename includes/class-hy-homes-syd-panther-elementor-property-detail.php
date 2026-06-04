<?php
/**
 * Elementor property detail widget.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Property detail widget for Elementor.
 */
final class HY_Homes_Syd_Panther_Elementor_Property_Detail extends \Elementor\Widget_Base {
	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hy_homes_syd_property_detail';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'HY Homes Property Detail', 'hy-homes-syd-panther' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-single-post';
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
				'label' => __( 'Property Detail', 'hy-homes-syd-panther' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_id',
			array(
				'label'       => __( 'Property ID', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => __( 'Leave empty on a single property page.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'results_url',
			array(
				'label'       => __( 'Results URL', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
			)
		);

		$this->add_control(
			'related_per_page',
			array(
				'label'   => __( 'Related per page', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 24,
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

		echo HY_Homes_Syd_Panther_Plugin::instance()->render_property_detail(
			array(
				'post_id'          => $settings['post_id'],
				'results_url'      => $results_url,
				'related_per_page' => $settings['related_per_page'],
			)
		);
	}
}
