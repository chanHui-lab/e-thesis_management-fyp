<section id="sidebar">
    <a class="navbar-brand brand-logo" href="#">
        <img class="logo-image" src='{{ asset('admindash/img/logoethesis.png') }}' alt="logo"/>
    </a>
    {{-- <div class="user-panel mt-4" style="margin-left: 40px; margin-top: 20px; padding-bottom: 5px; border-bottom: 1px solid #4f5962; border-top: 1px solid #4f5962;"> --}}
    <div class="user-panel mt-3 mb-3 d-flex" style=" border-bottom: 1px solid #4f5962; border-top: 1px solid #4f5962;">
      {{-- <h6 class="info" style="margin-left: 40px; margin-top: 10px;" >Hello, {{ auth()->user()->name }}</h6> --}}
    </div>

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            {{-- <a href="{{ url('dashboardfake') }}" class="nav-link"> --}}
              <a href="{{ url('student/dashboard') }}" class="nav-link {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                <i class="nav-icon bx bxs-home"></i>
                <p>
                  Home
                </p>
              </a>
        </li>

          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ url('student/form/submission') }}" class="nav-link {{ Str::startsWith(url()->current(), url('student/form/submission')) ? 'active' : '' }}">
            {{-- <a href="{{ url('student/form/submission') }}" class="nav-link {{ request()->is('student/form/submission*') ? 'active' : '' }}"> --}}
              {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
              <i class="nav-icon bx bxs-file-doc"></i>
              <p>
                Form
                {{-- <i class="bx bxs-chevron-down" style="justify-content: space-between; position: absolute; right: 0;"></i> --}}
              </p>
            </a>

            {{-- <ul class="nav nav-treeview" style="background-color: #fafaeb">
              <li class="nav-item">
                <a href="{{ url('admin/formpage/admintemplateupload') }}" class="nav-link {{ request()->is('admin/template_page/admintemplateupload*') ? 'active' : '' }}">
                  <i class="bx bx-circle nav-icons"></i>
                  <p>Upload Form Template</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/formsubmissionpage/allpost') }}" class="nav-link">
                    <i class="bx bx-circle  nav-icons"></i>
                  <p>Create Form Submission Post</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="bx bx-circle  nav-icons"></i>
                  <p>All Form Submission</p>
                </a>
              </li>
            </ul> --}}
          </li>

          {{-- thesis section --}}
          <li class="nav-item">
            <a href="#" class="nav-link">
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
                <a href="{{ url('student/proposal/submission') }}" class="nav-link {{ request()->is('student/proposal/submission*') ? 'active' : '' }}" >
                  <i class="bx bx-circle nav-icons"></i>
                  <p>Proposal Section</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('student/thesis/submission') }}" class="nav-link {{ request()->is('student/thesis/submission*') ? 'active' : '' }}" >
                  <i class="bx bx-circle  nav-icons"></i>
                  <p>Thesis Section</p>
                </a>
              </li>
            </ul>
          </li>

        {{-- slides section --}}
        <li class="nav-item">
          <a href="{{ url('student/slide/submission') }}" class="nav-link {{ request()->is('student/slide/submission*') ? 'active' : '' }}" >
            {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
            <i class="nav-icon bx bxs-slideshow"></i>
            <p>
              Slides
            </p>
          </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('student/calendar') }}" class="nav-link {{ request()->is('admin/calendar*') ? 'active' : '' }}">
              <i class="nav-icon bx bxs-calendar"></i>
            <p>
              Presentation Schedule
            </p>
          </a>
      </li>

      <li class="nav-item">
        <a href="{{ url('student/thesis') }}" class="nav-link {{ request()->is('student/calendar*') ? 'active' : '' }}">
          <i class="nav-icon bx bx-library"></i>
          <p>
            Thesis Repository
          </p>
        </a>
      </li>

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
</section>