<?php
/**
 * Sample test demonstrating BrainMonkey patterns.
 *
 * @package WPPluginTemplate
 */

namespace WPPluginTemplate\Tests;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use WPPluginTemplate\Admin\Admin;
use WPPluginTemplate\Core\Assets;
use Mockery;

class SampleTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		Functions\stubTranslationFunctions();
		Functions\stubEscapeFunctions();
	}

	/**
	 * Test that Admin registers the correct hooks on construction.
	 */
	public function test_admin_registers_hooks() {
		$admin = new Admin();

		self::assertNotFalse(
			has_action( 'admin_menu', 'WPPluginTemplate\Admin\Admin->add_menu_page()' )
		);
		self::assertNotFalse(
			has_action( 'admin_init', 'WPPluginTemplate\Admin\Admin->register_settings()' )
		);
	}

	/**
	 * Test that Admin::register_settings calls the expected WP functions.
	 */
	public function test_admin_register_settings() {
		Functions\expect( 'register_setting' )
			->once()
			->with(
				'wp_plugin_template_settings',
				'wp_plugin_template_options',
				Mockery::type( 'array' )
			);

		Functions\expect( 'add_settings_section' )
			->once()
			->with(
				'wp_plugin_template_general',
				Mockery::type( 'string' ),
				'__return_null',
				'wp-plugin-template'
			);

		Functions\expect( 'add_settings_field' )
			->once()
			->with(
				'sample_text',
				Mockery::type( 'string' ),
				Mockery::type( 'array' ),
				'wp-plugin-template',
				'wp_plugin_template_general'
			);

		$admin = new Admin();
		$admin->register_settings();
	}

	/**
	 * Test that Admin::sanitize_options sanitizes known keys.
	 */
	public function test_admin_sanitize_options() {
		Functions\when( 'sanitize_text_field' )->returnArg();

		$admin  = new Admin();
		$result = $admin->sanitize_options( array( 'sample_text' => 'Hello World' ) );

		self::assertSame( array( 'sample_text' => 'Hello World' ), $result );
	}

	/**
	 * Test that Admin::sanitize_options ignores unknown keys.
	 */
	public function test_admin_sanitize_options_drops_unknown_keys() {
		$admin  = new Admin();
		$result = $admin->sanitize_options( array( 'unknown_key' => 'should be dropped' ) );

		self::assertSame( array(), $result );
	}

	/**
	 * Test that Assets registers the frontend enqueue hook.
	 */
	public function test_assets_registers_frontend_enqueue() {
		$assets = new Assets();

		self::assertNotFalse(
			has_action( 'wp_enqueue_scripts', 'WPPluginTemplate\Core\Assets->enqueue_frontend()' )
		);
	}

	/**
	 * Test that Assets::enqueue_frontend calls wp_enqueue_style and wp_enqueue_script.
	 */
	public function test_assets_enqueue_frontend() {
		Functions\expect( 'wp_enqueue_style' )
			->once()
			->with(
				'wp-plugin-template-main',
				Mockery::type( 'string' ),
				array(),
				Mockery::type( 'string' )
			);

		Functions\expect( 'wp_enqueue_script' )
			->once()
			->with(
				'wp-plugin-template-main',
				Mockery::type( 'string' ),
				array(),
				Mockery::type( 'string' ),
				Mockery::type( 'array' )
			);

		$assets = new Assets();
		$assets->enqueue_frontend();
	}
}
