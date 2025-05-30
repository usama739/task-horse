@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Tasks -->
        {{-- #0d1729 --}}
        <div class="card border-l-4 border-yellow-600 shadow-md rounded-lg p-6 flex flex-col items-center" data-aos="fade-right" style="background: #161f30;">
            <h5 class="text-yellow-300 font-medium text-lg flex items-center gap-2 mb-2">
                <i class="fas fa-hourglass-half text-yellow-400 fa-2x"></i> Pending Tasks
            </h5>
            <h3 class="text-3xl font-bold text-white">{{ $pendingCount }}</h3>
        </div>

        <!-- In-Progress Tasks -->
        <div class="card border-l-4 border-blue-600 shadow-md rounded-lg p-6 flex flex-col items-center" data-aos="fade-up" style="background: #161f30;">
            <h5 class="dark:text-blue-300 font-medium text-lg flex items-center gap-2 mb-2">
                <i class="fas fa-spinner text-blue-400 fa-2x"></i> In-Progress Tasks
            </h5>
            <h3 class="text-3xl font-bold text-white">{{ $inProgressCount }}</h3>
        </div>

        <!-- Completed Tasks -->
        <div class="card border-l-4 border-green-600 shadow-md rounded-lg p-6 flex flex-col items-center" data-aos="fade-left" style="background: #161f30;">
            <h5 class="dark:text-green-300 font-medium text-lg flex items-center gap-2 mb-2">
                <i class="fas fa-check-circle text-green-400 fa-2x"></i> Completed Tasks
            </h5>
            <h3 class="text-3xl font-bold text-white">{{ $completedCount }}</h3>
        </div>

    </div>

    <div class="shadow rounded-lg p-6" data-aos="fade-down" style="margin-top: 100px;">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b dark:border-blue-500 pb-4">
            <h4 class="text-3xl font-semibold mb-4 md:mb-0 text-white">Tasks</h4>
            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">

                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" id="search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Tasks..." />
                </div>

                
                <button id="dropdownDefaultButton" data-dropdown-toggle="filterPrioritySelect" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Filter by Priority
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="filterPrioritySelect" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                    <li>
                        <a href="#" data-value="Low" class="block px-4 py-2 hover:bg-gray-100 dropdown-priority dark:hover:bg-gray-600 dark:hover:text-white">Low</a>
                    </li>
                    <li>
                        <a href="#" data-value="Medium" class="block px-4 py-2 hover:bg-gray-100 dropdown-priority dark:hover:bg-gray-600 dark:hover:text-white">Medium</a>
                    </li>
                    <li>
                        <a href="#" data-value="High" class="block px-4 py-2 hover:bg-gray-100 dropdown-priority dark:hover:bg-gray-600 dark:hover:text-white">High</a>
                    </li>
                    </ul>
                </div>


                <button id="dropdownButton" data-dropdown-toggle="filterStatusSelect" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Filter by Status
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="filterStatusSelect" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownButton">
                    <li>
                        <a data-value="Pending" href="#" class="block px-4 py-2 dropdown-status hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Pending</a>
                    </li>
                    <li>
                        <a data-value="In-Progress" href="#" class="block px-4 py-2 dropdown-status hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">In-Progress</a>
                    </li>
                    <li>
                        <a data-value="Completed" href="#" class="block px-4 py-2 dropdown-status hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Completed</a>
                    </li>
                    </ul>
                </div>

                <input type="hidden" id="filterPriority" value="">
                <input type="hidden" id="filterStatus" value="">

                @if(auth()->user()->role != 'user')
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 transition w-full md:w-auto cursor-pointer" onclick="openTaskModal()">Add Task</button>
                @endif
            </div>
        </div>

        <div class="relative overflow-x-auto rounded-lg shadow">
            <table class="w-full text-sm text-center rtl:text-right text-gray-300">
                <thead class="text-xs uppercase bg-gray-900 text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 md:table-cell">Title</th>
                        {{-- <th scope="col" class="px-6 py-3">Description</th> --}}
                        <th scope="col" class="px-6 py-3">Priority</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 md:table-cell">Category</th>
                        <th scope="col" class="px-6 py-3">Due Date</th>
                        @cannot('user')
                            <th class="px-6 py-3">Assigned To</th>
                        @endcannot
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="taskTable" style="background: #0c1220">            <!-- #0a0f1a -->
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
                        elseif ($task->status == 'In-Progress')
                            $status = 'indigo';
                        else
                            $status = 'yellow';
                    @endphp
                    <tr class="border-b">
                        <td class="px-6 py-4">{{ $task->title }}</td>
                        {{-- <td class="md:table-cell">{{ $task->description }}</td> --}}
                        <td>
                            <span class="task-priority inline-block rounded px-2 py-1 text-xs font-semibold bg-opacity-20 text-white bg-{{ $priorityClass }}-500">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td>
                            <span class="task-status inline-block rounded px-2 py-1 text-xs font-semibold bg-opacity-20 text-white bg-{{ $status }}-500">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td class="md:table-cell">{{ $task->category->name ?? 'No Category' }}</td>
                        <td>{{ $task->due_date ? date('d M Y', strtotime($task->due_date)) : 'No Due Date' }}</td>
                        @cannot('user')
                            <td>{{ $task->user->name }}</td>
                        @endcannot
                        <td>
                            <div class="inline-flex overflow-hidden shadow-sm py-4" role="group">
                                <a href="{{ route('tasks.show', $task->id) }}" class="px-4 py-2 text-sm font-medium rounded-s-lg text-white bg-blue-500 hover:bg-blue-600 focus:outline-none">
                                    View
                                </a>
                                @cannot('user')
                                    <button class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 cursor-pointer transition" onclick="openTaskModal({{ $task }})">Edit</button>
                                    <button class="px-4 py-2 text-sm font-medium rounded-e-lg text-white bg-red-500 hover:bg-red-600 transition cursor-pointer deleteTaskBtn" data-id="{{ $task->id }}">Delete</button>
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
                @foreach($timelineTasks as $index => $task)
                <div class="timeline flex {{ $index % 2 == 0 ? 'left' : 'right' }} mb-6">
                    <div class="card">
                        <div class="card-body p-6 w-full max-w-md">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ date('M d, Y', strtotime($task->due_date)) }}</h3>
                            <div class="text-xl font-bold text-gray-900 mb-1">{{ $task->title }}</div>
                            <div class="text-gray-500 text-sm mb-2">{{ $task->user->name }}</div>
                            <p class="font-semibold text-{{ strtolower($task->priority) == 'high' ? 'red' : (strtolower($task->priority) == 'medium' ? 'yellow' : 'green') }}-600">{{ $task->priority }}</p>
                        </div>
                    </div>
                   
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Tailwind Modal -->
<div id="taskModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 bg-black backdrop-blur-sm justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="text-white rounded-xl shadow-2xl w-full max-w-xl mx-auto animate-fade-in" style="margin-top: 540px; background: #161f30;">
        <div class="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
            <h5 class="text-2xl font-semibold" id="taskModalLabel">Task Form</h5>
            <button type="button" class="text-blue-400 hover:text-red-600 text-2xl font-bold focus:outline-none cursor-pointer">&times;</button>
        </div>
        <div class="p-6 space-y-4">
            <form id="taskForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="taskId" id="taskId">

                <div>
                    <label class="block font-medium text-gray-500 mb-1">Title</label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 " style="background: #1a2238;" required>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Description</label>
                    <textarea name="description" id="description" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3"  style="background: #1a2238;"></textarea>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Priority</label>
                    <select name="priority" id="priority" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3" style="background: #1a2238;">
                        <option value="">Select Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3" style="background: #1a2238;">
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In-Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3" style="background: #1a2238;">
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3" style="background: #1a2238;">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Assigned To</label>
                    <select name="user_id" id="user_id" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3" style="background: #1a2238;">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Attachments</label>
                    <input type="file" name="attachments[]" multiple class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3" style="background: #1a2238;">
                </div>

                <div class="flex justify-center gap-2 pt-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteTaskModal" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black backdrop-blur-sm flex items-center justify-center">
    <div class="text-white rounded-lg shadow-lg w-full max-w-md mx-auto animate-fade-in" style="background: #161f30;">
        <div class="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
            <h5 class="text-xl font-semibold">Confirm Deletion</h5>
            <button type="button" class="text-blue-400 hover:text-red-600 text-2xl font-bold focus:outline-none">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-white mb-6">Are you sure you want to delete this task?</p>
            <div class="flex justify-end gap-2 pt-4">
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Cancel</button>
                <form id="deleteTaskForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Yes, Delete</button>
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

    $('#taskModal .text-blue-400, #taskModal .bg-gray-200').click(function() {
        closeTaskModal();
    });
    
    function closeTaskModal() {
        $('#taskModal').addClass('hidden').removeClass('flex');
    }

    // Close Add/Edit Modal on background click
    $('#taskModal').on('click', function(e) {
        if (e.target === this) closeTaskModal();
    });

    // Close Delete Modal on close or cancel
    $('#deleteTaskModal .text-blue-400, #deleteTaskModal .bg-gray-200').click(function() {
        closeDeleteModal();
    });

    function closeDeleteModal() {
        $('#deleteTaskModal').addClass('hidden').removeClass('flex');
    }

    $(document).ready(function() {
        $('.deleteTaskBtn').click(function() {
            var taskId = $(this).data('id');
            $('#deleteTaskForm').attr('action', '/tasks/' + taskId);
            $('#deleteTaskModal').removeClass('hidden').addClass('flex');
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

        $(document).on('click', '.dropdown-priority', function (e) {
            e.preventDefault();
            const value = $(this).data('value');
            $('#filterPriority').val(value).trigger('change');
        });

        $(document).on('click', '.dropdown-status', function (e) {
            e.preventDefault();
            const value = $(this).data('value');
            $('#filterStatus').val(value).trigger('change');
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


<script>
  document.getElementById('calendarBtn').addEventListener('click', () => {
    document.getElementById('calendarView').classList.remove('hidden');
    document.getElementById('timelineView').classList.add('hidden');
    toggleButtons('calendarBtn');
  });

  document.getElementById('timelineBtn').addEventListener('click', () => {
    document.getElementById('calendarView').classList.add('hidden');
    document.getElementById('timelineView').classList.remove('hidden');
    toggleButtons('timelineBtn');
  });

  function toggleButtons(activeId) {
    document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(activeId).classList.add('active');
  }

  document.querySelectorAll('.toggle-date-group').forEach(button => {
    button.addEventListener('click', () => {
      const group = button.nextElementSibling;
      group.classList.toggle('hidden');
    });
  });

  let page = 1;
  document.getElementById('loadMoreTimeline').addEventListener('click', () => {
    page++;
    // Load more logic via AJAX or Laravel pagination
  });
</script>
@endsection
