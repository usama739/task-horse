@extends('layouts.app')

@section('content')

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 1px solid lightgray; padding-bottom: 20px;"  data-aos="fade-right">
        <h4>Categories</h4>
        <button class="btn btn-primary" id="addCategoryBtn">Add Category</button>
    </div>
    
    <table class="table mt-3" data-aos="fade-left">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    <button class="btn btn-warning btn-sm editCategoryBtn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Edit</button>
                    <button class="btn btn-danger btn-sm deleteCategoryBtn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="categoryFormMethod">
                    <input type="hidden" id="categoryId">
                    
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#addCategoryBtn').click(function() {
        $('#categoryModalTitle').text('Add Category');
        $('#categoryForm').attr('action', '/categories');
        $('#categoryFormMethod').val('POST');
        $('#categoryId').val('');
        $('#categoryName').val('');
        $('#categoryModal').modal('show');
    });

    $('.editCategoryBtn').click(function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');

        $('#categoryModalTitle').text('Edit Category');
        $('#categoryForm').attr('action', '/categories/' + categoryId);
        $('#categoryFormMethod').val('PUT');
        $('#categoryId').val(categoryId);
        $('#categoryName').val(categoryName);
        $('#categoryModal').modal('show');
    });

    $('#categoryForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = $('#categoryFormMethod').val();

        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function() {
                $('#categoryModal').modal('hide');
                location.reload();
            }
        });
    });

    $('.deleteCategoryBtn').click(function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');

        $('#deleteCategoryName').text(categoryName);
        $('#deleteCategoryForm').attr('action', '/categories/' + categoryId);
        $('#deleteCategoryModal').modal('show');
    });

    $('#deleteCategoryForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function() {
                $('#deleteCategoryModal').modal('hide');
                location.reload();
            }
        });
    });
});
</script>
@endsection
