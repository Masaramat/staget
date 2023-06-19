@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">

       
        <div class="row profile-body">
         <!-- left wrapper start -->
          <div class="d-none d-md-block col-md-4 col-xl-4 left-wrapper">
            <div class="card rounded">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  
                   <div>
                    <img class="wd-100 rounded-circle" src="{{ !empty($profileData->photo) ? url('uploads/user_images/passports/'.$profileData->photo) : url('uploads/no_image.jpg') }}" alt="profile">
                    
                  </div>                 
                 
                  
                </div>                 
                 
                 <div class="mt-3">
                  <p class="h4 mt-1 ">{{$profileData->name}} </p>
                </div>
                <div class="mt-3">
                    <img class="wd-50 " src="{{ !empty($profileData->signature) ? url('uploads/user_images/signatures/'.$profileData->signature) : url('uploads/no_image.jpg') }}" alt="profile">
                    
                  </div>
                <div class="mt-3">
                  <label class="tx-11 fw-bolder mb-0 text-uppercase">Total asset:</label>
                  <p class="text-muted">N{{number_format($account_details['total_savings'], 2)}}</p>
                </div>
                
                <div class="mt-3">
                  <label class="tx-11 fw-bolder mb-0 text-uppercase">Total year savings:</label>
                  <p class="text-muted">N{{number_format($account_details['total_year_savings'], 2)}}</p>
                </div>
                <div class="mt-3">
                  <label class="tx-11 fw-bolder mb-0 text-uppercase">Total loan balance (Internal/External):</label>
                  <p class="text-muted">N{{number_format($account_details['total_outstanding_loan'] + $account_details['total_external_loan_outstanding'], 2)}}</p>
                </div>
                <div class="mt-3">
                  <label class="tx-11 fw-bolder mb-0 text-uppercase">Interest/Liability:</label>
                  <p class="text-muted">N{{number_format($account_details['interest_due'], 2) }}(stake: {{number_format($account_details['interest_stake'], 2)}}) / N{{number_format($account_details['liability_due'], 2)}}</p>
                </div>
                <div class="mt-3">
                  <label class="tx-11 fw-bolder mb-0 text-uppercase">Closing balance:</label>
                  <p class="text-muted">N{{number_format($account_details['closing_balance'], 2) }}</p>
                </div>
                <div class="mt-4 d-flex social-links">
                 
                  <button data-user_id = "{{$profileData->id}}"
                            data-closing_bal = "{{number_format($account_details['closing_balance'], 2)}}"
                            data-closing_balance = "{{$account_details['closing_balance']}}" data-bs-toggle="modal" data-bs-target="#disableUserModal"  class="btn btn-danger form-control" type="submit">Disable user</button>
                    
                  
                </div>
              </div>
            </div>
          </div>
          <!-- left wrapper end -->
          <!-- middle wrapper start -->
          <div class="col-md-8 col-xl-8 middle-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
               <div class="card">
              		<div class="card-body">

                <div class="d-flex justify-content-end">
                  <button class="btn btn-primary mx-2">Update NOK</button>
                  <button class="btn btn-primary mx-2">Update Official Info</button>
                </div>
                  
						<h6 class="card-title">Update User Profile</h6>            

						<form class="forms-sample" method="POST" action="{{ route('admin.user.update') }}" enctype="multipart/form-data">

              @csrf
              <div class="row">
                <div class="mb-3 col-lg-4">
                  <label for="username" class="form-label text-muted">Username</label>
                  <input type="text" name="username" class="form-control" id="username" autocomplete="off" value="{{ $profileData->username }}">
                </div>
                <div class="mb-3 col-lg-8">
                  <label for="name" class="form-label text-muted">Name</label>
                  <input type="text" name="name" class="form-control" id="name" autocomplete="off" value="{{ $profileData->name }}">
                </div>
              </div>
							
              <div class="row">
                <div class="mb-3 col-lg-6">
                  <label for="email" class="form-label text-muted">Email</label>
                  <input type="email" name="email" class="form-control" id="email" autocomplete="off" value="{{ $profileData->email }}">
                </div>
                <div class="mb-3 col-lg-6">
                  <label for="phone" class="form-label text-muted">Phone</label>
                  <input type="phone" name="phone" class="form-control" id="phone" autocomplete="off" value="{{ $profileData->phone }}">
                </div>
                
              </div>
              
              <div class="mb-3">
                <label for="address" class="form-label text-muted">Address</label>
                <input type="text" name="address" class="form-control" id="name" autocomplete="off" value="{{ $profileData->address }}">
              </div> 
              <div class="row">
                <div class="mb-3 col-lg-8">
                  <label for="photo" class="form-label text-muted">Photo</label>
                  <input type="file" value="{{$profileData->photo}}" name="photo" class="form-control" id="image">
                </div>

                <div class="mb-3 mt-3 col-lg-4">
                  <label for="photo" class="form-label text-muted"></label>
                  <img class="wd-80 rounded-circle" id="showImage" src="{{ !empty($profileData->photo) ? url('uploads/user_images/passports/'.$profileData->photo) : url('uploads/no_image.jpg') }}" alt="profile">
                </div>
              </div>
              <div class="row">
                <div class="mb-3 col-lg-8">
                  <label for="photo" class="form-label text-muted">Signature</label>
                  <input type="file" name="signature" class="form-control" id="signature" value="{{$profileData->signature}}">
                </div>

                <div class="mb-3 mt-3 col-lg-4">
                  <label for="photo" class="form-label text-muted"></label>
                  <img class="wd-80 rounded-circle" id="showSignature" src="{{ !empty($profileData->photo) ? url('uploads/user_images/signatures/'.$profileData->signature) : url('uploads/no_image.jpg') }}" alt="profile">
                </div>
              </div>  
              <div class="row">
                <div class="mb-3 col-lg-8">
                  <label for="photo" class="form-label text-muted">Monthly deposit</label>
                  <input type="test" value="{{App\Models\Account::where('user_id', $profileData->id)->first()->monthly_savings}}" name="monthly_deposit" class="form-control" id="monthly_deposit">
                </div>

              </div> 
              <input type="hidden" name="id" value="{{$profileData->id}}">
							
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

   <!-- Modal -->
    <div class="modal fade" id="disableUserModal" tabindex="-1" aria-labelledby="disableUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.user.disable') }}">
                     @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveLoanModalLabel">Approve Loan Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">                        
                       
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Closing amount</label>
                            <input class="form-control " id="closing_balance" name="closing_amount">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="closing_balance" id="closing_amount">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <input name="id" type="hidden" id="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                        
                        <button name="disable" type="submit" class="btn btn-success">Approve</button>
                        
                    </div>
                </form>
            </div>
            
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
    });

     $(document).ready(function(){
      $('#signature').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e){
          $('#showSignature').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
      })
    })
    
    $(document).ready(function () {
        $('#disableUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var closing_bal = button.data('closing_balance');
            var closing_balance = button.data('closing_bal');
            var user_id = button.data('user_id');
            var modal = $(this);
            modal.find('#closing_amount').val(closing_bal);
            modal.find('#closing_balance').val(closing_balance);
            modal.find('#user_id').val(user_id);
        });
    });

  </script>

@endsection