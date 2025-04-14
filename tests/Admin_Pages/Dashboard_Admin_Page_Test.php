<?php

namespace WP_Ultimo\Admin_Pages;

use WP_UnitTestCase;

class Dashboard_Admin_Page_Test extends WP_UnitTestCase {

	/**
	 * Test the register_scripts method enqueues the necessary scripts and styles.
	 */
	public function test_register_scripts(): void {
		// Create a mock instance of Dashboard_Admin_Page and call the register_scripts method
		$dashboard_admin_page = $this->getMockBuilder(Dashboard_Admin_Page::class)
									->disableOriginalConstructor()
									->setMethods(['output'])
									->getMock();

		// Fake dates for testing
		$dashboard_admin_page->start_date = '2023-01-01';
		$dashboard_admin_page->end_date   = '2023-01-31';

		// Execute register_scripts method
		$dashboard_admin_page->register_scripts();

		// Assert scripts are registered
		$this->assertTrue(wp_script_is('wu-apex-charts', 'registered'));
		$this->assertTrue(wp_script_is('wu-vue-apex-charts', 'registered'));
		$this->assertTrue(wp_script_is('wu-dashboard-stats', 'registered'));

		// Assert styles are registered
		$this->assertTrue(wp_style_is('wu-apex-charts', 'registered'));

		// Assert scripts are enqueued
		$this->assertTrue(wp_script_is('wu-dashboard-stats', 'enqueued'));

		// Verify localized script data is correct
		$localized_vars = wp_scripts()->get_data('wu-dashboard-stats', 'data');
		$this->assertStringContainsString('"month_list":["Jan ', $localized_vars);
		$this->assertStringContainsString('"today":"', $localized_vars); // Check that today is included
		$this->assertStringContainsString('"new_mrr":"New MRR"', $localized_vars);
	}
}
