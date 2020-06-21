<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTask;

class TaskController extends Controller
{
    /**
     * タスク一覧
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function index(Folder $folder)
    {
        if (Auth::user()->id !== $folder->user_id) {
            abort(403);
        }
        $folders = Auth::user()->folders()->get();

        $tasks = $folder->tasks()->get();

        return view('tasks/index',[
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
        ]);
    }
    
    public function showCreateForm(Folder $folder)
    {
        return view('tasks/create', [
            'folder_id' => $folder->id
        ]);
    }

    public function create(Folder $folder, CreateTask $request)
    {
    
        $task = new Task();
        $task->title = $request->title;
        $task->due_data = $request->due_data;
    
        $folder->tasks()->save($task);
    
        return redirect()->route('tasks.index', [$folder->id]);

        // return redirect()->route('tasks.index', [
        //     'id' => $folder->id,
        // ]);
    }

    public function showEditForm(Folder $folder, Task $task)
    {
        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(Folder $folder, Task $task, EditTask $request)
        {
            $task->title = $request->title;
            $task->status = $request->status;
            $task->due_data = $request->due_data;
            $task->save();

            return redirect()->route('tasks.index', [$folder->id]);

            // return redirect()->route('tasks.index', [
            //     'id' => $task->folder_id,
            // ]);
        }
}
