const allSideMenu = document.querySelectorAll('#sidebar .nav-links.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});

// window.onscroll = function() {myFunction()};

//     var navbar = document.getElementById("navbar");
//     var sticky = navbar.offsetTop;

//     function myFunction() {
//         if (window.pageYOffset >= sticky) {
//             navbar.classList.add("sticky")
//         } else {
//             navbar.classList.remove("sticky");
//         }
//     }

// $("#sidebar > nav-links > li").click(function(e)){
// 	$(this).siblings.removeClass("active");
// 	$(this).toggleClass("active");
// }


// menu-highlight.js

// // Get the current URL path
// var currentPath = window.location.pathname;

// // Define a function to highlight the active submenu item
// function highlightActiveSubmenu(sectionId) {
//   var sectionSubMenu = document.getElementById(sectionId);
//   if (sectionSubMenu) {
//     var submenuItems = sectionSubMenu.querySelectorAll('li a');

//     // Iterate through the submenu items
//     submenuItems.forEach(function (item) {
//       var href = item.getAttribute('href');
//       if (href === currentPath) {
//         // Add the "active" class to the parent <li> element
//         item.parentElement.classList.add('active');
//       }
//     });
//   }
// }

// // Call the function for each submenu section you have
// highlightActiveSubmenu('forms-sub-menu');
// highlightActiveSubmenu('thesis-sub-menu');
// highlightActiveSubmenu('slides-sub-menu');

document.addEventListener('DOMContentLoaded', function () {
	// Get the current page's title
	var currentPageTitle = document.title;

	// Find all top-level menu items
	const allSideMenu = document.querySelectorAll('#sidebar .nav-links.top li a');

	allSideMenu.forEach(item => {
	  const li = item.parentElement;

	  item.addEventListener('click', function () {
		allSideMenu.forEach(i => {
		  i.parentElement.classList.remove('active');
		});
		li.classList.add('active');
	  });

	  // Check if the item's data-title matches the current page title
	  var itemTitle = item.getAttribute('data-title');
	  if (itemTitle === currentPageTitle) {
		// Add the "active" class to the parent <li> element
		li.classList.add('active');
	  }
	});
  });


// // Get the current URL path
// const currentPath = window.location.pathname;

// // Select all sidebar navigation links
// const allSideMenu = document.querySelectorAll('#sidebar .nav-links.top li a');

// // Loop through each sidebar navigation link
// allSideMenu.forEach(item => {
//     const li = item.parentElement;
//     const href = item.getAttribute('href');

//     // Check if the current URL path matches the link's href attribute
//     if (currentPath === href) {
//         li.classList.add('active'); // Add the "active" class to the current item
//     }

//     // Add a click event listener to handle manual clicks
//     item.addEventListener('click', function (event) {
//         event.preventDefault(); // Prevent the default link behavior
//         allSideMenu.forEach(i => {
//             i.parentElement.classList.remove('active');
//         });
//         li.classList.add('active');
//     });
// });



// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

// menuBar.addEventListener('click', function () {
// 	sidebar.classList.toggle('hide');
// })

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})


if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})

// sidebar
let arrow = document.querySelectorAll(".arrow");
console.log(arrow);

for(var i=0 ; i< arrow.length; i++){
	arrow[i].addEventListener("click", (e)=>{
		// console.log(e);
		let arrowParent = e.target.parentElement.parentElement;
		console.log(arrowParent);
		arrowParent.classList.toggle("showMenu");
	});
}

