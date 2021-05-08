@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tasks</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive">
        <table class="table table-striped" id="tasks_table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Title</th>
              <th>Created At</th>
              <th>Status</th>
              <th>Time Worked</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($tasks as $task)
              <tr>
                <td>{{$task->id}}</td>
                <td>{{$task->title}}</td>
                <td>{{$task->created_at->diffForHumans()}}</td>
                <td>
                    <select class="progress-change" data-id="{{$task->id}}" class="form-control select2" style="width: 100%;">
                            @foreach($progresses as $item)
                                <option  value="{{ $item->id }}" 
                                    @if($task->status->id == $item->id)
                                        selected="selected" 
                                    @endif

                                    @if($task->status->id == 1 && in_array($item->id, [3,4,5]))
                                        disabled="disabled"
                                    @endif

                                    @if(in_array($task->status->id, [2,3])&& in_array($item->id, [1,5]))
                                        disabled="disabled"
                                    @endif 

                                    @if($task->status->id == 4 && in_array($item->id, [1,2,3]))
                                        disabled="disabled"
                                    @endif

                                    value="{{ $item->id }}"
                                >
                                        {{$item->status}}
                                </option>
                            @endforeach
                          </select>
                </td>
                <td class="progress-time" data-seconds="{{ $task->task_progress_seconds }}">{{$task->task_progress_time}}</td>
              </tr>
            @empty
                <p class="text-center">No tasks</p>
            @endforelse
          </tbody>
        </table>
        <div class="row">
          <div class="col-md-3 right">
            {{$tasks->links()}}
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('dist/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tasks_table tbody tr').each(function(item) {
                if($(this).find('select').val() == 2) {
                    setTimer($(this).find('.progress-time').data('seconds'), $(this));
                }
            });            
        });

        $('.progress-change').on('change', function() {
            let event = $(this);
            if(event.val() != 2) {
                closeTimer();
            }
            $.ajax({
                'url': '/task/status_change',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'task': event.data('id'),
                    'status': event.val() 
                },
                method: "POST",
                success: function(response) {
                    /*if(event.val() == 2) {
                        setTimer(response.time, event);
                    }*/
                    location.reload();
                }
            })
        });


        let timer = 0; 
        let count = 0;
        function setTimer(seconds, event) {
            timer = setInterval(addTime, 1000, seconds, event);
        }

        function addTime(seconds, event) {
            count += 1;
            event.closest('tr').find('.progress-time').text(secondsToDhms(seconds))
        }

        function secondsToDhms(seconds) {
            seconds += count;
            console.log(seconds);
            seconds = Number(seconds);
            var d = Math.floor(seconds / (3600*24));
            var h = Math.floor(seconds % (3600*24) / 3600);
            var m = Math.floor(seconds % 3600 / 60);
            var s = Math.floor(seconds % 60);

            var dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days, ") : "";
            var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
            var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
            var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
            return dDisplay + hDisplay + mDisplay + sDisplay;
        }

        function closeTimer() {
            clearInterval(timer);
        }
    </script>
@endsection