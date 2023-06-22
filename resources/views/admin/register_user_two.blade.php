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

            <form id="regForm" class="forms-sample" method="POST" action="{{ route('admin.registeruser.post.two') }}" enctype="multipart/form-data">
              @csrf
              <h6 class="card-title mt-4">Next of kin information</h6>
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
                <div class=" col-xl-6 col-md-12">
                  <label for="nok_name" class="form-label">NOK Name</label>
                  <input oninput="this.className = 'form-control'" type="text" name="nok_name" class="form-control" id="nok_name" autocomplete="off" value="{{ $user->nok_name ?? '' }}">
                </div>
                <div class="col-xl-6 col-md-12">
                  <label for="nok_address" class="form-label">NOK Address</label>
                  <input oninput="this.className = 'form-control'" type="text" name="nok_address" class="form-control" id="nok_address" autocomplete="off" value="{{ $user->nok_address ?? '' }}">
                </div>
              </div>

              <div class="row mb-4">
                <div class=" col-xl-4 col-md-12">
                  <label for="nok_email" class="form-label">NOK email</label>
                  <input oninput="this.className = 'form-control'" type="email" name="nok_email" class="form-control" id="nok_email" autocomplete="off" value="{{ $user->nok_email ?? '' }}">
                </div>
                <div class="col-xl-4 col-md-12">
                  <label for="nok_phone" class="form-label">NOK phone</label>
                  <input oninput="this.className = 'form-control'" type="phone" name="nok_phone" class="form-control" id="nok_phone" autocomplete="off" value="{{ $user->nok_phone ?? '' }}">
                </div>
                <div class="col-xl-4 col-md-12">
                  <label for="nok_relationship" class="form-label">NOK Relationship</label>
                  <select oninput="this.className = 'form-control form-select'" name="nok_relationship" class="form-control form-select" id="nok_relationship" autocomplete="off">
                    <option value="{{ $user->nok_relationship ?? '' }}"> {{ $user->nok_relationship ?? 'Select Relationship'  }}</option>
                    <option value="father">Father</option>
                    <option value="mother">Mother</option>
                    <option value="child">Child</option>
                    <option value="sibling">Sibling</option>
                  </select>
                </div>
              </div>

              <h6 class="card-title mt-4">Official profile information</h6>
              <div class="row mb-3">
                <div class=" col-xl-6 col-md-12">
                  <label for="branch_id" class="form-label">Branch</label>
                  <select oninput="this.className = 'form-control form-select'" name="branch_id" class="form-control form-select" id="branch_id">
                    <option value="{{ $user->branch_id ?? '' }}"> {{ !empty($user->branch_id) ? $branch::find($user->branch_id)->name : 'Select branch' }}</option>
                    @foreach($branches as $branch)
                      <option value="{{ $branch->id }}">{{ $branch->name }}</option>

                    @endforeach
                  </select>
                 
                </div>
                <div class="col-xl-6 col-md-12">
                  <label for="department_id" class="form-label">Department</label>
                  <select oninput="this.className = 'form-control form-select'"  name="department_id" class="form-control form-select" id="department_id">
                    <option value="">Select department</option>
                    @foreach($departments as $department)
                      <option value="{{ $department->id }}">{{ $department->name }}</option>

                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-4">
                <div class=" col-xl-4 col-md-12">
                  <label for="role" class="form-label">role</label>
                  <select oninput="this.className = 'form-control form-select'" type="text" name="role" class="form-control form-select" id="role">                   
                    <option value="">Select role</option>
                    <option value="admin">admin</option>
                    <option value="secretary">secretary</option>
                    <option value="treasurer">treasurer</option>
                    <option value="patron">patron</option>
                    <option value="member">member</option>
                    <option value="coordinator">coordinator</option>
                  </select>
                </div>
                <div class="col-xl-4 col-md-12">
                  <label for="status" class="form-label">Status</label>
                  <div class="form-control">

                    <input checked = 'checked' type="radio" id="status1" name="status" value="active">
                    <label for="age1">Active  <i style="width:20px;"></i></label>
                    
                    <input type="radio" id="status2" name="status" value="inactive">
                    <label for="age2">Inactive</label>                         
                
                  </div>
                  
                  
                </div>
                <div class="col-xl-4 col-md-12">
                  <label for="password" class="form-label">Default Password</label>
                  <input oninput="this.className = 'form-control'" type="password" name="password" class="form-control" id="password" autocomplete="off">
                </div>
              </div>              
              
              <div class="d-flex  justify-content-end">
                <a class="btn btn-lg btn-secondary mx-3" href="{{ route('admin.register.one') }}">Previous</a>
               <button type="submit" class="btn btn-lg btn-primary"> Next </button>
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