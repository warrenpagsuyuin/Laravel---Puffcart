# PuffCart Laravel - Comprehensive Security & QA Test Cases

**Date:** 2026-05-13  
**Scope:** Local development + pre-production validation  
**Test Focus:** Authentication, authorization, input validation, checkout logic, admin functions, security vulnerabilities

---

## 1. AUTHENTICATION & REGISTRATION

### TEST-AUTH-001: Brute Force Registration Captcha

**Category:** Security - Weak CAPTCHA  
**Severity:** MEDIUM  
**Threat Model:** External attacker, careless user

**Description:**  
The registration captcha is a simple arithmetic problem (2 random numbers 1-9 added together). This has only 18 possible answers (2-18), making it trivial to brute force.

**Reproduction Steps:**
1. Navigate to `/register`
2. Fill all fields correctly (name, email, username, phone, address, DOB, valid_id, password)
3. Attempt captcha answers: 2, 3, 4, 5... 18 (only needs ~9 attempts on average)
4. One will succeed

**Expected Result:**  
Captcha should be resistant to brute force (1000+ possible combinations)

**Actual Risk:**
- Attackers can automate registration with 18 requests max
- Bots can easily create thousands of accounts
- Age verification bypass possible (fake accounts)
- Spam, abuse accounts created rapidly

**Suggested Fix:**
- Use established CAPTCHA library (hCaptcha, reCAPTCHA v3)
- Implement image-based CAPTCHA (>1000 combinations)
- Add rate limiting on failed captcha attempts (3 per IP per hour)
- Or: Math problem with random operator (+, -, *, /) and larger numbers (1-100)

---

### TEST-AUTH-002: No Rate Limiting on Registration

**Category:** QA - DoS / Spam  
**Severity:** MEDIUM  
**Threat Model:** External attacker

**Description:**  
No rate limiting protects the `/register` endpoint. An attacker can spam registrations without throttling.

**Reproduction Steps:**
1. Use automated tool to submit registration forms rapidly
```bash
for i in {1..100}; do
  curl -X POST http://localhost/register \
    -d "name=User$i&email=user$i@test.com&username=user$i&contact_number=09123456789&address=Test&date_of_birth=2000-01-01&password=Test1234!@&password_confirmation=Test1234!@&age_confirmed=on&privacy_consent=on&captcha=5"
done
```
2. Monitor database for created accounts
3. Create spam accounts with disposable email domains

**Expected Result:**  
System should throttle registration attempts (e.g., 5 per IP per hour)

**Actual Risk:**
- Database bloat from spam accounts
- Resource exhaustion
- Email spoofing / disposal email abuse
- File storage exhaustion (valid_id uploads)

**Suggested Fix:**
- Add `\Illuminate\Routing\Middleware\ThrottleRequests` to registration route
- Use `throttle:5,60` (5 registrations per IP per hour)
- Validate email domain (reject disposable/temporary email services)
- Verify unique phone numbers
- Add cooldown between registration attempts

---

### TEST-AUTH-003: Session-Based Age Verification Bypass

**Category:** Security - Authorization  
**Severity:** HIGH  
**Threat Model:** External attacker, underage user

**Description:**  
Age verification is stored in session (`session(['age_verified' => true])`). Session can be manipulated or recreated.

