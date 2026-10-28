<?php
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="py-5 text-center text-white position-relative" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
  <div class="container py-4">
    <h1 class="display-4 fw-bold mb-3">Manage Your IT Assets Effortlessly</h1>
    <p class="lead mb-4 mx-auto" style="max-width: 600px;">
      A streamlined platform to register, track, and manage all organization hardware and devices in real time.
    </p>
  </div>
</section>

<!-- Services / Features Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Our Key Features</h2>
      <p class="text-muted">Everything you need to control your hardware inventory</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-3 text-center service-card">
          <div class="card-body">
            <div class="text-primary fs-1 mb-3">
              <i class="bi bi-cpu-fill" aria-hidden="true"></i>
            </div>
            <h5 class="card-title fw-semibold">Device Tracking</h5>
            <p class="card-text text-muted">Keep a complete log of all laptops, desktops, and peripherals assigned across teams.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-3 text-center service-card">
          <div class="card-body">
            <div class="text-primary fs-1 mb-3">
              <i class="bi bi-shield-lock-fill" aria-hidden="true"></i>
            </div>
            <h5 class="card-title fw-semibold">Role-Based Access</h5>
            <p class="card-text text-muted">Granular permissions ensuring security for standard users and super admins alike.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-3 text-center service-card">
          <div class="card-body">
            <div class="text-primary fs-1 mb-3">
              <i class="bi bi-bar-chart-line-fill" aria-hidden="true"></i>
            </div>
            <h5 class="card-title fw-semibold">Instant Reports</h5>
            <p class="card-text text-muted">Filter and locate active or unassigned devices with speed and accuracy.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials Carousel -->
<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold">What Users Say</h2>
    </div>
    <div id="testimonialCarousel" class="carousel slide mx-auto" data-bs-ride="carousel" style="max-width: 700px;">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner text-center p-4">
        <div class="carousel-item active">
          <p class="fs-5 italic text-secondary">"The IT Device Tracker simplified our entire auditing process. Super clean and fast!"</p>
          <h6 class="fw-bold mt-3 mb-0">Sarah Jenkins</h6>
          <small class="text-muted">IT Operations Lead</small>
        </div>
        <div class="carousel-item">
          <p class="fs-5 italic text-secondary">"Role permissions ensure that team leads only manage devices assigned specifically to them."</p>
          <h6 class="fw-bold mt-3 mb-0">David Miller</h6>
          <small class="text-muted">Systems Administrator</small>
        </div>
        <div class="carousel-item">
          <p class="fs-5 italic text-secondary">"Adding devices and maintaining records has never been this simple."</p>
          <h6 class="fw-bold mt-3 mb-0">Anita Rao</h6>
          <small class="text-muted">Network Engineer</small>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</section>

<?php
require_once 'includes/footer.php';
?>