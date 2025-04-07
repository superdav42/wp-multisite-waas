<?php
/**
 * Hidden field view.
 *
 * @since 2.0.0
 */
?>
<li class="wu-hidden wu-m-0">

	<input class="form-control wu-w-full" name="<?php echo esc_attr($field->id); ?>" type="<?php echo esc_attr($field->type); ?>" placeholder="<?php echo esc_attr($field->placeholder); ?>" value="<?php echo esc_attr($field->value); ?>" <?php echo $field->get_html_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

</li>
