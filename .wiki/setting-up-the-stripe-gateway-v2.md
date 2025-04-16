# Setting Up The Stripe Gateway (v2)

_**IMPORTANT NOTE: This article refers to WP Ultimo version 2.x.**_

You can activate up to four methods of payment on our payment settings page: Stripe, Stripe Checkout, PayPal and Manual. In this article, we will see how to integrate with **Stripe**.

## Enabling Stripe

To enable Stripe as an available payment gateway on your network, go to **WP Ultimo > Settings > Payments** and tick the toggle next to **Stripe** or **Stripe Checkout** on the Active Payment Gateways section.

![](assets/images/4c68fd96.png)

### Stripe vs Stripe Checkout:

**Stripe:** This method will show a space to insert the credit card number during the checkout.

![](assets/images/5f3c05c9.png)

**Stripe Checkout:** This method will redirect the customer to a Stripe Checkout page during the checkout.

![](assets/images/7b28765b.png)

Getting your Stripe API keys

Once Stripe is enabled as a payment gateway, you will need to populate the fields for **Stripe Publishable Key** and **Stripe Secret Key** . You can get this by logging in to your Stripe account.

_**Note:** you can activate **Sandbox mode** to test if the payment method is working._

![](assets/images/2e42357f.png)

On your Stripe dashboard, click **Developers** on the top-right corner, and then **API Keys** in the left menu.

![](assets/images/3e5fb006.png)

You can either use **Test Data** (to test if the integration is working on your production site) or not. To change this, twitch the **Viewing test data** toggle.

![](assets/images/addc6ca2.png)

Copy the value from the **Publishable key** and **Secret key** , from the **Token** column and paste it on WP Ultimo Stripe Gateway fields. Then click to **Save Changes**.

![](assets/images/cabfe357.png)

![](assets/images/51a29968.png)

## Setting up Stripe Webhook

Stripe sends webhook events that notify WP Ultimo any time an event happens on **your stripe account**.

Click **Developers** and then choose the **Webhooks** item in the left menu. Then on the right hand side click **Add endpoint** *.*

![](assets/images/608970dd.png)

You will need an **Endpoint URL** *.* WP Ultimo automatically generates the endpoint URL which you can find right below the **Webhook Listener URL** field in **WP Ultimo Stripe Gateway** section_._

![](assets/images/1e029d74.png)

**Copy** the endpoint URL and **paste** it on Stripe **Endpoint URL** field.

![](assets/images/7635581e.png)

Next is to select an **Event** *.* Under this option, you just simply need to check the **Select all events** box and click to **Add events**. After that click **Add Endpoint** to save the changes.

![](assets/images/08dd77fb.png)

That’s it, your Stripe payment integration is complete!
