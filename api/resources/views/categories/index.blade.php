@extends('layouts.app')

@section('content')

<div class="container mx-auto">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b dark:border-blue-500 pb-4" data-aos="fade-right">
        <h4 class="text-3xl font-semibold mb-4 md:mb-0 text-white">Categories</h4>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 transition w-full md:w-auto cursor-pointer" id="addCategoryBtn">Add Category</button>
    </div>
    
    <div class="relative overflow-x-auto rounded-lg shadow">
        <table class="w-full text-sm text-center rtl:text-right text-gray-300" data-aos="fade-left">
            <thead class="text-xs uppercase bg-gray-900 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody style="background: #0c1220">
                @foreach ($categories as $category)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $category->name }}</td>
                    <td>
                        <div class="inline-flex overflow-hidden shadow-sm py-4" role="group">
                            <button class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-s-lg cursor-pointer editCategoryBtn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Edit</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-e-lg text-white bg-red-500 hover:bg-red-600 transition cursor-pointer deleteCategoryBtn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Add/Edit Category Modal -->
<div class="modal fade hidden overflow-y-auto overflow-x-hidden fixed top-0 bg-black backdrop-blur-sm right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" id="categoryModal" tabindex="-1" aria-hidden="true" >
    <div class="text-white rounded-xl shadow-2xl w-full max-w-xl mx-auto animate-fade-in" style="background: #161f30;">
        <div class="modal-content">
            <div class="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
                <h5 class="text-2xl font-semibold" id="categoryModalTitle">Add Category</h5>
                <button type="button" class="text-blue-400 hover:text-red-600 text-2xl font-bold focus:outline-none cursor-pointer">&times;</button>
            </div>
            <div class="p-6">
                <form id="categoryForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="categoryFormMethod">
                    <input type="hidden" id="categoryId">
                    
                    <div class="mb-3">
                        <label for="categoryName" class="block font-medium text-gray-500 mb-1">Category Name</label>
                        <input type="text" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 " style="background: #1a2238;" id="categoryName" name="name" required>
                    </div>

                    <div class="flex justify-center gap-2 pt-4">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer transition" onclick="closeCategoryModal()">Cancel</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade fixed inset-0 z-50 hidden overflow-y-auto bg-black backdrop-blur-sm flex items-center justify-center" id="deleteCategoryModal" tabindex="-1">
    <div class="text-white rounded-lg shadow-lg w-full max-w-md mx-auto animate-fade-in" style="background: #161f30;">
        <div class="modal-content">
            <div class="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
                <h5 class="text-xl font-semibold">Confirm Deletion</h5>
                <button type="button" class="text-blue-400 hover:text-red-600 text-2xl font-bold focus:outline-none">&times;</button>
            </div>
            <div class="p-6"">
                <p class="text-white mb-6">Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
                <div class="flex justify-center gap-2 pt-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer transition" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteCategoryForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Yes, Delete</button>
                    </form>
                </div>
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
        $('#categoryModal').removeClass('hidden').addClass('flex');
    });

    $('.editCategoryBtn').click(function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');
        $('#categoryModalTitle').text('Edit Category');
        $('#categoryForm').attr('action', '/categories/' + categoryId);
        $('#categoryFormMethod').val('PUT');
        $('#categoryId').val(categoryId);
        $('#categoryName').val(categoryName);
        $('#categoryModal').removeClass('hidden').addClass('flex');
    });

    // Close Add/Edit Modal on close or cancel
    $('#categoryModal .text-blue-400, #categoryModal .bg-gray-200').click(function() {
        closeCategoryModal();
    });
    function closeCategoryModal() {
        $('#categoryModal').addClass('hidden').removeClass('flex');
    }
    // Close Add/Edit Modal on background click
    $('#categoryModal').on('click', function(e) {
        if (e.target === this) closeCategoryModal();
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
                closeCategoryModal();
                location.reload();
            }
        });
    });

    $('.deleteCategoryBtn').click(function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');
        $('#deleteCategoryName').text(categoryName);
        $('#deleteCategoryForm').attr('action', '/categories/' + categoryId);
        $('#deleteCategoryModal').removeClass('hidden').addClass('flex');
    });

    // Close Delete Modal on close or cancel
    $('#deleteCategoryModal .text-blue-400, #deleteCategoryModal .bg-gray-200').click(function() {
        closeDeleteCategoryModal();
    });
    function closeDeleteCategoryModal() {
        $('#deleteCategoryModal').addClass('hidden').removeClass('flex');
    }
    // Close Delete Modal on background click
    $('#deleteCategoryModal').on('click', function(e) {
        if (e.target === this) closeDeleteCategoryModal();
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
                closeDeleteCategoryModal();
                location.reload();
            }
        });
    });
});
</script>
@endsection
