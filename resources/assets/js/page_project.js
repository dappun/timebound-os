import List from 'list.js';

var options = {
  	valueNames: ['name', 'status', 'client']
};

var list = new List('table-project', options);
list.filter(function(item) {
	return (item.values().status == 'active');
});

$('.filter-button-group .btn-link').on('click', function() {
	var filter = $(this).attr('data-filter');
	list.filter(function(item) {
		return (item.values().status == filter);
	});
});