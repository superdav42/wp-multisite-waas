<?php
/**
 * Note field view.
 *
 * @since 2.0.0
 */
defined( 'ABSPATH' ) || exit;

?>

<div class="<?php echo esc_attr(trim($field->wrapper_classes)); ?>" <?php $field->print_wrapper_html_attributes(); ?>>

	<?php echo $field->desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

</div>
