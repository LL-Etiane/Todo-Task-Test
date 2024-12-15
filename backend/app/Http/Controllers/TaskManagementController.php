<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskManagementController extends Controller
{
    /**
     * Get all tasks
     * 
     * Returns all the tasks in the system
     * 
     */
    public function index()
    {
        $tasks = Task::latest();
        return $tasks->paginate(10);
    }


    /**
     * Create Task
     *
     * This endpoint allows you to create a new task in the system.
     * 
     * @bodyParam title string required The title of the task. Example: "Finish project"
     * @bodyParam description string required A detailed explanation of the task. Example: "Complete the project by the end of the week."
     * @bodyParam status string optional The status of the task. Must be one of "pending" or "completed". Defaults to "pending"
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|string|in:pending,completed',
        ]);
    
        $task = Task::create($validated);
    
        return response()->json($task, 201);
    }

    /**
     * Update Task
     *
     * This endpoint allows you to update an existing task in the system.
     *
     * @urlParam task int required The ID of the task to be updated.
     * 
     * @bodyParam title string optional The title of the task
     * @bodyParam description string optional A detailed explanation of the task"
     * @bodyParam status string optional The status of the task. Must be one of "pending" or "completed"
     *
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,completed',
        ]);
        
        $task->update($validated);
        return response()->json($task);
    }

    /**
     * Delete Task
     *
     * This endpoint allows you to delete an existing task from the system.
     *
     * @urlParam task int required The ID of the task to be deleted. Example: 1
     *
     * @response 204 {
     *   "message": "Task Deleted successfully"
     * }
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json("Task Deleted successfully", 204);
    }
    
}
