@extends('admin.admin_dashboard')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
          </div>
          <div class="d-flex align-items-center flex-wrap text-nowrap">
            
          </div>
        </div>

       
        

        <div class="row">
         
          <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Pending loan applications</h6>
                  
                </div>
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">#</th>
                        <th class="pt-0">Applicant name</th>
                        <th class="pt-0">Guarantor</th>
                        <th class="pt-0">Repayment type</th>
                        <th class="pt-0">amount</th>
                        <th class="pt-0">Tenor</th>
                        <th class="pt-0">Options</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      @foreach($loans as $loan) 

                      <tr>
                        <td class="border-bottom">{{ $xy++ }}</td>
                        <td class="border-bottom">{{ $loan->applicant_name }}</td>
                        <td class="border-bottom">{{ $loan->name }}</td>
                        <td class="border-bottom">{{ $loan->repayment_type. ' '.' payment' }}</td>
                        <td class="border-bottom">{{ $loan->amount_applied }}</td>
                        <td class="border-bottom">{{ strval($loan->tenor) . ' ' . $loan->tenor_type }}</td>
                        <td class="border-bottom"> 
                            <button data-id="{{ $loan->id }}" data-amount="{{$loan->amount_applied}}"
                            data-tenor="{{$loan->tenor}}" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveLoanModal">
                                Approve
                            </button>
                        </td>
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
    <!-- Modal -->
    <div class="modal fade" id="approveLoanModal" tabindex="-1" aria-labelledby="approveLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('loan.external.approve') }}">
                     @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveLoanModalLabel">Approve Loan Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">                        
                       
                        <div class="form-group mb-3">
                            <label>Approval amount</label>
                            <input class="form-control" id="amount_approved" name="amount_approved">
                        </div>
                        <div class="row form-group mb-3">
                            <label>Tenor Approved</label>
                            <div class="col-4">
                                <input class="form-control" id="tenor_approved" name="tenor_approved">
                            </div>
                            <div class="col-8">
                                <p>Months</p>
                            </div> 
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <input name="id" type="hidden" id="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button name="deny" type="submit" class="btn btn-danger">Deny</button>
                        <button name="approve" type="submit" class="btn btn-success">Approve</button>
                        
                    </div>
                </form>
            </div>
            
        </div>
    </div>

    <script>
    $(document).ready(function () {
        $('#approveLoanModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var amount = button.data('amount');
            var tenor = button.data('tenor');
            var id = button.data('id');
            var modal = $(this);
            modal.find('#amount_approved').val(amount);
            modal.find('#tenor_approved').val(tenor);
            modal.find('#id').val(id);
        });
    });
</script>




@endsection