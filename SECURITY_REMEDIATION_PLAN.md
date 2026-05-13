# Puffcart Security Remediation Plan

## Priority 0 - Production Blockers

- Run `php artisan security:migrate-valid-ids` before deployment so existing customer ID documents are moved from `storage/app/public` to private `storage/app`.
- Confirm `.env` uses production-safe values: `APP_DEBUG=false`, a real `APP_KEY`, HTTPS `APP_URL`, valid SMTP credentials, and no test Gmail/app passwords committed.
- Protect all public diagnostics in production. `/test-email` and `/test-websocket` must remain local-only.
- Configure web server limits for uploads and request bodies. Laravel validation is not enough if Nginx/Apache allows oversized payloads through.
- Add a real bot challenge provider before a public launch if registration abuse is expected. The current server-side challenge is safer than the previous weak CAPTCHA, but it is still a lightweight local challenge.

## Priority 1 - Critical Fixes

1. CAPTCHA hardening
   - Store only an HMAC hash of the expected answer in session.
   - Expire the challenge after 10 minutes.
   - Regenerate the challenge after failed validation.
   - Rate limit registration attempts by IP.
   - Test: `SecurityHardeningTest::test_registration_stores_valid_id_on_private_disk_and_consumes_captcha`.

2. Public ID exposure
   - Store new valid ID uploads on the private `local` disk, not the public disk.
   - Serve documents only through an authenticated admin route.
   - Send `X-Content-Type-Options: nosniff` and private cache headers.
   - Migration command: `php artisan security:migrate-valid-ids --dry-run`, then `php artisan security:migrate-valid-ids`.
   - Test: `SecurityHardeningTest::test_admin_valid_id_document_route_is_protected`.

3. Upload validation
   - Validate allowed extensions and MIME types for valid IDs and admin product images.
   - Keep valid IDs private even when the filename extension is allowed.
   - Test: `SecurityHardeningTest::test_registration_rejects_files_with_spoofed_extensions`.

4. Rate limiting
   - Apply named throttles to login, admin login, MFA verification, registration, password reset, checkout, chatbot, and local mail diagnostics.
   - Test: `SecurityHardeningTest::test_password_reset_requests_are_rate_limited`.

## Priority 2 - High Fixes

1. Password reset enumeration
   - Return the same generic success message whether the email exists, is inactive, or mail delivery fails.
   - Log only a hash of missing/inactive email addresses.

2. Checkout integrity
   - Recalculate subtotal, delivery fee, discounts, and totals server-side.
   - Ignore client-submitted totals.
   - Lock cart, product, and option rows during order creation.
   - Test: `SecurityHardeningTest::test_checkout_recalculates_totals_server_side`.

3. Development routes
   - Keep test email and websocket routes restricted to `local`.
   - Throttle diagnostic routes to prevent accidental abuse in development.

## Automated Security Tests

Run:

```bash
php artisan test --testsuite=Feature --filter=SecurityHardeningTest
```

Expected coverage:

- Registration valid ID is private and not publicly stored.
- Spoofed upload content is rejected even with an allowed extension.
- Admin verification documents require an authenticated admin.
- Password reset requests return HTTP 429 after the configured limit.
- Checkout ignores tampered client totals and creates the correct order/payment values.

## Pass/Fail QA Criteria

### Auth

Pass:
- Login accepts valid credentials and rejects invalid credentials without revealing which field failed.
- Registration fails with an expired/wrong CAPTCHA and issues a new challenge.
- Password reset always shows a generic success message and rate limits repeated attempts.
- Admin login and MFA verification rate limit repeated attempts.

Fail:
- Password reset reveals whether an email exists.
- Registration accepts a spoofed upload or stores ID files under public storage.
- More than 5 password reset or login attempts per minute are accepted for the same identity/IP.

### Checkout

Pass:
- Cart totals shown to the user match the order created on the server.
- Tampered `subtotal`, `discount`, or `total` fields do not affect the stored order/payment amount.
- Checkout fails cleanly when stock is insufficient or a selected option is inactive.
- Online-payment orders cannot be tracked as complete until payment is paid.

Fail:
- Client-submitted totals are trusted.
- A user can checkout another user's cart item.
- Stock becomes negative after simultaneous checkout attempts.

### Admin

Pass:
- Non-admin users are redirected away from admin verification document URLs.
- Admins can view valid ID documents through the protected route only.
- Product image uploads reject spoofed content and oversized files.
- Local diagnostic routes are not available outside `local`.

Fail:
- `storage/app/public/valid-ids/*` contains accessible ID documents after migration.
- Customer users can open admin document URLs.
- Debug/test routes are exposed in production.
