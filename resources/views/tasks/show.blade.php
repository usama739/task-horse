@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left"></i> Task Details
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <h4>{{ $task->title }}</h4>
            <p><strong>Description:</strong> {{ $task->description }}</p>
            <p><strong>Priority:</strong> <span class="badge bg-primary">{{ $task->priority }}</span></p>
            <p><strong>Status:</strong> <span class="badge bg-success">{{ $task->status }}</span></p>
            <p><strong>Due Date:</strong> {{ $task->due_date ?? 'No due date' }}</p>
            <p><strong>Assigned To:</strong> {{ $task->user->name ?? 'Unassigned' }}</p>
        </div>
    </div>


    <h3 class="mt-5">Comments</h3>
    <div class="card">
        <div class="card-body">
            @foreach ($task->comments as $comment)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2 px-2 mt-2">
                    <div>
                        <strong>{{ $comment->user->name }}</strong> - 
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        <p>{{ $comment->comment }}</p>
                    </div>
                    <div>
                        @if(auth()->user()->role != 'user')
                            <button class="btn btn-light btn-sm" onclick="toggleDropdown({{ $comment->id }})">
                                &#x22EE;
                            </button>
                        @endif
                        <div class="dropdown-menu" id="dropdown-{{ $comment->id }}" style="display:none; position:absolute;">
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <form action="{{ route('comments.store', $task->id) }}" method="POST" class="mt-3">
        @csrf
        <div class="form-group">
            <textarea name="comment" class="form-control" rows="3" placeholder="Write a comment..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Add Comment</button>
    </form>
</div>



<script>
    function toggleDropdown(commentId) {
        let dropdown = document.getElementById('dropdown-' + commentId);
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
