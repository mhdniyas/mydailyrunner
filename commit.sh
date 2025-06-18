#!/bin/bash

# Colors for terminal output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
RESET='\033[0m'

# Default commit message types
TYPE=${1:-"update"}
MESSAGE=${2:-"general changes"}

# Generate commit message based on type
case "$TYPE" in
  "feat"|"feature")
    COMMIT_MSG="✨ Feature: $MESSAGE"
    ;;
  "fix"|"bugfix")
    COMMIT_MSG="🐛 Fix: $MESSAGE"
    ;;
  "docs"|"documentation")
    COMMIT_MSG="📚 Docs: $MESSAGE"
    ;;
  "style")
    COMMIT_MSG="💎 Style: $MESSAGE"
    ;;
  "refactor")
    COMMIT_MSG="♻️ Refactor: $MESSAGE"
    ;;
  "perf"|"performance")
    COMMIT_MSG="⚡ Performance: $MESSAGE"
    ;;
  "test")
    COMMIT_MSG="🧪 Test: $MESSAGE"
    ;;
  "chore")
    COMMIT_MSG="🔧 Chore: $MESSAGE"
    ;;
  "remove"|"delete")
    COMMIT_MSG="🔥 Remove: $MESSAGE"
    ;;
  *)
    COMMIT_MSG="📦 Update: $MESSAGE"
    ;;
esac

# Show help message if requested
if [ "$TYPE" == "help" ] || [ "$TYPE" == "--help" ] || [ "$TYPE" == "-h" ]; then
  echo -e "${BLUE}EquipNow Commit Helper${RESET}"
  echo -e "Usage: ./commit.sh [type] [message]"
  echo
  echo -e "Available types:"
  echo -e "  ${GREEN}feat, feature${RESET}    - New feature"
  echo -e "  ${GREEN}fix, bugfix${RESET}      - Bug fix"
  echo -e "  ${GREEN}docs${RESET}             - Documentation changes"
  echo -e "  ${GREEN}style${RESET}            - Code style/formatting changes"
  echo -e "  ${GREEN}refactor${RESET}         - Code refactoring"
  echo -e "  ${GREEN}perf${RESET}             - Performance improvements"
  echo -e "  ${GREEN}test${RESET}             - Adding/updating tests"
  echo -e "  ${GREEN}chore${RESET}            - Build process or tooling changes"
  echo -e "  ${GREEN}remove${RESET}           - Removing code or files"
  echo
  echo -e "Example: ${YELLOW}./commit.sh feat 'add new booking feature'${RESET}"
  exit 0
fi

# Check if there are changes to commit
if [ "$TYPE" == "run" ]; then
  echo -e "${YELLOW}Running script without committing changes...${RESET}"
  RUN_ONLY=true
else
  RUN_ONLY=false
fi

# Check for required message
if [ "$TYPE" != "run" ] && [ -z "$MESSAGE" ] && [ "$TYPE" != "update" ]; then
  echo -e "${RED}Error: You must provide a commit message!${RESET}"
  echo -e "Example: ${YELLOW}./commit.sh $TYPE 'your specific message here'${RESET}"
  exit 1
fi

# Laravel cache clearing
echo -e "${BLUE}🧹 Clearing Laravel caches...${RESET}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Asset building
echo -e "${BLUE}🔨 Building assets...${RESET}"
npm run build

# Laravel optimization
echo -e "${BLUE}⚡ Optimizing Laravel...${RESET}"
php artisan optimize 2>/tmp/laravel_optimize_error.log || {
  echo -e "${YELLOW}⚠️ Note: Laravel optimization completed with warnings.${RESET}"
  echo -e "${YELLOW}   This is normal if some components are not found and won't affect functionality.${RESET}"
}

# Check if we should stop here (run only mode)
if [ "$RUN_ONLY" = true ]; then
  echo -e "${GREEN}✅ Build and optimization completed successfully!${RESET}"
  exit 0
fi

# Git operations
echo -e "${BLUE}📝 Adding all changes...${RESET}"
git add .

# Check if there are changes to commit
if git diff-index --quiet HEAD --; then
  echo -e "${YELLOW}No changes to commit. Working tree clean.${RESET}"
  exit 0
fi

# Commit changes
echo -e "${BLUE}💾 Committing with message:${RESET} ${GREEN}'$COMMIT_MSG'${RESET}"
git commit -m "$COMMIT_MSG"

# Check if we should skip pushing (for testing)
if [ "${SKIP_PUSH}" != "true" ]; then
  echo -e "${BLUE}🚀 Pushing to remote...${RESET}"
  
  # Get current branch
  CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
  
  # Try to push, handle conflicts with pull and rebase if needed
  if ! git push origin $CURRENT_BRANCH; then
    echo -e "${YELLOW}⚠️ Push failed. Trying to pull latest changes...${RESET}"
    
    # Try pulling with rebase
    if git pull --rebase origin $CURRENT_BRANCH; then
      echo -e "${BLUE}📥 Successfully pulled and rebased changes.${RESET}"
      
      # Try pushing again
      if git push origin $CURRENT_BRANCH; then
        echo -e "${GREEN}✅ Changes pushed successfully after rebase!${RESET}"
      else
        echo -e "${RED}❌ Push failed even after rebase. Please resolve conflicts manually.${RESET}"
        exit 1
      fi
    else
      echo -e "${RED}❌ Pull with rebase failed. Please resolve conflicts manually.${RESET}"
      exit 1
    fi
  fi
else
  echo -e "${YELLOW}🔍 Push skipped (SKIP_PUSH=true)${RESET}"
fi

echo -e "${GREEN}✅ Changes committed successfully!${RESET}"
echo -e "${PURPLE}Commit message:${RESET} ${GREEN}$COMMIT_MSG${RESET}"
