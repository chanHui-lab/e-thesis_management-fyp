<section id="content">
    <nav id="navbar" class="navbar">
        <i class='bx bx-menu' ></i>

    {{-- display e-thesis at the top --}}
    {{-- <a href="#" class="nav-link">E-Thesis</a> --}}

    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search.csdfdsfsdf..">
            <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
        </div>
    </form>
    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>

    {{-- <a href="{{ url('student/notifications') }}">
        <i class='bx bxs-bell' ><span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span></i>
    </a> --}}

    {{-- <li class="nav-item dropdown show">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
          <i class="bx bxs-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right show" style="left: inherit; right: 0px;">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> --}}

      <li class="nav-item dropdown show">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
            <i class="bx bxs-bell"></i>
            <span class="badge badge-warning navbar-badge" id="notification-count">{{ auth()->user()->unreadNotifications->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right show" style=" left: inherit; right: 0px;z-index:9999;">
            <span class="dropdown-item dropdown-header" id="notification-header">{{ auth()->user()->unreadNotifications->count() }} Notifications</span>
            <div class="dropdown-divider"></div>
            <ul id="notification-list">
            </ul>
            <div class="dropdown-divider"></div>
            <a href="{{ url('student/notifications') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
    </li>

    {{-- <div class="notification-container">
        <a href="#" id="notification-icon">
            <i class='bx bxs-bell'><span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span></i>
        </a>
        <div class="dropdown" id="notification-dropdown">
        </div>
    </div> --}}

    <a href="#" class="profile">
        <img src='{{ asset('admindash/img/people.png') }}'>
    </a>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var notificationList = document.getElementById('notification-list');
    var notificationHeader = document.getElementById('notification-header');
    var notificationCount = document.getElementById('notification-count');
    var notificationIcon = document.querySelector('.nav-link');
    var dropdownMenu = document.querySelector('.dropdown-menu');

    // Function to toggle the visibility of the dropdown
//     function toggleDropdown() {
//         dropdownMenu.classList.toggle('show');
//     }
// // Initially hide the dropdown
dropdownMenu.classList.remove('show');
    // Fetch and display notifications when the icon is clicked
    notificationIcon.addEventListener('click', function () {
        fetchNotifications();
        // toggleDropdown();
    });

    // Function to fetch notifications and update the dropdown
    function fetchNotifications() {
        document.querySelector('.dropdown-menu').classList.toggle('show');

        // Make an AJAX request to get notifications
        fetch('{{ url("student/notifications") }}')
            .then(response => response.json())
            .then(data => {
                // Update the dropdown content with the fetched notifications
                notificationList.innerHTML = ''; // Clear existing content
                if (data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        var listItem = document.createElement('li');
                        listItem.className = 'dropdown-item';
                        console.log(notification.data.link);

                        // listItem.innerHTML = `${notification.data.message}<span class="float-right text-muted text-sm">${notification.created_at}</span>`;
                        // Display message and "Go to" button
                        listItem.innerHTML = `
                        <i class="fas fa-envelope mr-2"></i>${notification.data.message}
                            <a href="${notification.data.link}" class="btn btn-primary btn-sm float-right" onclick="handleGoToButtonClick('${notification.id}')">View</a>
                        `;
                        notificationList.appendChild(listItem);
                    });

                    // Update other elements
                    notificationCount.innerText = data.notifications.length;
                    notificationHeader.innerText = `${data.notifications.length} Notifications`;
                } else {
                    // Handle case where there are no notifications
                    notificationList.innerHTML = '<li class="dropdown-item">No new notifications</li>';
                    notificationCount.innerText = '0';
                    notificationHeader.innerText = 'No Notifications';
                }
            });
    }
});
    // Function to handle "Go to" button click
    function handleGoToButtonClick(notificationId) {
        // Make an AJAX request to mark the notification as read
        fetch('{{ url("student/mark-notification-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ notificationId: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            // Remove the corresponding list item from the dropdown
            var listItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (listItem) {
                listItem.remove();
            }

            // Update the badge count and header
            notificationIcon.querySelector('.badge').innerText = data.unreadCount;
            notificationCount.innerText = data.unreadCount;
            notificationHeader.innerText = `${data.unreadCount} Notifications`;
        });
    }
</script>

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    var notificationList = document.getElementById('notification-list');
    var notificationHeader = document.getElementById('notification-header');
    var notificationCount = document.getElementById('notification-count');
    var notificationIcon = document.querySelector('#notification-icon i');

    // Fetch and display notifications when the icon is clicked
    document.querySelector('.nav-link').addEventListener('click', function () {
        fetchNotifications();
    });

    // Function to fetch notifications and update the dropdown
    function fetchNotifications() {
        // Make an AJAX request to get notifications
        fetch('{{ url("student/notifications") }}')
            .then(response => response.json())
            .then(data => {
                // Update the dropdown content with the fetched notifications
                notificationList.innerHTML = ''; // Clear existing content
                console.log(data);

                if (data.notifications.length > 0) {
                    console.log(data.notifications);

                    data.notifications.forEach(notification => {
                        var listItem = document.createElement('li');
                        listItem.className = 'dropdown-item';
                        listItem.innerHTML = `<i class="fas fa-envelope mr-2"></i>${notification.data.message}<span class="float-right text-muted text-sm">${notification.created_at}</span>`;
                        notificationList.appendChild(listItem);
                        console.log(notificationList);
                    });

                    notificationIcon.querySelector('.badge').innerText = data.notifications.length;
                    notificationCount.innerText = data.notifications.length;
                    notificationHeader.innerText = `${data.notifications.length} Notifications`;
                } else {
                    // If there are no notifications, display a message
                    notificationList.innerHTML = '<li class="dropdown-item">No new notifications</li>';
                    notificationCount.innerText = '0';
                    notificationHeader.innerText = 'No Notifications';
                }
            });
    }
});
</script> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var notificationIcon = document.getElementById('notification-icon');
        var notificationDropdown = document.getElementById('notification-dropdown');

        // Fetch and display notifications when the icon is clicked
        notificationIcon.addEventListener('click', function () {
            fetchNotifications();
        });

        // Function to fetch notifications and update the dropdown
        function fetchNotifications() {
            // Make an AJAX request to get notifications
            // You can use libraries like Axios or jQuery.ajax for this

            // For simplicity, let's assume you have a route to fetch notifications
            fetch('{{ url("student/notifications") }}')
                .then(response => response.json())
                .then(data => {
                    // Update the dropdown content with the fetched notifications
                    notificationDropdown.innerHTML = data.html;

                    // Mark notifications as read after displaying them
                    markNotificationsAsRead();
                });
        }

        // Function to mark notifications as read
        function markNotificationsAsRead() {
            // Make an AJAX request to mark notifications as read
            // You can use libraries like Axios or jQuery.ajax for this

            // For simplicity, let's assume you have a route to mark notifications as read
            fetch('{{ url("student/mark-notifications-as-read") }}', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    // Update the badge count to indicate no unread notifications
                    document.querySelector('#notification-icon .badge').innerText = 0;
                });
        }
    });

    </script> --}}
{{-- </section> --}}
