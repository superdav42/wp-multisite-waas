<?php
/**
 * Link field view.
 *
 * @since 2.0.0
 */
defined( 'ABSPATH' ) || exit;

?>
<li class="<?php echo esc_attr(trim($field->wrapper_classes)); ?>" <?php echo $field->get_wrapper_html_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<div class="wu-block wu-w-full">

	<?php

	/**
	 * Adds the partial title template.
	 *
	 * @since 2.0.0
	 */
	wu_get_template(
		'admin-pages/fields/partials/field-title',
		[
			'field' => $field,
		]
	);

	?>

	<a class="form-control <?php echo esc_attr($field->classes); ?>" type="<?php echo esc_attr($field->type); ?>" <?php echo $field->get_html_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

		<?php echo esc_html($field->display_value); ?>

	</a>

	<?php

	/**
	 * Adds the partial title template.
	 *
	 * @since 2.0.0
	 */
	wu_get_template(
		'admin-pages/fields/partials/field-description',
		[
			'field' => $field,
		]
	);

	?>

	</div>

</li>
