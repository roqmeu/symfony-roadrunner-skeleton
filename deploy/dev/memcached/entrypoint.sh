#!/bin/sh
set -eu

mkdir -p /var/run/shared/memcached
chown -R memcache:memcache /var/run/shared/memcached
chmod -R 777 /var/run/shared/memcached

exec su memcache -c "memcached \
-v \
--unix-socket="/var/run/shared/memcached/memcached.sock" \
--unix-mask="777" \
--memory-limit="${MEMORY_LIMIT:-64}" \
--conn-limit="${CONN_LIMIT:-1024}" \
--threads="${THREADS:-4}" \
--listen-backlog="${LISTEN_BACKLOG:-1024}" \
--max-item-size="${MAX_ITEM_SIZE:-1m}" \
"
