@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

  <link rel="stylesheet" href="{{ asset('backend/assets/css/form.css') }}">
 

	<div class="page-content">
    <!-- middle wrapper start -->
    <div class="col-md-12 col-xl-12 middle-wrapper">
      <div class="row">
        <div class="col-md-12 grid-margin">
         <div class="card">
            <div class="card-body">

              <form id="regForm" class="forms-sample" method="POST" action="{{ route('admin.registeruser.post.three') }}" enctype="multipart/form-data">
                @csrf
                 <h6 class="card-title">Personal information</h6>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif 
               
                <div class="row mb-3">
                  <div class="col-xl-6 col-md-12">
                    <div class="mb-3">
                      <label for="photo" class="form-label">Passport</label>
                      <input oninput="this.className = 'form-control'" type="file" name="photo" class="form-control" id="image" value="{{ !empty($user->photo) ? $user->photo : '' }}">
                      
                    </div>

                    <div class="">
                      <img class="wd-80" id="showImage" src="{{ !empty($user->photo) ? url('uploads/user_images/passports/'.$user->photo) : url('uploads/no_image.jpg') }}" alt="profile">            
                      
                    </div>            
                  </div>
                  <div class="col-xl-6 col-md-12">
                    <div class="mb-3">
                      <label for="signature" class="form-label">Signature</label>
                      <input type="file" name="signature" class="form-control" id="signature" value="{{ !empty($user->signature) ? url('uploads/user_images/signatures/'.$user->signature) : '' }}">
                      
                    </div>

                    <div class="">
                       <img class="wd-80" id="showSignature" src="{{ !empty($user->signature) ? url('uploads/user_images/signatures/'.$user->signature) : url('uploads/no_image.jpg') }}" alt="profile">            
                      
                    </div> 

                    <div class="row">
                      <div class="col-xl-6 col-md-12">
                      <label for="deposit" class="form-label">Monthly Savings</label>
                      <input oninput="this.className = 'form-control'" type="text" name="deposit" class="form-control" id="deposit" autocomplete="off" value="3000">
                    </div> 
                    </div>
                                    
                  </div>
                </div>
                
                <div class="d-flex  justify-content-end">
                  <a class="btn btn-lg btn-secondary mx-3" href="{{ route('admin.register.one') }}">Previous</a>
                 <button type="submit" class="btn btn-lg btn-success me-2 flex flex-end"> Submit </button>
                </div>
               
                
                
              </form>

            </div>
        </div>
        </div>
        
      </div>
    </div>
    <!-- middle wrapper end -->

	</div>
  <script src="{{ asset('backend/assets/js/form.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('#image').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e){
          $('#showImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
      });

      $('#signature').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e){
          $('#showSignature').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
      });
     $("#nationality").change(function(){
        $("#state_of_origin").html($('<option value="">Select State</option>'));
        $("#local_government_of_origin").html($('<option value="">Select LGA</option>'));
        if(this.value == "Nigerian"){
        $.get("http://localhost/state_lga_api/api/state/read.php", function(data, status){
          status = data['status']
					message = data['message']
					// alert(message)	
					let i = 0;			
					
					if(status == 0){
						data = JSON.stringify(data['data'])
						data = JSON.parse(data)
						for(i=0; i<data.length; i++){
							$('<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>').appendTo('#state_of_origin');
							console.log(data[i])
						}
					}else if(status == 1){
						// alert(status)
						$('<option value="">' + message + '</option>').appendTo('#state_of_origin');
					}
        });
      }else{
        $("#state_of_origin").html($('<option value="38">Other</option>'));
        $("#local_government_of_origin").html($('<option value="775">Other</option>'));
      }
      });

      $("#state_of_origin").change(function(){
        $("#local_government_of_origin").html($('<option value="">Select LGA</option>'));       
        $.get("http://localhost/state_lga_api/api/lgas/read.php?id="+this.value, function(data, status){
          status = data['status']
					message = data['message']
					// alert(message)	
					let i = 0;			
					
					if(status == 0){
						data = JSON.stringify(data['data'])
						data = JSON.parse(data)
						for(i=0; i<data.length; i++){
							$('<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>').appendTo('#local_government_of_origin');
							console.log(data[i])
						}
					}else if(status == 1){
						// alert(status)
						$('<option value="">' + message + '</option>').appendTo('#state_of_origin');
					}
        });
      
      });






      
    });   
      
  </script>

@endsection