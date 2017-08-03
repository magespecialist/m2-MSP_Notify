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

Your template must be placed under `view/adminhtml/templates` and should be something like:

```
<?php
$observedObject = $block->getObject();

echo __("Object %1 has been processed", $observedObject->getName());
?>
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
2. Then you need to know the chat id of the [group](https://stackoverflow.com/questions/32423837/telegram-bot-how-to-get-a-group-chat-id) or the [single user](https://stackoverflow.com/questions/31078710/how-to-obtain-telegram-chat-id-for-a-specific-user)

Email
-----

This is easy! Just specify email object and recipients (separated by commas)
