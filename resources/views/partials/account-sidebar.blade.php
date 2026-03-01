<div class="col-md-3 col-lg-2 sidebar p-3">
  <h5 class="fw-bold mb-4">My Account</h5>
  <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-person"></i> Profile</a>
  <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}"><i class="bi bi-bag"></i> Orders</a>
  <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.index') ? 'active' : '' }}"><i class="bi bi-heart"></i> Wishlist</a>
  <a href="{{ route('account.address') }}" class="{{ request()->routeIs('account.address') ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> Address</a>
  <a href="{{ route('account.settings') }}" class="{{ request()->routeIs('account.settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Settings</a>
  <a href="{{ route('logout') }}" class="text-danger mt-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
