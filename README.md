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
