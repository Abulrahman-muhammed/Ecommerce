@extends('admin.layouts.master')

@section('title', 'Categories')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark font-weight-bold">Category </h5>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus"></i> Add New Category
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm fade show d-flex align-items-center" 
         role="alert" 
         style="background-color: #e1f7ec; border-left: 4px solid #0fb972 !important;">
        <i class="fas fa-check-circle me-3 fa-lg" style="color: #0fb972;"></i>
        <div>
            <strong class="d-block text-dark">Success!</strong>
            <span class="text-secondary small">{{ session('success') }}</span>
        </div>
    </div>
@endif
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark font-weight-bold">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Image</th>
                                    <th class="border-0">Category Name</th>
                                    <th class="border-0">Slug</th>
                                    <th class="border-0">Parent Category</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0">Created At</th>
                                    <th class="border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                        <td>
                                            @if($category->images->first())
                                                <img src="{{ $category->image_url }}" 
                                                    alt="Category Image" 
                                                    class="img-fluid rounded" 
                                                    style="max-width: 100px; max-height: 100px;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td><span class="fw-bold text-dark">{{ $category->name }}</span></td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>{{ $category->parent->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill px-3 py-2  text-capitalize
                                                text-{{ $category->status->color() }} 
                                                bg-{{ $category->status->color() }}-subtle 
                                                border border-{{ $category->status->color() }} 
                                                fw-semibold shadow-sm">
                                                {{ $category->status->label() }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>{{ $category->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group shadow-sm" role="group">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                                   class="btn btn-outline-primary btn-sm" title="Edit">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger btn-sm delete-btn">
                                                        <i class="fe fe-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This category and its associations will be removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6e7881',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush