## Configure Cron Commands using following intervals


```bash
# Every 5 minutes
*/5 * * * * php ~/www/yii cron/update-transactions > /dev/null 2>&1
*/5 * * * * php ~/www/yii cron/update-stock-qty > /dev/null 2>&1

# Every 10 minutes
*/10 * * * * php ~/www/yii cron/send-reminder-email  > /dev/null 2>&1

# Every day at midnight
0 0 * * * php ~/www/yii  cron/update-voucher-status > /dev/null 2>&1
