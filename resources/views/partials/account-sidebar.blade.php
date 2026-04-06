<div class="col-md-3 col-lg-2 sidebar p-3">
  <h5 class="fw-bold mb-4">My Account</h5>
  <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-person"></i> Profile</a>
  <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}"><i class="bi bi-bag"></i> Orders</a>
  <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.index') ? 'active' : '' }}"><i class="bi bi-heart"></i> Wishlist</a>
  <a href="{{ route('account.address') }}" class="{{ request()->routeIs('account.address') ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> Address</a>
  <a href="{{ route('account.settings') }}" class="{{ request()->routeIs('account.settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Settings</a>
  @if(session()->has('auth.api_token'))
    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
      @csrf
    </form>
    <a href="javascript:void(0)" id="sidebar-logout-trigger" class="text-danger mt-3">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var logoutTrigger = document.getElementById('sidebar-logout-trigger');
        if (logoutTrigger) {
          logoutTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            var form = document.getElementById('sidebar-logout-form');
            if (!form) return;
            var csrfToken = form.querySelector('input[name="_token"]').value;
            fetch(form.action, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
              },
              credentials: 'include',
            }).then(function() {
              window.location.href = '/';
            });
          });
        }
      });
    </script>
  @else
    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#authModal">
      <i class="bi bi-box-arrow-right"></i> Login
    </a>
  @endif
</div>
