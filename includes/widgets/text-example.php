<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Text_Example extends Widget_Base {

	public function get_name() {
		return 'text-example';
	}

	public function get_title() {
		return esc_html__( 'Text Widget', 'elementor-text-example' );
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
				'label' => esc_html__( 'Image Box', 'elementor-text-example' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'elementor-text-example' ),
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
				'label' => esc_html__( 'Content', 'elementor-text-example' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title', 'elementor-text-example' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'elementor-text-example' ),
			]
		);

        $this->add_control(
			'title_size',
			[
				'label' => esc_html__( 'Title HTML Tag', 'elementor-text-example' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'elementor-text-example' ),
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

        $has_image = ! empty( $settings['image']['url'] );
        $has_content = ! empty( $settings['title_text'] );

		if ( ! $has_image && ! $has_content ) {
			return;
		}

        $html = '<div class="elementor-text-example-wrapper">';

        if ( $has_image ) {

			$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ) );

			if ( ! empty( $settings['link']['url'] ) ) {
				$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . ' tabindex="-1">' . $image_html . '</a>';
			}

			$html .= '<figure class="elementor-text-example-img">' . $image_html . '</figure>';
		}

        if ( $has_content ) {
			$html .= '<div class="elementor-text-example-content">';

			if ( ! Utils::is_empty( $settings['title_text'] ) ) {
				$this->add_render_attribute( 'title_text', 'class', 'elementor-text-example-title' );

				$this->add_inline_editing_attributes( 'title_text', 'none' );

				$title_html = $settings['title_text'];

				$html .= sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['title_size'] ), $this->get_render_attribute_string( 'title_text' ), $title_html );
			}

            $html .= '</div>';

        }

        $html .= '</div>';

        Utils::print_unescaped_internal_string( $html );
	}


	protected function content_template() {
		?>
        <#
		var hasImage = !! settings.image.url;
		var hasContent = !! settings.title_text;

		if ( ! hasImage && ! hasContent ) {
			return;
		}

		var html = '<div class="elementor-text-example-wrapper">';

		if ( hasImage ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.thumbnail_size,
				dimension: settings.thumbnail_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			var imageHtml = '<img src="' + _.escape( image_url ) + '" class="elementor-animation-' + settings.hover_animation + '" />';

			html += '<figure class="elementor-text-example-img">' + imageHtml + '</figure>';
		}

		if ( hasContent ) {
			html += '<div class="elementor-text-example-content">';

			if ( settings.title_text ) {
				var title_html = settings.title_text,
					titleSizeTag = elementor.helpers.validateHTMLTag( settings.title_size );

				view.addRenderAttribute( 'title_text', 'class', 'elementor-image-box-title' );

				view.addInlineEditingAttributes( 'title_text', 'none' );

				html += '<' + titleSizeTag  + ' ' + view.getRenderAttributeString( 'title_text' ) + '>' + title_html + '</' + titleSizeTag  + '>';
			}

			html += '</div>';
		}

		html += '</div>';

		print( html );
		#>
		<?php
	}
}