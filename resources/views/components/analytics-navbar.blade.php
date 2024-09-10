<ul class="nav nav-pills mb-3 mb-xxl-4">
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'orders' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics">Orders</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'registration' ? 'active' : null; @endphp"  href="/{{ App::getLocale() }}/admin/analytics/registration">Registration</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'bonus' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics/bonus">Bonus</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'withdrawal' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics/withdrawal">Withdrawal</a>
    </li>
</ul>
