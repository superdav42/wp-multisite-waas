<?php

// File generated from our OpenAPI spec
namespace WP_Ultimo\Dependencies\Stripe\Terminal;

/**
 * A Configurations object represents how features should be configured for terminal readers.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object's type. Objects of the same type share the same value.
 * @property null|\Stripe\StripeObject $bbpos_wisepos_e
 * @property null|bool $is_account_default Whether this Configuration is the default for your account
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|\Stripe\StripeObject $tipping
 * @property null|\Stripe\StripeObject $verifone_p400
 */
class Configuration extends \WP_Ultimo\Dependencies\Stripe\ApiResource
{
    const OBJECT_NAME = 'terminal.configuration';
    use \WP_Ultimo\Dependencies\Stripe\ApiOperations\All;
    use \WP_Ultimo\Dependencies\Stripe\ApiOperations\Create;
    use \WP_Ultimo\Dependencies\Stripe\ApiOperations\Delete;
    use \WP_Ultimo\Dependencies\Stripe\ApiOperations\Retrieve;
    use \WP_Ultimo\Dependencies\Stripe\ApiOperations\Update;
}
