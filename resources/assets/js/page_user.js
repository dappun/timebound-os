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
});