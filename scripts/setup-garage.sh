#!/bin/bash
# One-time Garage S3 setup for Simple Invoices
# Run after: docker compose -f docker-compose.yml -f docker-compose.s3.yml up -d
set -euo pipefail

COMPOSE_ARGS="-f docker-compose.yml -f docker-compose.s3.yml"

echo "=== Waiting for Garage to be ready..."
docker compose ${COMPOSE_ARGS} exec -T garage garage status --check 2>/dev/null || {
    echo "Waiting for Garage health check..."
    sleep 5
    for i in $(seq 1 30); do
        if docker compose ${COMPOSE_ARGS} exec -T garage garage status --check 2>/dev/null; then
            break
        fi
        echo "  ...waiting (${i}/30)"
        sleep 2
    done
}

echo ""
echo "=== Getting Garage node ID..."
NODE_ID=$(docker compose ${COMPOSE_ARGS} exec -T garage garage status 2>/dev/null | grep -oP '^[a-f0-9]{64}' | head -1)
if [ -z "$NODE_ID" ]; then
    echo "ERROR: Could not determine Garage node ID. Is garage running?"
    exit 1
fi
echo "Node ID: ${NODE_ID:0:16}..."

echo ""
echo "=== Assigning layout (single node, zone dc1)..."
docker compose ${COMPOSE_ARGS} exec -T garage garage layout assign -z dc1 -c 1 "$NODE_ID" 2>&1 || true

echo ""
echo "=== Applying layout (version 0 = latest)..."
docker compose ${COMPOSE_ARGS} exec -T garage garage layout apply --version 0 2>&1

echo ""
echo "=== Creating bucket: si-biller-logos..."
docker compose ${COMPOSE_ARGS} exec -T garage garage bucket create si-biller-logos 2>&1 || true

echo ""
echo "=== Creating API key..."
KEY_OUTPUT=$(docker compose ${COMPOSE_ARGS} exec -T garage garage key create s3-logo-key 2>&1)
ACCESS_KEY=$(echo "$KEY_OUTPUT" | grep -oP 'Key ID: \K\S+')
SECRET_KEY=$(echo "$KEY_OUTPUT" | grep -oP 'Secret key: \K\S+')

if [ -z "$ACCESS_KEY" ] || [ -z "$SECRET_KEY" ]; then
    echo "ERROR: Could not create API key. Output was:"
    echo "$KEY_OUTPUT"
    exit 1
fi

echo ""
echo "========================================="
echo "  Garage setup complete!"
echo "  Add these to your .env file:"
echo ""
echo "  SI_S3_ENABLED=true"
echo "  SI_S3_KEY=${ACCESS_KEY}"
echo "  SI_S3_SECRET=${SECRET_KEY}"
echo "========================================="
