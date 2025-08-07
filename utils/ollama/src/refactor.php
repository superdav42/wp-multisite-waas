<?php
use LLPhant\OpenAIConfig;
use LLPhant\Chat\OpenAIChat;
use ArdaGnsrn\Ollama\Resources\Chat;
//phpcs:disable
require_once __DIR__ . '/../../../vendor/autoload.php';
//$config = new OpenAiConfig();
//$config->apiKey = 'sk-12df7cbb1e3c42aabc7828db0d9e4f18';
//$config->url = 'https://mc.stonefamily.ro/openai';
//$config->model = 'qwen2.5-coder';
//$chat = new OpenAIChat($config);
//$response = $chat->generateText('what is one + one ?'); // will return something like "Two"
//var_dump($response);
//die();
//$client = \ArdaGnsrn\Ollama\Ollama::client('https://mc.stonefamily.ro/ollama');
$client = new \ArdaGnsrn\Ollama\OllamaClient('http://nas:11434', 'sk-12df7cbb1e3c42aabc7828db0d9e4f18' );
//sk-12df7cbb1e3c42aabc7828db0d9e4f18
$files = [
//	'views/emails/admin/payment-received.php',
//	'views/emails/admin/domain-created.php',
//	'views/checkout/fields/field-password.php',
//	'views/checkout/paypal/confirm.php',
//	'views/checkout/templates/pricing-table/legacy.php',
//	'views/checkout/templates/order-summary/simple.php',
//	'views/checkout/templates/template-selection/clean.php',
//	'views/checkout/templates/template-selection/legacy.php',
//	'views/checkout/templates/template-selection/minimal.php',
//	'views/checkout/templates/order-bump/simple.php',
//	'views/checkout/templates/steps/clean.php',
//	'views/checkout/templates/steps/minimal.php',
//	'views/checkout/partials/pricing-table-list.php',
//	'views/checkout/register.php',
//	'views/dashboard-statistics/widget-mrr-growth.php',
//	'views/dashboard-statistics/widget-taxes.php',
//	'views/dashboard-statistics/widget-tax-by-code.php',
//	'views/dashboard-statistics/widget-tax-graph.php',
//	'views/dashboard-statistics/widget-new-accounts.php',
//	'views/dashboard-statistics/widget-revenue.php',
//	'views/dashboard-statistics/widget-tax-by-day.php',
	'views/dashboard-statistics/widget-countries.php',
	'views/dashboard-statistics/widget-forms.php',
	'views/settings/fields/field-multi_checkbox.php',
	'views/settings/fields/field-image.php',
	'views/memberships/product-list.php',
	'views/taxes/list.php',
	'views/system-info/system-info.php',
	'views/system-info/system-info-table.php',
	'views/events/widget-payload.php',
	'views/events/widget-message.php',
	'views/events/widget-initiator.php',
	'views/about.php',
	'views/domain/dns-table.php',
	'views/domain/log.php',
	'views/ui/selectize-templates.php',
	'views/ui/template-previewer.php',
	'views/ui/branding/footer.php',
	'views/ui/branding/header.php',
	'views/ui/jumper-trigger.php',
	'views/ui/container-toggle.php',
	'views/ui/toolbox.php',
	'views/ui/jumper.php',
	'views/broadcast/widget-targets.php',
	'views/sites/edit-placeholders.php',
	'views/payments/line-item-actions.php',
	'views/payments/tax-details.php',
	'views/wizards/setup/requirements_table.php',
	'views/wizards/setup/installation_steps.php',
	'views/wizards/setup/support_terms.php',
	'views/wizards/setup/ready.php',
	'views/wizards/host-integrations/cloudflare-instructions.php',
	'views/wizards/host-integrations/runcloud-instructions.php',
	'views/wizards/host-integrations/configuration.php',
	'views/wizards/host-integrations/configuration-results.php',
	'views/wizards/host-integrations/test.php',
	'views/wizards/host-integrations/activation.php',
	'views/wizards/host-integrations/ready.php',
	'views/wizards/host-integrations/gridpane-instructions.php',
	'views/email/widget-placeholders.php',
	'views/legacy/signup/signup-steps-navigation.php',
	'views/legacy/signup/signup-nav-links.php',
	'views/legacy/signup/pricing-table/coupon-code.php',
	'views/legacy/signup/pricing-table/frequency-selector.php',
	'views/legacy/signup/pricing-table/no-plans.php',
	'views/legacy/signup/pricing-table/plan.php',
	'views/legacy/signup/steps/step-domain-url-preview.php',
	'views/legacy/signup/signup-main.php',
	'views/admin-notices.php',
	'views/checkout/fields/field-group.php',
	'views/checkout/fields/field-products.php',
	'views/checkout/fields/field-checkbox-multi.php',
	'views/checkout/fields/field-select.php',
	'views/checkout/fields/form.php',
	'views/checkout/fields/field-hidden.php',
	'views/checkout/fields/field-submit.php',
	'views/checkout/fields/field-checkbox.php',
	'views/checkout/fields/partials/field-description.php',
	'views/checkout/fields/partials/field-title.php',
	'views/checkout/fields/field-toggle.php',
	'views/checkout/fields/field-radio.php',
	'views/checkout/fields/field-payment-methods.php',
	'views/checkout/fields/field-html.php',
	'views/checkout/fields/field-text.php',
	'views/checkout/fields/field-note.php',
	'views/checkout/templates/pricing-table/list.php',
	'views/checkout/templates/steps/legacy.php',
	'views/checkout/templates/period-selection/clean.php',
	'views/checkout/templates/period-selection/legacy.php',
	'views/dashboard-statistics/filter.php',
	'views/settings/fields/field-wp_editor.php',
	'views/settings/fields/field-heading.php',
	'views/settings/fields/field-select.php',
	'views/settings/fields/field-heading_collapsible.php',
	'views/settings/fields/field-select2.php',
	'views/settings/fields/field-checkbox.php',
	'views/settings/fields/field-textarea.php',
	'views/settings/fields/field-color.php',
	'views/settings/fields/field-ajax_button.php',
	'views/settings/fields/field-text.php',
	'views/settings/fields/field-note.php',
	'views/base/edit/widget-save.php',
	'views/base/dash.php',
	'views/base/list.php',
	'views/base/edit.php',
	'views/base/wizard.php',
	'views/base/grid.php',
	'views/base/empty-state.php',
	'views/dashboard-widgets/site-maintenance.php',
	'views/broadcast/emails/base.php',
	'views/wizards/setup/default.php',
	'views/wizards/host-integrations/serverpilot-instructions.php',
	'views/dynamic-styles/template-previewer.php',
	'views/legacy/signup/pricing-table/pricing-table.php',
	'views/legacy/signup/steps/step-default.php',
	'views/wizards/host-integrations/cloudways-instructions.php',
	'views/settings/widget-settings-body.php',
	'views/base/wizard-body.php',
	'views/events/ascii-badge.php',
	'views/classes.php',
];
$chat = new Chat($client);

