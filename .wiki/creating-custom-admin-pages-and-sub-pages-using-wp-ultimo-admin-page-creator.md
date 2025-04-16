# Creating Custom Admin Pages and Sub-pages using WP Ultimo: Admin Page Creator

While WordPress makes it really easy to add content to the front end of sites, creating pages and sub-pages on the admin side requires some basic coding skills. WP Ultimo: Admin Page Creator aims to bring that same ease of use of the front end to the back end of your network sites.

## Why use Admin Page Creator?

Maybe you want to offer a custom page on your clients’ admin panel with tutorials, maybe you want to add a custom page with FAQs and a contact widget. The possibilities are endless with Admin Page Creator, and that’s what we will explore in today’s tutorial.

[![](assets/images/2d28aec4.png)](assets/images/2d28aec4.png)

_Creating a Tutorials Page with an embedded YouTube playlist is now possible!_

[![](assets/images/3da86f31.png)](assets/images/3da86f31.png)

_Here’s the final result on the user’s Dashboard Panel_

## Creating your first Custom Admin Page

After installing and activating WP Ultimo: Admin Page Creator, head to your network admin menu and go to Admin Pages.

[![](assets/images/ed3cade2.png)](assets/images/ed3cade2.png)

_Custom Admin Pages list on the Network Admin_

You will see a list of all the admin pages you have created so far (as this is your first visit, the list will be empty). Go ahead and click **Add new Admin Page**. You’ll be redirected to the Admin Page editor.

## The elements of a Custom Admin Page

On the Admin Page editor, you’ll have a number of different options to customize your page.

### Page Title

This is the title that will be displayed at the top of the page, not the admin menu label.

[![](assets/images/9373f6ee.png)](assets/images/9373f6ee.png)

_Enter a Page Title_

### Content Type Selector

WP Ultimo: Admin Page Creator gives you two options when it comes to the content of the page: you can either use the default WordPress WYSIWYG editor or switch to an HTML editor.

If you are not familiar with HTML, stick to the default WordPress editor. You’ll be able to edit the contents of the page just like you would with a normal Post or Page.

[![](assets/images/0fcda23c.png)](assets/images/0fcda23c.png)

_Using the default WordPress WYSIWYG editor_

[![](assets/images/96491467.png)](assets/images/96491467.png)

_Using the HTML editor option, with syntax highlight and error alerts_

## Menu Options

Over on the side, on the General Options meta-box, you’ll be able to configure the menu type, label, and much more.

### Menu Types

Your new custom admin page can have two different menu types: top-level menu and submenu.

A top-level menu page, as the name suggests, will add your newly created page to the admin menu alongside the default WordPress pages.

If you select the top-level option, you’ll be able to select the menu order and a menu icon as well.

If you want to make this page a sub-menu page, you’ll be able to select a parent page to attach this page to. You can add your custom page to default WordPress pages or to your top-level custom admin pages.

[![](assets/images/a7cfb92c.png)](assets/images/a7cfb92c.png)

_Completely customize the menu item for this Custom Page_

[![](assets/images/22c7d6c7.png)](assets/images/placeholder.svg)

_You can also add this page as a sub-menu of other menu pages, including your own custom top-level ones._

## Advanced Options

You should be able to add all sorts of content to your custom admin pages. That includes CSS rules and files, JavaScript, and external libraries as well. You can do that using the Advanced Options Tab.

### Custom CSS

On the CSS tab, you can add your own custom CSS code, as well as import CSS files from remote sites (like CDNs, Google Fonts, etc).

[![](assets/images/d1f8a7a5.png)](assets/images/d1f8a7a5.png)

_Custom CSS is also supported. You can also include external style files!_

### Custom JavaScript

The same is valid for JavaScript. You can add external libraries to use on your custom JavaScript code.

[![](assets/images/8a87663c.png)](assets/images/8a87663c.png)

_Add your own JavaScript code!_

## Permissions

Also in the Advanced Options, you’ll be able to select which sub-sites will have access to this page. This is useful if you are planning to serve different exclusive content to different plan tiers, for example.

[![](assets/images/306d243c.png)](assets/images/306d243c.png)

_You have total control over where this page is going to show up_

**Important** : Both conditions must be met for the page to be displayed. That means that if the user is a client of a given plan, but does not have one of the selected roles (or vice-versa) the page won’t appear on their panel.

## Active

If you want to disable a custom admin page without deleting it or messing with the permissions, just uncheck the Active option.

Deactivating a top-level page will automatically deactivate all the sub-pages attached to that parent page.

[![](assets/images/2c5d77a9.png)](assets/images/2c5d77a9.png)

_The final result on the user panel!_
