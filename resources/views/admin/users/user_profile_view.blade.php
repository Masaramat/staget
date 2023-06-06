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

						<h6 class="card-title">Update User Profile</h6>
            <img class="wd-100 rounded-circle" src="{{ !empty($profileData->photo) ? url('uploads/user_images/passports/'.$profileData->photo) : url('uploads/no_image.jpg') }}" alt="profile">

						<form class="forms-sample" method="POST" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data">

              @csrf
							<div class="mb-3">
								<label for="username" class="form-label">Username</label>
								<input type="text" name="username" class="form-control" id="username" autocomplete="off" value="{{ $profileData->username }}">
							</div>
							<div class="mb-3">
								<label for="name" class="form-label">Name</label>
								<input type="text" name="name" class="form-control" id="name" autocomplete="off" value="{{ $profileData->name }}">
							</div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" autocomplete="off" value="{{ $profileData->email }}">
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="phone" name="phone" class="form-control" id="phone" autocomplete="off" value="{{ $profileData->phone }}">
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" id="name" autocomplete="off" value="{{ $profileData->address }}">
              </div>              

              <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control" id="image">
              </div>

              <div class="mb-3">
                <label for="photo" class="form-label"></label>
                 <img class="wd-80 rounded-circle" id="showImage" src="{{ !empty($profileData->photo) ? url('uploads/admin_images/'.$profileData->photo) : url('uploads/no_image.jpg') }}" alt="profile">
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

  <script type="text/javascript">
    $(document).ready(function(){
      $('#image').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e){
          $('#showImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
      })
    })
  </script>

@endsection