@extends('front-end.layouts.app')
@section('title', 'Refund Policy')
@section('content')

<div class="py-5 bg-gradient-to-r from-pink-50 to-purple-50 border-b">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="display-5 fw-bold text-dark mb-2">
					<i class="ri-refund-2-line me-2"></i>Refund & Return Policy
				</h1>
				<p class="text-muted">Your satisfaction is our priority - Easy returns for your peace of mind</p>
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
							<a href="#eligibility" class="nav-link text-secondary py-2"><i class="ri-checkbox-circle-line me-1"></i> Eligibility</a>
							<a href="#conditions" class="nav-link text-secondary py-2"><i class="ri-file-list-line me-1"></i> Return Conditions</a>
							<a href="#process" class="nav-link text-secondary py-2"><i class="ri-settings-3-line me-1"></i> Return Process</a>
							<a href="#refund" class="nav-link text-secondary py-2"><i class="ri-money-dollar-circle-line me-1"></i> Refund Timeline</a>
							<a href="#nonrefundable" class="nav-link text-secondary py-2"><i class="ri-close-circle-line me-1"></i> Non-Refundable</a>
							<a href="#exchange" class="nav-link text-secondary py-2"><i class="ri-exchange-line me-1"></i> Exchanges</a>
							<a href="#damaged" class="nav-link text-secondary py-2"><i class="ri-error-warning-line me-1"></i> Damaged Items</a>
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
								At <strong>Sajid Beauty BD</strong>, we want you to love your cosmetics purchase! If you're not completely satisfied with your beauty products, we're here to help with our easy return and refund process.
							</p>
						</div>

						<!-- Introduction -->
						<div id="intro" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-information-fill text-primary me-2"></i>1. Introduction
							</h3>
							<div class="alert alert-info w-100">
								<h6 class="alert-heading"><i class="ri-shield-check-line me-2"></i>Customer Satisfaction Guarantee</h6>
								<br>
								<p class="mb-0">We stand behind the quality of our cosmetics and beauty products. Your satisfaction is our top priority, and we've designed our return policy to be fair and transparent.</p>
							</div>
							<p>This policy covers:</p>
							<ul>
								<li>When and how you can return beauty products</li>
								<li>Conditions for refunds and exchanges</li>
								<li>Special considerations for cosmetic items</li>
								<li>Timeline for refund processing</li>
							</ul>
						</div>

						<!-- Eligibility -->
						<div id="eligibility" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-checkbox-circle-fill text-success me-2"></i>2. Return Eligibility Timeframe
							</h3>
							<div class="row g-3">
								<div class="col-md-4">
									<div class="card text-center border-danger h-100">
										<div class="card-body">
											<i class="ri-box-3-line display-4 text-danger"></i>
											<h5 class="mt-3">Unopened Products</h5>
											<h3 class="text-danger">7 Days</h3>
											<p class="small text-muted mb-0">Factory seal intact</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card text-center border-warning h-100">
										<div class="card-body">
											<i class="ri-error-warning-line display-4 text-warning"></i>
											<h5 class="mt-3">Defective Items</h5>
											<h3 class="text-warning">7 Days</h3>
											<p class="small text-muted mb-0">Damaged or defective</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card text-center border-info h-100">
										<div class="card-body">
											<i class="ri-swap-line display-4 text-info"></i>
											<h5 class="mt-3">Wrong Product</h5>
											<h3 class="text-info">3 Days</h3>
											<p class="small text-muted mb-0">Incorrect item sent</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Conditions -->
						<div id="conditions" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-file-list-3-fill text-info me-2"></i>3. Return Conditions for Beauty Products
							</h3>
							<div class="card border-success mb-3">
								<div class="card-header bg-success text-dark">
									<h6 class="mb-0 text-white"><i class="ri-checkbox-circle-line me-2"></i>Products We CAN Accept for Return</h6>
								</div>
								<div class="card-body">
									<ul class="mb-0 p-2">
										<li><strong>Unopened Cosmetics:</strong> Factory seal/shrink wrap intact, unused</li>
										<li><strong>Defective Products:</strong> Manufacturing defects (broken powder, leaking bottles, etc.)</li>
										<li><strong>Wrong Items:</strong> We sent the wrong product or shade</li>
										<li><strong>Damaged During Delivery:</strong> Broken packaging or product damage during shipping</li>
										<li><strong>Expired Products:</strong> If you receive an expired item (very rare)</li>
										<li><strong>Original Packaging:</strong> Product must be in original box with all tags and labels</li>
									</ul>
								</div>
							</div>
							<div class="card border-danger">
								<div class="card-header bg-danger text-white">
									<h6 class="mb-0 text-white"><i class="ri-close-circle-line me-2"></i>Products We CANNOT Accept for Return</h6>
								</div>
								<div class="card-body">
									<ul class="mb-0 p-2">
										<li><strong>Opened Cosmetics:</strong> Any product that has been opened, used, or tested (hygiene reasons)</li>
										<li><strong>Personal Care Items:</strong> Brushes, sponges, puffs once opened</li>
										<li><strong>Fragrances/Perfumes:</strong> Once seal is broken (due to hygiene regulations)</li>
										<li><strong>Clearance/Sale Items:</strong> Final sale items marked "Non-returnable"</li>
										<li><strong>No Receipt/Proof:</strong> Returns without order number or purchase proof</li>
										<li><strong>Damaged by Customer:</strong> Drops, mishandling, or improper storage</li>
										<li><strong>Allergic Reactions:</strong> Skin reactions or product incompatibility (please patch test)</li>
									</ul>
								</div>
							</div>
							<div class="alert alert-warning mt-3 w-100">
								<i class="ri-information-line me-2"></i><strong>Important:</strong> Due to health and hygiene regulations for cosmetic products, we cannot accept returns of opened beauty items unless they are genuinely defective or damaged.
							</div>
						</div>

						<!-- Process -->
						<div id="process" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-settings-3-fill text-primary me-2"></i>4. How to Return Beauty Products
							</h3>
							<div class="accordion" id="returnAccordion">
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#step1">
											<i class="ri-number-1 me-2"></i> Contact Customer Support
										</button>
									</h2>
									<div id="step1" class="accordion-collapse collapse show" data-bs-parent="#returnAccordion">
										<div class="accordion-body">
											<p><strong>Within 7 days of delivery</strong>, contact us via:</p>
											<ul>
												<li>Phone: <strong>+88 01648-022175</strong></li>
												<li>Email: <strong>sajidbeautybd@gmail.com</strong></li>
												<li>WhatsApp: Send message to the phone number</li>
											</ul>
											<p>Provide: Order number, product name, reason for return, and photos (if damaged)</p>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step2">
											<i class="ri-number-2 me-2"></i> Get Return Approval
										</button>
									</h2>
									<div id="step2" class="accordion-collapse collapse" data-bs-parent="#returnAccordion">
										<div class="accordion-body">
											<p>Our team will review your request within <strong>24-48 hours</strong> and:</p>
											<ul>
												<li>Verify your order details</li>
												<li>Check eligibility based on our policy</li>
												<li>Provide a Return Authorization Number (RAN)</li>
												<li>Send return instructions</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step3">
											<i class="ri-number-3 me-2"></i> Pack & Send Product
										</button>
									</h2>
									<div id="step3" class="accordion-collapse collapse" data-bs-parent="#returnAccordion">
										<div class="accordion-body">
											<p>Pack the product securely:</p>
											<ul>
												<li>Use original packaging if possible</li>
												<li>Include all accessories, manuals, freebies</li>
												<li>Write RAN on the package</li>
												<li>Ship to our address or arrange pickup (we'll coordinate)</li>
											</ul>
											<p class="text-danger mb-0"><strong>Note:</strong> Return shipping cost may be borne by customer for non-defective returns.</p>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step4">
											<i class="ri-number-4 me-2"></i> Inspection & Refund
										</button>
									</h2>
									<div id="step4" class="accordion-collapse collapse" data-bs-parent="#returnAccordion">
										<div class="accordion-body">
											<p>Once we receive your return:</p>
											<ul>
												<li>We'll inspect the product within 2-3 business days</li>
												<li>Verify it meets return conditions</li>
												<li>Notify you via email/SMS about approval or rejection</li>
												<li>Process refund if approved</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Refund Timeline -->
						<div id="refund" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-money-dollar-circle-fill text-success me-2"></i>5. Refund Processing Timeline
							</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead class="table-dark bg-dark">
										<tr>
											<th>Payment Method</th>
											<th>Refund Timeline</th>
											<th>Notes</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><strong>Cash on Delivery</strong></td>
											<td><span class="badge bg-success">3-5 Business Days</span></td>
											<td>Bank transfer to your account</td>
										</tr>
										<tr>
											<td><strong>bKash/Nagad/Rocket</strong></td>
											<td><span class="badge bg-info">2-4 Business Days</span></td>
											<td>Refund to your mobile wallet</td>
										</tr>
										<tr>
											<td><strong>Credit/Debit Card</strong></td>
											<td><span class="badge bg-warning text-dark">5-7 Business Days</span></td>
											<td>Depends on your bank processing</td>
										</tr>
										<tr>
											<td><strong>Bank Transfer</strong></td>
											<td><span class="badge bg-primary">3-7 Business Days</span></td>
											<td>Direct to your bank account</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="alert alert-info w-100">
								<i class="ri-information-line me-2"></i><strong>Refund Amount:</strong> Original product price will be refunded. Delivery charges are non-refundable unless the product was defective or we sent the wrong item.
							</div>
						</div>

						<!-- Non-Refundable -->
						<div id="nonrefundable" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-close-circle-fill text-danger me-2"></i>6. Non-Refundable Items & Situations
							</h3>
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card bg-light border-danger h-100">
										<div class="card-body">
											<h6 class="text-danger"><i class="ri-forbid-line me-2"></i>No Refund If:</h6>
											<ul class="small mb-0">
												<li>Product has been used or opened (except defective)</li>
												<li>Return period (7 days) has expired</li>
												<li>No valid proof of purchase</li>
												<li>Product damaged by customer misuse</li>
												<li>Clearance or final sale items</li>
												<li>Changed mind after product use</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card bg-light border-warning h-100">
										<div class="card-body">
											<h6 class="text-warning"><i class="ri-gift-line me-2"></i>Special Cases:</h6>
											<ul class="small mb-0">
												<li>Gift items follow same policy</li>
												<li>Combo/bundle deals must be returned complete</li>
												<li>Promotional gifts cannot be returned separately</li>
												<li>Custom/made-to-order items (if any) are final</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Exchanges -->
						<div id="exchange" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-exchange-fill text-info me-2"></i>7. Product Exchanges
							</h3>
							<p>We offer exchanges in the following cases:</p>
							<div class="card border-info">
								<div class="card-body">
									<ul class="mb-0">
										<li><strong>Wrong Shade/Color:</strong> If we sent the wrong makeup shade, exchange within 3 days (unopened)</li>
										<li><strong>Size Issues:</strong> Wrong product size sent by us</li>
										<li><strong>Defective for Same Product:</strong> Exchange defective item for same product</li>
										<li><strong>Stock Availability:</strong> Exchange subject to stock availability</li>
										<li><strong>Price Difference:</strong> If exchange item costs more, pay difference; if less, refund difference</li>
									</ul>
								</div>
							</div>
							<p class="mt-3"><strong>Exchange Process:</strong> Same as return process above. Mention you want exchange instead of refund when contacting us.</p>
						</div>

						<!-- Damaged Items -->
						<div id="damaged" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-error-warning-fill text-danger me-2"></i>8. Damaged or Defective Products
							</h3>
							<div class="alert alert-danger w-100">
								<h6 class="alert-heading"><i class="ri-alert-line me-2"></i>Immediate Action Required</h6>
								<p class="mb-0">If you receive a damaged or defective beauty product, please contact us <strong>immediately</strong> (within 24 hours if possible) with photos of the damage.</p>
							</div>
							<p><strong>We will provide:</strong></p>
							<ul>
								<li>Free return pickup at your doorstep</li>
								<li>Full refund including delivery charges</li>
								<li>Or immediate replacement shipment</li>
								<li>Compensation for inconvenience (case by case)</li>
							</ul>
							<p><strong>Evidence Required:</strong></p>
							<ul>
								<li>Clear photos of damaged product</li>
								<li>Photos of outer packaging if damaged in transit</li>
								<li>Unboxing video (if available - helps process faster)</li>
							</ul>
						</div>

						<!-- Changes -->
						<div id="changes" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-refresh-fill text-warning me-2"></i>9. Changes to Refund Policy
							</h3>
							<p>We reserve the right to update this Refund & Return Policy to reflect:</p>
							<ul>
								<li>Changes in cosmetics industry regulations</li>
								<li>Improvements to our return process</li>
								<li>Legal or business requirements</li>
							</ul>
							<p>Changes will be posted on this page with an updated date. Continued purchases after changes constitute acceptance.</p>
						</div>

						<!-- Contact -->
						<div id="contact" class="mb-4">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-customer-service-2-fill text-success me-2"></i>10. Need Help with Returns?
							</h3>
							<div class="card bg-gradient-to-r from-pink-50 to-purple-50 border-0">
								<div class="card-body">
									<p class="mb-3">Our customer support team is here to assist you with returns and refunds!</p>
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
													<small class="text-muted d-block">Return Address</small>
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
							<p class="mb-0 mt-1">Your satisfaction is our priority. We're committed to making your shopping experience smooth and worry-free.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection