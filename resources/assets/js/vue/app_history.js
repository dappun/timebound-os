var appHistory = new Vue({
    el: '#history',
    data: {
        busy: false,
        data: [],
        paging: {
            currentPage: 0
        },
        total: {
            seconds: 0,
            display: '',
            dayHeaders: []
        },
        counter: 0,
        last_date: null,
        loader_show: true,
    },
    methods: {
        loadMore: function() {
            this.busy = true;
            this.loader_show = true;

            var that = this;
            var url = core.url('/api/timer') + '?page=' + this.paging.currentPage;

            var currentYear = moment().format('YYYY');

            $.ajax({
                type: "GET",
                url: url,
                error: function (response, ajaxOptions, thrownError) {
                    that.loader_show = false;
                    if (response.status == 401) {
                        window.location = core.url('/');
                    } else {
                        console.log(response);
                        alert('Error ' + response.status + ': ' + response.statusText);
                    }
                },
            }).done(function(result) {
                var responseData = result.data;

                for (var id in responseData) {
                    that.addLineItem(responseData[id]);
                }

                that.paging.currentPage = result.currentPage;
                if (result.lastPage != result.currentPage) {
                    that.busy = false;
                }
                
                that.loader_show = false;
                that.counter++;
                that.displayTotalHours();
            });
        },
        addLineItem: function(item, append) {
            if (append == null) {
                append = true;
            }
            
            var currentYear = moment().format('YYYY');
            var fr = item.date.start;
            var to = item.date.end;

            var timeformat = 'hh:mm a';
            var tostring = to ? moment(to).format(timeformat) : 'ongoing';
            item.date.start = moment(fr).format(timeformat);
            item.date.end = tostring;
            
            var dayformat = '(ddd) MMM DD';
            if (moment(fr).format('YYYY') != currentYear) {
                dayformat = '(ddd) MMM DD, YYYY';
            }

            var ongoing = moment(fr).format(dayformat)

            // Add title at the end of the line
            if (append && ongoing != this.last_date) {
                var day = moment(fr).format(dayformat);
                this.data.push({description: day, line_item: false});
                var key = this.data.length;
            }

            // Add title at the beginning of the line
            if (!append && moment(this.data[0]).format(dayformat) != ongoing) {
                this.data.unshift({description: day, line_item: false});
            }

            item.line_item = true;
            item.warning = '';
            if (item.date.duration > core.setting.alert_warning) {
                item.warning = 'alert-danger';
            } else if (item.date.duration > core.setting.alert_notice) {
                item.warning = 'alert-warning';
            }

            item.edit_url = core.url('/timer/' + item.id + '/edit');
            item.copy_url = core.url('/timer/' + item.id + '/copy');

            // Add title at the full line item either at the end or second line
            if (append) {
                this.data.push(item);
                this.last_date = ongoing;
            } else {
                this.data.splice(1, 0, item)
            }
            
            this.computeTotalHours(item);
        },
        computeTotalHours: function(item) {
            if (item.date.duration) {
                this.total.seconds = this.total.seconds + item.date.duration;    
            }
        },
        displayTotalHours: function()
        {
            this.total.display = core.formatGMH(this.total.seconds);
        }
    }
});

window.appHistory = appHistory;