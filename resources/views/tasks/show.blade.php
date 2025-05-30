@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('tasks.index') }}" class="flex items-center text-blue-400 hover:text-blue-600 text-lg font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> <span>Back to Tasks</span>
        </a>
    </div>

    <div class="rounded-lg shadow-md p-8 mb-8" style="background: #161f30;">
        <h2 class="text-3xl font-bold text-white mb-5">{{ $task->title }}</h2>
        <div class="mb-5">
            <span class="block text-gray-400 font-medium mb-1">Description:</span>
            <p class="text-gray-200">{{ $task->description }}</p>
        </div>
        <div class="flex flex-wrap gap-8 mb-3">
            <div>
                <span class="block text-gray-400 font-medium mb-1">Priority:</span>
                <span class="inline-block rounded px-2 py-1 text-xs font-semibold bg-opacity-20 text-white bg-{{ strtolower($task->priority) == 'high' ? 'red' : (strtolower($task->priority) == 'medium' ? 'yellow' : 'green') }}-500">
                    {{ $task->priority }}
                </span>
            </div>
            <div>
                <span class="block text-gray-400 font-medium mb-1">Status:</span>
                <span class="inline-block rounded px-2 py-1 text-xs font-semibold bg-opacity-20 text-white bg-{{ strtolower($task->status) == 'completed' ? 'green' : (strtolower($task->status) == 'in-progress' ? 'indigo' : 'yellow') }}-500">
                    {{ $task->status }}
                </span>
            </div>
            <div>
                <span class="block text-gray-400 font-medium mb-1">Due Date:</span>
                <span class="text-gray-200">{{ $task->due_date ?? 'No due date' }}</span>
            </div>
            <div>
                <span class="block text-gray-400 font-medium mb-1">Assigned To:</span>
                <span class="text-gray-200">{{ $task->user->name ?? 'Unassigned' }}</span>
            </div>
        </div>
    </div>

    <div class="rounded-lg shadow-md p-6 mb-8" style="background: #161f30;">
        <h3 class="text-2xl font-bold text-white mb-4">Comments</h3>
        @foreach ($task->comments as $comment)
            <div class="flex justify-between items-center border-b border-gray-700 py-4 px-2">
                <div>
                    <span class="font-semibold text-blue-300">{{ $comment->user->name }}</span>
                    <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                    <p class="text-gray-200 mt-1">{{ $comment->comment }}</p>
                </div>
                @if(auth()->user()->role != 'user')
                    <div class="relative">
                        <button class="text-gray-400 hover:text-red-500 px-2 py-1 rounded focus:outline-none cursor-pointer" onclick="toggleDropdown({{ $comment->id }})">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-32 bg-gray-800 rounded shadow-lg z-50 hidden" id="dropdown-{{ $comment->id }}">
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-500 hover:bg-gray-700 rounded">Delete</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="rounded-lg shadow-md p-6 mb-8" style="background: #161f30;">
        <form action="{{ route('comments.store', $task->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 font-medium mb-1">Add a Comment</label>
                <textarea name="comment" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white" rows="3" placeholder="Write a comment..." required style="background: #1a2238"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer transition">Add Comment</button>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(commentId) {
        let dropdown = document.getElementById('dropdown-' + commentId);
        dropdown.classList.toggle('hidden');
    }
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(event) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(function(dropdown) {
            if (!dropdown.contains(event.target) && !dropdown.previousElementSibling.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
@endsection
