#!/bin/bash

# Fetch from last commit and start development environment
echo "🔄 Fetching latest changes..."
git fetch origin
git pull origin main

echo "☁️ Starting cloud development..."
# Add your cloud start command here (e.g., docker-compose up, npm run dev:cloud, etc.)
# Example: docker-compose up -d

echo "💻 Starting local development..."
# Add your local start command here (e.g., npm start, yarn dev, etc.)
# Example: npm run dev

echo "✅ Development environment ready!"
echo "You can now work on both cloud and local environments"