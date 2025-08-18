#!/bin/sh

MYSQL_HOST="mysql-eemz.railway.internal"
MYSQL_PORT=3306
MYSQL_USER="root"
MYSQL_PASSWORD="xXzEvGzMcCYiFIkfogNUjqLcGFRVHbRp"
MYSQL_DATABASE="railway"

echo "Waiting for MySQL at $MYSQL_HOST:$MYSQL_PORT..."
for attempt in $(seq 1 60); do
  mysql -h "$MYSQL_HOST" -P "$MYSQL_PORT" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1" >/dev/null 2>&1 && break
  echo "Attempt $attempt: retrying in 1s..."
  sleep 1
  [ "$attempt" -eq 60 ] && echo "MySQL not responding." && exit 1
done

echo "Converting database tables to utf8mb4 for emoji support..."

# Update order table columns
mysql -h "$MYSQL_HOST" -P "$MYSQL_PORT" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" -e "
  ALTER TABLE \`order\`
  MODIFY special_directions VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  MODIFY order_instruction VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ALTER TABLE \`item\`
  MODIFY item_description VARCHAR(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  MODIFY item_description_ar VARCHAR(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
"