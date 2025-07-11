<?php

if ( ! class_exists('MUCD_Log') ) {

	class MUCD_Log {

		/**
		 * @readonly
		 */
		private string $log_file_path;

		/**
		 * @readonly
		 */
		private string $log_file_url;

		private $fp;

		/**
		 * @var boolean
		 */
		public $mod;

		/**
		 * @var string
		 */
		private $log_dir_path;

		/**
		 * @var string
		 */
		private $log_file_name;

		/**
		 * Constructor
		 *
		 * @since 0.2.0
		 * @param boolean $mod is log active
		 * @param string  $log_dir_path log directory
		 * @param string  $log_file_name log file name
		 */
		public function __construct($mod, $log_dir_path = '', $log_file_name = '') {
			$this->mod           = $mod;
			$this->log_dir_path  = $log_dir_path;
			$this->log_file_name = $log_file_name;
			$this->log_file_path = $log_dir_path . $log_file_name;

			$this->log_file_url = str_replace(ABSPATH, get_site_url(1, '/'), $log_dir_path) . $log_file_name;

			if ( false !== $mod) {
				$this->init_file();
			}
		}

		/**
		 * Returns log directory path
		 *
		 * @since 0.2.0
		 * @return string $this->log_dir_path
		 */
		public function dir_path() {
			return $this->log_dir_path;
		}

		/**
		 * Returns log file path
		 *
		 * @since 0.2.0
		 * @return string $this->log_file_path
		 */
		public function file_path() {
			return $this->log_file_path;
		}

		/**
		 * Returns log file name
		 *
		 * @since 0.2.0
		 * @return string $this->log_file_name
		 */
		public function file_name() {
			return $this->log_file_name;
		}

		/**
		 * Returns log file url
		 *
		 * @since 0.2.0
		 * @return string $this->log_file_url
		 */
		public function file_url() {
			return $this->log_file_url;
		}

		/**
		 * Checks if log is writable
		 *
		 * @since 0.2.0
		 * @return boolean True if plugin can writes the log, or false
		 */
		public function can_write() {
			return (is_resource($this->fp) && is_writable($this->log_file_path)); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		}

		/**
		 * Returns log mod (active or not)
		 *
		 * @since 0.2.0
		 * @return boolean $this->mod
		 */
		public function mod() {
			return $this->mod;
		}

		/**
		 * Initialize file before writing
		 *
		 * @since 0.2.0
		 * @return boolean True on success, False on failure
		 */
		private function init_file(): bool {
			if (MUCD_Files::init_dir($this->log_dir_path) !== false) {
				if ( ! $this->fp = @fopen($this->log_file_path, 'a') ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
					return false;
				}

				chmod($this->log_file_path, 0777); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_chmod
				return true;
			}

			return false;
		}

		/**
		 * Writes a message in log file
		 *
		 * @since 0.2.0
		 * @param  string $message the message to write
		 * @return boolean True on success, False on failure
		 */
		public function write_log($message): bool {
			if (false !== $this->mod && $this->can_write() ) {
				$time = @gmdate('[d/M/Y:H:i:s]');
				fwrite($this->fp, "$time $message" . "\r\n"); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
				return true;
			}

			return false;
		}

		/**
		 * Closes the log file
		 *
		 * @since 0.2.0
		 */
		public function close_log(): void {
			@fclose($this->fp); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		}
	}
}
