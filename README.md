## Set up Docker Dev Environment -1

Run the following command after installing Docker

```bash
docker-compose up
```

This should set you up with the entire app along with MySQL and Redis. Use the following links to check it out:

* [Backend on localhost:21080](http://localhost:21080)
* [Store Dashboard API on localhost:23080](http://localhost:23080)
* [Phpmyadmin on localhost:8080](http://localhost:8080)


## Accessing terminal in backend container

```bash
docker-compose exec backend bash

# Now you can run things like
./init
./yii migrate
```

## Running Codeception Tests

Use `docker-compose run --rm` to launch a new backend container which will run the automated tests then destroy the container after it's done.

We have a shortcut script in the project main folder you can use to run complete tests.

```bash
# Shortcut script in project root folder.
# Launch this from your own device(host) not the container
./run-tests.sh

# What this is doing is calling
docker-compose run --rm backend vendor/bin/codecept run --fail-fast --html report-web.html

# You can also run this in the background by passing `-d` flag
# to docker-compose and check the test results in the
# outputted report-web.html
```

## Managing MySQL Database

### Using Terminal / CLI

```bash
# Connect to mysql container
docker-compose exec mysql bash

# Connect to db
mysql -uroot -p12345
```


### Using Phpmyadmin

Phpmyadmin is running on localhost port 8080.

* [http://localhost:8080](http://localhost:8080)
* Username: root
* Password: 12345

## Configure Cron Commands using following intervals

```bash
# Every  minute
* * * * * php ~/www/yii cron/site-status > /dev/null 2>&1
* * * * * php ~/www/yii cron/create-payment-gateway-account > /dev/null 2>&1

# Every 5 minutes
*/5 * * * * php ~/www/yii cron/update-transactions > /dev/null 2>&1
*/5 * * * * php ~/www/yii cron/update-stock-qty > /dev/null 2>&1
*/5 * * * * php ~/www/yii cron/send-reminder-email  > /dev/null 2>&1
*/5 * * * * php ~/www/yii cron/make-refund  > /dev/null 2>&1
*/5 * * * * php ~/www/yii cron/update-refund-status-message  > /dev/null 2>&1

# Every day at midnight
0 0 * * * php ~/www/yii  cron/update-voucher-status > /dev/null 2>&1
0 0 * * * php ~/www/yii  cron/update-sitemap  > /dev/null 2>&1
0 0 * * * php ~/www/yii  cron/retention-emails-who-passed-five-days-and-no-sales  > /dev/null 2>&1
0 0 * * * php ~/www/yii  cron/retention-emails-who-passed-two-days-and-no-products > /dev/null 2>&1
0 0 * * * php ~/www/yii  cron/notify-agents-for-subscription-that-will-expire-soon > /dev/null 2>&1
0 0 * * * php ~/www/yii  cron/downgraded-store-subscription    > /dev/null 2>&1

# Build every 10 sec
* * * * * php ~/www/yii  cron/update-voucher-status > /dev/null 2>&1
* * * * * sleep 10 && php ~/www/yii  cron/create-build-js-file > /dev/null 2>&1
* * * * * sleep 20 && php ~/www/yii  cron/create-build-js-file > /dev/null 2>&1
* * * * * sleep 30 && php ~/www/yii  cron/create-build-js-file > /dev/null 2>&1
* * * * * sleep 40 && php ~/www/yii  cron/create-build-js-file > /dev/null 2>&1
* * * * * sleep 50 && php ~/www/yii  cron/create-build-js-file > /dev/null 2>&1

# Every Sunday
0 0 * * SAT php ~/www/yii cron/weekly-report  > /dev/null 2>&1
```

## Database schema 

[https://dbdiagram.io/d/6409f34a296d97641d86b825](https://dbdiagram.io/d/6409f34a296d97641d86b825)


## Events 

- Addon Purchase
- Store Created
- Premium Plan Purchase
- Tap Charge Attempt
- Order Completed
- Voucher Created

to publish in production

- disable segment to mixpanel flow and config mixpanel from admin 
- upload 
- git pull > ./yii init > ./yii migrate 
- composer require mixpanel.... install mixpanel 
- test config is there in admin as it should be 
- test from console 

`circleci local execute -e SLACK_ACCESS_TOKEN=xoxb-47737144055-4606269551878-bOLPfBq1x0ZvfC4OHbj7WgRP`


