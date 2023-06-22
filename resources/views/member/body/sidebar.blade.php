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
          <li class="nav-item nav-category">Pages</li>          

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


     
    
    
    


     