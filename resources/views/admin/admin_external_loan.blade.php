@extends('admin.admin_dashboard')

@section('admin')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

	<div class="page-content">
        <div class="row profile-body">
          <!-- left wrapper start -->
          <div class="d-none d-md-block col-md-6 col-xl-6 left-wrapper">
            <div class="card rounded">
              <div class="card-body">           

                <form id="regForm" class="forms-sample" method="POST" action="{{ route('loan.external.apply') }}" enctype="multipart/form-data">
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
                
                <div class="form-group mt-2">
                    <label for="applicant_name" class="form-label">Applicant Name</label>
                    <input oninput="this.className = 'form-control'" type="text" name="applicant_name" class="form-control" id="applicant_name" autocomplete="off" value="">
                </div>
                <div class="form-group mt-2">
                    <label for="applicant_bvn" class="form-label">Applicant BVN</label>
                    <input oninput="this.className = 'form-control'" type="text" name="applicant_bvn" class="form-control" id="applicant_bvn" autocomplete="off" value="">
                </div>
                <div class="form-group mt-2">
                    <label for="guarantor_name" class="form-label">Guarantor Name</label>
                    <input disabled oninput="this.className = 'form-control'" type="text" name="guarantor_name" class="form-control" id="guarantor_name" autocomplete="off" value="{{$account->name}}">
                    <input type="hidden" name="guarantor_id" value="{{$account->user_id}}">
                </div>
                
                <div class="form-group mt-2">
                    <label for="amount_applied" class="form-label">Application amount</label>
                    <input oninput="this.className = 'form-control'" type="text" name="amount_applied" class="form-control" id="amount_applied" autocomplete="off" value="">
                </div>
                <div class="row form-group mt-2">
                    <div class="row ">
                        <div class="col-md-2 form-group">
                            <label for="tenor" class="form-label">Tenor</label>
                            <input oninput="this.className = 'form-control'" type="text" name="tenor" class="form-control" id="tenor" autocomplete="off" value="">
                        </div>
                        <div class="col-md-10 form-group">
                            <label for="tenor_type" class="form-label">Tenor Type</label>
                            <select disabled   name="tenor_type" class="form-control text-dark form-select" id="tenor_type" >
                                <option selected value="months">Months</option>
                            </select>
                        </div>
                    </div>
                    

                </div>
                
                <div class="form-group mt-2">
                    <label for="repayment_type" class="form-label">Repayment type</label>
                    <select oninput="this.className = 'form-control form-select'" type="text" name="repayment_type" class="form-control form-select" id="repayment_type" autocomplete="off" value="{{ $user->repayment_type ?? '' }}">
                        <option value="flat upfront interest">Flat upfront interest</option>                       
                        <option value="flat">Flat</option>
                        <option value="balloon upfront interest">Balloon upfront interest</option>
                        <option value="balloon">Balloon</option>
                    </select>
                </div>
                <div class="d-flex mt-3  justify-content-end">
                    <button type="submit" class="btn btn-lg btn-primary  flex flex-end">Apply Loan</button>
                </div>
                
                
                </form>
                 
                </div>
              
            </div>
          </div>
          <!-- left wrapper end -->
          <!-- middle wrapper start -->
          <div class="col-md-6 col-xl-6 middle-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
               <div class="card">
              		<div class="card-body">
						<h6 class="card-title">Loan summary</h6>
                         <div>
                            <ul>
                                <li><p class="mb-2">Total savings: {{$account->total_savings}}</p></li>
                                <li><p class="mb-2">Amount applicable: {{$account->total_savings * 3}}</p></li>
                                <li><p class="mb-2">Interest rate: <span id="rate">{{$year_plan->interest_rate}}</span><span>%</span></p></li>
                            </ul>
                            
                            
                        </div>

                        <h5 class="card-title mt-4">Repayment summary</h5>
                        <div>
                            <ul>
                                <li><p class="mb-2">Interest (Upfront): <span id="interest"></span></p></li>
                                <li><p class="mb-2" id="rep">Monthy repayment: <span id="monthly_payment"></span></p></li>
                            </ul>
                            
                        </div>
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
      $('#tenor').keyup(function(e){
        let amount = $('#amount_applied').val();
        let tenor = $('#tenor').val();
        var repayment = $('#repayment_type').find(":selected").val();
        let rates = $('#rate').html();
        let interest = amount * (rates/100) * tenor;
        $('#interest').html(interest);
        if(repayment === 'flat'){
            let repayment_type = amount/tenor;
            $('#monthly_payment').html(repayment_type);
        }else if(repayment === 'balloon'){
            $('#rep').html("Balloon payment of "+ amount+"at the end of tenor");
        }          
      });

      $('#amount_applied').keyup(function(e){
        let amount = $('#amount_applied').val();
        let tenor = $('#tenor').val();
        var repayment = $('#repayment_type').find(":selected").val();
        let rates = $('#rate').html();
        let interest = amount * (rates/100) * tenor;
        $('#interest').html(interest);
        if(repayment === 'flat'){
            let repayment_type = amount/tenor;
            $('#monthly_payment').html(repayment_type);
        }else if(repayment === 'balloon'){
            $('#rep').html("Balloon payment of "+ amount+"at the end of tenor");
        }       
      });
      $('#repayment_type').change(function(e){
        let amount = $('#amount_applied').val();
        let tenor = $('#tenor').val();
        var repayment = $('#repayment_type').find(":selected").val();
        let rates = $('#rate').html();
        let interest = amount * (rates/100) * tenor;
        $('#interest').html(interest);
        if(repayment === 'flat'){
            let repayment_type = amount/tenor;
            $('#monthly_payment').html(repayment_type);
        }else if(repayment === 'balloon'){
            $('#rep').html("Balloon payment of "+ amount+" at the end of tenor");
        }         
      });
    });
  </script>

@endsection