# 🧪 Test Data Setup Guide
## MorningCricket Inventory Management System

This guide helps you set up test data for development and testing of the subscription and role-based access control system.

---

## 📋 Available Seeders

### 1. **QuickTestSeeder** (Minimal Setup)
```bash
php artisan db:seed --class=QuickTestSeeder
```

**Creates:**
- 4 test users with different subscription statuses
- 1 test shop
- Minimal role assignments

**Use Case:** Quick testing of subscription and authentication flows

### 2. **SubscribedShopOwnerSeeder** (Comprehensive Setup)
```bash
php artisan db:seed --class=SubscribedShopOwnerSeeder
```

**Creates:**
- 7 test users (various roles and subscription statuses)
- 1 fully configured shop
- 5 sample products
- 4 sample customers
- 8 financial categories

**Use Case:** Full system testing with realistic data

### 3. **TestDatabaseSeeder** (Complete Reset + Setup)
```bash
php artisan db:seed --class=TestDatabaseSeeder
```

**Creates:** All essential seeders + SubscribedShopOwnerSeeder

**Use Case:** Fresh environment setup for comprehensive testing

---

## 🚀 Quick Setup Scripts

### Interactive Setup Script
```bash
./setup-test-data.sh
```

**Features:**
- Interactive menu for seeder selection
- Database reset options
- Detailed output with account information

### Data Validation Script
```bash
./validate-test-data.sh
```

**Features:**
- Validates seeded data integrity
- Checks subscription statuses
- Verifies role assignments
- Lists all test accounts

---

## 👥 Test Accounts Created

### **System-Level Accounts**

| Account Type | Email | Password | Access Level |
|-------------|--------|----------|--------------|
| **Super Admin** | admin@mydailyrunner.com | password123 | Full system access, bypasses subscription |
| **Test Admin** | admin@test.com | admin123 | Full system access, bypasses subscription |

### **Shop-Level Accounts (Subscribed & Approved)**

| Role | Email | Password | Permissions |
|------|--------|----------|-------------|
| **Owner** | testowner@example.com | password123 | Full shop management |
| **Owner** | owner@test.com | owner123 | Full shop management |
| **Manager** | testmanager@example.com | password123 | Daily operations, reports |
| **Finance** | testfinance@example.com | password123 | Sales, financial entries |
| **Stock** | teststock@example.com | password123 | Inventory management |
| **Viewer** | testviewer@example.com | password123 | Read-only access |

### **Test Accounts (Restricted Access)**

| Status | Email | Password | Limitation |
|--------|--------|----------|------------|
| **Pending Approval** | pending@example.com | password123 | Subscribed but needs admin approval |
| **Pending Approval** | pending@test.com | pending123 | Subscribed but needs admin approval |
| **Unsubscribed** | unsubscribed@example.com | password123 | No subscription, cannot access |
| **Unsubscribed** | unsubscribed@test.com | unsubscribed123 | No subscription, cannot access |

---

## 🧪 Testing Scenarios

### **1. Subscription Workflow Testing**

```bash
# Test admin approval workflow
1. Login as admin@mydailyrunner.com
2. Navigate to /admin/subscriptions/pending
3. Approve pending@example.com
4. Login as pending@example.com (should now work)
```

### **2. Role-Based Access Testing**

```bash
# Test role permissions
1. Login as teststock@example.com
2. Try to access /users (should be denied - owner only)
3. Access /products (should work - stock role)
4. Try to create financial entry (should be denied)
```

### **3. Multi-Shop Context Testing**

```bash
# Test shop context switching
1. Login as testowner@example.com
2. Select shop from dashboard
3. Verify session context
4. Test role-specific features
```

### **4. Subscription Requirement Testing**

```bash
# Test subscription middleware
1. Login as unsubscribed@example.com
2. Try to access /products
3. Should redirect to subscription status page
4. Test admin bypass with admin account
```

---

## 📊 Sample Data Overview

