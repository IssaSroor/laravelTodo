<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        // Retrieve tasks for the logged-in user
        $tasks = Task::where('user_id', Auth::id())->get();
        // dd($tasks);
        // Return the dashboard view with the tasks data
        return view('dashboard', compact('tasks'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:191'
        ]);

        // Create a new task for the authenticated user
        Task::create([
            'title' => $request->title,
            'status' => $request->status ?? 'Pending',
            'user_id' => Auth::id()  // Automatically associate the task with the logged-in user
        ]);

        // Redirect to the task index page with a success message
        return redirect()->route('dashboard')->with('success', 'Task created successfully!');
    }
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        // Check if the logged-in user is the owner of the task
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized action.');
        }

        return view('dashboard', compact('task'));
    }

    // Update an existing task
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:191'
        ]);

        $task = Task::findOrFail($id);
        
        // Check if the logged-in user is the owner of the task
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'Pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Task updated successfully!');
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Check if the logged-in user is the owner of the task
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully!');
    }
    public function show(Task $task)
{
    $subtasks = $task->subtasks()->get();
    return response()->json(['task' => $task, 'subtasks' => $subtasks]);
}

// Store a new subtask

public function showSubtasks(Task $task)
{
    $subtasks = $task->subtasks;
    return response()->json(['subtasks' => $subtasks]);
}
public function storeSubtask(Request $request, Task $task)
{
    $request->validate([
        'subtask_name' => 'required|string|max:191',
        'description' => 'nullable|string',
        'status' => 'nullable|string|max:191'
    ]);

    $subtask = new Subtask();
    $subtask->task_id = $task->id;
    $subtask->user_id = auth()->id();
    $subtask->subtask_name = $request->subtask_name;
    $subtask->description = $request->description;
    $subtask->status = $request->status ?? 'Pending';
    $subtask->save();

    return redirect()->back()->with('success', 'Subtask added successfully.');
}
}
?>