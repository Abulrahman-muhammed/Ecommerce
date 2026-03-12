  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="index.html" class="app-brand-link">
        <span class="app-brand-logo demo me-1">
          <span class="text-primary">
            <svg width="30" height="24" viewBox="0 0 250 196" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M12.3002 1.25469L56.655 28.6432C59.0349 30.1128 60.4839 32.711 60.4839 35.5089V160.63C60.4839 163.468 58.9941 166.097 56.5603 167.553L12.2055 194.107C8.3836 196.395 3.43136 195.15 1.14435 191.327C0.395485 190.075 0 188.643 0 187.184V8.12039C0 3.66447 3.61061 0.0522461 8.06452 0.0522461C9.56056 0.0522461 11.0271 0.468577 12.3002 1.25469Z"
                fill="currentColor" />
              <path
                opacity="0.077704"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 65.2656L60.4839 99.9629V133.979L0 65.2656Z"
                fill="black" />
              <path
                opacity="0.077704"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 65.2656L60.4839 99.0795V119.859L0 65.2656Z"
                fill="black" />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M237.71 1.22393L193.355 28.5207C190.97 29.9889 189.516 32.5905 189.516 35.3927V160.631C189.516 163.469 191.006 166.098 193.44 167.555L237.794 194.108C241.616 196.396 246.569 195.151 248.856 191.328C249.605 190.076 250 188.644 250 187.185V8.09597C250 3.64006 246.389 0.027832 241.935 0.027832C240.444 0.027832 238.981 0.441882 237.71 1.22393Z"
                fill="currentColor" />
              <path
                opacity="0.077704"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M250 65.2656L189.516 99.8897V135.006L250 65.2656Z"
                fill="black" />
              <path
                opacity="0.077704"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M250 65.2656L189.516 99.0497V120.886L250 65.2656Z"
                fill="black" />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z"
                fill="currentColor" />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z"
                fill="white"
                fill-opacity="0.15" />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z"
                fill="currentColor" />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z"
                fill="white"
                fill-opacity="0.3" />
            </svg>
          </span>
        </span>
        <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ config('app.name') }}</span>
      </a>


    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <!-- Dashboards -->
      <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="menu-link">
          <i class="menu-icon icon-base ri ri-home-smile-line"></i>
          <div data-i18n="Dashboards">Dashboards</div>
        </a>
      </li>

      <!-- Apps & Pages -->
      <li class="menu-header mt-7">
        <span class="menu-header-text">Apps &amp; Pages</span>
      </li>

    <!-- Products -->
    <li class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
      <a href="{{ route('admin.products.index') }}" class="menu-link">
        <i class="menu-icon ri ri-shopping-bag-3-line"></i>
        <div>Products</div>
      </a>
    </li>

    <!-- Categories -->
    <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
      <a href="{{ route('admin.categories.index') }}" class="menu-link">
        <i class="menu-icon ri ri-list-check-2"></i>
        <div>Categories</div>
      </a>
    </li>

    <!-- Tags -->
    <li class="menu-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
      <a href="{{ route('admin.tags.index') }}" class="menu-link">
        <i class="menu-icon ri ri-price-tag-3-line"></i>
        <div>Tags</div>
      </a>
    </li>

    <!-- Orders -->
    <li class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
      <a href="#" class="menu-link">
        <i class="menu-icon ri ri-shopping-cart-2-line"></i>
        <div>Orders</div>
      </a>
    </li>

    <!-- Customers -->
    <li class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
      <a href="#" class="menu-link">
        <i class="menu-icon ri ri-user-3-line"></i>
        <div>Customers</div>
      </a>
    </li>

    <!-- Payments -->
    <li class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
      <a href="#" class="menu-link">
        <i class="menu-icon ri ri-bank-card-line"></i>
        <div>Payments</div>
      </a>
    </li>

    <!-- Reports -->
    <li class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
      <a href="#" class="menu-link">
        <i class="menu-icon ri ri-bar-chart-line"></i>
        <div>Reports</div>
      </a>
    </li>
      <li class="menu-header mt-7">
        <span class="menu-header-text">Settings</span>
      </li>


      <li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <a
          href="#"
          class="menu-link">
          <i class="menu-icon ri ri-settings-3-line"></i>
          <div data-i18n="Calendar">Settings</div>
        </a>
      </li>
    </ul>
  </aside>