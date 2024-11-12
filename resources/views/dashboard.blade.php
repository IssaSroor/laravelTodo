<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTaskModal">ADD NEW TASK</button>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped table-bordered table-responsive">
                        <thead class="thead-dark">
                            <tr>
                                <th>Task Title</th>
                                <th>Task Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->status }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-info btn-sm"
                                            onclick="showSubtasks({{ $task->id }})">Details</button>
                                        <button class="btn btn-success btn-sm"
                                            onclick="openAddSubtaskModal({{ $task->id }})">Add Subtask</button>

                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editTaskModal{{ $task->id }}">Edit</button>
                                        <!-- Delete Button (Triggers Delete Confirmation Modal) -->
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteTaskModal{{ $task->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Task -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Task Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Task Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Task -->
    @foreach ($tasks as $task)
        <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1"
            aria-labelledby="editTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task: {{ $task->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Task Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ $task->title }}" required>
                            </div>

                            <div class="form-group">
                                <label for="status">Task Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>
                                        In Progress</option>
                                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for Deleting Task -->
        <div class="modal fade" id="deleteTaskModal{{ $task->id }}" tabindex="-1"
            aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTaskModalLabel">Delete Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this task: <strong>{{ $task->title }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Task</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Structure -->
    <!-- Subtask List Modal -->
    <div id="subtaskModal" style="display: none;">
        <h3>Subtasks for Task</h3>
        <ul id="subtaskList"></ul>
        <button onclick="closeModal()">Close</button>
    </div>

    <!-- Add Subtask Modal -->
    <div id="addSubtaskModal" style="display: none;">
        <form action="" id="addSubtaskForm">
            @csrf
            <input type="hidden" id="taskId" name="task_id">
            <input type="text" name="subtask_name" placeholder="Subtask Name" required>
            <input type="text" name="description" placeholder="Subtask Description">
            <button type="submit">Add Subtask</button>
        </form>
    </div>

    <script>
       function showSubtasks(taskId) {
    fetch(`/tasks/${taskId}/subtasks`)
        .then(response => response.json())
        .then(data => {
            const subtaskList = document.getElementById('subtaskList');
            subtaskList.innerHTML = '';
            data.subtasks.forEach(subtask => {
                const li = document.createElement('li');
                li.textContent = `${subtask.subtask_name} - ${subtask.status}`;
                subtaskList.appendChild(li);
            });
            document.getElementById('subtaskModal').style.display = 'block';
        });
}

function openAddSubtaskModal(taskId) {
    document.getElementById('taskId').value = taskId;
    document.getElementById('addSubtaskModal').style.display = 'block';
}

document.getElementById('addSubtaskForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const taskId = formData.get('task_id');

    fetch(`/tasks/${taskId}/subtasks`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Subtask added successfully!');
            document.getElementById('addSubtaskModal').style.display = 'none';
            showSubtasks(taskId); // Refresh subtask list
        }
    });
});

function closeModal() {
    document.getElementById('subtaskModal').style.display = 'none';
    document.getElementById('addSubtaskModal').style.display = 'none';
}

    </script>
</x-app-layout>
