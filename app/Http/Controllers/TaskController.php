<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTask;
use App\Folder;
use App\Http\Requests\EditTask;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\Node\FunctionNode;

class TaskController extends Controller
{
    public function index(Folder $folder)
    {
        //ユーザのフォルダを取得する
        $folders  = Auth::user()->folders()->get();

        //選ばれたフォルダを取得する
        //$current_folder = Folder::find($id);

        //　選ばれたフォルダに紐づくタスクを取得する
        //$tasks = Task::where('folder_id', $current_folder->id)->get();
        //$tasks = $current_folder->tasks()->get(); 

        $tasks = $folder->tasks()->get();

        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
        ]);
    }

    public function showCreateForm(Folder $folder)
    {
        return view('tasks/create', [
            'folder_id' => $folder->id,
        ]);
    }

    public function create(Folder $folder, CreateTask $request)
    {
        //$current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $folder->tasks()->save($task);

         return redirect()->route('tasks.index', [
           $folder->id,
         ]);
    }

    /**
     *  GET /folder/{id}/tasks/{task_id}/edit
     */

    public function showEditForm(Folder $folder, Task $task)
    {
        //$task = Task::find($task_id);

        $this->CheckRelation($folder, $task);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(Folder $folder, Task $task, EditTask $request)
    {
        //$task = Task::find($task_id);

        $this->checkRelation($folder, $task);

        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
          $task->folder_id,
        ]);
    }

    private function checkRelation(Folder $folder, Task $task)
    {
        if($folder->id !== $task->folder_id) {
            abort(404);
        }
    }
}