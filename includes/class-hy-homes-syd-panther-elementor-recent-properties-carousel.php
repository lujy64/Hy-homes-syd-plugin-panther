<?php
/**
 * Elementor recent properties carousel widget.
 *
 * @package HY_Homes_Syd_Panther
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recent properties carousel widget for Elementor.
 */
final class HY_Homes_Syd_Panther_Elementor_Recent_Properties_Carousel extends \Elementor\Widget_Base {
	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hy_homes_syd_recent_properties_carousel';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'HY Homes Recent Properties Carousel', 'hy-homes-syd-panther' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-push';
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
		return array( 'property', 'recent', 'carousel', 'slider', 'hy homes', 'panther' );
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Recent Properties Carousel', 'hy-homes-syd-panther' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Explore Our Available Places',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Properties to load', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 1,
				'max'     => 48,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'hy-homes-syd-panther' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 4,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo HY_Homes_Syd_Panther_Plugin::instance()->render_recent_properties_carousel(
			array(
				'title'          => $settings['title'],
				'posts_per_page' => $settings['posts_per_page'],
				'columns'        => $settings['columns'],
			)
		);
	}
}
