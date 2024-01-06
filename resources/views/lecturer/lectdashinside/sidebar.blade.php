<section id="sidebar">
    <a class="navbar-brand brand-logo" href="#">
        <img class="logodash-image" src='{{ asset('admindash/img/logoethesis.png') }}' alt="logo"/>
    </a>
    {{-- <div class="user-panel mt-4" style="margin-left: 40px; margin-top: 20px; padding-bottom: 5px; border-bottom: 1px solid #4f5962; border-top: 1px solid #4f5962;"> --}}
    <div class="user-panel mt-3 mb-3 d-flex" style=" border-bottom: 1px solid #4f5962; border-top: 1px solid #4f5962;">
      <h6 class="info" style="margin-left: 40px; margin-top: 10px;" >Hello, {{ auth()->user()->name }}</h6>
    </div>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            {{-- <a href="{{ url('dashboardfake') }}" class="nav-link"> --}}
              <a href="{{ url('lecturer/dashboard') }}" class="nav-link {{ request()->is('lecturer/dashboard*') ? 'active' : '' }}">
                <i class="nav-icon bx bxs-home"></i>
              <p>
                Home
              </p>
            </a>
        </li>

          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('lecturer/formpage/*') ? 'active' : '' }}" >
              {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
              <i class="nav-icon bx bxs-file-doc"></i>
              <p>
                Form
                {{-- <i class="right fas fa-angle-left"></i> --}}
                <i class="bx bxs-chevron-down" style="justify-content: space-between; position: absolute; right: 0;"></i>
              </p>
            </a>

            <ul class="nav nav-treeview" style="background-color: #fafaeb">
              <li class="nav-item">
                <a href="{{ url('lecturer/formpage/admintemplateupload') }}" class="nav-link {{ request()->is('lecturer/template_page/admintemplateupload*') ? 'active' : '' }}">
                  <i class="bx bx-circle nav-icons"></i>
                  <p>Form Template</p>
                </a>
              </li>
              <li class="nav-item">
                {{-- <a href="{{ url('admin/formsubmissionpage/allpost') }}" class="nav-link {{ request()->is('admin/template_page/admintemplateupload*') ? 'active' : '' }}"> --}}
                <a href="{{ url('lecturer/formsubmissionpage/allpost') }}" class="nav-link">
                    <i class="bx bx-circle  nav-icons"></i>
                  <p>Form Submission Post</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="bx bx-circle  nav-icons"></i>
                  <p>All Form Submission</p>
                </a>
              </li> --}}
            </ul>
          </li>

          {{-- thesis section --}}
          <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('lecturer/thesissubmissionpage/*') ? 'active' : '' }}" >
              {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
              <i class="nav-icon bx bxs-file"></i>
              <p>
                Report
                {{-- <i class="right fas fa-angle-left"></i> --}}
                <i class="bx bxs-chevron-down" style="justify-content: space-between; position: absolute; right: 0;"></i>
              </p>
            </a>

            <ul class="nav nav-treeview" style="background-color: #fafaeb">
              <li class="nav-item">
                <a href="{{ url('lecturer/proposalsubmissionpage/admintemplatelist') }}" class="nav-link {{ request()->is('lecturer/proposalsubmissionpage/admintemplatelist*') ? 'active' : '' }}" >
                  <i class="bx bx-circle nav-icons"></i>
                  <p>Proposal Template</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('lecturer/proposalsubmissionpage/allpost') }}" class="nav-link {{ request()->is('lecturer/proposalsubmissionpage/allpost*') ? 'active' : '' }}" >
                {{-- <a href="lecturer/proposalsubmissionpage/allpost" class="nav-link"> --}}
                  <i class="bx bx-circle  nav-icons"></i>
                  <p>Proposal Submission Post</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('lecturer/thesissubmissionpage/admintemplatelist') }}" class="nav-link {{ request()->is('lecturer/thesispage/admintemplatelist*') ? 'active' : '' }}" >
                  <i class="bx bx-circle  nav-icons"></i>
                  <p>Thesis Template</p>
                </a>
              </li>
              <li class="nav-item">
                  <a href="{{ url('lecturer/thesissubmissionpage/allpost') }}" class="nav-link {{ request()->is('lecturer/thesissubmissionpage/allpost*') ? 'active' : '' }}" >
                  <i class="bx bx-circle  nav-icons"></i>
                  <p>Thesis Submission Post</p>
                </a>
              </li>
            </ul>
          </li>

        {{-- slides section --}}
        <li class="nav-item">
          <a href="{{ url('lecturer/slidesubmissionpage') }}" class="nav-link {{ request()->is('lecturer/slidesubmissionpage*') ? 'active' : '' }}" >
            {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
            <i class="nav-icon bx bxs-slideshow"></i>
            <p>
              Slides
              {{-- <i class="right fas fa-angle-left"></i> --}}
              <i class="bx bxs-chevron-down" style="justify-content: space-between; position: absolute; right: 0;"></i>
            </p>
          </a>

          <ul class="nav nav-treeview" style="background-color: #fafaeb">
            <li class="nav-item">
              <a href="{{ url('lecturer/slidessubmissionpage/admintemplatelist') }}" class="nav-link {{ request()->is('lecturer/slidessubmissionpage/admintemplatelist*') ? 'active' : '' }}" >
                <i class="bx bx-circle nav-icons"></i>
                <p>Slides Template</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('lecturer/slidessubmissionpage/allpost') }}" class="nav-link {{ request()->is('lecturer/slidessubmissionpage/allpost*') ? 'active' : '' }}" >
                <i class="bx bx-circle  nav-icons"></i>
                <p>Slides Submission Post</p>
              </a>
            </li>

          </ul>
        </li>

        <li class="nav-item">
            <a href="{{ url('lecturer/calendar') }}" class="nav-link {{ request()->is('lecturer/calendar*') ? 'active' : '' }}">
              <i class="nav-icon bx bxs-calendar"></i>
            <p>
              Presentation Schedule
            </p>
          </a>
      </li>

      <li class="nav-item">
        <a href="{{ url('lecturer/thesis') }}" class="nav-link {{ request()->is('lecturer/thesis*') ? 'active' : '' }}">
          <i class="nav-icon bx bx-library"></i>
          <p>
            Thesis Repository
          </p>
        </a>
      </li>


      <li class="nav-item">
        <a href="{{ url('lecturer/advisor-assignment') }}" class="nav-link {{ request()->is('lecturer/advisor-assignment*') ? 'active' : '' }}">
          <i class="nav-icon bx bx-id-card"></i>
        <p>
          Supervisor-Student List
        </p>
      </a>
    </li>

    {{-- <li class="nav-item">
        <a href="{{ url('admin/online_eva_form') }}" class="nav-link {{ request()->is('admin/online_eva_form*') ? 'active' : '' }}">
          <i class="nav-icon bx bxs-home"></i>
        <p>
          Online Evaluation Form
        </p>
      </a>
  </li> --}}

      <br>
      <br>
      <li class="nav-item">
          <a class="logout nav-link" href="{{route('logout')}}"
              onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
              <i class='nav-icon bx bxs-log-out-circle'></i>{{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
      </li>


        </ul>
    </nav>
</section>