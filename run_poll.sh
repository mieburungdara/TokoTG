#!/bin/bash
# This script runs the long-polling process in the background using the dedicated CLI entry point.
php cli.php getupdates > /dev/null 2>&1 &
