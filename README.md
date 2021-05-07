# Full-stack Test Task

### Task Description
Your task is to create a web auction application for an antique items seller. The application
will allow users to bid on antique items displayed in the site and admin users to set up items
for auction. Product management and auctioning are within the scope of the application;
shopping cart and payment integration are not.

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
