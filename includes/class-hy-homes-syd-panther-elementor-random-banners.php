<?php
/**
 * Elementor random banners widget.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Random banner carousel widget for Elementor.
 */
final class HY_Homes_Syd_Panther_Elementor_Random_Banners extends \Elementor\Widget_Base {
	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hy_homes_syd_random_banners';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'HY Homes Random Banners', 'hy-homes-syd-panther' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-3d';
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
				'label' => __( 'Random Banners', 'hy-homes-syd-panther' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
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
				'description' => __( 'Use 0 to show all banners.', 'hy-homes-syd-panther' ),
			)
		);

		$this->add_control(
			'banner_button_label',
			array(
				'label'   => __( 'Button Label', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'EXPLORE OUR LOCATIONS',
			)
		);

		$this->add_control(
			'banner_button_url',
			array(
				'label'       => __( 'Button URL', 'hy-homes-syd-panther' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://hyhomessyd.com/#locatios',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings          = $this->get_settings_for_display();
		$banner_button_url = 'https://hyhomessyd.com/#locatios';

		if ( ! empty( $settings['banner_button_url']['url'] ) ) {
			$banner_button_url = $settings['banner_button_url']['url'];
		}

		echo HY_Homes_Syd_Panther_Plugin::instance()->render_random_banners(
			array(
				'limit'               => $settings['limit'],
				'banner_button_label' => $settings['banner_button_label'],
				'banner_button_url'   => $banner_button_url,
			)
		);
	}
}
