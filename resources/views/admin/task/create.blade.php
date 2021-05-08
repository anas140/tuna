@extends('layouts.admin.app')

@section('title') Create Tasks @endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create Task</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="task_create_form" method="POST" action="{{ route('task.store') }}">
                @csrf()
                <div class="card-body">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror ">
                    
                    @error('title')  
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label>User</label>
                    <select name="user" class="form-control @error('user') is-invalid @enderror" >
                      @foreach($users as $user)
                          <option value="{{$user->id}}" > {{ $user->name }}</option>
                      @endforeach
                    </select>
                    @error('user')
                      <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>  

      </div>
  </div>  
  </div>
	
@endsection