// call this from the developer console and you can control both instances
var calendars = {};

$(document).ready( function() {

  // assuming you've got the appropriate language files,
  // clndr will respect whatever moment's language is set to.
  // moment.locale('ru');

  // here's some magic to make sure the dates are happening this month.
  

  // the order of the click handlers is predictable.
  // direct click action callbacks come first: click, nextMonth, previousMonth, nextYear, previousYear, or today.
  // then onMonthChange (if the month changed).
  // finally onYearChange (if the year changed).
	$.ajax({
        type: "GET",
        url: SITEURL+"getStuff/?ajax&gettimeslot&mnth="+thisMonth+"&intid="+intid,
        dataType: "json",
        sync : false,
        success: function(data){
            //eventArray = data;
			if(data != null)
			{
				eventArray = data;	
			}
			console.log(eventArray);
			calendars.clndr1 = $('.cal1').clndr({
    events: eventArray,
    // constraints: {
    //   startDate: '2013-11-01',
    //   endDate: '2013-11-15'
    // },
    clickEvents: {
      click: function(target) {
		  var cldate = target.date;
		  $("#datefont").text(cldate._i);
		  console.log(cldate._i);
		  var datei = cldate._i;
		  $.ajax({
				type: "GET",
				url: SITEURL+"getStuff/?ajax&get_timeslot&date="+datei+"&intid="+intid,
				dataType: "html",
				success: function(data){
					$('#rezlttimediv').html(data);
				}
			});
        // if you turn the `constraints` option on, try this out:
        // if($(target.element).hasClass('inactive')) {
        //   console.log('not a valid datepicker date.');
        // } else {
        //   console.log('VALID datepicker date.');
        // }
      },
      nextMonth: function() {
        console.log('next month.');
      },
      previousMonth: function() {
        console.log('previous month.');
      },
      onMonthChange: function() {
        console.log('month changed.');
      },
      nextYear: function() {
        console.log('next year.');
      },
      previousYear: function() {
        console.log('previous year.');
      },
      onYearChange: function() {
        console.log('year changed.');
      }
    },
    multiDayEvents: {
      startDate: 'startDate',
      endDate: 'endDate',
      singleDay: 'date'
    },
    showAdjacentMonths: true,
    adjacentDaysChangeMonth: false
  });

        }
    });
  calendars.clndr2 = $('.cal2').clndr({
    template: $('#template-calendar').html(),
    events: eventArray,
    multiDayEvents: {
      startDate: 'startDate',
      endDate: 'endDate',
      singleDay: 'date'
    },
    lengthOfTime: {
      days: 14,
      interval: 7
    },
    clickEvents: {
      click: function(target) {
        console.log(target);
      }
    }
  });

  calendars.clndr2 = $('.cal3').clndr({
    template: $('#template-calendar-months').html(),
    events: eventArray,
    multiDayEvents: {
      startDate: 'startDate',
      endDate: 'endDate'
    },
    lengthOfTime: {
      months: 2,
      interval: 1
    },
    clickEvents: {
      click: function(target) {
        console.log(target);
      }
    }
  });

  // bind both clndrs to the left and right arrow keys
  $(document).keydown( function(e) {
    if(e.keyCode == 37) {
      // left arrow
      calendars.clndr1.back();
      calendars.clndr2.back();
    }
    if(e.keyCode == 39) {
      // right arrow
      calendars.clndr1.forward();
      calendars.clndr2.forward();
    }
  });

});