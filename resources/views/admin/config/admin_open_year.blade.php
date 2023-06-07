@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">
        <div class="row profile-body">
          <!-- left wrapper start -->
          <div class="d-none d-md-block col-sm-12 col-md-8 col-xl-8 left-wrapper">
            <div class="card rounded">
              <div class="card-body">
                <form class="forms-sample" method="POST" action="{{ route('admin.config.open_year') }}">
                  @csrf
                  <div class="mb-3">
                    <label for="year" class="form-label">Active year</label>
                    <input name="year" id="year" type="text" disabled value="{{Date('Y')}}" class="form-control">
                  </div>
                  <div class="mb-3">
                    <label for="min_savings" class="form-label">Minimum savings</label>
                    <input type="text" name="min_savings" class="form-control" id="min_savings" autocomplete="off" >
                  </div>
                  <div class="mb-3">
                    <label for="loan_percentage" class="form-label">Loan Percentage (From savings)</label>
                    <input type="text" name="loan_percentage" class="form-control" id="loan_percentage" autocomplete="off" >
                  </div>
                  <div class="mb-3">
                    <label for="interest_rate" class="form-label">Interest rate (Percentage)</label>
                    <input type="text" name="interest_rate" class="form-control" id="interest_rate" autocomplete="off" >
                  </div>
                  <div class="mb-3">
                    <label for="interest_type" class="form-label">Interest type</label>
                    <select type="text" name="interest_type" class="form-control form-select" id="interest_type" autocomplete="off" >
                      <option value="">Select</option>
                      <option value="monthly">Monthly</option>
                      <option value="one_off">One-off</option>

                    </select>
                  </div>
                  
                  <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                  
                </form>
                
                
              </div>
            </div>
          </div>
          <!-- left wrapper end -->
         
          
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