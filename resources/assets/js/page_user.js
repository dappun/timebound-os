// import '../../../node_modules/list.js/dist/list.js';
import List from 'list.js';

var options = {
  	valueNames: ['name', 'role', 'status']
};

var userlist = new List('table-team', options);
userlist.filter(function(item) {
	return (item.values().status == 'active');
});

$('.filter-button-group .btn-link').on('click', function() {
	var filter = $(this).attr('data-filter');
	var type = $(this).attr('data-type');

	userlist.filter(function(item) {
		if (type == 'role') {
			return (item.values().role == filter);
		} else {
			return (item.values().status == filter);
		}
	});
})

// var iso = $('.grid').isotope({
//   // options...
//   itemSelector: '.element-item',
//   layoutMode: 'fitRows',
//   filter: '.active'
// });


// // filter functions
// var filterFns = {
//   // show if number is greater than 50
//   numberGreaterThan50: function( itemElem ) {
//     var number = itemElem.querySelector('.number').textContent;
//     return parseInt( number, 10 ) > 50;
//   },
//   // show if name ends with -ium
//   ium: function( itemElem ) {
//     var name = itemElem.querySelector('.name').textContent;
//     return name.match( /ium$/ );
//   }
// };

// var filtersElem = document.querySelector('.filter-button-group');
// filtersElem.addEventListener( 'click', function( event ) {
//   // only work with buttons
//   // if ( !matchesSelector( event.target, 'button' ) ) {
//   //   return;
//   // }

//   var filterValue = event.target.getAttribute('data-filter');
//   // use matching filter function
//   filterValue = filterFns[ filterValue ] || filterValue;
//   iso.arrange({ filter: filterValue });
// });

// // change is-checked class on buttons
// $('.button-group .btn').on('click', function(e) {
//     $('.button-group .is-checked').removeClass('is-checked');
//     $(this).addClass('is-checked');
// });