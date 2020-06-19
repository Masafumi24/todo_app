<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTask;

class TaskController extends Controller
{
    public function index(int $id)
    {
        $folders = Folder::all();
        $current_folder = Folder::find($id);
        $tasks = $current_folder->tasks()->get();

        return view('tasks/index',[
            'folders' => $folders,
            'current_folder_id' => $current_folder->id,
            'tasks' => $tasks,
        ]);
    }
    
    public function showCreateForm(int $id)
    {
        return view('tasks/create', [
            'folder_id' => $id
        ]);
    }

    public function create(int $id, CreateTask $request)
    {
        $current_folder = Folder::find($id);
    
        $task = new Task();
        $task->title = $request->title;
        $task->due_data = $request->due_data;
    
        $current_folder->tasks()->save($task);
    
        return redirect()->route('tasks.index', [
            'id' => $current_folder->id,
        ]);
    }

    public function showEditForm(int $id, $task_id)
    {
        $task = Task::find($task_id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
        {
            $task = Task::find($task_id);

            $task->title = $request->title;
            $task->status = $request->status;
            $task->due_data = $request->due_data;
            $task->save();

            return redirect()->route('tasks.index', [
                'id' => $task->folder_id,
            ]);
        }
}
