<nav class="sidebar">
      <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
          STA<span>get</span>
        </a>
        <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="sidebar-body">
        <ul class="nav">
          <li class="nav-item nav-category">Main</li>
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
              <i class="link-icon fa fa-dashboard"></i>
              <span class="link-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item nav-category">Configuration</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#emails" role="button" aria-expanded="false" aria-controls="emails">
              <i class="link-icon" data-feather="calendar"></i>
              <span class="link-title">Year Plan</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="emails">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('admin.config.year_plan') }}" class="nav-link">Open Year</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.config.year_close') }}" class="nav-link">Close Year</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.config.liabilities') }}" class="nav-link">Liabilities</a>
                </li>
              </ul>
            </div>
          </li>
          
          
         
          <li class="nav-item nav-category">Pages</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#uiComponents" role="button" aria-expanded="false" aria-controls="uiComponents">
              <i class="link-icon" data-feather="users"></i>
              <span class="link-title">Users</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="uiComponents">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('admin.users') }}" class="nav-link">view</a>
                </li>
                <li class="nav-item">
                  <a href="" data-bs-toggle="modal" data-bs-target="#disableUserModal" class="nav-link">Disable user</a>
                </li>
                
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#advancedUI" role="button" aria-expanded="false" aria-controls="advancedUI">
              <i class="link-icon fa fa-credit-card-alt"></i>
              <span class="link-title">Payments</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="advancedUI">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('admin.user.deposit') }}" class="nav-link">Monthly deposit</a>
                </li>
                <li class="nav-item">
                   <a href="" data-bs-toggle="modal" data-bs-target="#singleDepositModal" class="nav-link">Single deposit</a>
                </li>
                
                
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#loans" role="button" aria-expanded="false" aria-controls="uiComponents">
              <i class="link-icon fa fa-university"></i>
              <span class="link-title">Loans</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="loans">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('loan.apply') }}" class="nav-link">Apply Loan</a>
                </li>
                <li class="nav-item">
                  <a href="" data-bs-toggle="modal" data-bs-target="#externalLoanModal" class="nav-link">Create external loan</a>
                </li>
                 <li class="nav-item">
                  <a href="{{ route('loan.external.approval') }}" class="nav-link">Approve external loans</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('loan.approve_loan') }}" class="nav-link">Approve Loans</a>
                </li>

                <li class="nav-item">
                  <a href="{{ route('loan.external.repayment') }}" class="nav-link">External loan repayment</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('loan.approve_loan') }}" class="nav-link">Close Loan</a>
                </li>
                
                <li class="nav-item">
                  <a href="{{ route('admin.loan.repayment') }}" class="nav-link">Repayment</a>
                </li>
                
              </ul>
            </div>
          </li>

          <li class="nav-item nav-category">Reports</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#reports" role="button" aria-expanded="false" aria-controls="uiComponents">
              <i class="link-icon" data-feather="users"></i>
              <span class="link-title">Users</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="reports">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="pages/ui-components/accordion.html" class="nav-link">Accordion</a>
                </li>
                <li class="nav-item">
                  <a href="pages/ui-components/alerts.html" class="nav-link">Alerts</a>
                </li>
                
              </ul>
            </div>
          </li>
        
          <li class="nav-item nav-category">Docs</li>
          <li class="nav-item">
            <a href="#" target="_blank" class="nav-link">
              <i class="link-icon" data-feather="hash"></i>
              <span class="link-title">Documentation</span>
            </a>
          </li>
          
        </ul>
      </div>
    </nav>


     <!-- Modal -->
    <div class="modal fade" id="singleDepositModal" tabindex="-1" aria-labelledby="singleDepositModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.user.single.deposit') }}">
                     @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveLoanModalLabel">Single user deposit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        
                       
                        <div class="form-group mb-3">
                            <label class="text-muted">Depositor</label>
                            <select required class="form-control form-select" name="id">
                              <option value="">Select</option>
                              @foreach(App\Models\User::where('status', 'active')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>

                              @endforeach
                            </select>
                           
                        </div>
                        <div class="form-group">
                           <label for="amount" class="text-muted">Amount</label>
                            <input required type="number" step="any" name="amount" value="" class="form-control">
                        </div>
                       
                    </div>
                    <div class="modal-footer">                       
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                        <button type="submit" class="btn btn-success">Save</button>
                        
                    </div>
                </form>
            </div>
            
        </div>
    </div> <!-- end of modal -->
    
     <!-- Modal -->
    <div class="modal fade" id="externalLoanModal" tabindex="-1" aria-labelledby="externalLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('loan.external') }}">
                     @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveLoanModalLabel">Create external loan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        
                       <p class="text-light mb-2 mt-2">Select the user that quarantees the borrower</p>
                        <div class="form-group mb-3">
                            <label>Quarantor</label>
                            <select class="form-control form-select" name="guarantor">
                              <option value="">Select</option>
                              @foreach(App\Models\User::where('status', 'active')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>

                              @endforeach
                            </select>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <input name="id" type="hidden" id="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                        <button type="submit" class="btn btn-success">Proceed to loan creation</button>
                        
                    </div>
                </form>
            </div>
            
        </div>
    </div> <!-- end of modal -->
    


     