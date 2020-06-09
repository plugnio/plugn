## Configure Cron Commands using following intervals


```bash
# Update transactions Every 5 minutes
*/5 * * * * php ~/www/yii cron/update-transactions > /dev/null 2>&1

# Update transactions Every 1 hour
*/60 * * * * php ~/www/yii cron/update-stock-qty > /dev/null 2>&1
