@if (session()->get("message") && session()->get("class"))
    <div class="alert-response alert alert-{{ session()->get("class") }} alert-dismissible fade show" role="alert">
        <p class="m-0">{{ session()->get("message") }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
