# Creating Discount Codes (v2)

_**IMPORTANT NOTE: This article refers to WP Ultimo version 2.x.**_

With WP Ultimo you can create discount codes to give your clients discounts on their subscriptions. And creating them is easy!

## Creating and Editing Discount Codes

To create or edit a discount code, go to **WP Ultimo > Discount Codes**.

![](assets/images/18df2849.png)

There you’ll have a list of the discount codes you’ve already created.

You can click on **Add Discount** **Code** to create a new coupon or you can edit the ones you have by hovering over them and clicking **Edit**.

![](assets/images/0f286bcb.png)

![](assets/images/7b737480.png)

You will be redirected to the page where you will create or edit your coupon code. On this example we will create a new one.

![](assets/images/af4997e4.png)

Lets take a look at the settings available here:

**Enter Discount Code:** This is just the name of your discount code. This is not the code your customers will need to use on the checkout form.

**Description:** Here, you can briefly describe what this coupon is for.

![](assets/images/6401e6fb.png)

**Coupon code:** Here is where you define the code your customers will need to enter during the checkout.

![](assets/images/e0911fec.png)

**Discount:** Here, you can set either a **percentage** or a **fixed amount** of money for your discount code.

![](assets/images/49b1f35e.png)

**Apply to renewals:** If this option is toggled off, this discount code will only be applied to the **first payment**. All the other payments will have no discount. If this option is toggled on, the discount code will be valid for all future payments.

**Setup fee discount:** If this option is toggled off, the coupon code will **not give any discount for the setup fee** of the order. If this option is toggled on, you can set the discount (percentage or fixed amount) that this coupon code will apply to the setup fee of your plans.

![](assets/images/1ad0fe21.png)

**Active:** Manually activate or deactivate this coupon code.

![](assets/images/d7a883a5.png)

Under **Advanced Optio** **ns** , we have the following settings:

**Limit uses:**

  * **Uses:** Here, you can see how many times the discount code was used.

  * **Max uses:** This will limit the amount of times users can use this discount code. For example, if you put 10 here, the coupon could only be used 10 times. After this limit, the coupon code cannot be used anymore.

![](assets/images/854d9d00.png)**Start & expiration dates:** Here you will have the option to add a start date and/or an expiration date to your coupon.

![](assets/images/a9d5394d.png)

**Limit products:** If you toggle **Select products** on, all your products will be shown to you. You will have the option to manually select (by toggling on or off) which product can accept this coupon code. Products that are toggled off here will not show any change if your customers try to use this coupon code to them.

![](assets/images/220b3e3e.png)

After setting up all of these options, click on **Save Discount Code** to save your coupon and it’s done!

![](assets/images/fcb6b7f2.png)

The coupon is now on your list and, from there, you can click to **edit or delete** it.

![](assets/images/3eaaf3eb.png)

### 

### Using URL Parameters:

If you want to customize your pricing tables or build a nice coupon code page for your website and want to apply a discount code to your checkout form automatically, you can do this via URL parameters.

First, you need to get the shareable link for your plan. To do this, go to **WP Ultimo > Products** and select a plan.

Click on the **Click to Copy Shareable Link** button. This will give you the shareable link to this specific plan. In our case, the shareable link given was [_**mynetworkdomain.com/register/premium/**_](http://mynetworkdomain.com/register/premium/)_._

![](assets/images/7ef542f8.png)

To apply your discount code to this specific plan, just add the parameter **?discount_code=XXX** to the URL. Where **XXX** is the coupon code.

In our example here, we will be applying the coupon code **50OFF** to this specific product.

The URL for this specific plan and with the 50OFF discount code applied will look as: [_**mynetworkdomain.com/register/premium/**_](http://mynetworkdomain.com/register/premium/) _**?discount_code=50OFF**_.

### 
