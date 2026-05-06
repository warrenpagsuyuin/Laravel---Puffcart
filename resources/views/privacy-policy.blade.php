@extends('layouts.app')

@section('content')
<div class="bg-[#f9f9f9] min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-bold text-[#1a1a1a] mb-8">Privacy Policy</h1>
        <p class="text-[#666666] mb-8">Last updated: May 2026</p>

        <div class="space-y-8 text-[#1a1a1a]">
            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">1. Introduction</h2>
                <p class="text-[#666666] mb-4">
                    Puffcart ("we," "us," "our," or "Company") operates as an age-restricted ecommerce platform specializing in vape products and accessories.
                    This Privacy Policy explains how we collect, use, disclose, and otherwise handle your information when you use our website and services.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">2. Information We Collect</h2>
                <p class="text-[#666666] mb-4">We collect information you provide directly and information collected automatically:</p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li><strong>Account Information:</strong> Name, email address, username, password, phone number, address, and date of birth.</li>
                    <li><strong>Age Verification:</strong> Valid government-issued ID (photo ID, passport, etc.) to verify you are 18 years or older.</li>
                    <li><strong>Payment Information:</strong> Payment data processed securely through PayMongo. We do NOT store full credit card details.</li>
                    <li><strong>Order Information:</strong> Products purchased, order history, delivery addresses, and order tracking data.</li>
                    <li><strong>Communication:</strong> Messages through our chatbot and customer support channels.</li>
                    <li><strong>Device Information:</strong> IP address, browser type, device type, and usage patterns.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">3. How We Use Your Information</h2>
                <p class="text-[#666666] mb-4">We use collected information for:</p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li>Creating and managing your account</li>
                    <li>Processing orders and payments</li>
                    <li>Verifying your age (18+) for compliance</li>
                    <li>Delivering products and providing customer support</li>
                    <li>Fraud prevention and security</li>
                    <li>Improving our services and user experience</li>
                    <li>Sending transactional and marketing communications (with your consent)</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">4. Age Verification & ID Handling</h2>
                <p class="text-[#666666] mb-4">
                    <strong>Puffcart requires age verification before account approval and purchasing.</strong>
                </p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li>Your government-issued ID is securely stored and used solely for age verification.</li>
                    <li>Valid IDs are NOT publicly displayed or shared with third parties.</li>
                    <li>Only authorized Puffcart administrators can view ID documents for verification purposes.</li>
                    <li>Once verified, your account status is updated. If rejected, the ID is securely deleted.</li>
                    <li>ID documents are retained for 90 days after account verification, then permanently deleted.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">5. Payment & PayMongo Data</h2>
                <p class="text-[#666666] mb-4">
                    Puffcart uses PayMongo for secure payment processing.
                </p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li>We do NOT store full credit card details or sensitive payment information.</li>
                    <li>All payment data is encrypted and processed by PayMongo, a PCI-DSS compliant payment processor.</li>
                    <li>We store only transaction references, payment status, and order amounts for record-keeping.</li>
                    <li>Payment information is retained for 7 years for accounting and tax compliance.</li>
                    <li>PayMongo's privacy policy applies to payment data: https://paymongo.com/privacy</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">6. Data Security</h2>
                <p class="text-[#666666] mb-4">
                    We implement industry-standard security measures to protect your information:
                </p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li>Passwords are hashed using Laravel Hash::make() (Bcrypt algorithm)</li>
                    <li>All data transmitted is encrypted using HTTPS/TLS</li>
                    <li>Session IDs are regenerated after login to prevent session fixation attacks</li>
                    <li>Access to admin functions is restricted and logged in our audit system</li>
                    <li>Multi-factor authentication (MFA) protects admin accounts</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">7. Data Retention</h2>
                <p class="text-[#666666]">We retain information for as long as necessary to provide services or as legally required:</p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4 mt-4">
                    <li><strong>Account Data:</strong> Retained while account is active. Deleted 90 days after account deletion.</li>
                    <li><strong>Age Verification IDs:</strong> Deleted 90 days after verification approval or immediate deletion if rejected.</li>
                    <li><strong>Payment Records:</strong> Retained for 7 years for tax and accounting compliance.</li>
                    <li><strong>Audit Logs:</strong> Retained for 1 year for security and compliance audit purposes.</li>
                    <li><strong>Order History:</strong> Retained for 3 years for customer service and dispute resolution.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">8. Your Rights</h2>
                <p class="text-[#666666] mb-4">You have the right to:</p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li>Access your personal data</li>
                    <li>Request correction of inaccurate data</li>
                    <li>Request deletion of your account and associated data (subject to legal retention requirements)</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Request a copy of data we hold about you</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">9. Account Deletion & Data Removal</h2>
                <p class="text-[#666666]">
                    To delete your account and request removal of your personal data, please contact us at: <strong>support@puffcart.com</strong>
                </p>
                <p class="text-[#666666] mt-4">
                    After account deletion, we will remove your data within 30 days, except for:
                </p>
                <ul class="list-disc list-inside text-[#666666] space-y-2 ml-4">
                    <li>Payment records (retained for 7 years for tax compliance)</li>
                    <li>Order history (retained for 3 years for dispute resolution)</li>
                    <li>Legal holds or litigation records</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-[#1a1a1a] mb-4">10. Contact Us</h2>
                <p class="text-[#666666] mb-4">
                    If you have questions about this Privacy Policy or your data, please contact us:
                </p>
                <p class="text-[#666666]">
                    <strong>Puffcart Support</strong><br>
                    Email: support@puffcart.com<br>
                    Address: Metro Manila, Philippines
                </p>
            </section>
        </div>

        <div class="mt-12 p-6 bg-[#e6f0ff] rounded-lg border border-[#0066ff]">
            <p class="text-[#0066ff] font-semibold">
                By using Puffcart, you acknowledge that you have read and agree to this Privacy Policy. If you disagree with any provisions,
                please discontinue using our services.
            </p>
        </div>
    </div>
</div>
@endsection
