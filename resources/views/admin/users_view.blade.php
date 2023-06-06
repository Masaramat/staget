@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
          </div>
          <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ route('admin.register.one') }}" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
              <i class="btn-icon-prepend" data-feather="user"></i>
              Add new user
            </a>
          </div>
        </div>

       
        

        <div class="row">
         
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">RCCs</h6>
                  <div class="dropdown mb-2">
                    <a type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                      <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                      <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">#</th>
                        <th class="pt-0">Name</th>
                        <th class="pt-0">Email</th>
                        <th class="pt-0">Phone</th>
                        <th class="pt-0">Role</th>
                        <th class="pt-0">Options</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      @foreach($users as $user) 

                      <tr>
                        <td class="border-bottom">{{ $xy++ }}</td>
                        <td class="border-bottom">{{ $user->name }}</td>
                        <td class="border-bottom">{{ $user->email }}</td>
                        <td class="border-bottom">{{ $user->phone }}</td>
                        <td class="border-bottom">{{ $user->role }}</td>
                        <td class="border-bottom">
                          <form action="{{ route('admin.user.view') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ $user->id }}" name="id">
                            <button type="submit" class="text-primary">View</button>
                          </form>
                          
                          
                        </td>
                      </tr>
                      
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div> 
            </div>
          </div>
        </div> <!-- row -->

			</div>

@endsection