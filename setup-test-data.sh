#!/bin/bash

# Test Data Seeder Script for MorningCricket Inventory System
# This script helps you quickly set up test data for development and testing

echo "🧪 MorningCricket Test Data Seeder"
echo "=================================="
echo ""

# Function to run seeder with confirmation
run_seeder() {
    local seeder=$1
    local description=$2
    
    echo "📋 $description"
    echo "   Seeder: $seeder"
    echo ""
    
    read -p "   Do you want to run this seeder? (y/N): " -n 1 -r
    echo ""
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "   🚀 Running $seeder..."
        php artisan db:seed --class="$seeder"
        
        if [ $? -eq 0 ]; then
            echo "   ✅ Seeder completed successfully!"
        else
            echo "   ❌ Seeder failed!"
            return 1
        fi
    else
        echo "   ⏭️ Skipped $seeder"
    fi
    echo ""
}

# Function to reset database
reset_database() {
    echo "⚠️  Database Reset Warning"
    echo "   This will delete ALL existing data!"
    echo ""
    
    read -p "   Are you sure you want to reset the database? (y/N): " -n 1 -r
    echo ""
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "   🔄 Resetting database..."
        php artisan migrate:fresh
        
        if [ $? -eq 0 ]; then
            echo "   ✅ Database reset completed!"
            return 0
        else
            echo "   ❌ Database reset failed!"
            return 1
        fi
    else
        echo "   ⏭️ Database reset cancelled"
        return 1
    fi
    echo ""
}

# Main menu
echo "Choose an option:"
echo ""
echo "1. 🚀 Quick Test Setup (Minimal test data)"
echo "2. 📊 Full Test Setup (Complete test data)"
echo "3. 🔄 Reset Database + Quick Setup"
echo "4. 🔄 Reset Database + Full Setup"
echo "5. 🎯 Custom Seeder Selection"
echo "6. ❌ Exit"
echo ""

read -p "Enter your choice (1-6): " choice

case $choice in
    1)
        echo ""
        echo "🚀 Quick Test Setup"
        echo "=================="
        run_seeder "QuickTestSeeder" "Creates minimal test data for quick testing"
        ;;
        
    2)
        echo ""
        echo "📊 Full Test Setup"
        echo "=================="
        run_seeder "TestDatabaseSeeder" "Creates comprehensive test data with all roles and sample data"
        ;;
        
    3)
        echo ""
        echo "🔄 Reset Database + Quick Setup"
        echo "==============================="
        if reset_database; then
            run_seeder "QuickTestSeeder" "Creates minimal test data for quick testing"
        fi
        ;;
        
    4)
        echo ""
        echo "🔄 Reset Database + Full Setup"
        echo "=============================="
        if reset_database; then
            run_seeder "TestDatabaseSeeder" "Creates comprehensive test data with all roles and sample data"
        fi
        ;;
        
    5)
        echo ""
        echo "🎯 Custom Seeder Selection"
        echo "=========================="
        run_seeder "DefaultCategoriesSeeder" "Creates default financial categories"
        run_seeder "SubscriptionAdminSeeder" "Creates system admin for subscription management"
        run_seeder "SubscribedShopOwnerSeeder" "Creates subscribed shop owner with complete test data"
        run_seeder "QuickTestSeeder" "Creates minimal test users for quick testing"
        ;;
        
    6)
        echo "👋 Goodbye!"
        exit 0
        ;;
        
    *)
        echo "❌ Invalid choice. Please run the script again."
        exit 1
        ;;
esac

echo ""
echo "🎉 Setup completed!"
echo ""
echo "💡 Tips:"
echo "   • Use admin@mydailyrunner.com / password123 for system admin access"
echo "   • Use testowner@example.com / password123 for shop owner testing"
echo "   • Check the seeder output above for more account details"
echo "   • Run 'php artisan tinker' to interact with the data programmatically"
echo ""
echo "📚 Next steps:"
echo "   • Test the subscription workflow with pending users"
echo "   • Verify role-based access controls"
echo "   • Test shop selection and context switching"
echo "   • Validate CRUD operations with different user roles"
