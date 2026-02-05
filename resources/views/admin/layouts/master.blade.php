<!doctype html>
<html lang="en">
  <head>
    <!-- Simple bar CSS -->
    @include('admin.layouts.partials._style')
    @stack('css')
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
      @include('admin.layouts.partials._navbar')
      </nav>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
      @include('admin.layouts.partials._sidebar')
      </aside>
      <main role="main" class="main-content">
        @yield('content')
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    @include('admin.layouts.partials._script')
    @stack('js')
  </body>
</html> 