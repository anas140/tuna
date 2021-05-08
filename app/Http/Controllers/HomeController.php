<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Progress;
use App\Models\TaskProgress;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $progress = Progress::where('id', '!=', 5)->get();
        $tasks = Task::where('user_id', auth()->user()->id)->paginate();
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
        
        return view('home', [
            'tasks' => $tasks,
            'progresses' => $progress
        ]);
    }

    private function calculateDiff($items) {
        // foreach
    }

    private function secondsToTime($seconds) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }
}
