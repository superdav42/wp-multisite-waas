<?php
/**
 * Taxes list view.
 *
 * @since 2.0.0
 */
?>
<div id="wu-tax-rates" class="<?php wu_wrap_use_container(); ?> wrap wp-ultimo">

	<h1 class="wp-heading-inline">

	<?php _e('Tax Rates', 'wp-multisite-waas'); ?>

	</h1>

	<a href="<?php echo network_admin_url('admin.php?page=wp-ultimo-settings&tab=taxes'); ?>" class="page-title-action">

	<?php _e('Go to the Tax Settings Page', 'wp-multisite-waas'); ?>

	</a>

	<!-- <p class="description"></p> -->

	<hr class="wp-header-end" />

	<div class="wu-advanced-filters">

	<div class="tablenav top">

		<div v-cloak class="alignleft actions bulkactions">

		<div v-show="creating">

			<input type="text" style="background: white !important;" class="button wu-bg-white" v-model="create_name" placeholder="<?php _e('Tax Category Name', 'wp-multisite-waas'); ?>">

			<button class="button button-primary" v-on:click.prevent="add_tax_category" v-bind:disabled="create_name.length <= 3">
			<?php _e('Create', 'wp-multisite-waas'); ?>
			</button>

			<button class="button action" v-on:click.prevent="creating = false">
			<?php _e('&larr; Back', 'wp-multisite-waas'); ?>
			</button>

		</div>

		<div v-show="switching">

			<button class="button action" v-on:click.prevent="switching = false">
			<?php _e('&larr; Back', 'wp-multisite-waas'); ?>
			</button>

			<select v-model="tax_category" class="wu-bg-white">
			<option v-cloak v-for="(tax, slug) in data" :value="slug">
				{{ tax.name }}
			</option>
			</select>

		</div>

		<div v-show="!switching && !creating">

			<input type="text" style="background: white !important;" class="button wu-bg-white" v-model="data[tax_category].name">

			<button class="button action" v-on:click.prevent="switching = true">
			<?php _e('Switch', 'wp-multisite-waas'); ?>
			</button>

			<button class="button action" v-on:click.prevent="delete_tax_category">
			<?php _e('Delete', 'wp-multisite-waas'); ?>
			</button>

			&nbsp;

			<button class="button action wu-ml-3" v-on:click.prevent="creating = true">
			<?php _e('Add new Tax Category', 'wp-multisite-waas'); ?>
			</button>

		</div>

		</div>

		<div v-cloak class="tablenav-pages one-page">

		<span class="displaying-num">

			{{data[tax_category].rates.length}} <?php _e('item(s)', 'wp-multisite-waas'); ?>

		</span>

		</div>

		<br class="clear" />

	</div>

	<table class="wp-list-table widefat fixed striped">

		<thead>

		<tr>

			<th id="cb" class="manage-column column-cb" style="width: 50px;">

			<label class="screen-reader-text" for="wu-select-2">
				<?php _e('Select All'); ?>
			</label>

			<input v-bind:disabled="!data[tax_category].rates" v-model="toggle" v-on:click="select_all" id="wu-select-2"
				type="checkbox">

			</th>

			<?php foreach ($columns as $key => $label) : ?>

			<th scope="col" id="<?php echo $key; ?>" class="manage-column sortable asc column-<?php echo $key; ?>">
				<?php echo $label; ?>
			</th>

			<?php endforeach; ?>

		</tr>

		</thead>

		<tbody id="the-list">

		<tr v-if="loading && !data[tax_category].rates.length" class="wu-text-center">

			<td colspan="<?php echo count($columns) + 1; ?>">

			<div class="wu-p-4">

				<?php _e('Loading Tax Rates...', 'wp-multisite-waas'); ?>

			</div>

			</td>

		</tr>

		<tr v-cloak v-if="!loading && !data[tax_category].rates.length" class="wu-text-center">

			<td colspan="<?php echo count($columns) + 1; ?>">

			<div class="wu-p-4">

				<?php _e('No items to display', 'wp-multisite-waas'); ?>

			</div>

			</td>

		</tr>

		</tbody>

		<tbody
		v-cloak
		:list="data[tax_category].rates"
		:element="'tbody'"
		handle=".wu-placeholder-sortable"
		ghost-class="wu-bg-white"
		drag-class="wu-bg-white"
		is="draggable"
		>

		<tr v-for="item in data[tax_category].rates" :id="'tax-rate' + item.id" v-bind:class="{selected: item.selected}">

			<th scope="row" class="check-column">

			<label class="screen-reader-text" for="wu-select-1">

				<?php _e('Select'); ?> {{item.title}}

			</label>

			<input type="checkbox" v-model="item.selected" />

			</th>

			<?php foreach ($columns as $key => $label) : ?>

			<td class="date column-<?php echo $key; ?>" data-colname="<?php echo $key; ?>">

				<?php

				/**
				 * Switch for some of the fields
				 */
				switch ($key) :
					case 'compound':
						?>

			<input type="checkbox" v-model="item.compound" />

			<?php break; ?>

					<?php
					case 'type':
						?>

			<select v-model="item.<?php echo $key; ?>" style="width: 100%;">

						<?php foreach ($types as $tax_rate_type => $tax_rate_type_label) : ?>

				<option value="<?php echo $tax_rate_type; ?>">

							<?php echo $tax_rate_type_label; ?>

				</option>

				<?php endforeach; ?>

			</select>

			<?php break; ?>

					<?php
					case 'country':
						?>

			<select v-cloak v-model="item.<?php echo $key; ?>" style="width: 100%;">

						<?php foreach (wu_get_countries_as_options() as $country_code => $country_name) : ?>

				<option value="<?php echo $country_code; ?>">

							<?php echo $country_name; ?>

				</option>

				<?php endforeach; ?>

			</select>

			<?php break; ?>

					<?php
					case 'state':
						?>

				<selectizer 
				v-cloak
				v-model="item.state" 
				:country="item.country" 
				:options="item.state_options" 
				model="state" 
				style="width: 100%;"
				placeholder="<?php esc_attr_e('Leave blank to apply to all', 'wp-multisite-waas'); ?>"
				></selectizer>

			<?php break; ?>

					<?php
					case 'city':
						?>

				<selectizer 
				v-model="item.city" 
				:state="item.state" 
				:country="item.country" 
				model="city" 
				style="width: 100%;"
				placeholder="<?php esc_attr_e('Leave blank to apply to all', 'wp-multisite-waas'); ?>"
				v-cloak
				></selectizer>

			<?php break; ?>

					<?php
					case 'move':
						?>

				<div class="wu-text-right">

				<span class="wu-placeholder-sortable dashicons-wu-menu"></span>

				</div>

			<?php break; ?>

					<?php
					default:
						?>

			<input
				class="form-control"
				name="" 
				type="text"
				placeholder="*"
				v-model="item.<?php echo $key; ?>" 
				v-cloak
			/>

			<?php break; ?>

			<?php endswitch; ?>

			</td>

			<?php endforeach; ?>

		</tr>

		</tbody>

		<tfoot>

		<tr>

			<th id="cb" class="manage-column column-cb">

			<label class="screen-reader-text" for="wu-select">

				<?php _e('Select All'); ?>

			</label>

			<input v-bind:disabled="!data[tax_category].rates.length" v-model="toggle" v-on:click="select_all" id="wu-select"
				type="checkbox">

			</th>

			<?php foreach ($columns as $key => $label) : ?>

			<th scope="col" id="<?php echo $key; ?>" class="manage-column sortable asc column-<?php echo $key; ?>">

				<?php echo $label; ?>

			</th>

			<?php endforeach; ?>

		</tr>

		</tfoot>

	</table>

	</div>

	<div class="tablenav bottom wu-bg-gray-100 wu-p-4" v-cloak v-show="!creating">

	<div class="alignleft actions">

		<button v-on:click.prevent="add_row" class="button">

		<?php _e('Add new Row', 'wp-multisite-waas'); ?>

		</button>

		<button v-on:click.prevent="delete_rows" class="button">

		<?php _e('Delete Selected Rows', 'wp-multisite-waas'); ?>

		</button>

	</div>

	<div class="alignleft actions">

		<?php

		/**
		 * Let developers print additional buttons to this screen
		 * Our very on EU VAT functions hook on this to display our VAT helper button
		 *
		 * @since 2.0.0
		 */
		do_action('wu_tax_rates_screen_additional_actions');

		?>

	</div>

	<div class="alignright actions">

		<span v-if="changed && !saveMessage && !saving" class="description"
		style="display: inline-block; line-height: 28px; margin-right: 10px;">
		<?php _e('Save your changes!', 'wp-multisite-waas'); ?>
		</span>

		<span v-if="saving" class="description" style="display: inline-block; line-height: 28px; margin-right: 10px;">
		<?php _e('Saving...', 'wp-multisite-waas'); ?>
		</span>

		<span v-if="saveMessage" class="description"
		style="display: inline-block; line-height: 28px; margin-right: 10px;">
		{{saveMessage}}
		</span>

		<button v-on:click.prevent="save" v-bind:disabled="saving" class="button button-primary">

		<?php _e('Save Tax Rates'); ?>

		</button>

	</div>

	<br class="clear" />

	</div>

	<form id="nonce_form">

	<?php wp_nonce_field('wu_tax_editing'); ?>

	</form>

	<br class="clear">

</div>
