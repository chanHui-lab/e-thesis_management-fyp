@extends('admin.template_page.adminpure')
{{-- @extends('student.studentpure') --}}

{{-- @extends('layouts.admindash') --}}

@section('master_content')
<main>
    <h1>Dashboard</h1>

    <div class="container mt-4">
        <div class="card-deck">
            <!-- Card 1 -->
            <div class="card">

                <div class="card-body mt-3 ml-3">
                    <i class="fas fa-file-alt fa-2x "></i>
                    <h4 class="card-title d-inline-block ml-3">Form</h4>
                    <p>Explore Form templates and its submission post</p>
                    <a href="{{ route('template.index') }}" class="btn btn-primary" style="background-color: #FACD3F; border:transparent">Explore</a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-body mt-3 ml-3">
                    <i class="fas fa-chart-bar fa-2x "></i>
                    {{-- <i class="bx bxs-file bx-2x "></i> --}}
                    <h4 class="card-title d-inline-block ml-3">Report</h4>
                    <p>Explore Report templates and its submission post</p>
                    <a href="{{ route('template.index') }}" class="btn btn-primary" style="background-color: #FACD3F; border:transparent">Explore</a>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-body mt-3 ml-3">
                    <div class="d-grid align-items-start">
                        <div class="d-flex">
                            <i class="fas fa-file-powerpoint fa-2x align-self-center"></i>
                            <h4 class="card-title ml-3 mb-0" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;" title="Presentation slides">Presentation slides</h4>
                        </div>
                        <p class="mt-2">Explore Presentation slides templates and its submission post</p>
                        <a href="{{ route('template.index') }}" class="btn btn-primary" style="background-color: #FACD3F; border: transparent">Explore</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mt-5 ml-0">
          <div class="card card-primary">
              <div class="card-header" style=" background-color: rgb(255, 242, 198); border:transparent">
                <div class="row align-items-center">
                  <div class="col-auto">
                      <!-- Calendar Icon -->
                      <i class="fas fa-calendar" style="font-size: 20px"></i>
                  </div>
                  <div class="col" >
                      <h4 class="card-title" style="margin-bottom: 0%">My Calendar</h4>
                  </div>
                  <div class="coltext-right">
                    <a href="{{ route('template.index') }}" class="btn btn-primary btn-common" style="background-color: #251d00; ">Edit Calendar</a>
                </div>
              </div>
                </div>
                <div class="card-body p-0">

                <!-- THE CALENDAR -->
                <div id="calendar" style="padding: 10px;overflow: auto;">
                </div>
              </div>
              {{-- end card body --}}
            </div>
        </div>
        <div class="col-md-4 mt-5 ml-0" style="padding:0px;margin:0px">
            <div class="mb-4">
                {{-- <h4>Upcoming Events</h4>
                <ul>
                    @foreach($combinedEvents as $event)
                        <li>{{ $event['title']}} - {{ $event['title'] }}</li>
                    @endforeach
                </ul> --}}
                <div id="app">
                    {{-- <router-view :server-data="{{ json_encode($data) }}"></router-view> --}}
                    <component-c></component-c>
                </div>
            </div>
          </div>

      </div>

      {{-- DISPLAY EVENT DETAILS CONFIRMATION MODAL --}}
  <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #f0f0f0; color: #333;"> <!-- Set your desired background and text color here -->
            <div class="modal-header" style="background-color: #ddd; color: #333;"> <!-- Adjust the header background and text color -->
                <h5 class="modal-title" id="eventDetailsModal">Event Details</h5>
                <button type="button" class="close" id="closeEventModal" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
                <h4 id="eventTitle"></h4>
                <p>Description: <span id="eventDesc"></span></p>

                <!-- Add more event details here -->
                <div id="additionalDetails">
                  <!-- This will be dynamically populated based on the event type -->
                  {{-- <p>Start Date: <span id="eventStartDate"></span></p>
                  <p>End Date: <span id="eventEndDate"></span></p> --}}
                  {{-- <p>Location: <span id="eventLoc"></span></p> --}}

                </div>
            </div>
            <div class="modal-footer" id="eventModalFooter">
              {{-- <button class="btn btn-primary" id="editEventButton">Edit</button> --}}
              {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
  </div>
{{-- <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{ __('You are logged in!') }}
            </div>
        </div>
    </div>
</div> --}}
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
    events: [
      @foreach($combinedEvents as $event){
        @if ($event['type'] === 'presentation')
              id: '{{ $event['id'] }}',
              title: '{{ $event['title'] }}',
              start: '{{ $event['start'] }}',
              @if(isset($event['end']))
                  end: '{{ $event['end'] }}',
              @endif
              description: '{{ $event['description'] }}',
              location: '{{ $event['location'] }}',
              type: '{{ $event['type'] }}', // Add this line
          @elseif ($event['type'] === 'deadline')
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
      console.log(info);
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
      } else if (arg.event.extendedProps.type === 'deadline') {
          return ['thesis-event'];
      }
      return [];
    },
    dayMaxEvents: 3,
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


    } else if (event.extendedProps.type === 'deadline') {
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
                } else if (event.type === 'deadline') {
                  eventItem.css('background-color', '#FFFFCC');
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