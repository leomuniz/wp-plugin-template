<?php
/**
 * Base test case for BrainMonkey + Mockery.
 *
 * All plugin test classes should extend this class instead of PHPUnit\Framework\TestCase.
 * It sets up and tears down BrainMonkey on every test, making WordPress hook functions
 * (add_action, add_filter, etc.) available without loading WordPress.
 *
 * @package WPPluginTemplate
 */

namespace WPPluginTemplate\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

abstract class TestCase extends PHPUnitTestCase {

	use MockeryPHPUnitIntegration;

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}
