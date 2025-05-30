@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b dark:border-blue-500 pb-4" data-aos="fade-down">
        <h4 class="text-3xl font-semibold mb-4 md:mb-0 text-white">Users</h4>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 transition w-full md:w-auto cursor-pointer" onclick="openUserModal()">Add User</button>
    </div>
    
    <div class="relative overflow-x-auto rounded-lg shadow">
        <table class="w-full text-sm text-center rtl:text-right text-gray-300" data-aos="fade-up">
            <thead class="text-xs uppercase bg-gray-900 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody style="background: #0c1220">
                @foreach($users as $user)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="inline-flex overflow-hidden shadow-sm py-4" role="group">
                            <button class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-s-lg cursor-pointer" onclick="editUser({{ $user->id }})">Edit</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-e-lg text-white bg-red-500 hover:bg-red-600 transition cursor-pointer deleteUserBtn" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div id="userModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black backdrop-blur-sm flex items-center justify-center">
    <div class="text-white rounded-xl shadow-2xl w-full max-w-xl mx-auto animate-fade-in" style="margin-top: 30px; background: #161f30;">
        <div class="modal-content">
            <div class="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
                <h5 class="text-2xl font-semibold">User Form</h5>
                <button type="button" class="text-blue-400 hover:text-red-600 text-2xl font-bold focus:outline-none cursor-pointer" onclick="closeUserModal()">&times;</button>
            </div>
            <div class="p-6">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" id="userId">
                    <input type="hidden" name="_method" value="POST" id="formMethod">
                    <div class="mb-3">
                        <label class="block font-medium text-gray-500 mb-1">Name:</label>
                        <input type="text" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 " style="background: #1a2238;" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium text-gray-500 mb-1">Email:</label>
                        <input type="email" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 " style="background: #1a2238;" id="email" name="email" required>
                    </div>
                    <div class="mb-3" id="passwordField">
                        <label class="block font-medium text-gray-500 mb-1">Password:</label>
                        <input type="password" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 " style="background: #1a2238;" id="password" name="password">
                    </div>

                    <div class="flex justify-center gap-2 pt-4">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer transition" onclick="closeUserModal()">Cancel</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteUserModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black backdrop-blur-sm flex items-center justify-center">
    <div class="text-white rounded-lg shadow-lg w-full max-w-md mx-auto animate-fade-in" style="background: #161f30;">
        <div class="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
            <h5 class="text-xl font-semibold">Confirm Deletion</h5>
            <button type="button" class="text-blue-400 hover:text-red-600 text-2xl font-bold focus:outline-none cursor-pointer" onclick="closeDeleteUserModal()">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-white mb-6">Are you sure you want to delete <strong id="userName"></strong>?</p>
            <div class="flex justify-center gap-2">
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer transition" onclick="closeDeleteUserModal()">Cancel</button>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg px-4 py-2 transition">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function openUserModal() {
        $('#userModal').removeClass('hidden').addClass('flex');
        $('#userForm').attr('action', "{{ route('users.store') }}");
        $('#passwordField').show();
        $('#userId').val('');
        $('#name, #email, #password').val('');
    }

    function closeUserModal() {
        $('#userModal').addClass('hidden').removeClass('flex');
    }

    function editUser(id) {
        $.get('/users/' + id + '/edit', function(user) {
            $('#userModal').removeClass('hidden').addClass('flex');
            $('#userForm').attr('action', "/users/" + id);
            $('#formMethod').val('PUT');
            $('#passwordField').hide();
            $('#userId').val(user.id);
            $('#name').val(user.name);
            $('#email').val(user.email);
        });
    }

    function openDeleteUserModal(userId, userName) {
        $('#userName').text(userName);
        $('#deleteUserForm').attr('action', '/users/' + userId);
        $('#deleteUserModal').removeClass('hidden').addClass('flex');
    }

    function closeDeleteUserModal() {
        $('#deleteUserModal').addClass('hidden').removeClass('flex');
    }

    $(document).ready(function() {
        $('.deleteUserBtn').click(function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            openDeleteUserModal(userId, userName);
        });

        // Close modals on background click
        $('#userModal').on('click', function(e) {
            if (e.target === this) closeUserModal();
        });
        $('#deleteUserModal').on('click', function(e) {
            if (e.target === this) closeDeleteUserModal();
        });
    });
</script>
@endsection
