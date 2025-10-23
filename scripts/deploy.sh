#!/bin/bash
# scripts/deploy.sh

set -e

# Variables
NAMESPACE="knowledgecity-production"
DEPLOYMENT_NAME="knowledgecity-app"
CONTAINER_NAME="knowledgecity-app"
IMAGE_TAG="${1:-latest}"

echo "Starting deployment to AKS..."

# Check if namespace exists, create if not
if ! kubectl get namespace "$NAMESPACE" >/dev/null 2>&1; then
    echo "Creating namespace $NAMESPACE"
    kubectl create namespace "$NAMESPACE"
fi

# Create secret for ACR (if not exists)
if ! kubectl get secret acr-secret -n "$NAMESPACE" >/dev/null 2>&1; then
    echo "Creating ACR pull secret"
    kubectl create secret docker-registry acr-secret \
        --namespace "$NAMESPACE" \
        --docker-server=myacr.azurecr.io \
        --docker-username=$ACR_USERNAME \
        --docker-password=$ACR_PASSWORD
fi

# Update deployment with new image
echo "Updating deployment with image tag: $IMAGE_TAG"
kubectl set image deployment/$DEPLOYMENT_NAME $CONTAINER_NAME=myacr.azurecr.io/knowledgecity-app:$IMAGE_TAG -n $NAMESPACE

# Wait for rollout to complete
echo "Waiting for rollout to complete..."
kubectl rollout status deployment/$DEPLOYMENT_NAME -n $NAMESPACE --timeout=300s

# Check deployment status
echo "Deployment completed successfully!"
echo "Current pod status:"
kubectl get pods -n $NAMESPACE -l app=knowledgecity-app
