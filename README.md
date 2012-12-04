twilio-sms-group
================

A quickie PHP script for managing a moderated Twilio SMS group.

I built this when I got tired of texting 15+ people every half hour with updates on my wife's neurosurgery.

License
-------

[WTFPL, version 2.0](http://sam.zoy.org/wtfpl/)

Requirements
------------

* PHP 5.3 with the cURL extension and the [Twilio SDK](https://github.com/twilio/twilio-php)
* A [Twilio](http://twilio.com/) account and number.

Installation
------------

* Update the configuration settings and the list of known users.
* Upload somewhere web-accessible. I used a quickie EC2 instance.
* Grant your web server permissions to `feed.log`.
* Point your Twilio number at the EC2 instance's public URL.

Usage
-----

* Any text received from the moderator is passed along to the group of users.
* Any other text received is passed back to the moderator.