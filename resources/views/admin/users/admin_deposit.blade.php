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
                                    <th class="pt-0">Name</th>
                                    <th class="pt-0">Total savigs</th>
                                    <th class="pt-0">Amount</th>
                                    
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($payers as $key => $payer) 

                                  <tr>
                                    <td class="border-bottom">{{ $xy++ }}</td>
                                    <td class="border-bottom">{{ $payer->name }}</td>
                                    <td class="border-bottom">{{ $payer->total_savings }}</td>
                                    <td class="border-bottom">{{ $payer->monthly_savings }}</td>
                                   
                                    <td class="border-bottom">
                                      <form action="{{ route('admin.user.remove_payer') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $key }}" name="payer">
                                        <button type="submit" class="text-primary">del</button>
                                      </form>
                                      
                                      
                                    </td>
                                  </tr>
                                  
                                  @endforeach
                                </tbody>
                              </table>
                              <div class="mt-3">
                                
                                <a href="{{ route('admin.user.complete_payments') }}" class="btn btn-success">Save Payments</a>
                              </div>
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