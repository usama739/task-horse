@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 1px solid lightgray; padding-bottom: 20px;" data-aos="fade-down">
        <h4>Users</h2>
        <button class="btn btn-primary" onclick="openUserModal()">Add User</button>
    </div>
    

    <table class="table" data-aos="fade-up">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editUser({{ $user->id }})">Edit</button>
                    <button class="btn btn-danger btn-sm deleteUserBtn" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                        Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" id="userId">
                    <input type="hidden" name="_method" value="POST" id="formMethod">
                    <div class="mb-3">
                        <label>Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3" id="passwordField">
                        <label>Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="userName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST">
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
    function openUserModal() {
        $('#userModal').modal('show');
        $('#userForm').attr('action', "{{ route('users.store') }}");
        $('#passwordField').show();
        $('#userId').val('');
        $('#name, #email, #password').val('');
    }

    function editUser(id) {
        $.get('/users/' + id + '/edit', function(user) {
            $('#userModal').modal('show');
            $('#userForm').attr('action', "/users/" + id);
            $('#formMethod').val('PUT');
            $('#passwordField').hide();
            $('#userId').val(user.id);
            $('#name').val(user.name);
            $('#email').val(user.email);
        });
    }

    // $(document).ready(function() {
    //     $('#userForm').submit(function(e) {
    //         e.preventDefault();
    //         console.log("Form submission intercepted!"); // Debugging message

    //         var form = $(this);
    //         var url = form.attr('action');
    //         var method = $('#userId').val() ? 'PUT' : 'POST';
    //         console.log("Method: ", method); // Debugging message
            
    //         $.ajax({
    //             url: url,
    //             type: method,
    //             data: form.serialize(),
    //             success: function(response) {
    //                 console.log("Success:", response);
    //                 location.reload();
    //             },
    //             error: function(xhr, status, error) {
    //                 console.log("Error:", xhr.responseText);
    //             }
    //         });
    //     });
    // });



    $(document).ready(function() {
        $('.deleteUserBtn').click(function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            console.log(userId, " ", userName);
            $('#userName').text(userName);
            $('#deleteUserForm').attr('action', '/users/' + userId);
            $('#deleteUserModal').modal('show');
        });

        $('#deleteUserForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    console.log(response);
                    $('#deleteUserModal').modal('hide'); 
                    location.reload(); 
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Error deleting user.');
                }
            });
        });
    });


</script>
@endsection
