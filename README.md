# NFTorah Wordpress Plugin
### A turnkey solution for anyone writing a Sefer Torah and offering individuals the ability to Buy/Sponsor individual letters in the Torah and providing each Buyer/Donor with an NFT.

## Licence
This plugin and all related technology will be given free of charge to any project fulfilling the requirements.
This software and any related technology is provided without any warranty - implied or otherwise.
Support is not included in the license. That must be purchased.

## Installation
Currently the best way to install this plugin and keep it up to date, is with WP Pusher
- Install https://wppusher.com/ plugin in your site.
- In the `WP Pusher` menu in your `wp-admin` panel click `Install Plugin`.
- Enter the github URL to this repository. `https://github.com/jewpaltz/NFTorah-Plugin`
- Use the `main` branch for your live site, and the `development` branch if you want to test the latest features.

## Use
The functionality is provided through a series of shortcodes

### Shortcodes
 - **[NFTorah_purchase_form]** The main form to purchase letters
 - **[NFTorah_purchase_list]** The list of people who purchased letters so far. Currently this is provided as a shortcode. Which means that care should be taken to make sure that it is only used on a page that is secured and only available to the right people. Eventually it will probably be moved to the wp-admin section.