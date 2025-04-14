<?php
/**
 * Description field partial view.
 *
 * @since 2.0.0
 */
?>

<?php if ($field->desc) : ?>

	<p class="description wu-text-2xs" id="<?php echo esc_attr($field->id); ?>-desc">

	<?php echo $field->desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	</p>

<?php endif; ?>
