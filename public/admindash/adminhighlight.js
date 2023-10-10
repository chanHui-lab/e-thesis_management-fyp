// // sidebar-highlight.js

document.addEventListener("DOMContentLoaded", function () {
    // Get the current URL
    var currentUrl = window.location.href;

    // Remove the "active" class from all menu items
    var allMenuItems = document.querySelectorAll("#sidebar .nav-link");
    allMenuItems.forEach(function (menuItem) {
        menuItem.classList.remove("active");
    });

    // Find the "Home" menu item and add the "active" class if it's the current URL
    var homeMenuItem = document.querySelector("#sidebar a[href='" + currentUrl + "']");
    if (homeMenuItem) {
        homeMenuItem.classList.add("active");
    }

    // Find the "Form" menu item and add the "active" class if any of its child items are active
    var formMenuItems = document.querySelectorAll("#sidebar .nav-item.menu-open");
    formMenuItems.forEach(function (menuItem) {
        var childLinks = menuItem.querySelectorAll("ul.nav.nav-treeview a.nav-link");
        for (var i = 0; i < childLinks.length; i++) {
            if (currentUrl.includes(childLinks[i].getAttribute("href"))) {
                menuItem.querySelector("a.nav-link").classList.add("active");
                break;
            }
        }
    });
});

// sidebar-highlight.js

// document.addEventListener("DOMContentLoaded", function () {
//     // Get the current URL
//     var currentUrl = window.location.href;

//     // Remove the "active" class from all menu items
//     var allMenuItems = document.querySelectorAll("#sidebar .nav-link");
//     allMenuItems.forEach(function (menuItem) {
//         menuItem.classList.remove("active");
//     });

//     // Find the "Home" menu item and add the "active" class if it's the current URL
//     var homeMenuItem = document.querySelector("#sidebar a[href='" + currentUrl + "']");
//     if (homeMenuItem) {
//         homeMenuItem.classList.add("active");
//     }

//     // Find the "Form" and "Proposal" menu items and add the "active" class if any of their child items are active
//     var sectionsWithSubmenus = document.querySelectorAll("#sidebar .nav-item.menu-open");
//     sectionsWithSubmenus.forEach(function (sectionItem) {
//         var submenuLinks = sectionItem.querySelectorAll("ul.nav.nav-treeview a.nav-link");
//         for (var i = 0; i < submenuLinks.length; i++) {
//             if (currentUrl.includes(submenuLinks[i].getAttribute("href"))) {
//                 sectionItem.querySelector("a.nav-link").classList.add("active");
//                 break;
//             }
//         }
//     });
// });

// document.addEventListener("DOMContentLoaded", function () {
//     // Get the current URL
//     var currentUrl = window.location.pathname;



//     // var allMenuItems = document.querySelectorAll("#sidebar .nav-link");
//     // allMenuItems.forEach(function (menuItem) {
//     //     menuItem.classList.remove("active");
//     // });
//     $('.nav-link').removeClass('active');


//     // Loop through each menu item with the class "nav-link"
//     $('.nav-link').each(function() {
//         // Get the link's href attribute
//         var linkHref = $(this).attr('href');

//         // Check if the current URL contains the link's href
//         if (currentUrl.indexOf(linkHref) !== -1) {
//             // Add the "active" class to the menu item
//             $(this).addClass('active');
//         }
//     });
// });