### **Products Created** (SubscribedShopOwnerSeeder)
- Rice Premium Grade A (150.5 kg, ₹45.00/kg)
- Wheat Flour (200.0 kg, ₹35.50/kg)  
- Sugar White (80.25 kg, ₹42.00/kg)
- Cooking Oil (45.0 liters, ₹95.00/liter)
- Lentils/Dal (65.75 kg, ₹85.00/kg)

### **Customers Created**
- Rajesh Kumar (₹50,000 credit limit)
- Priya Sharma (₹25,000 credit limit)
- Mohammed Ali (₹35,000 credit limit)
- Sunita Patel (₹40,000 credit limit)

### **Financial Categories Created**
- **Expenses:** Office Supplies, Utilities, Transportation, Marketing, Equipment
- **Income:** Other Income, Investment, Service Revenue

---

## 🔧 Manual Seeder Commands

### Run Individual Seeders
```bash
# Basic categories and admin
php artisan db:seed --class=DefaultCategoriesSeeder
php artisan db:seed --class=SubscriptionAdminSeeder

# Test users and data
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SubscribedShopOwnerSeeder
php artisan db:seed --class=QuickTestSeeder

# Complete setup
php artisan db:seed --class=TestDatabaseSeeder
```

### Fresh Database Setup
```bash
# Reset and seed everything
php artisan migrate:fresh --seed

# Reset and seed with test data
php artisan migrate:fresh
php artisan db:seed --class=TestDatabaseSeeder
```

---

## 🛠️ Customization

### **Adding Custom Test Users**

```php
// In any seeder file
$customUser = User::create([
    'name' => 'Custom User',
    'email' => 'custom@example.com',
    'password' => Hash::make('password123'),
    'is_subscribed' => true,
    'is_admin_approved' => true,
    'email_verified_at' => now(),
]);

ShopUser::create([
    'user_id' => $customUser->id,
    'shop_id' => $shop->id,
    'role' => 'manager', // owner, manager, finance, stock, viewer, admin
]);
```

### **Adding Custom Shop Data**

```php
$shop = Shop::create([
    'name' => 'Custom Shop',
    'address' => 'Custom Address',
    'phone' => '+1-555-CUSTOM',
    'email' => 'custom@shop.com',
    'owner_id' => $owner->id,
]);
```

---

## 🚨 Common Issues & Solutions

### **Issue: Seeder Fails with "Class not found"**
```bash
# Solution: Dump autoload
composer dump-autoload
```

### **Issue: Users can't login**
```bash
# Check: Email verification status
# Solution: Ensure email_verified_at is set in seeder
```

### **Issue: Subscription redirects**
```bash
# Check: is_subscribed and is_admin_approved flags
# Solution: Verify both are true for active users
```

### **Issue: Role permissions not working**
```bash
# Check: ShopUser records exist
# Check: Correct shop context in session
# Solution: Verify shop selection after login
```

---

## 📝 Best Practices

### **Development Environment**
1. Use `QuickTestSeeder` for rapid testing
2. Reset database frequently: `php artisan migrate:fresh`
3. Use validation script to verify setup

### **Testing Environment**
1. Use `TestDatabaseSeeder` for comprehensive tests
2. Create test-specific users for automated tests
3. Separate test data from production seeders

### **Production Considerations**
1. Never run test seeders in production
2. Use environment-specific seeder calls
3. Keep production seeders minimal and secure

---

## 📚 Next Steps

### **After Seeding:**
1. **Test Authentication:** Login with different user types
2. **Test Authorization:** Verify role-based access controls
3. **Test Subscription Flow:** Admin approval workflow
4. **Test Shop Context:** Multi-shop user scenarios
5. **Test Data Operations:** CRUD with different roles

### **Advanced Testing:**
1. **API Testing:** Use seeded accounts for API tests
2. **Performance Testing:** Test with larger datasets
3. **Security Testing:** Verify access restrictions
4. **Integration Testing:** Full workflow testing

---

*Generated for MorningCricket Inventory Management System*  
*Last Updated: June 10, 2025*
