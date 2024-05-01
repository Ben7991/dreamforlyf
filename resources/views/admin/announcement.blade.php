<x-layout.admin>
    <x-slot name="title">Announcement</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("announcement") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("announcement") }}</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-md-6 col-xl-5 mb-3">
                <div class="bg-white p-3 rounded border shadow-sm">
                    <h5 class="mb-4">Current Announcement</h5>
                    @if ($announcement !== null)
                        <div class="border rounded p-3">
                            <p class="mb-1">{{ $announcement->description }}</p>
                            <p class="mb-4">
                                <span>{{ $start_date }}</span>  =>  <span>{{ $end_date }}</span>
                            </p>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                onclick="setFormAction('/{{ App::currentLocale() }}/admin/announce/{{ $announcement->id }}')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    @else
                        <p class="m-0">No created announcement at the moment</p>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="bg-white p-3 rounded border shadow-sm">
                    <h5 class="mb-4">Create Announcement</h5>
                    <form action="/{{ App::currentLocale() }}/admin/announce" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="description_fr">French Description</label>
                            <textarea name="description_fr" id="description_fr" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description_en">English Description</label>
                            <textarea name="description_en" id="description_en" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <button class="btn btn-success">
                            <i class="bi bi-send"></i> Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Remove Announcement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">
                    @csrf
                    @method("DELETE")

                    <div class="modal-body">
                        <p class="m-0">Are you sure you want to remove this announcement?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i>
                            {{ __("remove") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.admin>
