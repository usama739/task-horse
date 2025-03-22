@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-5">
        <!-- Pending Tasks -->
        <div class="col-md-4 mb-3" data-aos="fade-right">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-hourglass-half text-warning fa-2x"></i> Pending Tasks
                    </h5>
                    <h3>{{ $pendingCount }}</h3>
                </div>
            </div>
        </div>

        <!-- In-Progress Tasks -->
        <div class="col-md-4 mb-3" data-aos="fade-up">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-spinner text-primary fa-2x"></i> In-Progress Tasks
                    </h5>
                    <h3>{{ $inProgressCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="col-md-4 mb-3" data-aos="fade-left">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">
                        <i class="fas fa-check-circle text-success fa-2x"></i> Completed Tasks
                    </h5>
                    <h3>{{ $completedCount }}</h3>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid tasksTable mt-3" data-aos="fade-down">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4" style="border-bottom: 1px solid lightgray; padding-bottom: 20px;">
            <h4 class="mb-3">Tasks</h4>
            <div class="row g-2 w-md-auto">
                <div class="col-12 col-sm-6 col-md-3">
                    <input type="text" id="search" class="form-control" placeholder="Search tasks...">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <select id="filterPriority" class="form-control">
                        <option value="">Filter by Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <select id="filterStatus" class="form-control">
                        <option value="">Filter by Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In-Progress">In-Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3 text-end">
                    @if(auth()->user()->role != 'user')
                        <button class="btn btn-primary w-100 w-md-auto" onclick="openTaskModal()">Add Task</button>
                    @endif
                </div>
            
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="d-none d-md-table-cell">Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th>Due Date</th>
                        @if(auth()->user()->role != 'user')
                            <th>Assigned To</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="taskTable">
                    @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td class="d-none d-md-table-cell">{{ $task->description }}</td>
                        <td>
                            <span class="task-priority badge 
                                @if($task->priority == 'High') bg-danger 
                                @elseif($task->priority == 'Medium') bg-warning 
                                @else bg-success 
                                @endif">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td>
                            <span class="task-status badge 
                                @if($task->status == 'Completed') bg-success 
                                @elseif($task->status == 'In Progress') bg-primary 
                                @else bg-secondary 
                                @endif">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $task->category->name ?? 'No Category' }}</td>
                        <td>{{ $task->due_date ? date('d M Y', strtotime($task->due_date)) : 'No Due Date' }}</td>
                        @if(auth()->user()->role != 'user')
                            <td>{{ $task->user->name }}</td>
                        @endif
                        <td>
                            <div class="buttons-group d-flex" style="gap:5px;">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">View</a>
                                @if(auth()->user()->role != 'user')
                                    <button class="btn btn-warning btn-sm" onclick="openTaskModal({{ $task }})">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteCategoryBtn" data-id="{{ $task->id }}">Delete</button>
                                @endif 
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    


    <div class="row" style="margin-top: 90px;" data-aos="fade-up">
        <div class="col-md-6 mb-4" data-aos="fade-right">
            <div id="taskCalendar"></div>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <h2 class="text-center mb-4">Task Timeline</h2>
            <div class="main-timeline">
                @foreach($tasks as $index => $task)
                <div class="timeline {{ $index % 2 == 0 ? 'left' : 'right' }}">
                    <div class="card">
                        <div class="card-body p-4">
                            <h3>{{ date('M d, Y', strtotime($task->due_date)) }}</h3>
                            <div>{{ $task->title }}</div>
                            <samll>{{ $task->user->name }}</samll>
                            <p><b>{{ $task->priority }}</b></p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Task Form</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="formMethod">
                    <input type="hidden" name="taskId" id="taskId">

                    <div class="form-group mb-3">
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Priority:</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="">Select Priority</option>
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="Pending">Pending</option>
                            <option value="In-Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Due Date</label>
                        <input type="date" id="due_date" name="due_date" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label>Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>Assigned To</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Save Task</button>
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
                <p>Are you sure you want to delete task ?</p>
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


<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function openTaskModal(task = null) {
        console.log(task);
        $('#taskForm')[0].reset();
     
        if (task) {
            $('#taskId').val(task.id);
            $('#title').val(task.title);
            $('#description').val(task.description);
            $('#status').val(task.status);
            $('#due_date').val(task.due_date);
            $('#user_id').val(task.user_id).trigger('change');
            $('#category_id').val(task.category_id).trigger('change');
            $('#taskForm').attr('action', '/tasks/' + task.id);
            $('#formMethod').val('PUT');
            $('#taskModalLabel').html('Edit Task');
        } else {
            $('#taskId').val('');
            $('#taskForm').attr('action', '/tasks');
            $('#formMethod').val('POST');
            $('#taskModalLabel').html('Add Task');
        }

        $('#taskModal').modal('show');
    }


    $(document).ready(function() {
        $('.deleteCategoryBtn').click(function() {
            var taskId = $(this).data('id');
            $('#deleteCategoryForm').attr('action', '/tasks/' + taskId);
            $('#deleteCategoryModal').modal('show');
        });


        function filterTasks() {
            var priority = $("#filterPriority").val().trim();
            var status = $("#filterStatus").val().trim();

            $("#taskTable tr").filter(function () {
                var rowPriority = $(this).find(".task-priority").text().trim();
                var rowStatus = $(this).find(".task-status").text().trim();

                var matchesPriority = (priority === "" || rowPriority === priority);
                var matchesStatus = (status === "" || rowStatus === status);

                if (matchesPriority && matchesStatus) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $("#search").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#taskTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#filterPriority, #filterStatus').on('input change', function () {
            filterTasks();
        });


        var calendarEl = document.getElementById('taskCalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: "{{ route('tasks.events') }}", // Use route helper
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        successCallback(response);
                    },
                    error: function(xhr) {
                        console.error("Failed to fetch events:", xhr);
                        failureCallback(xhr);
                    }
                });
            },
            eventClick: function (info) {
                // alert('Task: ' + info.event.title + '\nDue Date: ' + info.event.start.toISOString().split('T')[0]);
            }
        });

        calendar.render();
    });

</script>
@endsection
