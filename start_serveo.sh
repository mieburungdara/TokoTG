#!/bin/bash

# Kill any existing serveo processes
killall ssh

# Start serveo in the background
ssh -R 80:localhost:8080 serveo.net > serveo.log 2>&1 &

# Wait for the URL to be generated
echo "Waiting for serveo URL..."
while ! grep -q "Forwarding" serveo.log; do
  sleep 1
done

# Get the URL from the log file
URL=$(grep "Forwarding" serveo.log | sed -e "s/.*https/https/" | sed -e "s/\x1b\[0m//")

# Update the base_url in the config file
sed -i "s|\$config[\']base_url\'].*|\$config[\']base_url\'] = \'$URL/\';|" application/config/config.php

# Print the new base_url
echo "Base URL updated to: $URL"

# Start the PHP server
php -S localhost:8080