#!/bin/bash
# Import environment variables ...
. /etc/profile

# This script checks to see if the temperature sensor script has started.
#   If yes, then do nothing.
#   If no, then start it.

PYSCRIPT="temperature_logger.py"

pgrep -a python | grep $PYSCRIPT &> /dev/null
if [[ $? -eq 0 ]]
        then
            echo "$PYSCRIPT is running"
        else
            echo "$PYSCRIPT not running, attempting to start..."
            cd /var/www/html
            exec python "$PYSCRIPT" 28-0416850e9aff &
            # Check if script started successfully.
            pgrep -a python | grep $PYSCRIPT &> /dev/null
            if [[ $? -eq 0 ]]
                  then
                        echo "$PYSCRIPT started succesfully!"
                  else
                        echo "$PYSCRIPT failed to start"
            fi
fi
