@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">
        <div class="row profile-body">
          <!-- left wrapper start -->
          <div class="d-none d-md-block col-sm-12 left-wrapper">
            <div class="card rounded">
                <h2 class="m-3">Information for the year {{ $year_info['year'] }}</h2>
              <div class="card-body">           
                <ul class="text-large">
                    <li class="mb-3 text-bold">Total staget savings: {{ $year_info['total_savings'] }}</li>
                    <li class="mb-3 text-bold">Total savings for the year: {{ $year_info['year_savings'] }}</li>
                    <li class="mb-3 text-bold">Total interest for the year: {{ $year_info['year_interest'] }}</li>
                    <li class="mb-3 text-bold">Total liability for the year: {{ $year_info['total_liability'] }}</li>
                </ul>

                <p>Note: When you close a year profit will be calculated and shared to all accounts while liablities will be shared and deducted from each account</p>

                <button type="button" class="btn btn-lg  btn-danger mt-4" data-bs-toggle="modal" data-bs-target="#closeYearModal">
                                Close year {{ $year_info['year'] }}
                </button>
                
                
              </div>
            </div>
          </div>
          <!-- left wrapper end -->
          
         
          
        </div>

	</div>

    <!-- Modal -->
    <div class="modal fade" id="closeYearModal" tabindex="-1" aria-labelledby="closeYearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
               
                <div class="modal-header">
                    <h5 class="modal-title" id="closeYearModalLabel">Close Year Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <h3>Are you sure you want to close year {{ $year_info['year'] }}?</h3>
                    <p class="text-danger mb-2 mt-2">Note: This action cannot be undone</p>
                    
                </div>
                <div class="modal-footer">
                    <input name="id" type="hidden" id="id">
                    <button type="button" class="btn btn-danger mx-3" data-bs-dismiss="modal">No</button>
                    <button name="yes" type="submit" class="btn btn-success mx-3"><a href="{{ route('admin.config.year.close') }}">Yes</a> </button>
                    
                    
                </div>
                <
            </div>
            
        </div>
    </div>

 

@endsection