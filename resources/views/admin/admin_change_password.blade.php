@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">

       
        <div class="row profile-body">
         
          <!-- middle wrapper start -->
          <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
               <div class="card">
              		<div class="card-body">

						<h6 class="card-title">Change password</h6>

						<form class="forms-sample" method="POST" action="{{ route('admin.update.password') }}" enctype="multipart/form-data">
              @csrf
							
							<div class="mb-3">
								<label for="old_password" class="form-label">Old password</label>
								<input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" autocomplete="off">
                @error('old_password')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
							</div>  

              <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" autocomplete="off">
                @error('new_password')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>  

               <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation" autocomplete="off">
               
              </div>     

             
							
							
							<button type="submit" class="btn btn-primary me-2">Save Changes</button>
							
						</form>

              		</div>
            	</div>
              </div>
              
            </div>
          </div>
          <!-- middle wrapper end -->
          
        </div>

	</div>


@endsection