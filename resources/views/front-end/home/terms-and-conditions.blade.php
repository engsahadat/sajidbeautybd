@extends('front-end.layouts.app')
@section('title', 'Terms and Conditions')
@section('content')
<div class="py-5 bg-gradient-to-r from-pink-50 to-purple-50 border-b">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="display-5 fw-bold text-dark mb-2">
					<i class="ri-file-text-line me-2"></i>Terms & Conditions
				</h1>
				<p class="text-muted">Please read these terms carefully before shopping with us</p>
			</div>
		</div>
	</div>
</div>

<section class="py-5">
	<div class="container">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-lg-3 mb-4">
				<div class="card shadow-sm sticky-top" style="top: 100px;">
					<div class="card-body">
						<h6 class="fw-bold mb-3"><i class="ri-list-check me-2"></i>Quick Navigation</h6>
						<nav class="nav flex-column">
							<a href="#intro" class="nav-link text-secondary py-2"><i class="ri-information-line me-1"></i> Introduction</a>
							<a href="#products" class="nav-link text-secondary py-2"><i class="ri-product-hunt-line me-1"></i> Product Quality</a>
							<a href="#orders" class="nav-link text-secondary py-2"><i class="ri-shopping-cart-line me-1"></i> Orders & Pricing</a>
							<a href="#payment" class="nav-link text-secondary py-2"><i class="ri-bank-card-line me-1"></i> Payment</a>
							<a href="#shipping" class="nav-link text-secondary py-2"><i class="ri-truck-line me-1"></i> Shipping</a>
							<a href="#returns" class="nav-link text-secondary py-2"><i class="ri-arrow-go-back-line me-1"></i> Returns & Refunds</a>
							<a href="#liability" class="nav-link text-secondary py-2"><i class="ri-shield-check-line me-1"></i> Liability</a>
							<a href="#accounts" class="nav-link text-secondary py-2"><i class="ri-user-settings-line me-1"></i> User Accounts</a>
							<a href="#privacy" class="nav-link text-secondary py-2"><i class="ri-lock-line me-1"></i> Privacy</a>
							<a href="#law" class="nav-link text-secondary py-2"><i class="ri-government-line me-1"></i> Governing Law</a>
							<a href="#contact" class="nav-link text-secondary py-2"><i class="ri-customer-service-line me-1"></i> Contact</a>
						</nav>
					</div>
				</div>
			</div>

			<!-- Main Content -->
			<div class="col-lg-9">
				<div class="card shadow-sm mb-4">
					<div class="card-body p-4">
					<div class="mb-4 bg-light rounded">
						<p class="lead mb-0">
							Welcome to <strong>Sajid Beauty BD</strong>! By accessing and using our website to purchase cosmetics and beauty products, you agree to comply with and be bound by the following terms and conditions. Please review these terms carefully before making a purchase.
						</p>
					</div>						<!-- Introduction -->
						<div id="intro" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-file-info-fill text-primary me-2"></i>1. Introduction & Acceptance
							</h3>
							<p>These Terms and Conditions govern your use of <strong>Sajid Beauty BD</strong> (www.sajidbeautybd.com) and the purchase of cosmetics, skincare, makeup, fragrances, and other beauty products from our store.</p>
							<p>By placing an order with us, you confirm that:</p>
							<ul>
								<li>You are at least 18 years old or have parental consent</li>
								<li>You have read, understood, and accept these terms</li>
								<li>The information you provide is accurate and complete</li>
								<li>You accept the responsibility for all orders placed under your account</li>
							</ul>
						</div>

						<!-- Product Quality -->
						<div id="products" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-file-info-fill text-success me-2"></i>2. Product Authenticity & Quality
							</h3>
						<div class="alert alert-success w-100">
							<h6 class="alert-heading"><i class="ri-shield-check-line me-2"></i>100% Authentic Products Guarantee</h6>
							<p class="mb-0">All cosmetics and beauty products sold on Sajid Beauty BD are 100% authentic and sourced directly from authorized distributors and brand representatives. We do not sell counterfeit, expired, or tampered products.</p>
						</div>
							<ul>
								<li><strong>Original Products:</strong> All skincare, makeup, and beauty items are genuine brand products</li>
								<li><strong>Expiry Dates:</strong> Products have valid expiry dates with minimum 6 months shelf life (unless specified)</li>
								<li><strong>Storage Conditions:</strong> All cosmetics are stored in proper temperature and humidity conditions</li>
								<li><strong>Sealed Packaging:</strong> Products are delivered with factory-sealed packaging intact</li>
								<li><strong>Quality Control:</strong> Each item undergoes quality inspection before dispatch</li>
							</ul>
						</div>

						<!-- Orders & Pricing -->
						<div id="orders" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-shopping-bag-fill text-info me-2"></i>3. Orders, Pricing & Availability
							</h3>
							<h6 class="fw-bold">Pricing</h6>
							<ul>
								<li>All prices are listed in Bangladeshi Taka (৳) and include VAT where applicable</li>
								<li>Prices are subject to change without prior notice due to supplier pricing or promotions</li>
								<li>If there's a price error, we'll notify you and offer to confirm at the correct price or cancel</li>
								<li>Sale prices and discounts are valid for limited periods as mentioned on the website</li>
							</ul>
							<h6 class="fw-bold mt-3">Stock Availability</h6>
							<ul>
								<li>Product availability is updated regularly but not guaranteed until order confirmation</li>
								<li>If an item becomes unavailable after your order, we'll contact you for a refund or replacement</li>
								<li>Popular beauty products may have limited stock - orders are processed on first-come, first-served basis</li>
							</ul>
							<h6 class="fw-bold mt-3">Order Confirmation</h6>
							<ul>
								<li>You'll receive an email/SMS confirmation once your order is successfully placed</li>
								<li>Order confirmation means we've received your order, not that it's accepted</li>
								<li>We reserve the right to refuse or cancel any order at our discretion</li>
							</ul>
						</div>

						<!-- Payment -->
						<div id="payment" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-secure-payment-fill text-warning me-2"></i>4. Payment Methods & Security
							</h3>
							<div class="row g-3 mb-3">
								<div class="col-md-6">
									<div class="card border-primary h-100">
										<div class="card-body">
											<h6 class="text-primary"><i class="ri-cash-line me-2"></i>Cash on Delivery</h6>
											<p class="mb-0 small">Pay in cash when you receive your beauty products at your doorstep.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-success h-100">
										<div class="card-body">
											<h6 class="text-success"><i class="ri-smartphone-line me-2"></i>Mobile Banking</h6>
											<p class="mb-0 small">Pay securely via bKash, Nagad, Rocket, or other mobile banking services.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-info h-100">
										<div class="card-body">
											<h6 class="text-info"><i class="ri-bank-card-line me-2"></i>Credit/Debit Card</h6>
											<p class="mb-0 small">Visa, Mastercard, and other cards via SSL Commerz secure gateway.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-warning h-100">
										<div class="card-body">
											<h6 class="text-warning"><i class="ri-bank-line me-2"></i>Bank Transfer</h6>
											<p class="mb-0 small">Direct bank transfer to our official account (contact for details).</p>
										</div>
									</div>
								</div>
							</div>
						<div class="alert alert-info w-100">
							<i class="ri-shield-keyhole-line me-2"></i><strong>Payment Security:</strong> All online payments are processed through encrypted, PCI-DSS compliant payment gateways. We do not store your card information.
						</div>
						</div>

						<!-- Shipping -->
						<div id="shipping" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-truck-fill text-danger me-2"></i>5. Shipping & Delivery
							</h3>
							<p>Detailed shipping information is available in our <a href="{{ route('home.delivery-policy') }}" class="text-decoration-none">Delivery Policy</a>. Key points:</p>
							<ul>
								<li>Inside Dhaka delivery: 1-2 business days (৳60)</li>
								<li>Outside Dhaka delivery: 3-7 business days (৳120)</li>
								<li>Free delivery on orders above ৳1500</li>
								<li>Cosmetic products are packaged securely with protective materials</li>
								<li>You must provide accurate address and reachable phone number</li>
								<li>Someone must be present to receive the order</li>
							</ul>
						</div>

						<!-- Returns & Refunds -->
						<div id="returns" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-arrow-go-back-fill text-success me-2"></i>6. Returns, Refunds & Exchanges
							</h3>
						<div class="card border-warning mb-3 w-100">
							<div class="card-body">
								<h6 class="text-warning"><i class="ri-alert-line me-2"></i>Important Note for Cosmetics</h6>
								<p class="mb-0 small">Due to hygiene and safety regulations, cosmetics and beauty products have specific return conditions.</p>
							</div>
						</div>
							<h6 class="fw-bold">Return Conditions</h6>
							<ul>
								<li><strong>Damaged/Defective Products:</strong> Return within 7 days if product is damaged, defective, or wrong item delivered</li>
								<li><strong>Unopened Products:</strong> Return within 3 days if product is unused and factory seal is intact</li>
								<li><strong>Opened Products:</strong> Cannot be returned due to hygiene reasons (unless defective)</li>
								<li><strong>Allergic Reactions:</strong> We cannot accept returns for skin reactions - please check ingredients before purchase</li>
							</ul>
							<h6 class="fw-bold mt-3">Refund Process</h6>
							<ul>
								<li>Contact customer support with order number and issue details</li>
								<li>Return product in original packaging with all accessories</li>
								<li>Refunds processed within 7-14 business days after inspection</li>
								<li>Refund amount credited to original payment method or store credit</li>
							</ul>
						</div>

						<!-- Liability -->
						<div id="liability" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-shield-cross-fill text-warning me-2"></i>7. Limitation of Liability
							</h3>
							<p>To the fullest extent permitted by law:</p>
							<ul>
								<li>Sajid Beauty BD is not liable for allergic reactions or skin sensitivity to any cosmetic product</li>
								<li>Customers must perform patch tests before using new beauty products</li>
								<li>We are not responsible for results that differ from product descriptions or expectations</li>
								<li>Not liable for delays caused by courier services, natural disasters, or political unrest</li>
								<li>Maximum liability limited to the purchase price of the product</li>
								<li>Always read product ingredients, warnings, and usage instructions before application</li>
							</ul>
						<div class="alert alert-danger w-100">
							<i class="ri-error-warning-line me-2"></i><strong>Medical Disclaimer:</strong> Our beauty products are cosmetic items, not medical treatments. Consult a dermatologist for skin conditions. Discontinue use if irritation occurs.
						</div>
						</div>

						<!-- User Accounts -->
						<div id="accounts" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-user-settings-fill text-primary me-2"></i>8. Customer Accounts
							</h3>
							<ul>
								<li>Creating an account is optional but provides order tracking and faster checkout</li>
								<li>You're responsible for maintaining confidentiality of your account credentials</li>
								<li>Notify us immediately if you suspect unauthorized access to your account</li>
								<li>You're responsible for all activities under your account</li>
								<li>We reserve the right to suspend/terminate accounts for violation of terms</li>
								<li>One account per customer - multiple accounts may be merged or suspended</li>
							</ul>
						</div>

						<!-- Privacy -->
						<div id="privacy" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-lock-password-fill text-info me-2"></i>9. Privacy & Data Protection
							</h3>
							<p>We respect your privacy and protect your personal information. Our <a href="{{ route('home.privacy-policy') }}" class="text-decoration-none">Privacy Policy</a> explains:</p>
							<ul>
								<li>What information we collect (name, address, phone, email)</li>
								<li>How we use your data (order processing, delivery, marketing)</li>
								<li>How we protect your information (encryption, secure servers)</li>
								<li>Your rights regarding your personal data</li>
							</ul>
						</div>

						<!-- Intellectual Property -->
						<div id="ip" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-copyright-fill text-danger me-2"></i>10. Intellectual Property
							</h3>
							<ul>
								<li>All content on this website (text, images, logos, graphics) is owned by Sajid Beauty BD</li>
								<li>Product images, brand logos, and names are property of respective brands</li>
								<li>You may not reproduce, distribute, or use any content without written permission</li>
								<li>Unauthorized use may result in legal action</li>
							</ul>
						</div>

						<!-- Governing Law -->
						<div id="law" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-scales-3-fill text-success me-2"></i>11. Governing Law & Disputes
							</h3>
							<p>These Terms and Conditions are governed by the laws of the People's Republic of Bangladesh:</p>
							<ul>
								<li>All disputes will be subject to the jurisdiction of courts in Dhaka, Bangladesh</li>
								<li>We encourage resolving disputes amicably through customer support first</li>
								<li>For unresolved issues, formal legal proceedings may be initiated</li>
							</ul>
						</div>

						<!-- Changes -->
						<div id="changes" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-refresh-fill text-warning me-2"></i>12. Changes to Terms
							</h3>
							<p>We may update these Terms and Conditions periodically to reflect:</p>
							<ul>
								<li>Changes in our services or business practices</li>
								<li>Legal or regulatory requirements</li>
								<li>New features or product categories</li>
							</ul>
							<p>Changes will be effective immediately upon posting. Continued use of the website after changes constitutes acceptance of modified terms.</p>
						</div>

						<!-- Contact -->
						<div id="contact" class="mb-4">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-customer-service-2-fill text-success me-2"></i>13. Contact Us
							</h3>
							<div class="card bg-gradient-to-r from-pink-50 to-purple-50 border-0">
								<div class="card-body">
									<p class="mb-3">Questions about these Terms & Conditions? We're here to help!</p>
									<div class="row g-3">
										<div class="col-md-6">
											<div class="d-flex align-items-center">
												<i class="ri-map-pin-fill text-danger fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Store Address</small>
													<strong>Shop No-95, Shimanto Shambar Mall, Dhaka-1205</strong>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="d-flex align-items-center">
												<i class="ri-phone-fill text-success fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Call Us</small>
													<strong>+88 01648-022175</strong>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="d-flex align-items-center">
												<i class="ri-mail-fill text-primary fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Email</small>
													<strong>sajidbeautybd@gmail.com</strong>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="text-center text-muted small">
							<p class="mb-0"><i class="ri-calendar-line me-1"></i>Last Updated: {{ now()->format('F j, Y') }}</p>
							<p class="mb-0 mt-1">By using Sajid Beauty BD, you acknowledge that you have read and agree to these Terms & Conditions.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
