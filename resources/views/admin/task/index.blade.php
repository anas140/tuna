@extends('layouts.admin.app')

@section('title') Tasks @endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-3">
                    <h3 class="card-title">Tasks</h3>
                  </div>
                  <div class="col-md-3"></div>
                  <div class="col-md-3"></div>
                  <div class="col-md-3">
                    <a href="{{route('task.create')}}" class="btn btn-primary">
                      Create
                    </a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table table-striped">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Title</th>
                      <th>User</th>
                      <th>Status</th>
                      <th>Time Worked</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($tasks as $task)
                      <tr>
                        <td>{{$loop->iteration + $tasks->firstItem() - 1}}</td>
                        <td>{{$task->title}}</td>
                        <td>{{$task->user->name}}</td>
                        <td>{{$task->status->status}}
                        <td>{{$task->task_progress_time}}</td>
                        <td>{{$task->created_at->diffForHumans()}}</td>
                        </td>
                        <td>
                          @if(!$task->user())
                            <button class="btn btn-sm">
                              + Assign User
                            </button>
                          @endif
                          <div class="row">
                            <div class="col">
                                <a href="/admin/task/{{$task->id}}/edit" class="btn btn-warning">Edit</a>                              
                            </div>
                            <div class="col">
                              <form action="/admin/task/{{$task->id}}" method="POST">
                                @csrf()
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">
                                  Delete
                                </button>
                              </form>
                            </div>
                          </div>
                        </td>
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
  </div>
	
@endsection