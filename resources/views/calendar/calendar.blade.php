<span id="calendar_event" data-link=" {{ asset('calendar_event') }} "></span>
<span id="save" data-link=" {{ asset('calendar_save') }} "></span>
<span id="calendar_update" data-link=" {{ asset('calendar_update') }} "></span>
<span id="token" data-token="{{ csrf_token() }}"></span>
<!-- fullCalendar 2.2.5-->
<link href="{{ asset('resources/plugin/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
<link href="{{ asset('resources/plugin/fullcalendar/fullcalendar.print.css') }}" media="print">
<!-- Theme style -->
<link href="{{ asset('resources/plugin/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    {{ csrf_field() }}
    <div class="col-md-9 wrapper">
        <div class="alert alert-jim">
            <div class="row no-print">
                <div class="col-xs-12">
                    <button type="button" class="btn btn-primary pull-plus" onclick="addEvent($(this));" data-link="{{ asset('calendar_form') }}" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> Add New Event
                    </button>
                </div>
            </div><br>
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    <!--CREATE EVENT SIDEBAR -->
    <div class="col-md-3">
        <!-- /. box -->
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Legends</h3>
            </div>
            <div class="box-body">
                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                    {{--<ul class="fc-color-picker" id="color-chooser">
                        <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                        <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                    </ul>--}}
                </div>
                <!-- /btn-group -->
                <div class="form-group">
                    <input id="event_title" type="text" class="form-control" placeholder="Event Title">
                    {{--<button id="add-new-event" type="button" class="pull-left" style="background-color:deepskyblue;color: white;margin-top: 2%;">
                        <i class="fa fa-plus"></i> ADD EVENT
                    </button>--}}
                </div>

                <!-- /input-group -->
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h4 class="box-title">Draggable Events</h4>
            </div>
            <div class="box-body">
                <!-- the events -->
                <div id="external-events">
                    <div class="external-event bg-green">Doctor</div>
                    <div class="external-event bg-yellow">Go home</div>
                    <div class="external-event bg-aqua">Do homework</div>
                    <div class="external-event bg-light-blue">Work on UI design</div>
                    <div class="external-event bg-red">Sleep tight</div>
                    <div class="checkbox">
                        {{--<label for="drop-remove">
                            <input type="checkbox" id="drop-remove">
                            remove after drop
                        </label>--}}
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <p id="tayong"></p>
@endsection
@section('js')
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('resources/plugin/fullcalendar/moment.js') }}"></script>
    <script src="{{ asset('resources/plugin/fullcalendar/fullcalendar.min.js') }}"></script>

    <script>
        $(function () {
            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {
                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                });
            }
            ini_events($('#external-events div.external-event'));

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var json = '';
            var calendar_event = $("#calendar_event").data('link');
            $.get(calendar_event,function(result){
                $('#calendar').fullCalendar({

                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    buttonText: {
                        today: 'today',
                        month: 'month',
                        week: 'week',
                        day: 'day'
                    },
                    //Random default events
                    events: result,

                    editable: true,
                    eventResize: function(event,delta,revertFunc)
                    {
                        var url = $('#calendar_update').data('link');
                        var object = {
                            'event_id' : json.event_id,
                            'end' : event.end.format(),
                            "_token" : $('#token').data('token')
                        };
                        $.post(url,object,function(){
                            console.log("Successfully updated");
                        });
                    },
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    drop: function (date, allDay) { // this function is called when something is dropped

                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');

                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.backgroundColor = $(this).css("background-color");
                        copiedEventObject.borderColor = $(this).css("border-color");
                        copiedEventObject.title = $('#event_title').val();
                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                        var url = $('#save').data('link');
                        json = {
                            'event_id' : new Date(),
                            'title' : $("#event_title").val(),/*$(this).data('eventObject')['title']*/
                            'start' : date.format(),
                            'end' : date.format(),
                            'background_color' : $(this).css('background-color'),
                            'border_color' : $(this).css('border-color'),
                            "_token" : $('#token').data('token')
                        };
                        $.post(url,json,function(){
                            console.log("Successfully added event");
                        });
                        /*$(this).remove();*/
                    }
                });
            });

            /* ADDING EVENTS */
            var currColor = "#3c8dbc"; //Red by default
            //Color chooser button
            var colorChooser = $("#color-chooser-btn");
            $("#color-chooser > li > a").click(function (e) {
                e.preventDefault();
                //Save color
                currColor = $(this).css("color");
                //Add color effect to button
                $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
            });
            $("#add-new-event").click(function (e) {
                e.preventDefault();
                //Get value and make sure it is not null
                var val = $("#new-event").val();
                if (val.length == 0) {
                    return;
                }

                //Create events
                var event = $("<div />");
                event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                event.html(val);
                $('#external-events').prepend(event);
                //Add draggable funtionality

                ini_events(event);

                //Remove event from text input
                $("#new-event").val("");
            });

        });

        function addEvent(result){
            $('#calendar_modal').modal('show');
            $('.modal_content').html(loadingState);
            $('.modal-title').html('Add New Event');
            var url = result.data('link');
            setTimeout(function() {
                $.get(url,function(data){
                    $('.modal_content').html(data);
                });
            },1000);
        }
    </script>
@endsection