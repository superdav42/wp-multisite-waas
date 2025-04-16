# Event: Register an account via Zapier

In the article [Integrating WP Ultimo with Zapier](1677127282-integrating-wp-ultimo-with-zapier.html), we discussed how to use Zapier to perform different actions within WP Ultimo based on triggers and events. In this article, we will show how you can integrate 3rd party applications. We will use Google Sheets as the source of data and send the information to WP Ultimo to register an account.

First, you need to create a **Google Sheet** under your Google Drive. Make sure you properly define each column so that you can easily map the data later.

![](assets/images/4a394388.png)After creating a Google sheet, you can log in to your Zapier account and start creating a zap.

![](assets/images/3f732cdf.png)Under the search field for **"App event"** select **"Google Sheets"**

![](assets/images/fbfd1d2a.png)

Then for the "**Event** " field select "**New spreadsheet row** " and hit "**Continue** "

![](assets/images/7d89881c.png)The next step will ask you to select a **Google Account** where the **Google Sheet** is saved. So just make sure that the right google account is specified.

![](assets/images/17095c21.png)

Under **"Set up trigger** ", you will need to select and specify the google spreadsheet and worksheet you will use where the data will be coming from. Just go ahead and fill those out and hit "**Continue** "

![](assets/images/19a646ca.png)Next is to "**test your trigger** " to make sure that your google sheet is properly connected.

![](assets/images/a7ded0c4.png)If your test is successful, you should see the result showing some values from your spreadsheets. Click "**Continue** " to proceed.

![](assets/images/38387f14.png)The next step is to set up the second action that will create or register an account in WP Ultimo. On the search field select "**WP Ultimo(2.0.2)** "

![](assets/images/55e331e0.png)

Under the "**Event** " field, select "**Register an Account in WP Ultimo** " then click the "**Continue** " button.

![](assets/images/e3981572.png)Under "**Set up an action** ", you will see different fields available for customer data, memberships, products, etc. You can map the values under your google sheet and assign them to the proper field where they should be populated as shown in the screenshot below.

![](assets/images/07ec48ba.png)

After mapping the values, you can test the action.

![](assets/images/b554459b.png)
