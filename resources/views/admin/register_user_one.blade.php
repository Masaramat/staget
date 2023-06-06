@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">
    <!-- middle wrapper start -->
    <div class="col-md-12 col-xl-12 middle-wrapper">
      <div class="row">
        <div class="col-md-12 grid-margin">
         <div class="card">
            <div class="card-body">

              <form id="regForm" class="forms-sample" method="POST" action="{{ route('admin.registeruser.post.one') }}" enctype="multipart/form-data">
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
                    <label for="name" class="form-label">Name</label>
                    <input oninput="this.className = 'form-control'" type="text" name="name" class="form-control" id="name" autocomplete="off" value="{{ $user->name ?? '' }}">
                  </div>
                  <div class="col-xl-6 col-md-12">
                    <label for="username" class="form-label">Username</label>
                    <input oninput="this.className = 'form-control'" type="text" name="username" class="form-control" id="username" autocomplete="off" value="{{ $user->username ?? '' }}">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class=" col-xl-6 col-md-12">
                    <label for="email" class="form-label">Email</label>
                    <input oninput="this.className = 'form-control'" type="email" name="email" class="form-control" id="email" autocomplete="off" value="{{ $user->email ?? '' }}">
                  </div>
                  <div class="col-xl-6 col-md-12">
                    <label for="phone" class="form-label">Phone</label>
                    <input oninput="this.className = 'form-control'" type="phone" name="phone" class="form-control" id="phone" autocomplete="off" value="{{ $user->phone ?? '' }}">
                  </div>
                </div>
                
                <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <input oninput="this.className = 'form-control'" type="text" name="address" class="form-control" id="address" autocomplete="off" value="{{ $user->address ?? '' }}">
                </div> 

                <div class="row mb-3">
                  <div class=" col-xl-4 col-md-12">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select  name="nationality" class="form-control form-select text-light" id="nationality">

                      @if(isset($user->nationality))
                        <option selected value="{{ $user->nationality }}">{{ $user->nationality }}</option>
                      @endif 
                      <option value="Nigerian">Nigerian</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                  <div class="col-xl-4 col-md-12">
                    <label for="state_of_origin" class="form-label">State of origin</label>
                    <select type="state_of_origin" name="state_of_origin" class="form-control form-select" id="state_of_origin" autocomplete="off">

                      <option>Select state</option>
                      @foreach($states as $state)
                        <option selected = "{{ $user->state_of_origin ?? '' }}" value="{{ $state->id }}">{{ $state->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xl-4 col-md-12">
                    <label for="local_government_of_origin" class="form-label">LGA of origin</label>
                    <select type="local_government_of_origin" name="local_government_of_origin" class="form-control form-select" id="local_government_of_origin" autocomplete="off">
                      <option value="{{ $user->local_government_of_origin ?? ''}}">{{ $user->local_government_of_origin ?? 'Select LGA'}}</option>           
                    </select>
                  </div>
                </div>  
                <div class="d-flex  justify-content-end">
                  <button type="submit" class="btn btn-lg btn-primary me-2 flex flex-end">Next</button>
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