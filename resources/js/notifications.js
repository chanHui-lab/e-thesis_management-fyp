// // resources/js/notifications.js

// document.addEventListener('DOMContentLoaded', function () {
//     var notificationIcon = document.getElementById('notification-icon');
//     var notificationDropdown = document.getElementById('notification-dropdown');

//     // Fetch and display notifications when the icon is clicked
//     notificationIcon.addEventListener('click', function () {
//         fetchNotifications();
//     });

//     // Function to fetch notifications and update the dropdown
//     function fetchNotifications() {
//         fetch('{{ url("/student/notifications") }}')
//             .then(response => response.json())
//             .then(data => {
//                 // Update the dropdown content with the fetched notifications
//                 notificationDropdown.innerHTML = data.html;
//             });
//     }
// });

// document.addEventListener('DOMContentLoaded', function () {
//     var notificationList = document.getElementById('notification-list');
//     var notificationHeader = document.getElementById('notification-header');
//     var notificationCount = document.getElementById('notification-count');
//     // Function to toggle visibility of the notification dropdown
//     function toggleNotificationDropdown() {
//         notificationDropdown.classList.toggle('show');
//     }
//     // Fetch and display notifications when the icon is clicked
//     document.querySelector('.nav-link').addEventListener('click', function () {
//         fetchNotifications();
//         toggleNotificationDropdown();

//     });

//     // Function to fetch notifications and update the dropdown
//     function fetchNotifications() {
//         // Make an AJAX request to get notifications
//         fetch('{{ url("student/notifications") }}')
//             .then(response => response.json())
//             .then(data => {
//                 // Update the dropdown content with the fetched notifications
//                 notificationList.innerHTML = '';
//                 data.notifications.forEach(notification => {
//                     var listItem = document.createElement('div');
//                     listItem.className = 'dropdown-item';
//                     listItem.innerHTML = `<i class="fas fa-envelope mr-2"></i>${notification.data.message}<span class="float-right text-muted text-sm">${notification.created_at}</span>`;
//                     notificationList.appendChild(listItem);
//                 });

//                 // Update the notification count and header
//                 notificationCount.innerText = data.notifications.length;
//                 notificationHeader.innerText = `${data.notifications.length} Notifications`;
//             });
//     }
// });
    document.addEventListener('DOMContentLoaded', function () {
        var notificationList = document.getElementById('notification-list');
        var notificationHeader = document.getElementById('notification-header');
        var notificationCount = document.getElementById('notification-count');

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

                        // Update the notification count and header
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
