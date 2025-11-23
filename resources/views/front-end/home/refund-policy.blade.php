@extends('front-end.layouts.app')
@section('title', 'Refund Policy')
@section('content')

<div class="py-5 bg-gradient-to-r from-pink-50 to-purple-50 border-b">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="display-5 fw-bold text-dark mb-2">
					<i class="ri-information-fill me-2"></i>No Refund Policy
				</h1>
				<p class="text-muted">Please review products carefully before purchase</p>
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
							<a href="#policy" class="nav-link text-secondary py-2"><i class="ri-information-line me-1"></i> Our Policy</a>
							<a href="#damaged" class="nav-link text-secondary py-2"><i class="ri-error-warning-line me-1"></i> Exceptions</a>
							<a href="#shopping" class="nav-link text-secondary py-2"><i class="ri-shopping-bag-line me-1"></i> Shopping Tips</a>
							<a href="#contact" class="nav-link text-secondary py-2"><i class="ri-customer-service-line me-1"></i> Contact</a>
						</nav>
					</div>
				</div>
			</div>

			<!-- Main Content -->
			<div class="col-lg-9">
				<div class="card shadow-sm mb-4">
					<div class="card-body p-4">
						<div class="mb-4 bg-light rounded p-3">
							<p class="lead mb-0">
								At <strong>Sajid Beauty BD</strong>, we are committed to providing high-quality cosmetics and beauty products. Please review all product details carefully before making a purchase.
							</p>
						</div>

					<!-- No Refund Policy Notice -->
					<div id="policy" class="mb-5">
						<h3 class="h4 fw-bold text-dark mb-4">
							<i class="ri-information-fill text-danger me-2"></i>Our No Refund Policy
						</h3>
						<div class="alert alert-danger border-danger w-100">
							<h5 class="alert-heading fw-bold"><i class="ri-close-circle-line me-2"></i>No Returns or Refunds</h5>
							<p class="mb-0">At <strong>Sajid Beauty BD</strong>, we do not accept returns or offer refunds on any cosmetics and beauty products. All sales are final. Please ensure you review product details, ingredients, shades, and specifications carefully before making a purchase.</p>
						</div>
						<div class="card border-info mb-3">
							<div class="card-body">
								<h5 class="fw-bold text-info mb-3"><i class="ri-question-line me-2"></i>Why We Don't Accept Returns</h5>
								<ul class="mb-0">
									<li><strong>Hygiene & Safety Standards:</strong> For health and safety reasons, cosmetic products cannot be resold once they leave our facility. This protects all our customers.</li>
									<li><strong>Product Integrity:</strong> We cannot guarantee the condition or authenticity of products that have been handled outside our control.</li>
									<li><strong>Industry Regulations:</strong> This policy aligns with cosmetic industry hygiene regulations and best practices in Bangladesh.</li>
									<li><strong>Quality Assurance:</strong> All products are carefully inspected before shipping to ensure they meet our quality standards.</li>
								</ul>
							</div>
						</div>
					</div>						<!-- Exceptions -->
						<div id="damaged" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-error-warning-fill text-warning me-2"></i>Exception: Damaged or Defective Products Only
							</h3>
							<div class="alert alert-warning border-warning w-100">
								<h5 class="alert-heading fw-bold"><i class="ri-alert-line me-2"></i>The ONLY Exception to Our Policy</h5>
								<p class="mb-0">If you receive a product that is <strong>genuinely damaged during delivery or defective from manufacturing</strong>, please contact us <strong>within 24 hours of delivery</strong> with clear photos/video evidence. We will investigate and provide a replacement if the claim is valid.</p>
							</div>
							<div class="card border-success mb-3">
								<div class="card-body">
									<h6 class="fw-bold text-success"><i class="ri-checkbox-circle-line me-2"></i>Valid Cases for Replacement:</h6>
									<ul class="mb-2">
										<li><strong>Broken/Damaged in Transit:</strong> Product broken during shipping with visible packaging damage</li>
										<li><strong>Manufacturing Defect:</strong> Factory defects like broken powder compacts, leaking bottles (unopened)</li>
										<li><strong>Wrong Product Sent:</strong> We shipped an incorrect item by our error</li>
										<li><strong>Expired Product:</strong> Product past expiration date on arrival (very rare)</li>
									</ul>
								</div>
							</div>
							<div class="card border-danger mb-3">
								<div class="card-body">
									<h6 class="fw-bold text-danger"><i class="ri-close-circle-line me-2"></i>NOT Valid for Replacement:</h6>
									<ul class="mb-0">
										<li>Product opened, used, or tested</li>
										<li>Allergic reactions or skin sensitivity</li>
										<li>Color/shade not matching expectations</li>
										<li>Changed mind after purchase</li>
										<li>Product damaged after opening/use</li>
										<li>No proof of purchase or delivery photos</li>
									</ul>
								</div>
							</div>
							<p><strong>Required Evidence (within 24 hours):</strong></p>
							<ul>
								<li>Clear photos of the damaged/defective product (unopened/sealed)</li>
								<li>Photos of outer packaging showing damage</li>
								<li>Unboxing video (highly recommended - speeds up approval)</li>
								<li>Order number and delivery confirmation</li>
							</ul>
						</div>

						<!-- Shopping Tips -->
						<div id="shopping" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-shopping-bag-line text-success me-2"></i>Shop with Confidence - Important Tips
							</h3>
							<div class="card bg-light border-0">
								<div class="card-body">
									<p class="mb-3">Since we don't offer returns, please follow these tips before purchasing:</p>
									<div class="row g-3">
										<div class="col-md-6">
											<ul class="mb-0">
												<li><strong>Read Product Details:</strong> Check ingredients, size, shade, and specifications carefully</li>
												<li><strong>Check Product Images:</strong> Review all photos to understand what you're buying</li>
												<li><strong>Read Reviews:</strong> See what other customers say about the product</li>
												<li><strong>Verify Shade/Color:</strong> Double-check makeup shades before ordering</li>
											</ul>
										</div>
										<div class="col-md-6">
											<ul class="mb-0">
												<li><strong>Contact Us First:</strong> Questions? Ask before buying - we're here to help!</li>
												<li><strong>Check Allergies:</strong> Review ingredients if you have sensitive skin</li>
												<li><strong>Quantity Matters:</strong> Confirm quantity, size, and volume before checkout</li>
												<li><strong>Authentic Products:</strong> All our products are 100% authentic and quality-checked</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Contact -->
						<div id="contact" class="mb-4">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-customer-service-2-fill text-success me-2"></i>Questions? Contact Us Before Buying
							</h3>
							<div class="card bg-gradient-to-r from-pink-50 to-purple-50 border-0">
								<div class="card-body">
									<p class="mb-3">Have questions about a product? Contact us before purchasing - we're happy to help!</p>
									<div class="row g-3">
										<div class="col-md-6">
											<div class="d-flex align-items-center">
												<i class="ri-phone-fill text-success fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Call/WhatsApp</small>
													<strong>+88 01648-022175</strong>
													<small class="d-block text-muted">Daily 10 AM - 8 PM</small>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="d-flex align-items-center">
												<i class="ri-mail-fill text-primary fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Email</small>
													<strong>sajidbeautybd@gmail.com</strong>
													<small class="d-block text-muted">24-48 hour response</small>
												</div>
											</div>
										</div>
											<div class="col-md-12">
												<div class="d-flex align-items-center">
													<i class="ri-map-pin-fill text-danger fs-3 me-3"></i>
													<div>
														<small class="text-muted d-block">Visit Our Store</small>
														<strong>Sajid Beauty BD</strong><br>
														<span>Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205</span>
													</div>
												</div>
											</div>
									</div>
								</div>
							</div>
						</div>

						<div class="text-center text-muted small">
							<p class="mb-0"><i class="ri-calendar-line me-1"></i>Last Updated: {{ now()->format('F j, Y') }}</p>
							<p class="mb-0 mt-2 fw-bold text-danger">Important: All sales are final. Please shop carefully as we do not accept returns or refunds.</p>
							<p class="mb-0 mt-1">We're committed to providing quality products and excellent customer service.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection