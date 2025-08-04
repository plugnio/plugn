#!/bin/sh
# One-time deployment script logging completion to deployment_complete.txt
SCRIPT_NAME="04_Aug_2025_tap_fix.sh"

# Exit if deployment already completed
if grep -Fx "$SCRIPT_NAME" ./deployments/deployment_complete.txt >/dev/null 2>&1; then
    exit 0
fi

# Check if script exists
if [ ! -f "./deployments/$SCRIPT_NAME" ]; then
    echo "Error: $SCRIPT_NAME not found in ./deployments"
    exit 1
fi

# Run deployment script and log completion only on success
chmod 0755 "./deployments/$SCRIPT_NAME"
./deployments/"$SCRIPT_NAME" && echo "$SCRIPT_NAME" >> ./deployments/deployment_complete.txt