@extends('front-end.layouts.app')
@section('title', 'Delivery Policy')
@section('content')

<div class="py-5 bg-gradient-to-r from-pink-50 to-purple-50 border-b">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="display-5 fw-bold text-dark mb-2">
					<i class="ri-truck-line me-2"></i>Delivery Policy
				</h1>
				<p class="text-muted">Fast & Reliable Beauty Product Delivery Across Bangladesh</p>
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
							<a href="#delivery-areas" class="nav-link text-secondary py-2"><i class="ri-map-pin-line me-1"></i> Delivery Areas</a>
							<a href="#delivery-time" class="nav-link text-secondary py-2"><i class="ri-time-line me-1"></i> Delivery Time</a>
							<a href="#charges" class="nav-link text-secondary py-2"><i class="ri-money-dollar-circle-line me-1"></i> Delivery Charges</a>
							<a href="#process" class="nav-link text-secondary py-2"><i class="ri-shopping-bag-line me-1"></i> Order Process</a>
							<a href="#packaging" class="nav-link text-secondary py-2"><i class="ri-gift-line me-1"></i> Product Packaging</a>
							<a href="#tracking" class="nav-link text-secondary py-2"><i class="ri-radar-line me-1"></i> Order Tracking</a>
							<a href="#conditions" class="nav-link text-secondary py-2"><i class="ri-file-list-line me-1"></i> Terms & Conditions</a>
							<a href="#contact" class="nav-link text-secondary py-2"><i class="ri-customer-service-line me-1"></i> Contact Us</a>
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
								<strong>Welcome to Sajid Beauty BD!</strong> We are committed to delivering authentic beauty and cosmetic products across Bangladesh with care and efficiency. This policy explains our delivery process, timelines, and conditions.
							</p>
						</div>

						<!-- Delivery Areas -->
						<div id="delivery-areas" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-map-pin-2-fill text-danger me-2"></i>Delivery Areas
							</h3>
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card border-primary h-100">
										<div class="card-body">
											<h5 class="card-title text-primary">
												<i class="ri-building-line me-2"></i>Inside Dhaka
											</h5>
											<p class="mb-2">We deliver to all areas within Dhaka city:</p>
											<ul class="mb-0">
												<li>Dhaka North & South</li>
												<li>Mirpur, Mohammadpur, Dhanmondi</li>
												<li>Uttara, Gulshan, Banani</li>
												<li>Old Dhaka, Motijheel, Paltan</li>
												<li>All other Dhaka localities</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-success h-100">
										<div class="card-body">
											<h5 class="card-title text-success">
												<i class="ri-map-2-line me-2"></i>Outside Dhaka
											</h5>
											<p class="mb-2">Nationwide delivery available:</p>
											<ul class="mb-0">
												<li>Chittagong, Sylhet, Rajshahi</li>
												<li>Khulna, Barisal, Rangpur</li>
												<li>Mymensingh and all divisional cities</li>
												<li>District and sub-district areas</li>
												<li>Remote areas via courier</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Delivery Time -->
						<div id="delivery-time" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-timer-flash-fill text-warning me-2"></i>Delivery Timeline
							</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead class="table-dark bg-dark" >
										<tr style="color:#000;">
											<th><i class="ri-map-pin-line me-2"></i>Location</th>
											<th><i class="ri-time-line me-2"></i>Delivery Time</th>
											<th><i class="ri-shopping-cart-line me-2"></i>Order Processing</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><strong>Inside Dhaka</strong></td>
											<td><span class="badge bg-success">1-2 Business Days</span></td>
											<td>Orders processed within 24 hours</td>
										</tr>
										<tr>
											<td><strong>Dhaka Suburbs</strong></td>
											<td><span class="badge bg-info">2-3 Business Days</span></td>
											<td>Same day processing</td>
										</tr>
										<tr>
											<td><strong>Major Cities</strong></td>
											<td><span class="badge bg-primary">3-5 Business Days</span></td>
											<td>24-48 hours processing</td>
										</tr>
										<tr>
											<td><strong>District Areas</strong></td>
											<td><span class="badge bg-warning text-dark">4-7 Business Days</span></td>
											<td>Within 48 hours</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="alert alert-info w-100">
								<i class="ri-information-line me-2"></i><strong>Note:</strong> <span style="margin-top: 23px">Delivery times may vary during public holidays, festivals (Eid, Puja), or unforeseen circumstances like weather conditions.</span>
							</div>
						</div>

						<!-- Delivery Charges -->
						<div id="charges" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-price-tag-3-fill text-success me-2"></i>Delivery Charges
							</h3>
							<div class="row g-3">
								<div class="col-md-4">
									<div class="card text-center border-success">
										<div class="card-body">
											<i class="ri-building-4-line display-4 text-success"></i>
											<h5 class="mt-3">Inside Dhaka</h5>
											<h3 class="text-success">৳ 60</h3>
											<p class="text-muted small mb-0">Standard Delivery</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card text-center border-primary">
										<div class="card-body">
											<i class="ri-map-pin-range-line display-4 text-primary"></i>
											<h5 class="mt-3">Outside Dhaka</h5>
											<h3 class="text-primary">৳ 120</h3>
											<p class="text-muted small mb-0">Courier Service</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card text-center border-danger bg-danger text-white">
										<div class="card-body pb-3">
											<i class="ri-gift-2-line display-4"></i>
											<h5 class="mt-3 text-white">Free Delivery</h5>
											<h3 class="text-white">৳ 5000+</h3>
											<p class="small mb-0 text-white">Orders Above ৳5000</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Order Process -->
						<div id="process" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-shopping-cart-2-fill text-info me-2"></i>Order Processing Steps
							</h3>
							<div class="timeline">
								<div class="d-flex mb-3">
									<div class="flex-shrink-0">
										<span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">1</span>
									</div>
									<div class="flex-grow-1 ms-3">
										<h6 class="fw-bold">Order Confirmation</h6>
										<p class="text-muted mb-0">You'll receive an email/SMS confirming your order details within minutes.</p>
									</div>
								</div>
								<div class="d-flex mb-3">
									<div class="flex-shrink-0">
										<span class="badge bg-info rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">2</span>
									</div>
									<div class="flex-grow-1 ms-3">
										<h6 class="fw-bold">Order Processing</h6>
										<p class="text-muted mb-0">Our team carefully picks and packs your beauty products with care.</p>
									</div>
								</div>
								<div class="d-flex mb-3">
									<div class="flex-shrink-0">
										<span class="badge bg-warning rounded-circle text-dark" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">3</span>
									</div>
									<div class="flex-grow-1 ms-3">
										<h6 class="fw-bold">Dispatch</h6>
										<p class="text-muted mb-0">Product is dispatched with our delivery partner or courier service.</p>
									</div>
								</div>
								<div class="d-flex">
									<div class="flex-shrink-0">
										<span class="badge bg-success rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">4</span>
									</div>
									<div class="flex-grow-1 ms-3">
										<h6 class="fw-bold">Delivery Complete</h6>
										<p class="text-muted mb-0">Your beauty products are delivered to your doorstep!</p>
									</div>
								</div>
							</div>
						</div>

						<!-- Packaging -->
						<div id="packaging" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-gift-fill text-danger me-2"></i>Product Packaging & Care
							</h3>
							<div class="row g-3">
								<div class="col-md-6">
									<div class="p-3 bg-light rounded">
										<h6><i class="ri-checkbox-circle-line text-success me-2"></i>Secure Packaging</h6>
										<p class="small text-muted mb-0">All cosmetic products are packed securely with bubble wrap and quality packaging materials to prevent damage.</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="p-3 bg-light rounded">
										<h6><i class="ri-checkbox-circle-line text-success me-2"></i>Product Safety</h6>
										<p class="small text-muted mb-0">Fragile items like perfumes, glass bottles, and liquid cosmetics receive extra protective packaging.</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="p-3 bg-light rounded">
										<h6><i class="ri-checkbox-circle-line text-success me-2"></i>Authentic Products</h6>
										<p class="small text-muted mb-0">We ensure all products are 100% original and sourced directly from authorized distributors.</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="p-3 bg-light rounded">
										<h6><i class="ri-checkbox-circle-line text-success me-2"></i>Hygiene Maintained</h6>
										<p class="small text-muted mb-0">All products are handled with clean hands and packed in sanitized conditions.</p>
									</div>
								</div>
							</div>
						</div>

						<!-- Tracking -->
						<div id="tracking" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-radar-fill text-primary me-2"></i>Order Tracking
							</h3>
							<div class="alert alert-primary w-100 d-flex flex-column justify-content-center">
								<h4 class="alert-heading"><i class="ri-smartphone-line me-2"></i>Track Your Order</h4>
								<p class="mb-2">Stay updated with real-time order tracking:</p>
								<ul class="mb-0">
									<li>Check order status anytime in your account dashboard</li>
									<li>Receive SMS/Email notifications at each stage</li>
									<li>For courier orders, tracking ID will be provided</li>
									<li>Contact our customer support for instant updates</li>
								</ul>
							</div>
						</div>

						<!-- Conditions -->
						<div id="conditions" class="mb-5">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-file-list-3-fill text-warning me-2"></i>Delivery Terms & Conditions
							</h3>
							<div class="card border-warning">
								<div class="card-body">
									<ul class="mb-0">
										<li class="mb-2"><strong>Accurate Address:</strong> Please provide complete and accurate delivery address. We are not responsible for wrong address deliveries.</li>
										<li class="mb-2"><strong>Contact Number:</strong> Ensure your phone number is reachable. Our delivery team may call for directions or confirmation.</li>
										<li class="mb-2"><strong>Inspection:</strong> Please inspect products upon delivery. Report any damage immediately before accepting.</li>
										<li class="mb-2"><strong>Receiving Orders:</strong> Someone must be present to receive the order. We cannot leave packages unattended.</li>
										<li class="mb-2"><strong>Delivery Attempts:</strong> If delivery fails, we'll attempt 2 more times. After that, the order may be returned.</li>
										<li class="mb-2"><strong>Refused Orders:</strong> Delivery charges will be deducted from refunds for refused orders without valid reason.</li>
										<li class="mb-0"><strong>Force Majeure:</strong> We're not liable for delays due to natural disasters, political unrest, or other unavoidable circumstances.</li>
									</ul>
								</div>
							</div>
						</div>

						<!-- Contact -->
						<div id="contact" class="mb-4">
							<h3 class="h4 fw-bold text-dark mb-3">
								<i class="ri-customer-service-2-fill text-success me-2"></i>Need Help?
							</h3>
							<div class="card bg-gradient-to-r from-pink-50 to-purple-50 border-0">
								<div class="card-body">
									<p class="mb-3">Have questions about your delivery? We're here to help!</p>
									<div class="row g-3">
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<i class="ri-phone-fill text-success fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Call Us</small>
													<strong>+88 01648-022175</strong>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<i class="ri-mail-fill text-primary fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Email Us</small>
													<strong>sajidbeautybd@gmail.com</strong>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<i class="ri-map-pin-fill text-danger fs-3 me-3"></i>
												<div>
													<small class="text-muted d-block">Visit Us</small>
													<strong>Shimanto Shambar Mall</strong>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="text-center text-muted small">
							<p class="mb-0"><i class="ri-information-line me-1"></i>Last Updated: {{ now()->format('F j, Y') }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection