#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --render="maintenance") || true
    # Update codebase
    git pull

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Migrate database
    php artisan migrate

    # Restart Queue Service
    php artisan queue:restart

    # Clear all cache
    php artisan optimize:clear

    # Reload PHP to update opcache
    echo "" | sudo -S service php7.4-fpm reload

# Exit maintenance mode
php artisan up

# Get latest tag
deploy_tag=$(git describe --abbrev=0 --tags $(git rev-list --tags --skip=1 --max-count=1))

# Get date
date=$(date)

#build_payload
notify_payload='{"text":"WAM '"$deploy_tag"' deployed at '"$date"'"}'

#post slack notification
curl -X POST -H 'Content-type: application/json' -d "$notify_payload" https://hooks.slack.com/services/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

echo "Application deployed!"
