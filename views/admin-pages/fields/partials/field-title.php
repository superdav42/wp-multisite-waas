<?php
/**
 * Title field partial view.
 *
 * @since 2.0.0
 */
?>

<?php if ($field->title && is_string($field->title)) : ?>

	<span class="wu-my-1 wu-text-2xs wu-uppercase wu-font-bold wu-block">

	<?php echo $field->title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php if ($field->tooltip) : ?>

		<?php echo wu_tooltip($field->tooltip); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php endif; ?>

	</span>

<?php endif; ?>
