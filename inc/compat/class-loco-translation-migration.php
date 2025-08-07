<?php
/**
 * Loco Translate Domain Migration Utility
 * This should be run once to copy/rename translation files
 *
 * @package WP_Ultimo
 * @subpackage Compat
 * @since 2.4.0
 */

namespace WP_Ultimo\Compat;

class Loco_Translation_Migration {

	use \WP_Ultimo\Traits\Singleton;

	private $old_domain = 'wp-ultimo';
	private $new_domain = 'multisite-ultimate';

	public function init(): void {
		// Only run this in admin and for administrators
		if (is_admin() && current_user_can('manage_network')) {
			add_action('admin_init', [$this, 'maybe_migrate_loco_files']);
		}
	}

	public function maybe_migrate_loco_files() {
		// Check if migration has already been done
		if (get_option('loco_domain_migration_completed', false)) {
			return;
		}

		$this->migrate_loco_translation_files();

		// Mark migration as completed
		update_option('loco_domain_migration_completed', true);
	}

	private function migrate_loco_translation_files() {
		// Common Loco Translate paths
		$loco_paths = [
			WP_CONTENT_DIR . '/languages/loco/plugins/',
			WP_CONTENT_DIR . '/languages/plugins/',
			WP_LANG_DIR . '/loco/plugins/',
			WP_LANG_DIR . '/plugins/',
		];

		foreach ($loco_paths as $path) {
			if (! is_dir($path)) {
				continue;
			}

			$this->process_translation_directory($path);
		}
	}

	private function process_translation_directory($directory) {
		$files = glob($directory . $this->old_domain . '-*.{po,pot,mo}', GLOB_BRACE);

		foreach ($files as $old_file) {
			$filename     = basename($old_file);
			$new_filename = str_replace($this->old_domain, $this->new_domain, $filename);
			$new_file     = $directory . $new_filename;

			// Only copy if the new file doesn't exist
			if (! file_exists($new_file)) {
				if (copy($old_file, $new_file)) {
					error_log("Migrated translation file: {$old_file} -> {$new_file}"); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

					// Update text domain inside .po and .pot files
					$this->update_text_domain_in_file($new_file);
				}
			}
		}
	}

	private function update_text_domain_in_file($file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);

		// Only update .po and .pot files (not .mo files as they're binary)
		if (! in_array($extension, ['po', 'pot'])) {
			return;
		}

		$content = file_get_contents($file);
		if ($content === false) {
			return;
		}

		// Update the text domain references in the file
		$content = str_replace(
			'"X-Domain: ' . $this->old_domain . '\\n"',
			'"X-Domain: ' . $this->new_domain . '\\n"',
			$content
		);

		// Update any other references to the old domain
		$content = preg_replace(
			'/("Text Domain: )' . preg_quote($this->old_domain, '/') . '(\\\\n")/',
			'${1}' . $this->new_domain . '${2}',
			$content
		);

		file_put_contents($file, $content);
	}
}
