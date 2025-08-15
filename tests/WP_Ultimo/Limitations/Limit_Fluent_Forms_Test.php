<?php
/**
 * Tests for Fluent Forms Limitations
 *
 * @package WP_Ultimo
 * @subpackage Tests\Limitations
 * @since 2.0.0
 */

/**
 * Test Limit_Fluent_Forms
 *
 * @since 2.0.0
 */
class Limit_Fluent_Forms_Test extends \WP_UnitTestCase {

	/**
	 * Test limitation initialization with boolean true
	 */
	public function test_limit_initialization_boolean_true() {

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => [
				'forms' => [
					'enabled' => true,
					'number'  => 10,
				],
			],
		]);

		$this->assertTrue($limit->is_enabled());
		$this->assertTrue($limit->forms->enabled);
		$this->assertEquals(10, $limit->forms->number);
	}

	/**
	 * Test limitation initialization with boolean false
	 */
	public function test_limit_initialization_boolean_false() {

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => false,
			'limit'   => [
				'forms' => [
					'enabled' => false,
					'number'  => 5,
				],
			],
		]);

		$this->assertFalse($limit->is_enabled());
		$this->assertFalse($limit->forms->enabled);
		$this->assertEquals(5, $limit->forms->number);
	}

	/**
	 * Test check method with enabled form type under limit
	 */
	public function test_check_with_enabled_form_type_under_limit() {

		$limit_data = [
			'forms' => [
				'enabled' => true,
				'number'  => 10,
			],
		];

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => $limit_data,
		]);

		// Test with 5 forms (under limit of 10)
		$result = $limit->check(5, $limit_data, 'forms');
		$this->assertTrue($result);
	}

	/**
	 * Test check method with enabled form type over limit
	 */
	public function test_check_with_enabled_form_type_over_limit() {

		$limit_data = [
			'forms' => [
				'enabled' => true,
				'number'  => 10,
			],
		];

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => $limit_data,
		]);

		// Test with 15 forms (over limit of 10)
		$result = $limit->check(15, $limit_data, 'forms');
		$this->assertFalse($result);
	}

	/**
	 * Test check method with disabled form type
	 */
	public function test_check_with_disabled_form_type() {

		$limit_data = [
			'forms' => [
				'enabled' => false,
				'number'  => 10,
			],
		];

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => $limit_data,
		]);

		// Even with low count, should return false if disabled
		$result = $limit->check(2, $limit_data, 'forms');
		$this->assertFalse($result);
	}

	/**
	 * Test check method with unlimited forms (number = 0)
	 */
	public function test_check_with_unlimited_forms() {

		$limit_data = [
			'forms' => [
				'enabled' => true,
				'number'  => 0, // 0 means unlimited
			],
		];

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => $limit_data,
		]);

		// Should return true regardless of count when unlimited
		$result = $limit->check(1000, $limit_data, 'forms');
		$this->assertTrue($result);
	}

	/**
	 * Test conversational forms functionality
	 */
	public function test_conversational_forms_limits() {

		$limit_data = [
			'conversational_forms' => [
				'enabled' => true,
				'number'  => 3,
			],
		];

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => $limit_data,
		]);

		$this->assertTrue($limit->conversational_forms->enabled);
		$this->assertEquals(3, $limit->conversational_forms->number);

		// Test under limit
		$result = $limit->check(2, $limit_data, 'conversational_forms');
		$this->assertTrue($result);

		// Test over limit
		$result = $limit->check(5, $limit_data, 'conversational_forms');
		$this->assertFalse($result);
	}

	/**
	 * Test is_fluent_forms_available static method
	 */
	public function test_is_fluent_forms_available() {

		// This should return false in test environment since Fluent Forms is not loaded
		$result = \WP_Ultimo\Limitations\Limit_Fluent_Forms::is_fluent_forms_available();
		$this->assertFalse($result);
	}

	/**
	 * Test get_form_count static method
	 */
	public function test_get_form_count() {

		// Should return 0 when Fluent Forms is not available
		$result = \WP_Ultimo\Limitations\Limit_Fluent_Forms::get_form_count('forms');
		$this->assertEquals(0, $result);

		$result = \WP_Ultimo\Limitations\Limit_Fluent_Forms::get_form_count('conversational_forms');
		$this->assertEquals(0, $result);
	}

	/**
	 * Test default permissions for form types
	 */
	public function test_default_permissions() {

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => [],
		]);

		// Test default permissions for forms
		$default_forms = $limit->forms;
		$this->assertTrue($default_forms->enabled);
		$this->assertEquals('', $default_forms->number); // unlimited by default

		// Test default permissions for conversational forms
		$default_conversational = $limit->conversational_forms;
		$this->assertTrue($default_conversational->enabled);
		$this->assertEquals('', $default_conversational->number); // unlimited by default
	}

	/**
	 * Test limitation with mixed enabled/disabled form types
	 */
	public function test_mixed_form_type_limits() {

		$limit_data = [
			'forms' => [
				'enabled' => true,
				'number'  => 5,
			],
			'conversational_forms' => [
				'enabled' => false,
				'number'  => 10,
			],
		];

		$limit = new \WP_Ultimo\Limitations\Limit_Fluent_Forms([
			'enabled' => true,
			'limit'   => $limit_data,
		]);

		// Regular forms should be allowed up to 5
		$result = $limit->check(3, $limit_data, 'forms');
		$this->assertTrue($result);

		$result = $limit->check(6, $limit_data, 'forms');
		$this->assertFalse($result);

		// Conversational forms should be disabled regardless of count
		$result = $limit->check(1, $limit_data, 'conversational_forms');
		$this->assertFalse($result);
	}
}