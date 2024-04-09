<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Text_Example extends Widget_Base {

	public function get_name() {
		return 'text-example-widget';
	}

	public function get_title() {
		return esc_html__( 'Text Widget', 'elementor-text-example-widget' );
	}

	public function get_icon() {
		return 'eicon-nerd';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'text', 'example' ];
	}

	public function get_custom_help_url() {
		return 'https://go.elementor.com/widget-name';
	}

	protected function get_upsale_data() {
		return [];
	}

	// public function get_script_depends() {}

	// public function get_style_depends() {}

	protected function register_controls() {



		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image Box', 'elementor-text-example-widget' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'elementor-text-example-widget' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'elementor-text-example-widget' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'elementor-text-example-widget' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'elementor-text-example-widget' ),
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'elementor-text-example-widget' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f00',
				'selectors' => [
					'{{WRAPPER}} h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

    }

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title'] ) ) {
			return;
		}
		?>
		<h3>
			<?php echo $settings['title']; ?>
		</h3>
		<?php

		// Get image url
		echo '<img src="' . esc_url( $settings['image']['url'] ) . '" alt="">';

		// Get image by id
		echo wp_get_attachment_image( $settings['image']['id'], 'thumbnail' );
	}


	protected function content_template() {
		?>
		<#
		if ( '' === settings.title ) {
			return;
		}
		#>
		<h3>
			{{{ settings.title }}}
		</h3>
		<img src="{{{ settings.image.url }}}">
		<?php
	}
}