@extends('front-end.layouts.app')
@section('title', 'Privacy Policy')
@section('content')

<div class="py-5 bg-gradient-to-r from-pink-50 to-purple-50 border-b">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="display-5 fw-bold text-dark mb-2">
					<i class="ri-shield-user-line me-2"></i>Privacy Policy
				</h1>
				<p class="text-muted">Your privacy is important to us - Learn how we protect your data</p>
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
							<a href="#info-collect" class="nav-link text-secondary py-2"><i class="ri-file-list-line me-1"></i> Data We Collect</a>
							<a href="#how-use" class="nav-link text-secondary py-2"><i class="ri-settings-3-line me-1"></i> How We Use Data</a>
							<a href="#share" class="nav-link text-secondary py-2"><i class="ri-share-line me-1"></i> Data Sharing</a>
							<a href="#security" class="nav-link text-secondary py-2"><i class="ri-shield-check-line me-1"></i> Security</a>
							<a href="#cookies" class="nav-link text-secondary py-2"><i class="ri-firefox-line me-1"></i> Cookies</a>
							<a href="#rights" class="nav-link text-secondary py-2"><i class="ri-user-settings-line me-1"></i> Your Rights</a>
							<a href="#thirdparty" class="nav-link text-secondary py-2"><i class="ri-links-line me-1"></i> Third Parties</a>
							<a href="#children" class="nav-link text-secondary py-2"><i class="ri-parent-line me-1"></i> Children's Privacy</a>
							<a href="#contact" class="nav-link text-secondary py-2"><i class="ri-customer-service-line me-1"></i> Contact</a>
						</nav>
					</div>
				</div>
			</div>

			<!-- Main Content -->
			<div class="col-lg-9">
				<div class="card shadow-sm mb-4">
					<div class="card-body p-4">
						<div class="mb-4 p-4 bg-light rounded">
							<p class="lead mb-0">
								<strong>Sajid Beauty BD</strong> values your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, share, and safeguard your data when you shop for cosmetics and beauty products on our website.
							</p>
						</div>

						<!-- Introduction -->
						<div id="intro" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-information-fill text-primary me-2"></i>1. Introduction
							</h3>
							<p>This Privacy Policy applies to www.sajidbeautybd.com and describes:</p>
							<ul>
								<li>What personal information we collect when you purchase beauty products</li>
								<li>How we use your information to process orders and improve service</li>
								<li>Who we share your data with (payment processors, delivery partners)</li>
								<li>Your rights to access, modify, or delete your personal information</li>
								<li>How we protect your sensitive data</li>
							</ul>
							<p>By using our website and making purchases, you consent to the data practices described in this policy.</p>
						</div>

						<!-- Information We Collect -->
						<div id="info-collect" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-file-list-3-fill text-success me-2"></i>2. Information We Collect
							</h3>
							<div class="row g-3 mb-3">
								<div class="col-md-6">
									<div class="card border-primary h-100">
										<div class="card-body">
											<h6 class="text-primary"><i class="ri-user-line me-2"></i>Personal Information</h6>
											<ul class="small mb-0">
												<li>Full name</li>
												<li>Email address</li>
												<li>Phone number</li>
												<li>Delivery address</li>
												<li>Billing address</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-success h-100">
										<div class="card-body">
											<h6 class="text-success"><i class="ri-bank-card-line me-2"></i>Payment Information</h6>
											<ul class="small mb-0">
												<li>Payment method choice</li>
												<li>Transaction details</li>
												<li>Order history</li>
												<li>Billing information</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-info h-100">
										<div class="card-body">
											<h6 class="text-info"><i class="ri-shopping-bag-line me-2"></i>Shopping Behavior</h6>
											<ul class="small mb-0">
												<li>Products viewed</li>
												<li>Cart items</li>
												<li>Wishlist items</li>
												<li>Purchase history</li>
												<li>Product reviews</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-warning h-100">
										<div class="card-body">
											<h6 class="text-warning"><i class="ri-computer-line me-2"></i>Technical Data</h6>
											<ul class="small mb-0">
												<li>IP address</li>
												<li>Browser type</li>
												<li>Device information</li>
												<li>Pages visited</li>
												<li>Time spent on site</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="alert alert-info w-100">
								<i class="ri-information-line me-2"></i><strong>Note:</strong> We do NOT store your credit/debit card information. All card payments are processed securely through SSL Commerz payment gateway.
							</div>
						</div>

						<!-- How We Use -->
						<div id="how-use" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-settings-3-fill text-info me-2"></i>3. How We Use Your Information
							</h3>
							<div class="accordion" id="useAccordion">
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#orderProcessing">
											<i class="ri-shopping-cart-line me-2"></i> Order Processing & Fulfillment
										</button>
									</h2>
									<div id="orderProcessing" class="accordion-collapse collapse show" data-bs-parent="#useAccordion">
										<div class="accordion-body">
											<ul class="mb-0">
												<li>Process and fulfill your cosmetics orders</li>
												<li>Verify payment and prevent fraud</li>
												<li>Coordinate delivery with courier services</li>
												<li>Send order confirmations and updates via SMS/email</li>
												<li>Handle returns and refunds</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#personalization">
											<i class="ri-user-smile-line me-2"></i> Personalization & Recommendations
										</button>
									</h2>
									<div id="personalization" class="accordion-collapse collapse" data-bs-parent="#useAccordion">
										<div class="accordion-body">
											<ul class="mb-0">
												<li>Recommend beauty products based on your preferences</li>
												<li>Personalize your shopping experience</li>
												<li>Show relevant skincare and makeup products</li>
												<li>Remember your wishlist and cart items</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#marketing">
											<i class="ri-mail-send-line me-2"></i> Marketing & Communication
										</button>
									</h2>
									<div id="marketing" class="accordion-collapse collapse" data-bs-parent="#useAccordion">
										<div class="accordion-body">
											<ul class="mb-0">
												<li>Send promotional offers and discounts (with your consent)</li>
												<li>Notify about new beauty product arrivals</li>
												<li>Share skincare tips and beauty trends</li>
												<li>Send newsletters about exclusive deals</li>
												<li><strong>Note:</strong> You can unsubscribe anytime</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#improvement">
											<i class="ri-line-chart-line me-2"></i> Service Improvement
										</button>
									</h2>
									<div id="improvement" class="accordion-collapse collapse" data-bs-parent="#useAccordion">
										<div class="accordion-body">
											<ul class="mb-0">
												<li>Analyze shopping patterns to improve product selection</li>
												<li>Enhance website performance and user experience</li>
												<li>Understand which cosmetic categories are popular</li>
												<li>Improve delivery and customer service</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Information Sharing -->
						<div id="share" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-share-fill text-warning me-2"></i>4. Information Sharing & Disclosure
							</h3>
							<div class="card border-warning mb-3">
								<div class="card-body">
									<h6 class="text-warning"><i class="ri-alert-line me-2"></i>We Do Not Sell Your Data</h6>
									<p class="mb-0 small">Sajid Beauty BD does not sell, rent, or trade your personal information to third parties for marketing purposes.</p>
								</div>
							</div>
							<p>We may share your information with:</p>
							<table class="table table-bordered">
								<thead class="table-light">
									<tr>
										<th>Service Partner</th>
										<th>Purpose</th>
										<th>Data Shared</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><strong>Payment Processors</strong></td>
										<td>Process payments securely</td>
										<td>Transaction details, amount</td>
									</tr>
									<tr>
										<td><strong>Delivery Partners</strong></td>
										<td>Deliver your beauty products</td>
										<td>Name, phone, address, order details</td>
									</tr>
									<tr>
										<td><strong>SMS/Email Services</strong></td>
										<td>Send notifications</td>
										<td>Phone, email, order status</td>
									</tr>
									<tr>
										<td><strong>Analytics Tools</strong></td>
										<td>Improve website performance</td>
										<td>Anonymous usage data</td>
									</tr>
									<tr>
										<td><strong>Law Enforcement</strong></td>
										<td>Legal compliance</td>
										<td>As required by law</td>
									</tr>
								</tbody>
							</table>
						</div>

						<!-- Security -->
						<div id="security" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-shield-check-fill text-success me-2"></i>5. Data Security Measures
							</h3>
							<p>We implement industry-standard security measures to protect your personal information:</p>
							<div class="row g-3">
								<div class="col-md-6">
									<div class="d-flex align-items-start">
										<i class="ri-lock-2-fill text-success fs-4 me-3 mt-1"></i>
										<div>
											<h6 class="fw-bold mb-1">SSL Encryption</h6>
											<p class="small text-muted mb-0">All data transmitted between your browser and our server is encrypted with SSL/TLS.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="d-flex align-items-start">
										<i class="ri-server-fill text-primary fs-4 me-3 mt-1"></i>
										<div>
											<h6 class="fw-bold mb-1">Secure Servers</h6>
											<p class="small text-muted mb-0">Your data is stored on secure servers with firewall protection.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="d-flex align-items-start">
										<i class="ri-shield-keyhole-fill text-danger fs-4 me-3 mt-1"></i>
										<div>
											<h6 class="fw-bold mb-1">Access Control</h6>
											<p class="small text-muted mb-0">Only authorized personnel can access your information.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="d-flex align-items-start">
										<i class="ri-eye-off-fill text-info fs-4 me-3 mt-1"></i>
										<div>
											<h6 class="fw-bold mb-1">Password Protection</h6>
											<p class="small text-muted mb-0">Your account password is encrypted and never stored in plain text.</p>
										</div>
									</div>
								</div>
							</div>
							<div class="alert alert-warning mt-3 w-100">
								<i class="ri-information-line me-2"></i><strong>Important:</strong> While we use best practices to protect your data, no internet transmission is 100% secure. Please use strong passwords and keep your account credentials confidential.
							</div>
						</div>

						<!-- Cookies -->
						<div id="cookies" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-firefox-fill text-danger me-2"></i>6. Cookies & Tracking Technologies
							</h3>
							<p>We use cookies to enhance your shopping experience. Cookies are small text files stored on your device.</p>
							<h6 class="fw-bold">Types of Cookies We Use:</h6>
							<ul>
								<li><strong>Essential Cookies:</strong> Required for website functionality (login, cart)</li>
								<li><strong>Performance Cookies:</strong> Help us understand how you use the site</li>
								<li><strong>Functional Cookies:</strong> Remember your preferences (language, currency)</li>
								<li><strong>Marketing Cookies:</strong> Show relevant beauty product ads (with consent)</li>
							</ul>
							<p>You can manage cookies through your browser settings. Note that disabling cookies may affect website functionality.</p>
						</div>

						<!-- Your Rights -->
						<div id="rights" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-user-settings-fill text-primary me-2"></i>7. Your Privacy Rights
							</h3>
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card bg-light border-0 h-100">
										<div class="card-body">
											<h6><i class="ri-eye-line text-primary me-2"></i>Access Your Data</h6>
											<p class="small mb-0">Request a copy of all personal data we hold about you.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card bg-light border-0 h-100">
										<div class="card-body">
											<h6><i class="ri-edit-line text-success me-2"></i>Update Information</h6>
											<p class="small mb-0">Correct or update your personal information anytime.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card bg-light border-0 h-100">
										<div class="card-body">
											<h6><i class="ri-delete-bin-line text-danger me-2"></i>Delete Account</h6>
											<p class="small mb-0">Request deletion of your account and personal data.</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card bg-light border-0 h-100">
										<div class="card-body">
											<h6><i class="ri-mail-close-line text-warning me-2"></i>Opt-Out Marketing</h6>
											<p class="small mb-0">Unsubscribe from promotional emails anytime.</p>
										</div>
									</div>
								</div>
							</div>
							<p class="mt-3">To exercise your rights, contact us at <strong>sajidbeautybd@gmail.com</strong> or through your account settings.</p>
						</div>

						<!-- Third Party Links -->
						<div id="thirdparty" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-links-fill text-info me-2"></i>8. Third-Party Links & Services
							</h3>
							<p>Our website may contain links to third-party websites (payment gateways, social media, beauty brand websites). We are not responsible for the privacy practices of these external sites. Please review their privacy policies before providing any information.</p>
						</div>

						<!-- Children's Privacy -->
						<div id="children" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-parent-fill text-danger me-2"></i>9. Children's Privacy
							</h3>
							<p>Sajid Beauty BD does not knowingly collect personal information from children under 18 years old. Our cosmetics products are intended for adult consumers. If you are under 18, please shop with parental consent.</p>
						</div>

						<!-- Changes to Policy -->
						<div id="changes" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-refresh-fill text-warning me-2"></i>10. Changes to Privacy Policy
							</h3>
							<p>We may update this Privacy Policy periodically to reflect:</p>
							<ul>
								<li>Changes in our data practices or services</li>
								<li>New features or product categories</li>
								<li>Legal or regulatory requirements</li>
							</ul>
							<p>We'll notify you of significant changes via email or website banner. Continued use after changes constitutes acceptance.</p>
						</div>

						<!-- Contact -->
						<div id="contact" class="mb-4">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-customer-service-2-fill text-success me-2"></i>11. Contact Us About Privacy
							</h3>
							<div class="card bg-gradient-to-r from-pink-50 to-purple-50 border-0">
								<div class="card-body">
									<p class="mb-3">Have questions about your privacy or want to exercise your data rights?</p>
									<div class="row g-3">
										<div class="col-md-6">
											<div class="d-flex align-items-center">
												<i class="ri-mail-fill text-primary fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Email</small>
													<strong>sajidbeautybd@gmail.com</strong>
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
												<i class="ri-map-pin-fill text-danger fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Visit Us</small>
													<strong>Shop No-95, Shimanto Shambar Mall, Dhaka-1205</strong>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="text-center text-muted small">
							<p class="mb-0"><i class="ri-calendar-line me-1"></i>Last Updated: {{ now()->format('F j, Y') }}</p>
							<p class="mb-0 mt-1">By using Sajid Beauty BD, you acknowledge that you have read and understood this Privacy Policy.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
