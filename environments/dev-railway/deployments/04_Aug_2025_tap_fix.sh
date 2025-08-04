#!/bin/sh

MYSQL_HOST="mysql.railway.internal"
MYSQL_PORT=3306
MYSQL_USER="root"
MYSQL_PASSWORD="FbSkwSvXwjsQPEfQrNvNzdLmJuTLFPyo"
MYSQL_DATABASE="railway"

echo "Waiting for MySQL at $MYSQL_HOST:$MYSQL_PORT..."
for attempt in $(seq 1 60); do
  mysql -h "$MYSQL_HOST" -P "$MYSQL_PORT" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1" >/dev/null 2>&1 && break
  echo "Attempt $attempt: retrying in 1s..."
  sleep 1
  [ "$attempt" -eq 60 ] && echo "MySQL not responding." && exit 1
done

echo "Updating is_tap_created for Grid and List-2 restaurants..."
mysql -h "$MYSQL_HOST" -P "$MYSQL_PORT" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" -e "
    UPDATE restaurant 
    SET is_tap_created = 0
    WHERE name IN ('Grid', 'List-2');
"