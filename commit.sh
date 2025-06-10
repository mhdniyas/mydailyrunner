#!/bin/bash

# Quick commit script with default message
COMMIT_MSG=${1:-"Update: auto-commit changes"}

echo "📝 Adding all changes..."
git add .

echo "💾 Committing with message: '$COMMIT_MSG'"
git commit -m "$COMMIT_MSG"

echo "🚀 Pushing to remote..."
git push origin main

echo "✅ Changes committed and pushed successfully!"