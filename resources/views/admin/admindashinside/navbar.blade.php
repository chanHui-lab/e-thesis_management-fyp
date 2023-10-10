<section id="content">
    <nav>
    <i class='bx bx-menu' ></i>

    {{-- display e-thesis at the top --}}
    {{-- <a href="#" class="nav-link">E-Thesis</a> --}}

    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
        </div>
    </form>
    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>
    <a href="#" class="notification">
        <i class='bx bxs-bell' ></i>
        <span class="num">8</span>
    </a>
    <a href="#" class="profile">
        <img src='{{ asset('admindash/img/people.png') }}'>
    </a>
</nav>
</section>