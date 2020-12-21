#!/bin/bash
cd /var/repo/iwgb-internal-apis || exit 1

rsync -a . /var/www/iwgb-internal-apis --delete --exclude .git --exclude .deploy --exclude .github --exclude vendor --exclude .gitignore

cd /var/repo/iwgb-internal-apis-static || exit 1
rsync -a . /var/www/iwgb-internal-apis

cd /var/www/iwgb-internal-apis || exit 1
mkdir var

chown -R www-data:www-data /var/www/iwgb-internal-apis
chmod -R 774 /var/www/iwgb-internal-apis
runuser -l deploy -c 'cd /var/www/iwgb-internal-apis && composer install'
