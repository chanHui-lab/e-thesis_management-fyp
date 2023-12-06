@extends('student.studentpure')
@section('master_content')
<main>
  <h1>Dashboard</h1>

{{-- <div class="row justify-content-center"> --}}
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="studashboard">
        {{-- <div class="card-body"> --}}
          <h4>Hello, Welcome back {{ auth()->user()->name }}</h4>
          <p>User Role: Student</p>
        {{-- </div> --}}
      </div>
    </div>
  </div>

<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
        <div class="card-body p-0">
          <!-- THE CALENDAR -->
          <div id="calendar" style="padding: 10px;overflow: auto;">
          </div>
        </div>
        {{-- end card body --}}
      </div>
  </div>
  <div class="col-md-4" style="padding:0px;margin:0px">
      <div class="sticky-top mb-3">
        <div id="app">
            {{-- <router-view :server-data="{{ json_encode($data) }}"></router-view> --}}
            <component-b></component-b>
        </div>
      </div>
    </div>

</div>
@vite('resources/js/app.js');


@foreach ($deadlines as $deadline)
    <p>{{ $deadline->title }} - {{ $deadline->submission_deadline }}</p>
@endforeach

{{-- @if ($reminders->isEmpty())
        <p>No reminders found.</p>
    @else
        <ul>
            @foreach ($reminders as $producthehe)
                <li>
                    <strong>Reminder:</strong> {{ $producthehe->name }}
                    <strong>Due Date:</strong> {{ $producthehe->submission_deadline->format('Y-m-d H:i') }}
                    <strong>Remaining Time:</strong> {{ $producthehe->remainingDays }} days {{ $producthehe->remainingHours }} hours
                    <br>
                    <br>

                </li>
            @endforeach
        </ul>
    @endif --}}
    {{-- </div> --}}
</main>
@endsection

<script type="module" src="{{ mix('resources/js/app.js') }}">


<script src={{ asset('./plugins/bootstrap/js/bootstrap.bundle.min.js') }}></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- fullCalendar 2.2.5 -->
<script src={{ asset('./plugins/moment/moment.min.js') }}></script>
<script src={{ asset('./plugins/fullcalendar/main.js') }}></script>

