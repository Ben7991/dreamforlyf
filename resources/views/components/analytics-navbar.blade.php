<ul class="nav nav-pills mb-3 mb-xxl-4">
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'registration' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics">Registrations</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'upgrade-bonus' ? 'active' : null; @endphp"  href="/{{ App::getLocale() }}/admin/analytics/upgrade-bonus">Upgrade Bonus</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'personal-purchase' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics/personal-purchase">Personal Purchase</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'maintenance' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics/maint">Maintenance</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @php echo $activePage === 'general-assessment' ? 'active' : null; @endphp" href="/{{ App::getLocale() }}/admin/analytics/general-assessment">General Assessment</a>
    </li>
</ul>
