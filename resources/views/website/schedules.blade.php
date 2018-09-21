@extends('layouts.websitelayout')

@section('title')
Schedules - UNILAB Bayanihan Center
@endsection

@section('styles')
<!-- fullCalendar -->
<script type="text/javascript" src="{{asset('adminlte/bower_components/moment/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<!-- fullCalendar -->
<link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">
@endsection

@section('content')
{{--  Calendar  --}}
<h2 class="text-center">Schedule</h2>
<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body no-padding">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<!-- Page specific script -->
<script>
    $(function () {
    //     /* initialize the external events
    //     -----------------------------------------------------------------*/
    //     function init_events(ele) {
    //     ele.each(function () {

    //         // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
    //         // it doesn't need to have a start or end
    //         var eventObject = {
    //         title: $.trim($(this).text()) // use the element's text as the event title
    //         }

    //         // store the Event Object in the DOM element so we can get to it later
    //         $(this).data('eventObject', eventObject)

    //         // make the event draggable using jQuery UI
    //         $(this).draggable({
    //         zIndex        : 1070,
    //         revert        : true, // will cause the event to go back to its
    //         revertDuration: 0  //  original position after the drag
    //         })

    //     })
    //     }

    //     init_events($('#external-events div.external-event'))

    //     /* initialize the calendar
    //     -----------------------------------------------------------------*/
    //     //Date for the calendar events (dummy data)
    //     var date = new Date()
    //     var d    = date.getDate(),
    //         m    = date.getMonth(),
    //         y    = date.getFullYear()
    //     $('#calendar').fullCalendar({
    //     header    : {
    //         left  : 'prev,next today',
    //         center: 'title',
    //         right : 'month,agendaWeek,agendaDay'
    //     },
    //     buttonText: {
    //         today: 'today',
    //         month: 'month',
    //         week : 'week',
    //         day  : 'day'
    //     },
    //     //Random default events
    //     events    : [
    //         {
    //         title          : 'All Day Event',
    //         start          : new Date(y, m, 1),
    //         backgroundColor: '#f56954', //red
    //         borderColor    : '#f56954' //red
    //         },
    //         {
    //         title          : 'Long Event',
    //         start          : new Date(y, m, d - 5),
    //         backgroundColor: '#f39c12', //yellow
    //         borderColor    : '#f39c12' //yellow
    //         },
    //         {
    //         title          : 'Meeting',
    //         start          : new Date(y, m, d),
    //         allDay         : false,
    //         backgroundColor: '#0073b7', //Blue
    //         borderColor    : '#0073b7' //Blue
    //         },
    //         {
    //         title          : 'Lunch',
    //         start          : new Date(y, m, d, 12, 0),
    //         end            : new Date(y, m, d, 14, 0),
    //         allDay         : false,
    //         backgroundColor: '#00c0ef', //Info (aqua)
    //         borderColor    : '#00c0ef' //Info (aqua)
    //         },
    //         {
    //         title          : 'Birthday Party',
    //         start          : new Date(y, m, d + 1, 19, 0),
    //         end            : new Date(y, m, d + 1, 22, 30),
    //         allDay         : false,
    //         backgroundColor: '#00a65a', //Success (green)
    //         borderColor    : '#00a65a' //Success (green)
    //         },
    //         {
    //         title          : 'Click for Google',
    //         start          : new Date(y, m, 28),
    //         end            : new Date(y, m, 29),
    //         url            : 'http://google.com/',
    //         backgroundColor: '#3c8dbc', //Primary (light-blue)
    //         borderColor    : '#3c8dbc' //Primary (light-blue)
    //         }
    //     ],
    //     })
    // })

    // $('#calendar').fullCalendar({
    // selectable: true,
    // dayClick: function(date, allDay, jsEvent, view) {

    //     if (allDay) {
    //         alert(date.format());
            
    //     }
    // }
});
</script>

@endsection
