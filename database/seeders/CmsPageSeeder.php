<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-bold text-slate-900 mb-3">About 2nd Nikah</h2>
                            <p class="text-slate-600 leading-relaxed">
                                <strong>2nd Nikah</strong> (www.2ndnikah.com) is Bangladesh\'s premier, dignified matrimonial platform specifically designed for widows, divorcees, mature singles, and individuals seeking a blessed second marriage. Guided by Islamic ethics, mutual dignity, and stringent privacy protocols, we offer a safe, respectful environment for individuals and their guardians to connect with serious candidates.
                            </p>
                        </section>

                        <section class="bg-rose-50/60 border border-rose-100 rounded-2xl p-5 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900">Company & Management Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                                <div><strong class="text-slate-900">Company / Organization:</strong> 2ndnikah</div>
                                <div><strong class="text-slate-900">Trade License Number:</strong> TRAD/DNCC/025984/2024</div>
                                <div><strong class="text-slate-900">Registered Office Address:</strong> Dhaka-1100, Bangladesh</div>
                                <div><strong class="text-slate-900">Operational Jurisdiction:</strong> Bangladesh & Global Expatriate Diaspora</div>
                                <div><strong class="text-slate-900">Official Support Email:</strong> 2ndnikahsupport@gmail.com</div>
                                <div><strong class="text-slate-900">Website:</strong> https://www.2ndnikah.com</div>
                                <div><strong class="text-slate-900">Payment Gateway Partner:</strong> SSLCommerz (Authorized Gateway)</div>
                            </div>
                        </section>

                        <section>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Our Leadership & Team</h3>
                            <p class="text-slate-600 leading-relaxed mb-3">
                                Founded and managed by <strong>Maruf Hossain</strong> (Founder & Managing Director) together with our dedicated Trust & Safety and Profile Moderation team. Our team manually reviews submissions, verifies phone numbers, and protects member confidentiality 24/7.
                            </p>
                            <ul class="list-disc list-inside text-slate-600 space-y-1">
                                <li><strong>Maruf Hossain:</strong> Founder, Executive Management & Platform Architecture</li>
                                <li><strong>Verification & Moderation Team:</strong> Manual profile vetting and document verification</li>
                                <li><strong>Member Support Desk:</strong> Dedicated customer service available via WhatsApp and email</li>
                            </ul>
                        </section>

                        <section>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Core Principles & Islamic Values</h3>
                            <p class="text-slate-600 leading-relaxed">
                                We believe every heart deserves a second chance at companionship. We actively prohibit casual dating, harassment, or commercial solicitation. Every tool—from phone masking to private mutual requests—is engineered to honor dignity and privacy.
                            </p>
                        </section>
                    </div>
                ',
                'is_published' => true,
                'meta_title' => 'About Us - Company & Management Details | 2nd Nikah',
                'meta_description' => 'Learn about 2nd Nikah, our mission, company leadership, trade license, and registered address in Dhaka, Bangladesh.',
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">Terms and Conditions</h2>
                            <p class="text-xs text-slate-500 mb-4">Last Updated: October 2026</p>
                            <p class="text-slate-600 leading-relaxed">
                                Welcome to <strong>2nd Nikah</strong> ("we", "our", "platform"), operated by <strong>2ndnikah</strong> (Trade License: TRAD/DNCC/025984/2024, Dhaka-1100, Bangladesh). By registering, accessing, or purchasing membership on www.2ndnikah.com, you agree to comply with and be bound by these Terms and Conditions.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">1. Eligibility</h3>
                            <p class="text-slate-600 leading-relaxed">
                                To register with 2nd Nikah, you must be an adult aged 18 years or older and legally eligible for marriage under the laws of Bangladesh or your respective jurisdiction. The platform is solely intended for genuine matrimonial pursuits (Nikah). Any use for casual dating, commercial advertisement, or unlawful activities is strictly prohibited.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">2. Profile Accuracy & Code of Conduct</h3>
                            <ul class="list-disc list-inside text-slate-600 space-y-1.5 leading-relaxed">
                                <li>You agree to provide accurate, truthful, and up-to-date personal details regarding age, marital status, education, and profession.</li>
                                <li>Impersonation, deceptive photo usage, or submitting forged documents will result in immediate profile suspension without notice.</li>
                                <li>Harassment, abusive language, or unsolicited solicitation of members is grounds for permanent blocking and IP ban.</li>
                            </ul>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">3. Membership Subscriptions & Billing</h3>
                            <p class="text-slate-600 leading-relaxed">
                                2nd Nikah offers premium membership subscription plans (Weekly, Monthly) granting digital access to contact candidates upon mutual consent. All online payments are securely processed through our authorized payment partner, <strong>SSLCommerz</strong>. Subscriptions are billed in Bangladeshi Taka (BDT) or supported international currencies as indicated at checkout.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">4. Digital Service Delivery</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Premium membership is an intangible, digital service. Upon successful transaction confirmation via SSLCommerz, account privileges are activated instantly.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">5. Privacy & Mutual Contact Exchange</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Contact numbers and WhatsApp details are never shared publicly. They are exclusively revealed between two consenting parties who have mutually accepted interest proposals.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">6. Limitation of Liability & Governing Law</h3>
                            <p class="text-slate-600 leading-relaxed">
                                While 2nd Nikah performs rigorous verification checks, members are strongly advised to independently verify credentials, family background, and legal standing before finalizing marital agreements. These Terms shall be governed by and construed in accordance with the laws of the People\'s Republic of Bangladesh.
                            </p>
                        </section>
                    </div>
                ',
                'is_published' => true,
                'meta_title' => 'Terms & Conditions - 2nd Nikah Matrimonial Platform',
                'meta_description' => 'Terms and conditions governing the use and membership services of 2nd Nikah in Bangladesh.',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">Privacy Policy</h2>
                            <p class="text-xs text-slate-500 mb-4">Last Updated: October 2026</p>
                            <p class="text-slate-600 leading-relaxed">
                                At <strong>2nd Nikah</strong> (operated by 2ndnikah, Registered Address: Dhaka-1100, Bangladesh), we treat the confidentiality of our matrimonial candidates with the highest level of care and sanctity. This Privacy Policy details how your personal data is collected, processed, and safeguarded.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">1. Information We Collect</h3>
                            <ul class="list-disc list-inside text-slate-600 space-y-1.5 leading-relaxed">
                                <li><strong>Identity & Profile Data:</strong> Name, age, gender, marital history, religion, educational background, occupation, and lifestyle preferences.</li>
                                <li><strong>Contact Information:</strong> Verified email address and mobile phone number.</li>
                                <li><strong>Photographs:</strong> Uploaded profile pictures (with options for private blurring / photo privacy).</li>
                                <li><strong>Payment Records:</strong> Transaction identifiers, amount, and payment status via SSLCommerz (we do NOT store credit card numbers, CVVs, or mobile banking PINs).</li>
                            </ul>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">2. How We Use and Protect Your Data</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Your information is strictly utilized to facilitate matrimonial matchmaking, verify user authenticity, and secure platform communications. We never sell, rent, or trade your personal information to third-party marketing companies.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">3. Payment Gateway Security (SSLCommerz)</h3>
                            <p class="text-slate-600 leading-relaxed">
                                All financial transactions are encrypted with 128-bit/256-bit bank-grade SSL technology and processed through <strong>SSLCommerz</strong>, an authorized, PCI-DSS Level 1 certified payment aggregator in Bangladesh. Your sensitive banking passwords, card PINs, and security keys are processed solely on the gateway\'s secure banking servers.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">4. User Privacy Controls & Account Deletion</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Members maintain full authority to pause visibility, hide photos, or permanently delete their account and associated personal data at any time from the Account Settings panel.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">5. Contact Our Privacy Officer</h3>
                            <p class="text-slate-600 leading-relaxed">
                                For inquiries regarding data protection, please contact us at <strong>2ndnikahsupport@gmail.com</strong>.
                            </p>
                        </section>
                    </div>
                ',
                'is_published' => true,
                'meta_title' => 'Privacy Policy - 2nd Nikah Confidential Matrimony',
                'meta_description' => 'Comprehensive privacy policy of 2nd Nikah detailing data protection, SSLCommerz payment encryption, and privacy controls.',
            ],
            [
                'title' => 'Return and Refund Policy',
                'slug' => 'refund-policy',
                'content' => '
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">Return and Refund Policy</h2>
                            <p class="text-xs text-slate-500 mb-4">Last Updated: October 2026</p>
                            <p class="text-slate-600 leading-relaxed">
                                Thank you for choosing <strong>2nd Nikah</strong> (www.2ndnikah.com), an enterprise matrimonial service operated by <strong>2ndnikah</strong> (Trade License: TRAD/DNCC/025984/2024, Dhaka-1100, Bangladesh). This Return and Refund Policy outlines the terms governing our digital membership subscriptions and billing dispute resolution.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">1. Nature of Digital Matrimonial Services</h3>
                            <p class="text-slate-600 leading-relaxed">
                                2nd Nikah provides online digital matchmaking, profile discovery, and matrimonial networking services. Upon successful payment through our secure payment gateway partner (SSLCommerz), the customer receives immediate, automated digital activation granting premium privileges (e.g. expressing direct interest, sending secure messages, and exchanging verified contact details).
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">2. General Refund Terms</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Because membership plans provide immediate and unrestricted digital access to our candidate database upon purchase, payments are generally <strong>non-refundable</strong> once the account has been upgraded and the membership features have been actively utilized.
                            </p>
                        </section>

                        <section class="bg-amber-50/70 border border-amber-200 rounded-2xl p-5 space-y-3">
                            <h3 class="text-lg font-bold text-amber-900">3. Eligible Circumstances for Refund</h3>
                            <p class="text-slate-700 leading-relaxed text-sm">
                                We review refund requests on a fair, case-by-case basis under the following verified conditions:
                            </p>
                            <ul class="list-disc list-inside text-slate-700 text-sm space-y-2">
                                <li><strong>Technical Activation Failure:</strong> If payment was successfully debited from your card/mobile wallet via SSLCommerz, but your membership plan failed to activate due to a technical server error, and our technical support team is unable to resolve the issue within 24 hours.</li>
                                <li><strong>Duplicate or Accidental Multiple Charges:</strong> If you were accidentally charged more than once for the same membership plan during a single checkout session due to gateway or connection latency. The duplicate amount will be refunded in full.</li>
                                <li><strong>Immediate Cancellation (Unused Service):</strong> If you submit a cancellation and refund request within <strong>24 to 48 hours</strong> of purchase, provided that no direct communication, proposal requests, or contact reveals were initiated during that period.</li>
                            </ul>
                        </section>

                        <section class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 space-y-2">
                            <h3 class="text-lg font-bold text-emerald-900">4. Standard Refund Timeline (7 to 10 Working Days)</h3>
                            <p class="text-emerald-800 leading-relaxed text-sm font-medium">
                                Once an eligible refund request is approved by our billing department, the refund transaction will be initiated immediately through the SSLCommerz gateway system.
                            </p>
                            <p class="text-emerald-900 leading-relaxed text-sm font-bold">
                                ⏱️ Refund Settlement Timeline: Approved refunds will be credited back to the customer\'s original payment source (Visa, Mastercard, bKash, Nagad, Rocket, or Bank Account) within <strong>7 to 10 working days</strong>, adhering to standard interbank clearance and gateway settlement schedules.
                            </p>
                        </section>

                        <section>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">5. How to Submit a Refund or Dispute Request</h3>
                            <p class="text-slate-600 leading-relaxed mb-3">
                                If you believe you are entitled to a refund or encountered an erroneous charge, please reach out to our dedicated support desk with the following information:
                            </p>
                            <ul class="list-disc list-inside text-slate-600 space-y-1.5 text-sm">
                                <li>Registered Member Email Address & Mobile Number</li>
                                <li>SSLCommerz Transaction ID (Tran ID) from your payment confirmation SMS/email</li>
                                <li>Date and Exact Amount Paid</li>
                                <li>Reason for the refund request</li>
                            </ul>
                        </section>

                        <section class="border-t border-slate-200 pt-5 text-sm text-slate-600 space-y-1">
                            <p><strong>Merchant Organization:</strong> 2ndnikah</p>
                            <p><strong>Trade License Number:</strong> TRAD/DNCC/025984/2024</p>
                            <p><strong>Registered Office Address:</strong> Dhaka-1100, Bangladesh</p>
                            <p><strong>Customer Support Email:</strong> 2ndnikahsupport@gmail.com</p>
                            <p><strong>Website:</strong> https://www.2ndnikah.com</p>
                        </section>
                    </div>
                ',
                'is_published' => true,
                'meta_title' => 'Return and Refund Policy - 2nd Nikah (7 to 10 Days Timeline)',
                'meta_description' => 'Official return and refund policy for 2nd Nikah membership plans with a clear 7 to 10 working days settlement timeline.',
            ],
        ];

        foreach ($pages as $page) {
            CmsPage::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
