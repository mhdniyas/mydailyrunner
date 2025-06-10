#!/bin/bash

# Test Data Validation Script
# Validates that the seeded data is correctly set up

echo "🔍 Validating Test Data Setup"
echo "=============================="
echo ""

# Function to run artisan tinker commands
check_data() {
    local description=$1
    local command=$2
    
    echo "📋 $description"
    echo "   Command: $command"
    
    result=$(php artisan tinker --execute="$command")
    echo "   Result: $result"
    echo ""
}

echo "🔍 Checking user accounts and subscription status..."
echo ""

check_data "System Admin Check" "echo App\\Models\\User::where('email', 'admin@mydailyrunner.com')->first()->isAdmin() ? 'ADMIN ✅' : 'NOT ADMIN ❌';"

check_data "Subscribed Shop Owner Check" "echo App\\Models\\User::where('email', 'testowner@example.com')->whereNotNull('email_verified_at')->where('is_subscribed', true)->where('is_admin_approved', true)->exists() ? 'SUBSCRIBED & APPROVED ✅' : 'NOT FULLY SUBSCRIBED ❌';"

check_data "Pending User Check" "echo App\\Models\\User::where('email', 'pending@example.com')->where('is_subscribed', true)->where('is_admin_approved', false)->exists() ? 'PENDING APPROVAL ⏳' : 'NOT PENDING ❌';"

check_data "Unsubscribed User Check" "echo App\\Models\\User::where('email', 'unsubscribed@example.com')->where('is_subscribed', false)->exists() ? 'UNSUBSCRIBED ❌' : 'SUBSCRIBED ✅';"

echo "🏪 Checking shop and role assignments..."
echo ""

check_data "Shop Count" "echo 'Shops: ' . App\\Models\\Shop::count();"

check_data "Shop Users Count" "echo 'Shop User Assignments: ' . App\\Models\\ShopUser::count();"

check_data "Owner Role Check" "echo App\\Models\\ShopUser::where('role', 'owner')->exists() ? 'OWNER ROLE EXISTS ✅' : 'NO OWNER ROLE ❌';"

check_data "Admin Role Check" "echo App\\Models\\ShopUser::where('role', 'admin')->exists() ? 'ADMIN ROLE EXISTS ✅' : 'NO ADMIN ROLE ❌';"

echo "📊 Checking sample data..."
echo ""

check_data "Products Count" "echo 'Products: ' . App\\Models\\Product::count();"

check_data "Customers Count" "echo 'Customers: ' . App\\Models\\Customer::count();"

check_data "Financial Categories Count" "echo 'Financial Categories: ' . App\\Models\\FinancialCategory::count();"

echo "✅ Data validation completed!"
echo ""
echo "🧪 Test Login Credentials:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "👑 System Admin: admin@mydailyrunner.com / password123"
echo "🏪 Shop Owner: testowner@example.com / password123"
echo "👔 Manager: testmanager@example.com / password123"
echo "💰 Finance: testfinance@example.com / password123"
echo "📦 Stock: teststock@example.com / password123"
echo "👁️ Viewer: testviewer@example.com / password123"
echo "⏳ Pending: pending@example.com / password123"
echo "❌ Unsubscribed: unsubscribed@example.com / password123"
