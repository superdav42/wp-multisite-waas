# Using WP Ultimo: MailChimp Integration

Syncing subscribers' emails with your MailChimp lists is really easy with our WP Ultimo: MailChimp Integration Add-on. This tutorial aims to help you get everything set up!

## Getting an API key

Before we start to get into the different settings available, we need a MailChimp API key. This will allow WP Ultimo to talk with the MailChimp API to retrieve lists, groups, and add email addresses to those lists and groups when users sign up.

Follow this tutorial to get your own MailChimp API key: [About API Keys | MailChimp](https://kb.mailchimp.com/integrations/api-integrations/about-api-keys)

[![](assets/images/519da130.png)](assets/images/519da130.png)

_Add your MailChimp API key on_ _**WP Ultimo Settings - > Add-on Settings -> MailChimp**_

## Integration Mode

The MailChimp Integration add-on offers two “integration modes”, which means there are two different ways you can use the add-on to sync email addresses.

[![](assets/images/745fb3a4.png)](assets/images/745fb3a4.png)

_Two integration modes for greater flexibility!_

### Mode 1: Multiple Lists

The default mode makes use of **Multiple Lists**. This mode will allow you to select multiple MailChimp lists for each of the plans. This is the best option if you segment your users into different lists.

For example, let’s suppose you have 3 different lists on your MailChimp account: **Newsletter** , **Plan A** , and **Plan B**. You want all users, regardless of their plan, to also be included on the Newsletter list, so they won’t miss your updates.

To achieve that, you can use the Multiple Lists mode to add both lists **Newsletter** and **Plan A** to the Plan A MailChimp Settings, and **Newsletter** and **Plan B** to the Plan B MailChimp Settings.

[![](assets/images/d39ba5f2.png)](assets/images/placeholder.svg)

_Select the default lists for new accounts and for canceled accounts_

### Mode 2: Single List + Multiple Groups

MailChimp allows you to segment your list into smaller groups of users called **Groups**. This allows you to have more control as you can send a campaign to a specific subset of your subscribers without having to create multiple copies of the same campaign on MailChimp (which makes it hard to analyze the metrics of that campaign afterward).

For example, you can have a single list called **My Network Subscribers,** and inside it have groups for **Plan A** and **Plan B**.

[![](assets/images/6726a6d4.png)](assets/images/6726a6d4.png)

_Select the default lists and groups, if using the “single list + multiple groups” integration mode_

## Default Lists and Groups

After you select which integration mode makes more sense to your particular use case, it is time to select the default lists (and groups, depending on the mode). The default list will be used when a given plan does not have a specific List selected.

You can also select the default list for canceled accounts. Whenever a user terminates his or her subscription and removes his or her account from your network, his or her email address will be moved to the list set for canceled accounts.

## Enabling Double Subscription

On the same settings page, you’ll be able to enable **Double Subscription**. This option will add the user to both the default list selected AND the list selected to that user’s plan. If this option is not enabled, the user will only be added to the list selected for the user’s plan. If none is set for that particular plan, then the user will be added to the default list selected.

[![](assets/images/92dec7f8.png)](assets/images/92dec7f8.png)

_You can have your users subscribed to both the default list and the plan’s list on sign-up_

## Selecting Lists and Groups for each of the Plans

Go to your **Plans** and select the plan you want to edit. Note that on the **Advanced Options** meta-box, a new tab called MailChimp will be available.

On that tab, you’ll be able to select multiple lists or a single list and multiple groups (depending on the integration mode) for that plan.

[![](assets/images/31879704.png)](assets/images/31879704.png)

_Select the Lists and Groups you want to use for each of your plans_

When a new user sign-up for that plan, his email address will be added to the selected lists and groups!