<script>
$(document).ready(function () {
  $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

  toastr.options.positionClass = 'toast-bottom-right';


  // /* initialize the calendar
  //  -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  var date = new Date()
  var d    = date.getDate(),
      m    = date.getMonth(),
      y    = date.getFullYear()

  var Calendar = FullCalendar.Calendar;
  // var Draggable = FullCalendar.Draggable;

  // var containerEl = document.getElementById('external-drag');
  // var checkbox = document.getElementById('drop-remove');
  var calendarEl = document.getElementById('calendar');

  var calendar = new Calendar(calendarEl, {
    headerToolbar: {
      left  : 'prev,next today',
      center: 'title',
      right : 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    // themeSystem: 'bootstrap',
    events: [
      @foreach($combinedEvents as $event){
        @if ($event['type'] === 'presentation')
              // <!-- Display presentation event details -->
              // <p>Presentation: {{ $event['title'] }} - {{ $event['start'] }}</p>
              id: '{{ $event['id'] }}',
              title: '{{ $event['title'] }}',
              start: '{{ $event['start'] }}',
              @if(isset($event['end']))
                  end: '{{ $event['end'] }}',
              @endif
              description: '{{ $event['description'] }}',
              location: '{{ $event['location'] }}',
              type: '{{ $event['type'] }}', // Add this line
          @elseif ($event['type'] === 'forms')
              type: '{{ $event['type'] }}', // Add this line
              id: '{{ $event['id'] }}',
              title: '{{ $event['title'] }}',
              description: '{{ $event['description'] }}',
              start: '{{ $event['start'] }}',
              className: 'form-event',
          @endif
        },
        @endforeach
    ],
    // editable  : true,
    // droppable : true, // this allows things to be dropped onto the calendar !!!
    eventClick: function (info) {
      // Handle the event click
      displayEventDetails(info.event);
    },
    dateClick:function (info) {
      const clickedDate = info.date;
      // calendar.gotoDate(clickedDate);
      calendar.gotoDate(clickedDate);

      // change terus to day view?
      calendar.changeView('timeGridDay');
      updateEventList(clickedDate, info);
    },
    // eventRender: function(info, element) {
    //     element.attr('title', info.title); // Set the title attribute as the event title
    // },
    // eventRender: function(info) {
    //     $(info.el).tooltip({
    //         title: info.event.title,
    //         placement: 'top',
    //         container: 'body'
    //     });
    // },
    eventRender: function (event, element, view) {
        console.log("eventafterrender");
        $(element).qtip({
            content: {
                text: event.title
            },
            position: {
                my: 'bottom center',
                at: 'top center',
                target: $(element)
            },
            style: {
                classes: 'qtip-dark'
            }
        });
    },
    eventDrop:function (info) {
      changeEvent(info.event);
      console.log("eventdrope", info.event);
      const clickedDate = info.date;
      updateEventList(clickedDate);
    },
    eventClassNames: function (arg) {
      // Add classes based on event type
      if (arg.event.extendedProps.type === 'presentation') {
          return ['presentation-event'];
      } else if (arg.event.extendedProps.type === 'forms') {
          return ['thesis-event'];
      }
      return [];
    },
    dayMaxEvents: 2,
    eventLimitClick: 'popover',

  });

  calendar.render();
  const today = moment().startOf('day');
  updateEventList(today);

  // For the eventDetailsModal
  var detailsModalFooter = $('#eventModalFooter');
  detailsModalFooter.empty(); // Clear existing buttons

  // function to display evnent details in modal
  function displayEventDetails(event) {
    detailsModalFooter.empty();
    calendar.refetchEvents();

    console.log("displayEventDetails",event);
    // Replace these lines with your own code to display the event details.
    $('#eventid').text(event.id);
    $('#eventTitle').text(event.title);
    $('#eventDesc').text(event.extendedProps.description);

    console.log(event);
    var eventId = event.id;

    if (event.extendedProps.type === 'presentation') {
      // Display additional presentation details
      $('#additionalDetails').html('<p>Start Date: ' + event.start.toLocaleString() + '</p>' +
                                '<p>End Date: ' + event.end.toLocaleString() + '</p>');


    } else if (event.extendedProps.type === 'forms') {
      // Display additional thesis details
      $('#additionalDetails').html('<p>Submission Deadline: ' + event.start.toLocaleString()+ '</p>');
      // var eventId = '{{ $event['id'] }}';
      var eventId = event.id;
      console.log("hereeee" + eventId);

      // CAN ADD A VIEW SUBMISSION COLUMN PAGE
        // var viewTheColumn = $('<button class="btn btn-success" id="viewOneSubmissionButton">View Submission Column</button>');
        // viewTheColumn.on('click', function () {
        //   var eventId = event.id;
        //   console.log("hereeee after clicked" + eventId);

        //   var viewAllUrl = '{{ route("formpost.showAll", ["submissionPostId" => ":eventId"]) }}';
        //   viewAllUrl = viewAllUrl.replace(':eventId', eventId);

        //   window.location.href = viewAllUrl;
        // });
      // detailsModalFooter.append(viewTheColumn);
    }

    // Show the modal
    $('#eventDetailsModal').modal('show');
  }

  // Define the updateEventList function
function updateEventList(clickedDate, info) {
  calendar.refetchEvents();

  // Use the current date if clickedDate is not provided
  const formattedDate = clickedDate ? moment(clickedDate).format('YYYY-MM-DD') : moment().format('YYYY-MM-DD');
  console.log("here formatted"+formattedDate);
  // const formattedDate = moment(clickedDate).format('YYYY-MM-DD');
  // console.log(formattedDate);
  $.ajax({
        url: '/student/calendar/events/' + formattedDate,
        method: 'GET',
        success: function (events) {

          console.log(JSON.stringify(events, null, 2));
          var eventList = $('#external-events');
            eventList.empty();

            const formattedClickedDate = moment(clickedDate).format('D MMMM YYYY');
            const [day, month, year] = formattedClickedDate.split(' ');

            eventList.append('<li style="font-size: 30px; line-height: 1.5; display: flex;align-items: center; justify-content: center;"><strong style="margin-right: 10px;">' + day + '</strong><div style="display: flex; flex-direction: column;"><div style="font-size: 13px;">' + month + '</div><div style="font-size: 12px;">' + year + '</div></div></li>');

            events.forEach(function (event) {
                var eventItem = $('<li class="event-item" style="font-size: 12px;"></li>');
                eventItem.append('<div class="event-title">' + event.title + '</div>');
                eventItem.append('<div class="event-detail"><strong>Student:</strong> ' + event.description + '</div>');
                // Check the event type
                if (event.type === 'presentation') {
                  eventItem.css('background-color', '#FFFFCC');
                  eventItem.append('<div class="event-detail"><strong>Start:</strong> ' + event.start + '</div>');
                  eventItem.append('<div class="event-detail"><strong>End:</strong> ' + event.end + '</div>');
                  eventItem.append('<div class="event-detail"><strong>Location:</strong> ' + event.location + '</div>');
                } else if (event.type === 'forms') {
                  eventItem.css('background-color', 'rgb(210, 255, 210)');
                    eventItem.append('<div class="event-detail"><strong>Submission Deadline:</strong> ' + event.submission_deadline + '</div>');
                }
                eventList.append(eventItem);
            });
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
  }

let selectedEventId = null;

calendar.on('eventClick', function (info) {
  selectedEventId = info.event.id; // Get the ID of the selected event
  // Now you can use eventId to open the edit modal or perform other actions
  console.log("selectedEventId",selectedEventId);

  // Get the current date being displayed in the calendar
  var currentDate = calendar.getDate();

  // Trigger a click on the current date to fetch and display events for that date
  calendar.gotoDate(currentDate);

  // Optionally, you can trigger a 'dayClick' event to handle the event list update
  calendar.trigger('dayClick', {
      date: currentDate,
      jsEvent: null,
      view: 'day',
  });
});

$('#closeEventModal').click(function() {
    $('#eventDetailsModal').modal('hide');
});

function retrieveEventDetails(eventId) {
    let eventDetails = null;

    // Send an AJAX request to fetch event details from the server
    $.ajax({
        method: 'GET',
        url: `/student/calendar/${eventId}`, // Adjust the URL to match your Laravel route
        success: function (response) {
            // Assuming the response is a JSON object containing event details
            eventDetails = response;
        },
        async: false, // Use synchronous AJAX request to wait for the response
    });

    return eventDetails;
  }


});

</script>
<style>


</style>