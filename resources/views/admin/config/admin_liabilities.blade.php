@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">
        <div class="row profile-body">
          <!-- left wrapper start -->
          <div class="d-none d-md-block col-sm-12 col-md-6 col-xl-6 left-wrapper">
            <div class="card rounded">
              <div class="card-body">
                <form class="forms-sample" method="POST" action="{{ route('admin.config.save_liability') }}">
                  @csrf
                  <div class="mb-3">
                    <label for="year" class="form-label">Active year</label>
                    <input name="year" id="year" type="text" disabled value="{{ $year_plan->year .' Total liabilities: ' }}" class="form-control">
                  </div>
                  
                  <div class="mb-3">
                    <label for="name" class="form-label">Liability</label>
                    <input type="text" name="name" class="form-control" id="name" autocomplete="off" >
                  </div>
                  <div class="mb-3">
                    <label for="cost" class="form-label">Cost</label>
                    <input type="text" name="cost" class="form-control" id="cost" autocomplete="off" >
                  </div> 
                  
                  <input type="hidden" name="year_id" value="{{ $year_plan->id }}">
                  
                  <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                  
                </form>
                
                
              </div>
            </div>
          </div>
          <!-- left wrapper end -->
          <!-- middle wrapper start -->
          <div class="col-md-6 middle-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
               <div class="card">
              		<div class="card-body">

						        <h6 class="card-title">Users Deposits</h6>
                    <div class="row">
         
                      <div class="col-lg-12 col-xl-12 stretch-card">
                        <div class="card">
                          <div class="card-body">
                          
                            <div class="table-responsive">
                              <table class="table table-hover mb-0">
                                <thead>
                                  <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Liability</th>
                                    <th class="pt-0">Cost</th>                                    
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($liabilities as $key => $liability) 

                                  <tr>
                                    <td class="border-bottom">{{ $xy++ }}</td>
                                    <td class="border-bottom">{{ $liability->name }}</td>
                                    <td class="border-bottom">{{ $liability->cost }}</td>
                                    
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