<?php
/**
 * Class used for querying emails.
 *
 * @package WP_Ultimo
 * @subpackage Database\Posts
 * @since 2.0.0
 */

namespace WP_Ultimo\Database\Emails;

use WP_Ultimo\Database\Posts\Post_Query;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Class used for querying emails.
 *
 * @since 2.0.0
 */
class Email_Query extends Post_Query {

	/**
	 * Name for a single item
	 *
	 * @since  2.0.0
	 * @access public
	 * @var string
	 */
	protected $item_name = 'post';

	/**
	 * Plural version for a group of items.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var string
	 */
	protected $item_name_plural = 'posts';

	/**
	 * Callback function for turning IDs into objects
	 *
	 * @since  2.0.0
	 * @access public
	 * @var mixed
	 */
	protected $item_shape = \WP_Ultimo\Models\Email::class;

	/**
	 * Group to cache queries and queried items in.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var string
	 */
	protected $cache_group = 'emails';

	/**
	 * If we should use a global cache group.
	 *
	 * @since 2.1.2
	 * @var bool
	 */
	protected $global_cache = true;

	/**
	 * Modifies the query call to add our types.
	 *
	 * @since 2.0.0
	 *
	 * @param array $query Query parameters being passed.
	 * @return array
	 */
	public function query($query = []) {

		$query['type__in'] = ['system_email'];

		return parent::query($query);
	}
}
