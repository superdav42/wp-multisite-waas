# A First Look on Webhooks (v2)

_**ATTENTION: Note that this feature or article is for advanced users.**_

A **webhook** is a way for an app or software like WP Ultimo to provide other applications with real-time information. A webhook delivers data or payloads to other applications as it happens, meaning you **get data immediately.**

This is helpful should you need to integrate or pass certain data from WP Ultimo going to another CRM or system each time an event is triggered. For example, you need to send the user's name and email address to a mailing list each time a new user account is created.

## How to create a webhook

To create a webhook, go to your network admin dashboard. Click on **WP Ultimo > Webhooks > Add New Webhook.**

![](assets/images/b408d428.png)

When creating a new webhook you will be asked for information like **Name, URL,** and **Event**. You can use any name you want for your webhook. The most important fields are the URL and Event.

![](assets/images/00319bd2.png)

URL is the **endpoint or the destination** to which WP Ultimo will send the **payload or data**. This is the application that will receive the data.

Zapier is the most common solution that user uses to make integration with 3rd party application easier. Without a platform like Zapier, you will need to manually create a custom function that will catch the data and process it. See this article on [how to use WP Ultimo webhook with Zapier.](https://help.wpultimo.com/article/348-integrating-wp-ultimo-with-zapier)

In this article, we will be looking at the basic concept of how a webhook works and the events available in WP Ultimo. We will be using a 3rd party site called [requestbin.com](https://requestbin.com/). This site will allow us to create an endpoint and catch the payload without doing any coding. _**Disclaimer: all it will do is show us that the data has been received.**_ There will be no processing or any kind of action done to the payload.

Go to [requestbin.com](https://requestbin.com/) and click Create Request Bin.

![](assets/images/54e84256.png)

After clicking that button, it will ask you to log in if you already have an account or sign up. If you already have an account it will lead you right to their dashboard. On their dashboard, you will immediately see the endpoint or URL you can use in creating your WP Ultimo webhook.

![](assets/images/c977395b.png)

Go ahead and copy the URL and go back to WP Ultimo. Place the endpoint on the URL field and select an event from the dropdown. In this example, we will be selecting **Payment Received**.

This event is triggered whenever a user makes a payment. All the events available, their description, and payloads are listed at the bottom of the page. Click the **Add New Webhook** button to save the webhook.

![](assets/images/9435ee6c.png)

We can now send a test event to the endpoint for us to see if the webhook we created is working. We can do this by clicking **Send Test Event** under the webhook we created.

![](assets/images/33d536d0.png)

This shows a confirmation window saying that the test was successful.

![](assets/images/aed9dd77.png)

Now if we go back to the _Requestbin_ site we will see that the payload has been received containing some test data.

![](assets/images/1d33f7fa.png)

This is the basic principle of how webhook and endpoints work. If you are to create a custom endpoint, you will need to create a custom function to process the data your receive from WP Ultimo.
