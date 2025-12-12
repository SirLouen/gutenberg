<?php
// @core-merge: Do not include these tests, they are for Gutenberg only.

/**
 * Tests for Global styles font face functions.
 *
 * @package WordPress
 * @subpackage Gutenberg
 */

class Tests_Global_Styles_Font_Faces extends WP_UnitTestCase {
	/**
	 * Ensures that only one style tag is printed when called multiple times.
	 *
	 * @group fonts
	 * @covers ::gutenberg_print_font_faces
	 */
	public function test_prints_single_style_tag_when_called_multiple_times() {
		add_filter( 'wp_theme_json_data_default', array( $this, 'add_test_font_family_to_theme_json' ) );
		WP_Theme_JSON_Resolver_Gutenberg::clean_cached_data();

		ob_start();
		gutenberg_print_font_faces();
		gutenberg_print_font_faces();
		$output = ob_get_clean();

		remove_filter( 'wp_theme_json_data_default', array( $this, 'add_test_font_family_to_theme_json' ) );
		WP_Theme_JSON_Resolver_Gutenberg::clean_cached_data();

		$this->assertSame( 1, substr_count( $output, "<style id='wp-fonts-local'>" ) );
		$this->assertStringContainsString( 'font-family:"Test Font"', $output );
	}

	/**
	 * Adds a simple font family definition to the theme.json default data.
	 *
	 * @param WP_Theme_JSON_Data_Gutenberg $theme_json_data Theme JSON data wrapper.
	 * @return WP_Theme_JSON_Data_Gutenberg Modified theme JSON data wrapper.
	 */
	public function add_test_font_family_to_theme_json( $theme_json_data ) {
		$theme_json_data->update_with(
			array(
				'settings' => array(
					'typography' => array(
						'fontFamilies' => array(
							'theme' => array(
								array(
									'fontFamily' => '"Test Font"',
									'fontFace'   => array(
										array(
											'fontFamily' => '"Test Font"',
											'fontStyle'  => 'normal',
											'fontWeight' => '400',
											'src'        => array( 'https://example.org/fonts/test-font.woff2' ),
										),
									),
								),
							),
						),
					),
				),
			)
		);

		return $theme_json_data;
	}
}
