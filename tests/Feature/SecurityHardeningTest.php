<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_stores_valid_id_on_private_disk_and_consumes_captcha(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $ip = $this->uniqueIp();
        RateLimiter::clear('register|' . $ip);

        $this->get(route('login'));
        $captcha = session('captcha_question');

        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])->post(route('register'), $this->registrationPayload([
            'email' => 'private-id@example.com',
            'username' => 'privateid',
            'valid_id' => $this->validPngUpload(),
            'captcha' => $captcha,
        ]));

        $response->assertRedirect(route('login'));

        $user = User::where('email', 'private-id@example.com')->firstOrFail();

        $this->assertNotEmpty($user->valid_id_path);
        Storage::disk('local')->assertExists($user->valid_id_path);
        Storage::disk('public')->assertMissing($user->valid_id_path);
        $this->assertNull(session('captcha_answer_hash'));
        $this->assertNull(session('captcha_question'));
    }

    public function test_registration_rejects_files_with_spoofed_extensions(): void
    {
        Storage::fake('local');
        $ip = $this->uniqueIp();
        RateLimiter::clear('register|' . $ip);

        $this->get(route('login'));
        $captcha = session('captcha_question');

        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])->from(route('login'))->post(route('register'), $this->registrationPayload([
            'email' => 'spoofed-upload@example.com',
            'username' => 'spoofedupload',
            'valid_id' => $this->spoofedJpegUpload(),
            'captcha' => $captcha,
        ]));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('valid_id');
        $this->assertDatabaseMissing('users', ['email' => 'spoofed-upload@example.com']);
    }

    public function test_admin_valid_id_document_route_is_protected(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('valid-ids/customer-id.pdf', '%PDF-1.4 test');

        $customer = $this->user([
            'email' => 'pending-customer@example.com',
            'valid_id_path' => 'valid-ids/customer-id.pdf',
            'verification_status' => 'pending',
        ]);

        $this->get(route('admin.verifications.document', $customer))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($customer)
            ->get(route('admin.verifications.document', $customer))
            ->assertRedirect(route('home'));

        $admin = $this->user([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.verifications.document', $customer));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_password_reset_requests_are_rate_limited(): void
    {
        $email = 'unknown-' . Str::lower(Str::random(12)) . '@example.com';
        $ip = $this->uniqueIp();
        $key = 'password-reset|' . $email . '|' . $ip;

        RateLimiter::clear($key);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])->post(route('password.send-reset-link'), [
                'email' => $email,
            ])->assertStatus(302);
        }

        $this->withServerVariables(['REMOTE_ADDR' => $ip])->post(route('password.send-reset-link'), [
            'email' => $email,
        ])->assertStatus(429);

        RateLimiter::clear($key);
    }

    public function test_checkout_recalculates_totals_server_side(): void
    {
        $user = $this->user(['email' => 'checkout@example.com']);
        $product = Product::create([
            'name' => 'Secure Pod Pack',
            'category' => 'Pods',
            'brand' => 'Puffcart',
            'product_type' => Product::TYPE_PODS,
            'price' => 250,
            'stock' => 10,
            'is_active' => true,
        ]);
        $flavor = ProductFlavor::create([
            'product_id' => $product->id,
            'name' => 'Mint',
            'option_type' => ProductFlavor::TYPE_FLAVOR,
            'stock' => 10,
            'reorder_level' => 2,
            'is_active' => true,
        ]);
        $cartItem = CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_flavor_id' => $flavor->id,
            'quantity' => 2,
            'selected_flavor' => $flavor->name,
            'product_type' => Product::TYPE_PODS,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.place'), [
            'delivery_address' => '123 Test Street, Metro Manila',
            'delivery_phone' => '09171234567',
            'payment_method' => 'cod',
            'cart_item_ids' => [$cartItem->id],
            'subtotal' => 1,
            'discount' => 9999,
            'total' => 1,
        ]);

        $order = Order::firstOrFail();
        $payment = Payment::where('order_id', $order->id)->firstOrFail();

        $response->assertRedirect(route('orders.show', $order));
        $this->assertSame(500.0, (float) $order->subtotal);
        $this->assertSame(0.0, (float) $order->delivery_fee);
        $this->assertSame(0.0, (float) $order->discount);
        $this->assertSame(500.0, (float) $order->total);
        $this->assertSame(500.0, (float) $payment->amount);
    }

    private function registrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Security Tester',
            'email' => 'security@example.com',
            'username' => 'securitytester',
            'contact_number' => '09171234567',
            'address' => '123 Test Street, Metro Manila',
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
            'valid_id' => $this->validPngUpload(),
            'password' => 'StrongerPass123!',
            'password_confirmation' => 'StrongerPass123!',
            'age_confirmed' => '1',
            'privacy_consent' => '1',
            'captcha' => 'ABC123',
        ], $overrides);
    }

    private function user(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Security Tester',
            'username' => 'security' . Str::random(8),
            'email' => 'security' . Str::random(8) . '@example.com',
            'password' => Hash::make('StrongerPass123!'),
            'role' => 'customer',
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
            'age_verified' => true,
            'age_confirmed' => true,
            'privacy_consent' => true,
            'verification_status' => 'approved',
            'is_active' => true,
        ], $overrides));
    }

    private function validPngUpload(): UploadedFile
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='
        );

        return UploadedFile::fake()->createWithContent('id.png', $png);
    }

    private function spoofedJpegUpload(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'spoofed-upload-');
        file_put_contents($path, '<?php echo "owned";');

        return new UploadedFile($path, 'id.jpg', 'text/x-php', null, true);
    }

    private function uniqueIp(): string
    {
        return '10.' . random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254);
    }
}