//foreach ( $files as $file) {
	echo 'php ' . __DIR__ . '/../../../vendor/bin/phpcs -q --parallel=4 --report=json ' . __DIR__ . '/../../../';
	$json   = shell_exec( 'php ' . __DIR__ . '/../../../vendor/bin/phpcs -q --parallel=4 --report=json ' . __DIR__ . '/../../../' );
//	$output = explode( "\n", $json );
//	$lines = file_get_contents( __DIR__ . '/../../../' . $file);
//	$prompt = "```\n" . $lines . "```\n\n";

	$problems = json_decode( $json, true );
//	var_dump( $problems );
	if(!$problems) {
		var_dump($json);
		die();
	}
	foreach ( $problems['files'] as $file => $f ) {
		$file_changed = false;
		$lines  = file( __DIR__ . '/../../../' . $file );

		foreach ( $f['messages'] as $message ) {
			if ( 'WordPress.WP.I18n.MissingTranslatorsComment' !== $message['source'] ) {
				continue;
			}
			$context = '';
			for ( $i = 1; $i > 0; $i -- ) {
				$context .= $lines [ $message['line'] - $i ];
			}
			$prompt = "I am working on a WordPress plugin and I am using PHP Code Sniffer to check for errors.\nPlease fix the following error reported by PHP Code Sniffer in the below code:\n";
			$prompt .= " Error: " . $message['source'] . ':' . " " . $message['message'] . "\n```\n" . $context . "\n```";

			echo $prompt;
			$response = $chat->create(
				[
					'model'    => 'qwen2.5-coder:latest',
					'messages' => [
						[
							'role'    => 'system',
							'content' => 'You are an expert PHP programming specializing in WordPress development. You will take PHP code and input and respond with the PHP code fixed and enhanced. You only produce valid PHP 7.4+ code. Please do not explain the code only output the fixed code if necessary. When adding a translators: comment use the short // comment syntax and place the comment in the line immediately before the translated string. If the translated string is wrapped in <?php tags wrap the comment in php tags like: `<?php // translators: %s is a placeholder for... ?>`.',
						],
						[
							'role'    => 'user',
							'content' => $prompt,
						],
					],
				]
			);

			preg_match( '/```php(.*?)```/s', $response->message->content, $matches );
			$newcode = $matches[1] ?? '';
			echo $newcode;
			$lines [ $message['line'] - 1 ] = $newcode;

			$descriptorspec = array(
				0 => array( "pipe", "r" ),  // stdout is a pipe that the child will write to
			);

			$cwd = '/tmp';
			$env = array( 'some_option' => 'aeiou' );

			$process = proc_open( 'php -l', $descriptorspec, $pipes );

			if ( is_resource( $process ) ) {
				fwrite( $pipes[0], join( '', $lines ) );
				$return_value = proc_close( $process );

				echo "command returned $return_value\n";
				if ( $return_value != 0 ) {
					// bad code
					$lines[ $message['line'] - 1 ] = $context;
				} else {
					$file_changed = true;
				}
			}

		}

		if ( $file_changed ) {
			file_put_contents( __DIR__ . '/../../../' . $file . '_qwen.php', join( '', $lines ) );
			passthru( 'php ' . __DIR__ . '/../../../vendor/bin/phpcbf ' . __DIR__ . '/../../../' . $file . '_qwen.php' );
		}
	}
//}
//
//	echo "processing $file of " . strlen($code) . "\n";


//	file_put_contents(__DIR__ . '/../../../' . $file . '.new.php', $newcode);
//}