Here’s a **full working flow** of your **Shop Stock & Financial Manager App**, broken into real-world actions — from login to report — with user roles and expected outcomes.

This will help you:

* Guide developers clearly
* Visualize actual shop operation
* Avoid scope confusion later

---

# ✅ WORKING FLOW OF THE APP (End-to-End)

---

## 🔐 STEP 1: **Login & Shop Selection**

**User opens the app** → Logs in
🔸 If user is in 1 shop → goes to dashboard
🔸 If user is in multiple shops → selects shop
🔸 Role is loaded per shop

---

## 🏠 STEP 2: **Dashboard Overview (Real-time shop summary)**

**Displayed Info (per selected shop):**

* Current stock status (value & quantity)
* Today’s sales
* Cash in/out summary
* Low stock warnings
* Discrepancy alerts (from daily checks)
* Bag average change alerts (optional)

> ⚙️ Access controlled by role (e.g., viewer sees summary only)

---

## 📦 STEP 3: **Stock-In Entry (Restocking inventory)**

**User (Owner/Manager):**

* Opens `Stock In` module
* Selects product
* Enters:

  * Quantity
  * No. of bags
  * Cost/unit (or total)
* System calculates:

  ```math
  avg_bag_weight = (quantity / bags) - 0.5
  ```
* Product’s cost & avg updated
* Entry saved in history

---

## 📅 STEP 4: **Daily Physical Stock Check (Morning/Evening)**

**Stock Checker (or Manager):**

* Opens `Daily Stock Check`
* Sees system stock values auto-filled
* Inputs physical stock per product
* System calculates and highlights:

  * Differences
  * % mismatch
* Can add note (e.g., “missing bag”, “spill”)
* Saved as daily report

---

## 🧾 STEP 5: **Sales Entry (Money In)**

**Manager/Finance User:**

* Enters a `Sale` or `Customer Order`:

  * Product, quantity
  * Customer (linked or walk-in)
  * Paid / pending amount
* Status auto-set:

  * `advance`, `pending`, `paid`
* Affects:

  * Stock balance (auto reduce)
  * Finance balance (money in)

---

## 💳 STEP 6: **Customer Payment Update**

**Finance or Manager:**

* View list of customer transactions
* Filter: pending / advance
* Enter new payment received
* Updates outstanding balance
* Status changes when fully paid

---

## 💰 STEP 7: **Daily Finance Entry (Expenses)**

**Finance Role or Manager:**

* Add entry:

  * Type: income / expense
  * Category: salary, fuel, etc.
  * Amount, notes
* System saves under:

  * Shop > Financial Ledger

> Auto-linked to reports

---

## 🧮 STEP 8: **Auto Calculations**

Every day:

* System recalculates:

  * Available stock
  * Bag averages per product
  * Net profit (if sale price is known)
  * Discrepancy summaries
  * Daily balance:

    ```math
    total_income - total_expense
    ```

---

## 📊 STEP 9: **Reporting (Export or View)**

**Owner / Manager / Finance can:**

* Filter reports by date / product / category
* View:

  * Stock In/Out Summary
  * Daily Discrepancy Report
  * Financial Summary
  * Customer Dues
  * Bag Weight Trends
* Export to Excel or PDF

---

## 👤 STEP 10: **User & Role Management (Owner Only)**

* Invite user via email or phone
* Assign role per shop
* Can view user logs, last login, recent activity

---

# 🔁 DAILY OPERATION SUMMARY

| Action               | Who Does It      | When             |
| -------------------- | ---------------- | ---------------- |
| Stock In             | Manager/Owner    | 1–4x/month       |
| Daily Physical Check | Stock Checker    | Daily (morning)  |
| Sales Entry          | Manager/Finance  | Daily            |
| Expense Entry        | Finance          | Daily            |
| Reports Review       | Owner/Manager    | Weekly / Monthly |
| Payment Follow-up    | Manager          | Weekly           |
| Bag Avg Review       | Owner (optional) | Monthly          |

---

# 🔐 SECURITY / CONTROL CHECKS

* All actions scoped per shop
* Logs for every change (who, when, what)
* Permission-based visibility
* Optional “lock past dates” for edits

---

