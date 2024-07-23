# Daily Stand Up

----

### What is this?

Running Stand Ups in a remote team is challenging. I wanted to make an app to solve
a few of the problems I've encountered. This is a simple app that allows you to
create a stand up, add team members, and then review what everyone is working on. It's very basic right now
with only the ability to record your stand up notes, view other team member's stand up notes
and view a summary of what everyone is working on.

### Set Up Guide

First and foremost, this is a Full Stack Laravel application. You can run it locally by doing the following:

1. Clone the repo.
2. Make sure you have Docker and Node installed on your machine.
3. Copy the `.env.example` file to `.env` so you can set your environment variables set up.
4. Install dependencies by running this command:

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

5. Run `./vendor/bin/sail up -d` to start your Docker containers.
6. Install npm dependencies by running `npm install`
7. Run `npm run dev` to compile your assets.
8. Finally, run migrations and seed your database by running 
   1. `./vendor/bin/sail artisan migrate`
   2. `./vendor/bin/sail artisan db:seed`
9. Create a key for your application by running `./vendor/bin/sail artisan key:generate`

The seeder should take care of creating a sample user: test@example.com, password is `password`.
