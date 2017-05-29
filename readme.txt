=== BP Signup Member Type ===
Contributors: meitar
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TJLPJYXHSRBEE&lc=US&item_name=BP%20Signup%20Member%20Type&item_number=bp-signup-member-type&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: BuddyPress, members, member type, administration, users, management, signup, registration, customization
Requires at least: WordPress 4.4 / BuddyPress 2.8
Tested up to: 4.7
Stable tag: 0.1
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Add a "Member Type" option to the BuddyPress registration form.

== Description ==

Augment your BuddyPress sign-up form with your social network's registered [Member Types](https://codex.buddypress.org/developer/member-types/). This allows new users to self-select one or more Member Types for themselves when they register for your site. You choose which Member Types you want to allow people to register with when they fill in your signup form.

* Works with all BuddyPress member types registered by other plugins and themes.
* Seamlessly integrates with the BuddyPress registration form and administration screens.

*Donations for this plugin make up a chunk of my income. If you continue to enjoy this plugin, please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TJLPJYXHSRBEE&lc=US&item_name=BP%20Signup%20Member%20Type&item_number=bp-signup-member-type&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted). :) Thank you for your support!*

Once installed, simply access your main BuddyPress options (WordPress Dashboard &rarr; Settings &rarr; BuddyPress &rarr; Options &rarr; Main Settings) and you'll see several *member type* options for you to configure.

== Installation ==

BP Signup Member Type can be installed automatically from the WordPress plugin repository by searching for "BP Signup Member Type" in the "Add new plugin" screen of your WordPress admin site and clicking the "Install now" button.

Minimum requirements:

* [BuddyPress](https://buddypress.org/)

The plugin will automatically de-activate itself, or certain features, if these requirements are not met. If you do not see a given feature, ensure your server (and your web hosting provider) meet the above requirements!

BP Signup Member Type can also be installed manually by following these instructions:

1. [Download the latest plugin code](https://downloads.wordpress.org/plugin/bp-signup-member-type.zip) from the WordPress plugin repository.
1. Upload the unzipped `bp-signup-member-type` folder to the `/wp-content/plugins/` directory of your WordPress installation.
1. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==

= This plugin doesn't appear to do anything? =

As the name implies, your BuddyPress website must have some code that registers one or more [member types](https://codex.buddypress.org/developer/member-types/). Moreover, you must [allow user registrations on your WordPress website](http://www.wpbeginner.com/beginners-guide/how-to-allow-user-registration-on-your-wordpress-site/). If either of these conditions are not met, the plugin doesn't have anything to do!

= I have enabled "Allow multiple member types" but newly registered users still only appear to have one member type? =

Unfortunately, BuddyPress's default user interface only displays the very first member type assigned to a user, even though plugin and theme code can detect multiple member types assigned to a single user. This is a known issue and is currently being worked on, but it's being done on a volunteer basis. If this is important to you, please consider [donating](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TJLPJYXHSRBEE&lc=US&item_name=BP%20Signup%20Member%20Type&item_number=bp-signup-member-type&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted) to help resource this work!

== Changelog ==

= 0.1 =
* Initial public release.
