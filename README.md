<strong> WARNING: This module has been superseeded by MSP_Notifier, it will not be maintained anymore. Unless you are using Magento &lt;= 2.1 consider switch to the new and improved version.</strong>

MSP Notify
==========

**MSP Notify** is a Magento 2 extension that allows to be notified when something happens in your store. (e.g. "if an admin fails to login send me a message on Slack")
You can attach to any Magento 2 or custom event and get a notification to a specified channel (e.g. Slack, Telegram, Email..)

It is easily customizable and you can create your custom adapter to add even mode notification channels.

Installation
============

Suggested installation method is by using composer `composer require msp/notify`. This module needs that the Magento cron is up and running.


Basic Usage
===========

Configure a notification channel
--------------------------------
First of all you need to tell Magento how you want to be notified.

1. In the admin panel go to system => configure channels
2. Add a new channel
3. Configure your channel (see adapter specific instructions below) and save.

Link channel to events
----------------------
Then you need to subscribe to events

1. In admin panel go to system => configure events
2. Add a new event
3. Specify (one per line) to which events you want to subscribe and save
4. Select the channel you want to be notified at

When the event is triggered you receive a message on the channel you selected.

The default message contains just the event's name, if you want to have more meaningful messages read the next section.

Advanced Usage
==============

Customize message text
----------------------

You can customize the message that is sent by creating a new module containing a new `notification_templates.xml`.

Example content for that file:

```
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:MSP_Notify:etc/notification_templates.xsd">
    <templates>
        <template id="event_to_be_observed" label="Template name" file="Your_Module::path_to_your_template.phtml" module="Your_Module" />
    </templates>
</config>
```

Your template must be placed under `msp_notify` (e.g.: `app/code/Vendor/Module/msp_notify/myevent.phtml`) and should be something like:

```
<?php
$observedObject = $block->getObject();

echo __("Object %1 has been processed", $observedObject->getName());
```

Optionally in the xml file you can specify a custom Block instead of the default one.

Adding a custom channel adapter
-------------------------------

This module contains three build in adapters:

* Slack
* Telegram
* Email

if you want to add another adapter you just need to do three things:

1. Create the adapter class that implements `MSP\Notify\Api\AdapterInterface`
2. Register the new adapter in `\MSP\Notify\Base\AdapterList` using `di.xml`
3. Extend the channel form by adding your adapter specific configuration


Adapter specific instructions
=============================

Slack
-----

To use slack you need an incoming webhook that can obtained following those [instructions](https://api.slack.com/incoming-webhooks)

All the other fields are optional.

Telegram
--------

1. To use telegram you need to get a bot token from the [BotFather](https://telegram.me/botfather).
    - Open your telegram app
    - Search for  [BotFather](https://telegram.me/BotFather) user
    - Start a new chat
    - Type: `/newbot`
    - Follow the instructions.
    - At the end you will get an HTTP API token. Copy and paste this token to your token field.
2. Then you need to know the chat id:
    - Open your telegram app
    - Search for  [get_id_bot](https://telegram.me/get_id_bot) user
    - Start a new chat
    - Type: `/my_id`
    - Copy the resulting value in your `Chat Id` field

The same procedure can be applied for a Telegram group by temporary adding the `get_id_bot` as group member.
After you get the Chat ID you should remove it.

     
Email
-----

This is easy! Just specify email object and recipients (separated by commas)
