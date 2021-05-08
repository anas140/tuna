<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskProgress;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::latest()->paginate();
        $tasks->map(function($task) {
            $time = 0;
            $last_time = NULL;
            TaskProgress::select('progress_id', 'created_at')->where('task_id', $task->id)->chunk(2, function($progresses) use(&$time, &$last_time) {
                    if(isset($progresses[1])) {
                        $time += $progresses[1]->created_at->diffInSeconds($progresses[0]->created_at);
                        $last_time = $progresses[1];
                    } else if(isset($last_time->progress_id) && $last_time->progress_id == 2) {
                            $time += $progresses[0]->created_at->diffInSeconds($last_time->created_at);
                    } else if($progresses[0]->progress_id == 2) {
                        $time += $progresses[0]->created_at->diffInSeconds(now());
                    }
            });
            $task->task_progress_time = $this->secondsToTime($time);
            $task->task_progress_seconds = $time;
        });
        return view('admin.task.index', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('is_admin', NULL)->get();
        return view('admin.task.create', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'user' => 'required|exists:users,id',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->user_id = $request->user;
        $task->save();
        return redirect()->back()->with('success_status', 'Task Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {   
        $users = User::where('is_admin', NULL)->get();
        return view('admin/task/edit', [
            'task' => $task,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required',
            'user' => 'required|exists:users,id',
        ]);

        $task->title = $request->title;
        $task->user_id = $request->user;
        $task->save();
        return redirect()->back()->with('success_status', 'Task updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();  
        return redirect()->back()->with('success_status', 'Task Deleted Successfully');
    }

    public function status_change(Request $request) {
        $task_progress = new TaskProgress;
        $task_progress->task_id = $request->task;
        $task_progress->progress_id = $request->status;
        if($task_progress->save()) {

            $task = Task::find($request->task);
            $task->progress_id = $request->status;
            $task->save();

            $time = 0;
            if($task->progress_id == 2) {
                            $time = 0;
                $last_time = NULL;
                TaskProgress::select('progress_id', 'created_at')->where('task_id', $task->id)->chunk(2, function($progresses) use(&$time, &$last_time) {
                        if(isset($progresses[1])) {
                            // dd($progresses);
                            $time += $progresses[1]->created_at->diffInSeconds($progresses[0]->created_at);
                            $last_time = $progresses[1];
                        } else if(isset($last_time->progress_id) && $last_time->progress_id == 2) {
                                $time += $progresses[0]->created_at->diffInSeconds($last_time->created_at);
                        } else if($progresses[0]->progress_id == 2) {
                            $time += $progresses[0]->created_at->diffInSeconds(now());
                        }
                });
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Task status Updaded successfully',
                'time' => $time
            ], 200);
        }
        return response()->json([
                'status' => 'error',
                'message' => 'Error! Task status Not Updaded successfully'
            ], 200);

    }

    private function secondsToTime($seconds) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }
}
