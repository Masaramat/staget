@extends('admin.admin_dashboard')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <div class="page-content">     

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
          </div>
          
        </div>
        <div class="row">
         
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Monthly loan repayments</h6>
                  
                </div>
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">#</th>
                        <th class="pt-0">Name</th>
                        <th class="pt-0">Loan Balance</th>
                        <th class="pt-0">Loan year</th>
                        <th class="pt-0">Repayment</th>
                        <th class="pt-0">Repayment type</th>
                        <th class="pt-0">Options</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      @foreach($loans as $index => $loan) 

                      <tr>
                        <td class="border-bottom">{{ $xy++ }}</td>
                        <td class="border-bottom">{{ $loan->applicant_name }}</td>
                        <td class="border-bottom">{{ $loan->balance }}</td>
                        <td class="border-bottom">{{ $loan->year }}</td>
                        <td class="border-bottom">{{ $loan->installments }}</td>
                        <td class="border-bottom">{{ $loan->repayment_type }}</td>
                        <td class="border-bottom"> 
                            <form method="post" action="{{ route('loan.remove_loan') }}">
                              @csrf
                              <input type="hidden" name="loan" value="{{ $index }}">
                              <input type="hidden" name="id" value="{{ $loan->id }}">
                              <button type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                      </tr>
                      
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div> 
              <div>
                <a class="btn btn-success m-3" href="{{ route('loan.external.complete_repayment') }}">Complete loan repayment</a>
              </div>
            </div>
          </div>
        </div> <!-- row -->

	</div>
    
@endsection