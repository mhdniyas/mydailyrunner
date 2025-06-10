#!/bin/bash

# Run subscription related tests
echo "Running subscription tests..."
cd /var/www/ard5
php artisan test --filter=SubscriptionTest
php artisan test --filter=SubscriptionMiddlewareTest

# If you want to run all tests
# php artisan test

echo "Tests completed!"
