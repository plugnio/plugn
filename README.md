## Set up Docker Dev Environment -1

Build production images 

```bash
docker compose -f docker-compose-prod.yml up --force-recreate
```

Step by step 

Run the following command after installing Docker

```bash
docker-compose up
```

This should set you up with the entire app along with MySQL and Redis. Use the following links to check it out:

* [Backend on localhost:21080](http://localhost:21080)
* [Frontend on localhost:22080](http://localhost:22080)
* [Agent API on localhost:23080](http://localhost:23080)
* [CRM API on localhost:23080](http://localhost:24080)
* [API on localhost:21080](http://localhost:25080)
* [Shortner on localhost:23080](http://localhost:26080)
* [Partner on localhost:23080](http://localhost:27080)
* [Phpmyadmin on localhost:8080](http://localhost:8080)


## Accessing terminal in backend container

```bash
docker-compose exec backend bash

# Now you can run things like
php composer.phar install 
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
- Store Created  (with campaign = utm_campaign + utm_medium)
- Premium Plan Purchase
- Tap Charge Attempt
- Order Completed
- Voucher Created

New events to add 

- Agent Signup (with campaign = utm_campaign + utm_medium)
- Item Published
- Order Initiated
- Domain Requests and Domain Request Updated - with request status
- Best Selling (cron based event)
- Item Shared
- Email Opened - with campaign details
- From Campaign - if open link from campaign - can be done by creating link in admin > campaign 
- Refunds Processed

- From Shared Link 
- Return Initiated //not having return process in our system 


- add time in all event?

https://docs.mixpanel.com/docs/data-structure/user-profiles
https://docs.mixpanel.com/docs/quickstart/connect-your-data
https://github.com/signalfx/angular-mixpanel/blob/master/README.md

https://github.com/segmentio/analytics-angular

segment + mixpanel
https://segment.com/docs/connections/spec/identify/

to publish in production

- disable segment to mixpanel flow and config mixpanel from admin 
- upload 
- git pull > ./yii init > ./yii migrate 
- composer require mixpanel.... install mixpanel 
- test config is there in admin as it should be 
- test from console 

`circleci local execute -e SLACK_ACCESS_TOKEN=xoxb-47737144055-4606269551878-bOLPfBq1x0ZvfC4OHbj7WgRP`

## MySql config  

SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

To check amount mismatched orders 

------------------

select `order`.order_uuid, `order`.total_price, payment.payment_amount_charged, `order`.store_currency_code, `order`.currency_code,  `order`.currency_rate from payment inner join `order` on `order`.payment_uuid = payment.payment_uuid where payment.payment_amount_charged = `order`.total_price and `order`.store_currency_code != `order`.currency_code and payment_current_status="CAPTURED"

## Spy pixel for campaign 

http://localhost:8888/bawes/plugn/agent/web/v1/store/log-email-campaign/campaign_08617922-5bc3-11ee-aa01-5aa7361ade0b

## menually sync events 

`./yii event/emulate --event="Best Selling"`

### Dealing with bad data accidentally sent to Mixpanel

https://medium.com/product-analytics-academy/dealing-with-bad-data-accidentally-sent-to-mixpanel-a417ecc256ba#:~:text=Important%3A%20Mixpanel%20event%20data%20is,be%20deleted%20from%20your%20project.

Todo
------------------
update cron > crontab

https://blog.logrocket.com/how-to-run-laravel-docker-compose-ubuntu-v22-04/

https://stackoverflow.com/questions/37741512/how-can-i-use-the-aramex-api-with-wsdl-and-python


//Alter table customer_address modify address_id BIGINT(20) AUTO_INCREMENT 


- list of events + data we passing 
- fire new events to add data in mixpanel 

