<?php
/**
 * Image field view.
 *
 * @since 2.0.0
 */
?>
<?php

// We need to get the media scripts
wp_enqueue_media();
wp_enqueue_script('media');

$suffix = WU_Scripts()->suffix();

wp_enqueue_script('wu-field-button-upload', WP_Ultimo()->get_asset("wu-field-image$suffix.js", 'js'));

?>

<tr>
	<th scope="row"><label for="<?php echo $field_slug; ?>"><?php echo $field['title']; ?></label></th>
<td>

	<?php
	$image_url = WU_Settings::get_logo('full', wu_get_setting($field_slug));

	if ( ! $image_url && isset($field['default'])) {
		$image_url = $field['default'];
	}

	if ( $image_url ) {
		$image = '<img id="%s" src="%s" alt="%s" style="width:%s; height:auto">';
		printf(
			$image,
			$field_slug . '-preview',
			$image_url,
			get_bloginfo('name'),
			$field['width'] . 'px'
		);
	}
	?>

	<br>

	<a href="#" class="button wu-field-button-upload" data-target="<?php echo $field_slug; ?>">
	<?php echo $field['button']; ?>
	</a>

	<a data-default="<?php echo $field['default']; ?>" href="#" class="button wu-field-button-upload-remove" data-target="<?php echo $field_slug; ?>">
	<?php _e('Remove Image', 'wp-multisite-waas'); ?>
	</a>

	<?php if ( ! empty($field['desc'])) : ?>
	<p class="description" id="<?php echo $field_slug; ?>-desc">
		<?php echo $field['desc']; ?>
	</p>

	<input type="hidden" name="<?php echo $field_slug; ?>" id="<?php echo $field_slug; ?>" value="<?php echo wu_get_setting($field_slug) ?: $field['default']; ?>">

	<?php endif; ?>

</td>
</tr>
