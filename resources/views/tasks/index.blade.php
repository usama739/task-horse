@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Tasks -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 shadow rounded-lg p-6 flex flex-col items-center" data-aos="fade-right">
            <h5 class="text-yellow-600 font-semibold text-lg flex items-center gap-2 mb-2">
                <i class="fas fa-hourglass-half text-yellow-500 fa-2x"></i> Pending Tasks
            </h5>
            <h3 class="text-3xl font-bold">{{ $pendingCount }}</h3>
        </div>
        <!-- In-Progress Tasks -->
        <div class="bg-blue-50 border-l-4 border-blue-400 shadow rounded-lg p-6 flex flex-col items-center" data-aos="fade-up">
            <h5 class="text-blue-600 font-semibold text-lg flex items-center gap-2 mb-2">
                <i class="fas fa-spinner text-blue-500 fa-2x"></i> In-Progress Tasks
            </h5>
            <h3 class="text-3xl font-bold">{{ $inProgressCount }}</h3>
        </div>
        <!-- Completed Tasks -->
        <div class="bg-green-50 border-l-4 border-green-400 shadow rounded-lg p-6 flex flex-col items-center" data-aos="fade-left">
            <h5 class="text-green-600 font-semibold text-lg flex items-center gap-2 mb-2">
                <i class="fas fa-check-circle text-green-500 fa-2x"></i> Completed Tasks
            </h5>
            <h3 class="text-3xl font-bold">{{ $completedCount }}</h3>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6" data-aos="fade-down" style="margin-top: 85px;">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b border-gray-200 pb-4">
            <h4 class="text-2xl font-semibold mb-4 md:mb-0">Tasks</h4>
            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <input type="text" id="search" class="rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 w-full md:w-48" placeholder="Search tasks...">
                <select id="filterPriority" class="rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 w-full md:w-48">
                    <option value="">Filter by Priority</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <select id="filterStatus" class="rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 w-full md:w-48">
                    <option value="">Filter by Status</option>
                    <option value="Pending">Pending</option>
                    <option value="In-Progress">In-Progress</option>
                    <option value="Completed">Completed</option>
                </select>
                @if(auth()->user()->role != 'user')
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-2 transition w-full md:w-auto" onclick="openTaskModal()">Add Task</button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 hidden md:table-cell">Title</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3">Priority</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 hidden md:table-cell">Category</th>
                        <th class="px-6 py-3">Due Date</th>
                        @cannot('user')
                            <th class="px-6 py-3">Assigned To</th>
                        @endcannot
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="taskTable">
                    @foreach($tasks as $task)
                    @php
                        $priorityClass = $status = '';
                        if ($task->priority == 'High')
                            $priorityClass = 'red';
                        elseif ($task->priority == 'Medium')
                            $priorityClass = 'yellow';
                        else
                            $priorityClass = 'green';

                        if ($task->status == 'Completed')
                            $status = 'green';
                        elseif ($task->status == 'In Progress')
                            $status = 'indigo';
                        else
                            $status = 'yellow';
                    @endphp
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $task->title }}</td>
                        <td class="hidden md:table-cell">{{ $task->description }}</td>
                        <td>
                            <span class="inline-block rounded px-2 py-1 text-xs font-semibold bg-{{ $priorityClass }}-100 text-{{ $priorityClass }}-800">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td>
                            <span class="inline-block rounded px-2 py-1 text-xs font-semibold bg-{{ $status }}-100 text-{{ $status }}-800">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell">{{ $task->category->name ?? 'No Category' }}</td>
                        <td>{{ $task->due_date ? date('d M Y', strtotime($task->due_date)) : 'No Due Date' }}</td>
                        @cannot('user')
                            <td>{{ $task->user->name }}</td>
                        @endcannot
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('tasks.show', $task->id) }}" class="px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 transition">View</a>
                                @cannot('user')
                                    <button class="px-3 py-1 text-sm font-medium text-yellow-700 bg-yellow-100 rounded hover:bg-yellow-200 transition" onclick="openTaskModal({{ $task }})">Edit</button>
                                    <button class="px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded hover:bg-red-200 transition deleteCategoryBtn" data-id="{{ $task->id }}">Delete</button>
                                @endcannot
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16" data-aos="fade-up">
        <div class="mb-4" data-aos="fade-right">
            <div id="taskCalendar"></div>
        </div>
        <div data-aos="fade-left">
            <h2 class="text-center text-2xl font-bold mb-4">Task Timeline</h2>
            <div class="main-timeline">
                @foreach($tasks as $index => $task)
                <div class="timeline flex {{ $index % 2 == 0 ? 'justify-start' : 'justify-end' }} mb-6">
                    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ date('M d, Y', strtotime($task->due_date)) }}</h3>
                        <div class="text-xl font-bold text-gray-900 mb-1">{{ $task->title }}</div>
                        <div class="text-gray-500 text-sm mb-2">{{ $task->user->name }}</div>
                        <p class="font-semibold text-{{ strtolower($task->priority) == 'high' ? 'red' : (strtolower($task->priority) == 'medium' ? 'yellow' : 'green') }}-600">{{ $task->priority }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Tailwind Modal -->
<div id="taskModal" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto animate-fade-in">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h5 class="text-xl font-semibold" id="taskModalLabel">Task Form</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none" onclick="closeTaskModal()">&times;</button>
        </div>
        <div class="p-6">
            <form id="taskForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="taskId" id="taskId">

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Title</label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Description</label>
                    <textarea name="description" id="description" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Priority</label>
                    <select name="priority" id="priority" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In-Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Assigned To</label>
                    <select name="user_id" id="user_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Attachments</label>
                    <input type="file" name="attachments[]" multiple class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded px-4 py-2 transition" onclick="closeTaskModal()">Cancel</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold rounded px-4 py-2 transition">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteCategoryModal" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto animate-fade-in">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h5 class="text-xl font-semibold">Confirm Deletion</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-6">Are you sure you want to delete this task?</p>
            <div class="flex justify-end gap-2">
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded px-4 py-2 transition" onclick="closeDeleteModal()">Cancel</button>
                <form id="deleteCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded px-4 py-2 transition">Yes, Delete</button>
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
        $('#taskModal').removeClass('hidden').addClass('flex');
    }
    function closeTaskModal() {
        $('#taskModal').addClass('hidden').removeClass('flex');
    }
    function closeDeleteModal() {
        $('#deleteCategoryModal').addClass('hidden').removeClass('flex');
    }
    $(document).ready(function() {
        $('.deleteCategoryBtn').click(function() {
            var taskId = $(this).data('id');
            $('#deleteCategoryForm').attr('action', '/tasks/' + taskId);
            $('#deleteCategoryModal').removeClass('hidden').addClass('flex');
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
