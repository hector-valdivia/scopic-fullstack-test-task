# Full-stack Test Task

### Task Description
Task create a web auction application for an antique items seller. The application
will allow users to bid on antique items displayed in the site and admin users to set up items
for auction. Product management and auctioning are within the scope of the application;
shopping cart and payment integration are not.

### The auto-bidding feature
The ability to activate the auto-bidding will be possible in the Item detail page. A user can
activate auto-bidding by clicking on a checkbox in the item detail page aside the Bid now
button.

The next time someone else makes a bid on the marked item, the bot should automatically
outbid them by 1.
Configuring the Auto-bidding
The Auto-bidding parameters can be configured by the user in a separate page
(you can choose at your discretion how to display / show the configurations page).
These parameters are listed below:
- Maximum bid amount (showing the maximum amount the bot can use 
  for auto-bidding purposes)
   
Note: This maximum amount will be split between all items where we 
have activated auto-bidding.

Important Note: Be mindful of the concurrency issues with auto-bidding!

### Backend part
The backend part is in the `api` folder, next is all the instruction
for running it:

* `cd api/`
* `cp .env.example .env`. Then set the environment variables according to your project needs.
* `composer install --no-scripts --ignore-platform-reqs`
* `sail up -d`
* `sail artisan key:generate`
* `sail artisan storage:link`
* `sail composer update`
* `sail artisan key:generate && sail artisan migrate:refresh && sail artisan passport:install`

### Aproach
To take care of the concurrence of the bids, the controller receive the 
requests then go to a queue and sequentially get processed in a 
different thread, so the client immediately get responded (optimistically).

You can find the documentation for insomnia 
https://insomnia.rest/download in 
`documentation/insomnia.json` folder 
