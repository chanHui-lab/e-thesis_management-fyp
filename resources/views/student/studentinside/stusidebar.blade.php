<section id="sidebar">
    <a class="navbar-brand brand-logo" href="#">
         <img class="logo-image" src='{{ asset('admindash/img/logoethesis.png') }}' alt="logo"/>
     </a>
     <div class="user-panel mt-4" style="margin-left: 40px; margin-top: 20px; padding-bottom: 5px; border-bottom: 1px solid #e6e6e6; border-top: 1px solid #e6e6e6;">
        {{-- <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div> --}}
        {{-- <div class="info"> --}}
          {{-- <a href="#" class="d-block">Alexander Pierce</a> --}}
          <h6 style="margin-top: 10px;" >Hello, {{ auth()->user()->name }}</h6>
        {{-- </div> --}}
    </div>
     <ul class="nav-links top">

         <li class="nav-item active">
             {{-- <a href="{{ url('student/studentpage/studenthome') }}"> --}}
            {{-- <a href="{{ url('admin/admin/adminlist') }}"> --}}
                <a href="#">
                 <i class='bx bxs-home' style='color:#422f01'></i>
                 <span class="text">Home</span>
             </a>
         </li>

         <li class="nav-item">
             {{-- <div class ="iocn-link"> --}}
                 <a href="#">
                     <i class='bx bxs-file-doc' style='color:#422f01'  ></i>
                     <span class="text">Forms</span>
                 </a>
             {{-- </div> --}}

         </li>

         <li class="nav-item">
             {{-- <div class ="iocn-link"> --}}
                 <a href="#">
                     <i class='bx bxs-file' style='color:#422f01'></i>
                     <span class="text">Proposal Submission</span>
                 </a>
             {{-- </div> --}}
         </li>

         <li class="nav-item">
             {{-- <div class ="iocn-link"> --}}
                 <a href="#">
                     <i class='bx bxs-slideshow' style='color:#422f01'></i>
                     <span class="text">Thesis Submission </span>
                 </a>
             {{-- </div> --}}
         </li>

         <li class="nav-item">
             <a href="#">
                 <i class="bx bxs-calendar" style='color:#422f01'></i>
                 <span class="text">Viva Schedule</span>
             </a>
         </li>

         <li class="nav-item">
             <a href="#">
                 <i class='bx bx-library' ></i>
                 <span class="text">Thesis Library</span>
             </a>
         </li>
     </ul>


     <ul class="nav-links">

         <li>

             {{-- copy from app.blade --}}
             <a class="logout" href="{{route('logout')}}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
             <i class='bx bxs-log-out-circle' ></i>{{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>

         </li>
     </ul>
 </section>