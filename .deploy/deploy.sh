#!/bin/bash
cd /var/repo/iwgb-media || exit 1

rsync -a . /var/www/iwgb-media --delete --exclude .git --exclude .deploy --exclude .github --exclude vendor --exclude .gitignore

cd /var/repo/iwgb-media-static || exit 1
rsync -a . /var/www/iwgb-media

cd /var/www/iwgb-org-uk/public || exit 1
mkdir var
cd var || exit 1
mkdir upload

chown -R www-data:www-data /var/www/iwgb-media
chmod -R 774 /var/www/iwgb-media
runuser -l deploy -c 'cd /var/www/iwgb-media && composer install'
