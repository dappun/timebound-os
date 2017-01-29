//var apiURl; // See main layout for this value

var dateNavRange; // Kalendae instance
var timerTableElem = '#timer-table';

new Vue({
    el: '#date-navigator',
    data: { 
        isOpenCalendars: false,
        minDate: "2016-09-27",
        maxDate: "2016-10-01",
        datesSelectable: [
            { name: "Today", value: 'today', rem: 'days' },
            { name: "This Week", value: 'this_week', rem: 'weeks' },
            { name: "This Month", value: 'this_month', rem: 'months' },
            { name: "This Year", value: 'this_year', rem: 'years' },
            { name: "Yesterday", value: 'yesterday', rem: 'days' }, 
            { name: "Last Week", value: 'last_week', rem: 'weeks' },
            { name: "Last Month", value: 'last_month', rem: 'months' },
            { name: "Last Year", value: 'last_year', rem: 'years' }
        ],
        dateRangeIndex: 0,
        currentRangeIndex: 1, // pertains to this_week
        currentRange: { name: "This Week", value: 'this_week', rem: 'weeks' }, // this_week as default
        rangeLabel: ""
    },
    
    ready: function(){
        
        this.currentRangeIndex = 2;
    },

    created: function(){

        // create instance of Kalendae.js
        dateNavRange = new Kalendae('date-nav-range', {
            months:2,
            mode: 'range'
        });

        var self = this;

        dateNavRange.subscribe('change', function( data ){
            self.changeRangeLabel( dateNavRange.getSelected(), true );
        });


        // console.log( Kalendae.moment().month(7).endOf('month').format("YYYY-MM-DD") );

        this.getDateRange = function(code, rangeIndex, rem){

            var dateNow = Kalendae.moment(); // Kalendae has built in moment library.
            var intMonth = dateNow.format('M');
            var intYear = dateNow.format('YYYY');
            var startDate;
            var endDate;
            
            // moment.js is 0 based so 9 is october. Subtract to 1 to compensate.
            // moment.js subtract is destructive. It mutates the original moment by subtracting time.
            // Use Kalendae.moment instead of dateNow

            switch (code) {
                case 'today':
                    startDate = dateNow;
                    endDate = dateNow;
                    break;

                case 'this_week':
                    startDate = Kalendae.moment([intYear, intMonth]).add(-1,"week").add(1, "day");
                    endDate = Kalendae.moment( startDate ).endOf('Week');
                    break;
                case 'this_month':
                    startDate =  Kalendae.moment([intYear, intMonth]).add(-1,"month"); 
                    endDate = Kalendae.moment().month(startDate.format('M')).endOf('Month');
                    break;
                case 'this_year':
                    startDate = Kalendae.moment();
                    endDate = Kalendae.moment();
                    break;

                case 'yesterday':
                    startDate = Kalendae.moment().subtract(1, "day");
                    endDate = Kalendae.moment().subtract(1, "day");
                    break;

                case 'last_week':
                    startDate = Kalendae.moment([intYear, intMonth]).add(-2,"week").add(1, "day");
                    endDate = Kalendae.moment( startDate ).endOf('Week');
                    break;

                case 'last_month':
                    startDate = Kalendae.moment([intYear, intMonth]).add(-2,"month");
                    endDate = Kalendae.moment( startDate ).endOf('Month');
                    break;

                case 'last_year':
                    startDate = Kalendae.moment().subtract(1, "year");
                    endDate = Kalendae.moment().subtract(1, "year");
                    break;
                
                default:
                    startDate = Kalendae.moment();
                    endDate = Kalendae.moment();
                    break;
            }
        
            if( typeof rangeIndex !== 'undefined' ){
                startDate = Kalendae.moment(startDate, 'YYYY-MM-DD').add( rangeIndex, rem );
                
                if( this.currentRange.rem == 'months'){
                    endDate = Kalendae.moment(endDate, 'YYYY-MM-DD').add( rangeIndex, rem).endOf('Month');
                }else{
                    endDate = Kalendae.moment(endDate, 'YYYY-MM-DD').add( rangeIndex, rem);
                }
                
            }

            // set currently selected dates
            dateNavRange.setSelected([startDate, endDate]);

            // force redraw contents
            dateNavRange.draw(); 
        },

        this.ajaxReportResult = function(){

            // range: ig. 2015-09-28 - 2015-09-28
            var dateRange = dateNavRange.getSelected().split(' - ');
            var startDate = dateRange[0];
            var endDate = dateRange[1];

            $.ajax({
                type: 'GET',
                url: apiURl + '/api/timer',
                data: {
                    start: startDate, // start date 
                    end: endDate // end date
                }   
            }).done(function( result ){

                $(timerTableElem).html( result.html );

                // re-render google chart
                if(result.total_entries > 0){
                    google.charts.load('current', {packages: ['corechart', 'bar']});
                    google.charts.setOnLoadCallback(loadChart);
                }
                
            });
        }

        this.changeRangeLabel = function( label, manual ){
            var dateRange = dateNavRange.getSelected().split(' - ');
            var startDate = dateRange[0];
            var endDate = dateRange[1];
            var formatting = "MMM DD";
            
            if( Kalendae.moment(startDate).format('YYYY') != Kalendae.moment().format('YYYY') ){
                formatting = "MMM DD YYYY";
            }

            if( this.currentRange.rem == 'years' ){
                this.rangeLabel = Kalendae.moment(startDate).format('YYYY');
            }

            this.rangeLabel = Kalendae.moment(startDate).format( formatting );

            if( typeof endDate !== 'undefined' ){
                this.rangeLabel = this.rangeLabel + ' - ' + Kalendae.moment(endDate).format( formatting );
            }
        }
    },

    watch: {
        'currentRangeIndex': function(){

            // next: set back to 1
            if(this.currentRangeIndex > this.datesSelectable.length){
                this.currentRangeIndex = 1;
            }

            // previous: set back to  max length
            if(this.currentRangeIndex < 1){
                this.currentRangeIndex = this.datesSelectable.length;
            }

            var range = this.datesSelectable[ this.currentRangeIndex - 1 ];
            
            // change date nav range
            this.getDateRange(range.value);
            
            // change current range data
            this.currentRange = range;

            // change button label
            this.rangeLabel = this.currentRange.name;

            // request reports related to dates selected
            this.ajaxReportResult();

            // reset range index so that nav arrows will set to zero initially.
            this.dateRangeIndex = 0;
        },

        'dateRangeIndex': function(){
            // change date nav range
            this.getDateRange( this.currentRange.value, this.dateRangeIndex, this.currentRange.rem );
            
            // change button label
            this.changeRangeLabel( dateNavRange.getSelected() );    

            // request reports related to dates selected
            this.ajaxReportResult();        
        }
    },

    methods: {
        toggleNavigator: function(event){
            this.isOpenCalendars = ! this.isOpenCalendars;

            if(! this.isOpenCalendars ){
                this.ajaxReportResult();
            }
        },

        selectDateRange: function( index ){
            this.currentRangeIndex = index;
        },

        prevDateRange: function(){
            this.dateRangeIndex--;
        },
        
        nextDateRange: function(){
            this.dateRangeIndex++;
        }
    }
})