**Reproduction Steps:**
1. Underage user (e.g., 15 years old) visits home page
2. User opens DevTools → Application → Cookies
3. Delete the session cookie (LARAVEL_SESSION)
4. Session is regenerated but age_verified is lost
5. User is still able to browse shop (if verification isn't enforced everywhere)
6. Alternatively: Use session manipulation tools or modify session after creating it

**Expected Result:**  
Age verification should be persistent (database record), not session-based

**Actual Risk:**
- Minors can bypass age restriction and purchase age-restricted products
- Legal/compliance violation (21+ vape products)
- Regulatory fines
- Platform liability

**Suggested Fix:**
- Store `age_verified` as database column in `users` table (already done)
- But enforce it in middleware on every request
- Create `CheckAgeVerification` middleware that verifies DB record
- Don't rely on session alone
- Require re-verification periodically (e.g., every 30 days)
- Track verification attempts in audit log

---

### TEST-AUTH-004: Login Rate Limiting Bypass (IP Spoofing)

**Category:** Security - Brute Force  
**Severity:** MEDIUM  
**Threat Model:** External attacker

**Description:**  
Login rate limiting uses `$request->ip()`. If the app is behind a proxy without proper `TRUSTED_PROXIES` configuration, attackers can spoof IP addresses via headers.

**Reproduction Steps:**
1. Configure app without proper proxy trust settings
2. Attempt brute force login with X-Forwarded-For header manipulation:
```bash
for i in {1..100}; do
  curl -X POST http://localhost/login \
    -H "X-Forwarded-For: 192.168.$((RANDOM % 256)).$((RANDOM % 256))" \
    -d "login=admin@test.com&password=wrongpassword$i"
done
```
3. Rate limit never triggers (each request from "different" IP)

**Expected Result:**  
Rate limiting should account for proxy headers correctly

**Actual Risk:**
- Brute force attack succeeds
- Admin account compromise
- Unauthorized access

**Suggested Fix:**
- Set `TRUSTED_PROXIES` in `.env` or middleware
- Use Laravel's proxy trust middleware correctly
- Consider additional brute force protection (fail2ban, Web Application Firewall)
- Add account lockout after N failed attempts (already partially done for admin)

---

### TEST-AUTH-005: Weak Password Validation for Admin

**Category:** Security - Weak Credentials  
**Severity:** MEDIUM  
**Threat Model:** External attacker

**Description:**  
Admin login request might not enforce same password rules as customer registration. Need to verify password complexity for admin accounts.

**Reproduction Steps:**
1. Check AdminLoginRequest validation rules
2. Attempt to set admin password to simple values (if possible)
3. Compare with RegisterRequest rules

**Expected Result:**  
Admin and customer passwords should both enforce high complexity

**Actual Risk:**
- Weak admin passwords easier to crack
- Admin account takeover

**Suggested Fix:**
- Apply same `Password::min(10)->mixedCase()->numbers()->symbols()` to admin password changes
- Document password policy
- Force password change on first login

---

## 2. AUTHORIZATION & ACCESS CONTROL

### TEST-AUTHZ-001: Privilege Escalation - Customer to Admin

**Category:** Security - Authorization  
**Severity:** CRITICAL  
**Threat Model:** Logged-in user, attacker

**Description:**  
Can a logged-in customer directly modify their `role` column to 'admin' via database manipulation or API parameter injection?

**Reproduction Steps:**
1. Register as customer
2. Intercept checkout request or profile update
3. Add hidden field: `role=admin`
4. Submit request
5. Try accessing `/admin/dashboard`

**Expected Result:**  
Request rejected; only legitimate admin role assignments accepted

**Actual Risk:**
- Full admin access gained
- Database tampering
- Order manipulation
- User data access
- Financial loss

**Suggested Fix:**
- Don't accept `role` in user input (hidden HTML fields)
- Never update `role` from request data
- Use explicit admin promotion process only (command line, separate admin panel)
- Check `AdminMiddleware` enforces role check (already done)
- Log all role changes with timestamps

---

### TEST-AUTHZ-002: Direct Order Access (IDOR - Insecure Direct Object Reference)

**Category:** Security - Authorization  
**Severity:** MEDIUM  
**Threat Model:** Logged-in user, attacker

**Description:**  
Can user access another user's order by modifying the order ID in the URL?

**Reproduction Steps:**
1. Login as User A, place order → order_id = 5
2. Note the order show URL: `/orders/5`
3. Login as User B
4. Try accessing `/orders/5`

**Expected Result:**  
Access denied; 403 Forbidden

**Actual Risk:**
- View other users' orders (PII leak, addresses, items purchased)
- Modify order status/tracking
- Fraudulent order claims

**Suggested Fix:**  
- ✓ Code already has: `abort_unless($order->user_id === auth()->id(), 403);`
- **Verify this is consistently applied to all order routes**
- Check: `/orders/{order}`, `/orders/{order}/tracking`, `/payment/{order}`

---

### TEST-AUTHZ-003: Admin Accessing Customer Data Without Audit Trail

**Category:** QA - Compliance / Audit  
**Severity:** MEDIUM  
**Threat Model:** Malicious admin

**Description:**  
When admin views customer details, is it logged?

**Reproduction Steps:**
1. Login as admin
2. Access `/admin/users` → view specific user details
3. Check audit logs - is this action recorded?

**Expected Result:**  
Every admin data access should be logged in audit_logs table

**Actual Risk:**
- Undetected data breaches
- Privacy violations
- No compliance audit trail
- GDPR/regulatory violations

**Suggested Fix:**
- Implement audit logging on all admin views
- Log: user, action, data accessed, timestamp, IP
- Review audit logs regularly
- Alert on suspicious patterns

---

### TEST-AUTHZ-004: Cart Item IDOR - Accessing Other User's Cart

**Category:** Security - Authorization  
**Severity:** MEDIUM  
**Threat Model:** Logged-in user

**Description:**  
Can user modify/delete other user's cart items by guessing/using different cart item IDs?

**Reproduction Steps:**
1. User A: Add item to cart → gets CartItem id=10
2. User B: Login
3. Try PATCH `/cart/10` or DELETE `/cart/10`

**Expected Result:**  
Access denied; 403 Forbidden

**Actual Risk:**  
- Modify other users' orders
- Delete competitor's cart
- Manipulation of purchase history

**Suggested Fix:**  
- ✓ Code has: `abort_unless($item->user_id === auth()->id(), 403);`
- **Verify on all cart operations (add, update, remove)**

---

## 3. INPUT VALIDATION & XSS

### TEST-INPUT-001: XSS in Checkout Delivery Address

**Category:** Security - XSS (Cross-Site Scripting)  
**Severity:** MEDIUM  
**Threat Model:** Attacker

**Description:**  
Delivery address field is stored and displayed back to user and admin. Is HTML escaped?

**Reproduction Steps:**
1. Register & checkout
2. In delivery address field, enter: `<img src=x onerror="alert('XSS')">`
3. Complete order
4. View order details (customer or admin view)

**Expected Result:**  
Script tags are escaped; no alert appears

**Actual Risk:**
- Cookie theft (session hijacking)
- Malware injection
- Admin account compromise

**Suggested Fix:**
- Ensure all blade templates use `{{ }}` not `{!! !!}` for user input
- Verify in `resources/views/orders/show.blade.php`:
  ```blade
  {{ $order->delivery_address }}  ✓ Escaped
  {!! $order->delivery_address !!}  ✗ NOT escaped
  ```
- Add Content Security Policy headers
- Sanitize input: `Purify::clean($input)` (use HTMLPurifier)

---

### TEST-INPUT-002: XSS in Order Notes

**Category:** Security - XSS  
**Severity:** MEDIUM  
**Threat Model:** Attacker

**Description:**  
Order notes field might allow HTML/JS injection.

**Reproduction Steps:**
1. Checkout with notes: `<script>alert('xss')</script>`
2. View order
3. Check if script executes

**Expected Result:**  
Notes are escaped in display

**Actual Risk:**  
- XSS attacks when order is viewed (admin or customer)

**Suggested Fix:**  
- Escape all user-provided text in templates
- Use Laravel's `{{ }}` syntax everywhere

---

### TEST-INPUT-003: SQL Injection in Product Search/Promo Code

**Category:** Security - SQL Injection  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Is promo code lookup vulnerable to SQL injection?

**Reproduction Steps:**
1. Checkout with promo code: `PROMO' OR '1'='1`
2. Check if query is bypassed

**Expected Result:**  
No SQL injection; query fails safely

**Actual Risk:**  
- Database access
- Data exfiltration
- Unauthorized discounts

**Suggested Fix:**  
- ✓ Code uses parameterized queries (Laravel Eloquent)
- Verify: `PromoCode::where('code', strtoupper($code))->first();` (safe)
- No string concatenation in queries

---

### TEST-INPUT-004: File Upload - Malicious Valid ID

**Category:** Security - File Upload  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Uploaded ID file could contain malware or be a web shell.

**Reproduction Steps:**
1. Register with valid_id = PHP file (`.php` renamed as `.jpg`)
2. Upload malware disguised as image
3. If stored in web-accessible directory, access it: `/storage/valid-ids/shell.jpg` → execute PHP

**Expected Result:**  
- File type validation prevents non-images
- Files stored outside web root or served as downloads only
- No PHP execution in upload directory

**Actual Risk:**  
- Remote code execution (RCE)
- Server compromise
- Data theft

**Suggested Fix:**
- ✓ RegisterRequest has: `mimes:jpg,jpeg,png,pdf|max:5120`
- **Verify MIME type check:**
  - Current check: validates extension only (weak)
  - Better: validate actual file content (magic bytes)
  - Use: `Storage::putFileAs('valid-ids', $file, hash('sha256', $file->getContent()) . '.' . $file->extension())`
- Store files outside `public/` directory
- Set `x-content-type-options: nosniff` header
- Disable script execution in upload directory (`.htaccess` or nginx config)
- Rename files with UUID: prevent direct access by filename
- Serve files via controller with proper headers (force download or inline with safe MIME)

---

### TEST-INPUT-005: Mass Assignment - Hidden Fields in Checkout

**Category:** Security - Mass Assignment  
**Severity:** MEDIUM  
**Threat Model:** Attacker

**Description:**  
Can attacker inject hidden fields in checkout form to modify order data?

**Reproduction Steps:**
1. Add hidden field to checkout form: `<input name="total" value="1">`
2. Intercept checkout request
3. Attempt to modify: `total`, `discount`, `delivery_fee`, `status`

**Expected Result:**  
Only authorized fields updated; mass assignment prevented

**Actual Risk:**  
- Order price manipulation
- Free orders
- Financial loss

**Suggested Fix:**
- ✓ Laravel's `$fillable` or `$guarded` protects against this
- Verify in Order model:
  ```php
  protected $fillable = ['user_id', 'status', 'subtotal', 'delivery_fee', 'discount', 'total', 'promo_code', 'delivery_address', 'delivery_phone', 'notes', 'payment_method'];
  ```
- Never include `total` in request validation; calculate server-side only
- Recalculate totals from cart items, never trust client input

---

## 4. CHECKOUT & PAYMENT LOGIC

### TEST-CHECKOUT-001: Race Condition - Concurrent Orders / Stock Depletion

**Category:** QA - Race Condition  
**Severity:** HIGH  
**Threat Model:** Accidental (high traffic), external attacker

**Description:**  
Two users checkout same item simultaneously. Does stock decrement properly?

**Scenario:**
- Product A has stock=2
- User 1 checkout: qty=1 (should work)
- User 2 checkout: qty=1 (should work)
- User 3 checkout: qty=1 (should FAIL - out of stock)

But if requests process concurrently without locking, User 3 might succeed.

**Reproduction Steps:**
1. Create product with stock=2
2. Simulate 3 concurrent checkout requests (different users)
3. Check final stock and order counts

```bash
# Terminal 1: User 1 checkout
curl -X POST http://localhost/checkout -d "cart_item_ids[]=1&payment_method=cod"

# Terminal 2: User 2 checkout (same instant)
curl -X POST http://localhost/checkout -d "cart_item_ids[]=1&payment_method=cod"

# Terminal 3: User 3 checkout (same instant)
curl -X POST http://localhost/checkout -d "cart_item_ids[]=1&payment_method=cod"
```

**Expected Result:**
- All 3 succeed if stock>=3
- Only 2 succeed and 1 fails with "Out of stock" if stock=2
- Stock atomically decrements; no overselling

**Actual Risk:**
- Negative stock
- More items sold than available
- Financial loss
- Inventory mismatch

**Suggested Fix:**
- ✓ Code uses `DB::transaction()` with `lockForUpdate()`
- **Verify in CheckoutService::placeOrder():**
  ```php
  $cartItems = $this->cartItemsForCheckout($user, $data['cart_item_ids'] ?? null)
      ->with('flavor', 'batteryColor')
      ->lockForUpdate()  // ✓ Pessimistic locking
      ->get();
  
  $flavors = ProductFlavor::whereIn('id', $optionIds)
      ->lockForUpdate()  // ✓ Locked
      ->get();
  ```
- **Test:** Use Apache Bench or wrk to simulate concurrent requests and verify stock counts match orders

---

### TEST-CHECKOUT-002: Duplicate Order Prevention

**Category:** QA - Race Condition  
**Severity:** MEDIUM  
**Threat Model:** Accidental (network retry), external attacker

**Description:**  
If checkout request is retried (network timeout, user clicks twice), is duplicate order created?

**Reproduction Steps:**
1. Start checkout
2. Submit order → show loading
3. User hits refresh or back/forward multiple times
4. Check: Are multiple orders created?

**Expected Result:**  
Only one order created (idempotency key or POST-redirect-GET pattern)

**Actual Risk:**
- Multiple charges
- Customer confusion
- Inventory inconsistency
- Revenue tracking errors

**Suggested Fix:**
- Use POST-Redirect-GET pattern:
  1. POST checkout → create order
  2. Redirect to GET order view
  3. Refresh doesn't resubmit
- Or: Idempotency key in request headers
- Store idempotency_key in orders table
- Check on checkout: `Order::where('idempotency_key', $key)->first();`
- Return existing order if found

---

### TEST-CHECKOUT-003: Order Total Tampering

**Category:** Security - Business Logic  
**Severity:** CRITICAL  
**Threat Model:** Attacker

**Description:**  
Can attacker modify order total before payment?

**Reproduction Steps:**
1. Checkout with total=₱1000
2. Intercept request, modify to `total=1`
3. Proceed to payment
4. Pay only ₱1 for ₱1000 order

**Expected Result:**  
Server recalculates total from cart items; client value ignored

**Actual Risk:**
- Revenue loss
- Free products
- Payment fraud

**Suggested Fix:**
- ✓ Code recalculates server-side:
  ```php
  $subtotal = $items->sum(fn (CartItem $item) => (float) $item->product->price * $item->quantity);
  $total = max(0, $subtotal + $deliveryFee - $discount);
  ```
- **Verify:**
  - Total never comes from client request
  - Recalculated on every checkout
  - Promo code discount validated against current rate

---

### TEST-CHECKOUT-004: Promo Code Abuse - Unlimited Use

**Category:** Security - Business Logic  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Can same promo code be used repeatedly by one user or multiple users?

**Reproduction Steps:**
1. Get promo code (e.g., NEWUSER - 20% off)
2. Checkout with promo code 5 times
3. Each order gets discount

**Expected Result:**
- Promo code has usage limit (e.g., 100 uses total, or 1 per user)
- Expired codes rejected
- Used codes show remaining count

**Actual Risk:**
- Discount abuse
- Revenue loss
- Attacker manipulates multiple accounts to use same code

**Suggested Fix:**
- Add to promo_codes table:
  - `max_uses` (total limit)
  - `user_limit` (per user)
  - `used_count` (current uses)
  - `used_by_user` (JSON list or separate table)
- Validation in checkout:
  ```php
  if ($promo->used_count >= $promo->max_uses) {
      throw ValidationException::withMessages(['promo_code' => 'Promo code exhausted']);
  }
  if ($promo->isUsedByUser(auth()->id())) {
      throw ValidationException::withMessages(['promo_code' => 'You already used this code']);
  }
  ```
- Track promo usage per user in orders table

---

### TEST-CHECKOUT-005: Zero-Price Bypass

**Category:** Security - Business Logic  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Can attacker create zero-price order (subtotal=0, discount>=subtotal)?

**Reproduction Steps:**
1. Create cart with products
2. Apply promo code that gives 100% discount
3. Checkout with payment_method=cod (no payment required)
4. Free order created

**Expected Result:**
- Promo discount capped at subtotal
- Cannot have negative total
- Orders with payment_method=online require actual payment

**Actual Risk:**
- Free products
- Revenue loss

**Suggested Fix:**
- ✓ Code has: `'total' => round(max(0, $subtotal + $deliveryFee - $discount), 2)`
- **Verify:** Max 0 prevents negative
- Add check: If total=0 and payment_method=online, reject order
- Enforce minimum order value if desired

---

## 5. PAYMENT INTEGRATION

### TEST-PAYMENT-001: Payment Webhook CSRF

**Category:** Security - CSRF  
**Severity:** MEDIUM  
**Threat Model:** Attacker

**Description:**  
Payment webhook at `/payment/webhook` intentionally bypasses CSRF verification. Is it properly secured with PayMongo signature verification?

**Reproduction Steps:**
1. Check webhook implementation: does it verify PayMongo signature?
2. Attempt spoofed webhook request without valid signature
3. Check if order status is updated

**Expected Result:**  
Webhook requires valid PayMongo signature; spoofed requests rejected

**Actual Risk:**
- Order status spoofed to "paid"
- Payment bypassed
- Revenue loss

**Suggested Fix:**
- Webhook should verify PayMongo-provided signature
- See PayMongo docs for signature verification
- Code should have:
  ```php
  $signature = $request->header('X-PayMongo-Signature');
  $payload = $request->getContent();
  $hash = hash_hmac('sha256', $payload, config('services.paymongo.secret'));
  if (!hash_equals($hash, $signature)) {
      abort(401);
  }
  ```
- Log all webhook calls to audit trail

---

### TEST-PAYMENT-002: Payment Status Tampering

**Category:** Security - Authorization  
**Severity:** CRITICAL  
**Threat Model:** Attacker

**Description:**  
Can user directly update payment status to "paid" without actually paying?

**Reproduction Steps:**
1. Create order (status=pending)
2. Find the payment record ID
3. Try PATCH request to payment status endpoint (if exists)
4. Change status to "paid"

**Expected Result:**  
Payment status only updated via PayMongo webhook or admin action

**Actual Risk:**
- Orders marked as paid without payment
- Revenue loss
- Inventory depletion without payment

**Suggested Fix:**
- Payment status should NEVER be updated from user requests
- Only update via:
  - PayMongo webhook (with signature verification)
  - Admin action (with audit log)
  - Reconciliation process
- No direct PATCH/PUT endpoints for payment status

---

### TEST-PAYMENT-003: Order Payment Linked to Wrong User

**Category:** Security - Authorization  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Can attacker pay for another user's order?

**Reproduction Steps:**
1. Get payment link for Order#5 (User A's order)
2. Login as User B
3. Access `/payment/5`
4. Attempt payment (success or fail)
5. Check which user gets credit

**Expected Result:**
- Access denied if not order owner
- 403 Forbidden

**Actual Risk:**
- Attacker pays and user pays = double payment
- Order ownership spoofed

**Suggested Fix:**
- ✓ Code has: `abort_unless($order->user_id === auth()->id(), 403);`
- **Verify in PaymentController::show(), ::initiateCheckout(), ::paymentSuccess()**

---

## 6. ADMIN FUNCTIONS

### TEST-ADMIN-001: Admin MFA Bypass

**Category:** Security - MFA  
**Severity:** CRITICAL  
**Threat Model:** Attacker

**Description:**  
After entering password, user is sent to MFA page. Can MFA be bypassed by:
- Directly accessing `/admin/dashboard`?
- Modifying session to skip MFA?

**Reproduction Steps:**
1. Admin login with password (correct)
2. System shows MFA page
3. Try directly accessing `/admin/dashboard` before MFA verification
4. Or: Delete `mfa_user_id` session variable and refresh

**Expected Result:**
- Access denied; forced back to MFA
- Session check validates MFA was completed

**Actual Risk:**
- Admin account compromise
- Full system access
- Data breach

**Suggested Fix:**
- ✓ Code checks: `if (!session('mfa_user_id')) { return redirect(...) }`
- Create separate `mfa_verified` session flag after verification
- Admin middleware should check `session('mfa_verified')`
- Clear `mfa_verified` on logout
- MFA session should timeout after 5 minutes

---

### TEST-ADMIN-002: Admin Data Export / Mass Access

**Category:** QA - Data Protection  
**Severity:** MEDIUM  
**Threat Model:** Malicious admin

**Description:**  
Can admin export all customer data without restrictions?

**Reproduction Steps:**
1. Login as admin
2. Access `/admin/users`
3. Export all user records (if export feature exists)
4. Check: Is export logged? Authorized?

**Expected Result:**
- Data exports logged in audit trail
- Exports limited to necessary fields
- Suspicious large exports trigger alerts

**Actual Risk:**
- GDPR violation
- Privacy breach
- Unauthorized data access

**Suggested Fix:**
- Add audit logging to all data access
- Limit export scope
- Alert on large data exports
- Implement data retention policies

---

### TEST-ADMIN-003: Inventory Manipulation - Negative Stock

**Category:** QA - Business Logic  
**Severity:** HIGH  
**Threat Model:** Careless admin

**Description:**  
Can admin set product stock to negative?

**Reproduction Steps:**
1. Login as admin
2. Go to `/admin/inventory`
3. Update product stock to -100
4. Save

**Expected Result:**
- Stock cannot go negative
- Validation rejects negative values
- Minimum stock=0

**Actual Risk:**
- Inventory confusion
- Reports show incorrect stock
- Purchasing logic breaks

**Suggested Fix:**
- Add validation: `stock` must be >= 0
- In migration/model:
  ```php
  $table->unsignedInteger('stock')->default(0);
  ```
- Or in validation rules:
  ```php
  'stock' => 'required|integer|min:0'
  ```

---

### TEST-ADMIN-004: Order Status Change Without Audit

**Category:** QA - Compliance  
**Severity:** MEDIUM  
**Threat Model:** Malicious admin

**Description:**  
When admin changes order status, is it logged?

**Reproduction Steps:**
1. Admin updates order status from "pending" to "delivered"
2. Check audit logs

**Expected Result:**
- Action logged with: admin ID, change (before/after), timestamp, IP
- Suspicious changes flagged

**Actual Risk:**
- Undetected fraud
- No accountability
- Compliance violation

**Suggested Fix:**
- Add audit logging to order status updates
- Use Laravel's `AuditLog::log()` (already in codebase)
- Log: old status, new status, reason (if admin-provided)

---

### TEST-ADMIN-005: Admin Role Escalation via URL Parameter

**Category:** Security - Authorization  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Can customer admin access super-admin functions?

**Reproduction Steps:**
1. Login as regular admin
2. Try accessing `/admin/system-settings` (if exists)
3. Try accessing `/admin/reports` with elevated scope

**Expected Result:**
- Role-based access control enforced
- Only authorized admins access sensitive functions

**Actual Risk:**
- Unauthorized admin actions
- Data manipulation
- System compromise

**Suggested Fix:**
- Implement role hierarchy (customer, admin, super_admin, auditor)
- Create permission middleware:
  ```php
  Route::middleware(['admin:super_admin'])->group(function () {
      Route::get('/system-settings', ...);
  });
  ```
- Check permissions on every admin action

---

## 7. RACE CONDITIONS & CONCURRENCY

### TEST-RACE-001: Inventory Sync Race Condition

**Category:** QA - Race Condition  
**Severity:** HIGH  
**Threat Model:** Accidental (high traffic)

**Description:**  
Product stock is managed at two levels: `products.stock` and `product_flavors.stock`. When a bundle is purchased, both need to decrement. Can they get out of sync?

**Scenario:**
- Product=Bundle, Flavor=Vanilla, BatteryColor=Red
- Vanilla flavor stock=5, Red battery stock=5
- User buys 2 bundles (needs 2 Vanilla + 2 Red)
- If process fails between flavor decrement and product sync, mismatch occurs

**Reproduction Steps:**
1. Create bundle product with flavors and colors
2. Load test concurrent bundle purchases
3. Check final stock values across tables
4. Verify `product.stock` = sum of active flavor stocks

**Expected Result:**
- Stock values consistent across tables
- No orphaned inventory

**Actual Risk:**
- Inventory mismatch
- Reports incorrect
- Purchasing logic errors

**Suggested Fix:**
- ✓ Code calls: `$product->syncStockFromFlavors()`
- **Verify this runs AFTER all flavor decrements**
- Use database constraints/triggers for consistency
- Add periodic inventory reconciliation job

---

## 8. PERFORMANCE & BOTTLENECKS

### TEST-PERF-001: Checkout Query Performance

**Category:** QA - Performance  
**Severity:** MEDIUM  
**Threat Model:** Accidental (high traffic)

**Description:**  
Checkout loads multiple products, flavors, and validates stock. With 1000 items in cart, does it timeout?

**Reproduction Steps:**
1. Create cart with 1000 items (different variations)
2. Measure checkout time
3. Check database queries (Laravel Debugbar)

**Expected Result:**
- Checkout completes in <5 seconds
- Queries optimized (no N+1)
- Indexes on foreign keys

**Actual Risk:**
- Timeout on large carts
- User frustration
- Lost revenue

**Suggested Fix:**
- Check for N+1 queries in checkout flow
- Verify eager loading: `->with('product', 'flavor', 'batteryColor')`
- Add indexes:
  ```php
  $table->index(['user_id', 'product_id']);
  $table->index(['product_flavor_id']);
  $table->index(['battery_color_id']);
  ```
- Limit max cart size (e.g., 100 items)

---

### TEST-PERF-002: Admin Reports Query Performance

**Category:** QA - Performance  
**Severity:** MEDIUM  
**Threat Model:** Accidental

**Description:**  
Admin sales reports load all orders. With 1M orders, does it timeout?

**Reproduction Steps:**
1. Generate 10k-100k test orders
2. Access `/admin/reports`
3. Check load time
4. Monitor database queries

**Expected Result:**
- Reports load in <5 seconds
- Pagination/filtering applied
- Indexed queries

**Actual Risk:**
- Admin panel sluggish
- Timeout errors
- Admin productivity loss

**Suggested Fix:**
- Implement pagination on reports
- Add date range filtering
- Cache report calculations
- Create materialized view for complex reports
- Add indexes on commonly filtered fields

---

## 9. SESSION & COOKIE SECURITY

### TEST-SESSION-001: Session Fixation

**Category:** Security - Session  
**Severity:** MEDIUM  
**Threat Model:** Attacker

**Description:**  
After login, is session ID regenerated to prevent fixation?

**Reproduction Steps:**
1. Note session ID before login
2. Login
3. Note session ID after login
4. Verify they're different

**Expected Result:**
- Session ID changes after login
- ✓ Code has: `$request->session()->regenerate();`

**Actual Risk:**
- Session fixation attack
- Account takeover

**Suggested Fix:**
- ✓ Already implemented in AuthController::login()

---

### TEST-SESSION-002: CSRF Token Validation

**Category:** Security - CSRF  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
POST requests require CSRF token. Can attacker submit forms from attacker.com?

**Reproduction Steps:**
1. Create form on attacker.com pointing to localhost/checkout
2. Trick user into visiting attacker.com while logged in
3. Form auto-submits without CSRF token
4. Check if checkout is created

**Expected Result:**
- Request rejected (419 token mismatch)
- ✓ Laravel includes CSRF middleware by default

**Actual Risk:**
- CSRF attacks succeed
- Unauthorized actions

**Suggested Fix:**
- ✓ `VerifyCsrfToken` middleware active
- Except payment webhook (intentional): `withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])`
- All other POST/PUT/DELETE require CSRF token

---

## 10. DATA EXPOSURE & PRIVACY

### TEST-PRIVACY-001: User Data in URLs

**Category:** Security - Privacy  
**Severity:** MEDIUM  
**Threat Model:** Attacker

**Description:**  
Are user IDs, order numbers exposed in URLs (not model binding)?

**Reproduction Steps:**
1. Check URLs for numeric IDs: `/user/123`, `/order/456`
2. Test guessing other IDs
3. Check if accessible

**Expected Result:**
- Model binding validates ownership
- Numeric IDs acceptable if validation strict

**Actual Risk:**
- Information leakage
- Account enumeration

**Suggested Fix:**
- Use UUID instead of numeric IDs for sensitive resources
- Or keep numeric IDs but enforce access control (already done)

---

### TEST-PRIVACY-002: Sensitive Data in Logs

**Category:** Security - Privacy  
**Severity:** MEDIUM  
**Threat Model:** Attacker with log access

**Description:**  
Are passwords, payment info, IDs logged?

**Reproduction Steps:**
1. Check `storage/logs/laravel.log`
2. Search for: "password", "credit", "123456789", email
3. Verify no sensitive data logged

**Expected Result:**
- Passwords never logged
- Payment info redacted
- PII minimal in logs

**Actual Risk:**
- Log exposure = credential compromise
- Payment data leak

**Suggested Fix:**
- Don't log request/response with sensitive fields
- Use `$request->except(['password', 'card_number'])`
- Mask PII in logs: `***-***-1234`

---

### TEST-PRIVACY-003: Valid ID File Exposure

**Category:** Security - Privacy  
**Severity:** HIGH  
**Threat Model:** Attacker

**Description:**  
Uploaded ID files are stored in `/storage/valid-ids/`. Can attacker directly access them?

**Reproduction Steps:**
1. Register with ID file
2. Check uploaded file path
3. Try accessing `/storage/valid-ids/filename.jpg` in browser

**Expected Result:**
- Files not web-accessible (stored outside public)
- Or served only to authenticated owner via controller

**Actual Risk:**
- PII leak (ID numbers, addresses)
- Identity theft

**Suggested Fix:**
- Store files outside `/public`
- Serve files via controller with authentication:
  ```php
  return response()->download(storage_path('valid-ids/file.jpg'));
  ```
- Don't expose filename; use UUID
- Add access control: only user can view their own ID

---

## SUMMARY TABLE

| Test ID | Category | Severity | Issue | Status |
|---------|----------|----------|-------|--------|
| AUTH-001 | CAPTCHA | MEDIUM | Weak 18-option CAPTCHA | ⚠️ NEEDS FIX |
| AUTH-002 | Registration | MEDIUM | No rate limiting | ⚠️ NEEDS FIX |
| AUTH-003 | Age Verification | HIGH | Session-based bypass | ⚠️ NEEDS FIX |
| AUTH-004 | Rate Limiting | MEDIUM | IP spoofing | ⚠️ CONSIDER |
| AUTH-005 | Password | MEDIUM | Weak admin password rules | ⚠️ VERIFY |
| AUTHZ-001 | Privilege Escalation | CRITICAL | Role tampering | ⚠️ VERIFY |
| AUTHZ-002 | IDOR | MEDIUM | Order access | ✓ MITIGATED |
| AUTHZ-003 | Audit | MEDIUM | No access logging | ⚠️ NEEDS AUDIT |
| AUTHZ-004 | IDOR | MEDIUM | Cart access | ✓ MITIGATED |
| INPUT-001 | XSS | MEDIUM | Delivery address | ⚠️ VERIFY |
| INPUT-002 | XSS | MEDIUM | Order notes | ⚠️ VERIFY |
| INPUT-003 | SQL Injection | HIGH | Promo code | ✓ SAFE |
| INPUT-004 | File Upload | HIGH | ID file malware | ⚠️ NEEDS FIX |
| INPUT-005 | Mass Assignment | MEDIUM | Hidden fields | ⚠️ VERIFY |
| CHECKOUT-001 | Race Condition | HIGH | Stock depletion | ✓ MITIGATED |
| CHECKOUT-002 | Duplicate Orders | MEDIUM | Retry issue | ⚠️ NEEDS FIX |
| CHECKOUT-003 | Tampering | CRITICAL | Total modification | ✓ SAFE |
| CHECKOUT-004 | Promo Abuse | HIGH | Unlimited use | ⚠️ NEEDS FIX |
| CHECKOUT-005 | Zero Price | HIGH | Free orders | ✓ SAFE |
| PAYMENT-001 | Webhook | MEDIUM | CSRF/Signature | ⚠️ VERIFY |
| PAYMENT-002 | Status Tampering | CRITICAL | Direct status update | ⚠️ VERIFY |
| PAYMENT-003 | Authorization | HIGH | Wrong user payment | ✓ MITIGATED |
| ADMIN-001 | MFA Bypass | CRITICAL | Session skip | ⚠️ VERIFY |
| ADMIN-002 | Data Export | MEDIUM | No audit | ⚠️ NEEDS AUDIT |
| ADMIN-003 | Inventory | HIGH | Negative stock | ⚠️ VERIFY |
| ADMIN-004 | Audit | MEDIUM | Status changes unlogged | ⚠️ NEEDS AUDIT |
| ADMIN-005 | Role Escalation | HIGH | Permission bypass | ⚠️ VERIFY |
| RACE-001 | Concurrency | HIGH | Inventory sync | ⚠️ VERIFY |
| PERF-001 | Performance | MEDIUM | Checkout timeout | ⚠️ TEST |
| PERF-002 | Performance | MEDIUM | Report timeout | ⚠️ TEST |
| SESSION-001 | Session | MEDIUM | Fixation | ✓ MITIGATED |
| SESSION-002 | CSRF | HIGH | Token bypass | ✓ MITIGATED |
| PRIVACY-001 | Privacy | MEDIUM | IDs in URL | ⚠️ VERIFY |
| PRIVACY-002 | Logs | MEDIUM | Sensitive data | ⚠️ VERIFY |
| PRIVACY-003 | File Access | HIGH | ID file leak | ⚠️ NEEDS FIX |

---

## NEXT STEPS

### Immediate (CRITICAL - Before Pre-Production)
- [ ] TEST-AUTHZ-001: Verify privilege escalation is impossible
- [ ] TEST-CHECKOUT-003: Verify total can't be tampered
- [ ] TEST-PAYMENT-002: Verify payment status immutable from client
- [ ] TEST-ADMIN-001: Verify MFA can't be bypassed
- [ ] TEST-INPUT-004: Secure file upload (MIME + rename)
- [ ] TEST-PRIVACY-003: Protect ID files from direct access

### High Priority (Before Production)
- [ ] TEST-AUTH-001: Replace weak CAPTCHA
- [ ] TEST-AUTH-002: Implement registration rate limiting
- [ ] TEST-AUTH-003: Enforce age verification in middleware
- [ ] TEST-CHECKOUT-004: Implement promo code limits
- [ ] TEST-CHECKOUT-002: Add idempotency check
- [ ] TEST-RACE-001: Verify inventory sync
- [ ] TEST-ADMIN-004: Add audit logging to order changes
- [ ] TEST-ADMIN-003: Validate stock >= 0

### Medium Priority (1-2 Weeks)
- [ ] TEST-INPUT-001/002: Verify XSS escaping in templates
- [ ] TEST-ADMIN-002: Add audit logging to all admin data access
- [ ] TEST-PERF-001/002: Load test and optimize queries
- [ ] TEST-PRIVACY-001/002: Review logs for data exposure
- [ ] TEST-PAYMENT-001: Verify webhook signature validation

### Testing Guidelines
- Use automated OWASP ZAP scanning
- Load test with 100+ concurrent users
- Verify all SQL queries use parameterization
- Check all HTML output uses `{{ }}` not `{!! !!}`
- Run security headers scan (CSP, X-Frame-Options, etc.)
- Test on staging environment first

