#!/bin/bash -i
cd ..
timeout 180 python3 classify_cmdline.py "$@" 2>&1
if [ $? -eq 124 ]; then 
    echo "Sorry, execution timed out to prevent server overload."
fi