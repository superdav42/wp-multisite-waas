<?php
/**
 * Submit field view.
 *
 * @since 2.0.0
 */
defined( 'ABSPATH' ) || exit;

?>
<div class="<?php echo esc_attr(trim($field->wrapper_classes)); ?>" <?php $field->print_wrapper_html_attributes(); ?>>
	<button id="<?php echo esc_attr($field->id); ?>-btn" type="submit" name="<?php echo esc_attr($field->id); ?>-btn" <?php $field->print_html_attributes(); ?> class="button <?php echo esc_attr(trim($field->classes)); ?>">
		<?php echo $field->title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>
</div>
