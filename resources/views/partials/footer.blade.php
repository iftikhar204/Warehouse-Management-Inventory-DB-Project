<!-- Bootstrap Icons CSS (Necessary for the icons used in the footer) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Bootstrap 5 CSS (Ensure this is loaded in your main layout for the classes to work) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  /* Custom styles for Bootstrap footer to match Navbar aesthetic */
  .footer-link {
    color: var(--bs-gray-400); /* A slightly lighter grey for links */
    transition: color 0.3s ease;
  }
  .footer-link:hover {
    color: var(--bs-white); /* White on hover */
    text-decoration: underline;
  }
  .footer-heading {
    color: var(--bs-yellow); /* Matching the yellow of the navbar brand/active links */
  }
  .footer-icon {
    color: var(--bs-primary); /* Matching the primary color of the navbar icons */
  }
  /* Ensuring consistent rounded corners as per previous requests */
  footer * {
      border-radius: 0.375rem; /* Equivalent to rounded-md */
  }
</style>

<!-- Main Footer Section -->
<footer class="bg-dark text-light pt-5 pb-3 border-top shadow-sm rounded-0">
  <div class="container-fluid px-4">
    <div class="row justify-content-center justify-content-md-start mb-4">

      <!-- Logo & About Section -->
      <div class="col-12 col-md-4 col-lg-3 col-xl-2 mb-4 mb-md-0">
        <h5 class="mb-3 footer-heading">
          <i class="bi bi-box-seam me-2 footer-icon"></i> Warehouse Management System
        </h5>
        <p class="text-secondary small">
          Efficiently manage your inventory, optimize warehouse operations, and streamline logistics for enhanced productivity.
        </p>
      </div>

      <!-- Quick Links Section -->
      <div class="col-6 col-md-2 col-lg-2 col-xl-1 mb-4 mb-md-0">
        <h5 class="mb-3 footer-heading">Quick Links</h5>
        <ul class="list-unstyled small">
          <li><a href="{{ route('home') }}" class="footer-link">Dashboard</a></li>
          <li><a href="{{ route('inventory.index') }}" class="footer-link">Inventory</a></li>
          <li><a href="{{ route('orders.index') }}" class="footer-link">Orders</a></li>
          <li><a href="{{ route('settings.index') }}" class="footer-link">Settings</a></li>
        </ul>
      </div>

      <!-- Contact Info Section -->
      <div class="col-6 col-md-3 col-lg-2 col-xl-2 mb-4 mb-md-0">
        <h5 class="mb-3 footer-heading">Support</h5>
        <ul class="list-unstyled small">
          <li class="d-flex align-items-center justify-content-center justify-content-md-start mb-2">
            <i class="bi bi-geo-alt-fill me-2 footer-icon"></i> Okara, Pakistan
          </li>
          <li class="d-flex align-items-center justify-content-center justify-content-md-start mb-2">
            <i class="bi bi-envelope-fill me-2 footer-icon"></i> support@wms.com
          </li>
          <li class="d-flex align-items-center justify-content-center justify-content-md-start">
            <i class="bi bi-telephone-fill me-2 footer-icon"></i> +92 3XX XXXXXXX
          </li>
        </ul>
      </div>

      <!-- Help & Resources Section -->
      <div class="col-12 col-md-3 col-lg-3 col-xl-2 mb-4 mb-md-0">
        <h5 class="mb-3 footer-heading">Help & Resources</h5>
        <ul class="list-unstyled small">
          <li><a href="#" class="footer-link">User Manual</a></li>
          <li><a href="#" class="footer-link">FAQs</a></li>
          <li><a href="#" class="footer-link">Privacy Policy</a></li>
          <li><a href="#" class="footer-link">Terms of Service</a></li>
        </ul>
      </div>
    </div>

    <!-- Footer Bottom - Copyright -->
    <hr class="border-secondary my-4" />
    <div class="row">
      <div class="col-12 text-center small text-secondary">
        <p class="mb-0">
          &copy; <span id="currentYearFooter"></span> Warehouse Management System. All Rights Reserved.
        </p>
      </div>
    </div>
  </div>
</footer>

<!-- JavaScript to dynamically set the current year in the copyright notice -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const yearSpan = document.getElementById('currentYearFooter');
    if (yearSpan) {
      yearSpan.textContent = new Date().getFullYear();
    }
  });
</script>
