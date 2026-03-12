<!doctype html>
<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="{{ asset('admin-assets/assets/') }}"
  data-template="vertical-menu-template-free">

  @include('admin.layouts.partials._head')

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
        @include('admin.layouts.partials._sidebar')
        <!-- / Menu -->

        <!-- Layout page -->
        <div class="layout-page">

          <!-- Navbar -->
          @include('admin.layouts.partials._navbar')
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">

            <!-- Content -->
            @yield('content')
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    &copy; {{ date('Y') }}, made with ❤️ by
                    <a href="#" class="footer-link fw-medium">Hereza</a>
                  </div>
                  <div class="footer-link fw-medium">
                    <span class="me-1">v</span><span class="fw-medium">1.0.0</span>
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- / Content wrapper -->

        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    @include('admin.layouts.partials._scripts')

  </body>
</html>