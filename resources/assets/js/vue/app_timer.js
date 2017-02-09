var appTimer = new Vue({
    el: '#timer',
    data: {
        sw: {
            h: '00',
            m: '00',
            s: '00'
        },
        ts: {
            id: '',
            description : '',
            project_id: '',
            ticket: '',
            projects: [],
            start: ''
        },
        busy: false,
        btn: {
            text: 'Start',
            active: false,
        },
        timer_active: false
    },
    methods: {
        onLoad: function() {
            this.$http.get(core.url('/api/timer/ongoing'), this.ts).then((response) => {
                this.ts = response.body;
                if (this.ts.id) {
                    this.sw.h = this.ts.duration_raw.h;
                    this.sw.m = this.ts.duration_raw.m;
                    this.sw.s = this.ts.duration_raw.s;
                    this.timer(true);
                }
            }, (response) => {
                if (response.status == 401) {
                    window.location = core.url('/');
                } else {
                    alert('Error ' + response.status + ': ' + response.statusText)
                }
            });
        },
        onTimerSubmit: function() {
            // Validate All returns a promise and provides the validation result.                
            this.$validator.validateAll().then(success => {
                if (!success) return;

                var that = this;
                var formdata = $('#timer-form').serialize();
                console.log(formdata)
                
                $.ajax({
                    type: "POST",
                    url: core.url('/api/timer/stopwatch'),
                    data: formdata,
                }).done(function(result) {
                    if (result.date.end) {
                        that.timer(false);
                        appHistory.addLineItem(result, false);
                    } else {
                        that.timer(true);
                    }
                });
            }); 
        },
        timer: function(status) {
            if (!status) {
                this.stopTimer();
            } else {
                this.startTimer();
            }
        },
        startTimer: function()
        {
            this.btn.active = true;
            this.btn.text = 'Stop';

            if (this.ts.start) {
                var browserTz = moment.tz.guess();
                var localTime = moment.tz(this.ts.start, core.userTimezone);
                // localTime = this.ts.start;
                
                $.APP.resumeTimer('sw', moment(localTime).format());
            } else {
                $.APP.startTimer('sw');    
            }
        },
        stopTimer: function()
        {
            this.btn.active = false;
            this.btn.text = 'Start';

            $.APP.stopTimer('sw');

            // set to default
            this.sw.h = '00';
            this.sw.m = '00';
            this.sw.s = '00';

            this.ts.id = '';
            this.ts.description = '';
            this.ts.project_id = 0;
            this.ts.ticket = '';
            this.ts.start = '';
        }
    },
    components: {select2: select2},
});

$( document ).ready(function() {
    appTimer.onLoad();
});

window.appTimer = appTimer;