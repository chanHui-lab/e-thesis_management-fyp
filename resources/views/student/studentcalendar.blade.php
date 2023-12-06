@extends('student.studentpure')

@section('master_content')
<main>
  <h1>PutraMas Calendar Schedule</h1>
    <div class="row">
      <div class="col-md-12" style="margin-left: -15px;">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
        @endif
      </div>
    </div>

    {{-- START template --}}
    <div class="row">
      <div class="col-md-12">
        <div class="card"  style="padding:0px; margin-left: -15px; margin-bottom:20px;">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Presentation Schedule Template</h5>

          </div>
          <div class="card-body">
            <p>Download the presentation schedule template in Excel format:</p>
            <div >
              @foreach ($calen as $template)
              <div class="file-container">
                <p class="file-link">
                  &#x2514;
                  @if (Str::endsWith($template->file_data, '.pdf'))
                    <a href="{{ asset('storage/' . $template->file_data) }}" download>
                      <i class="fa fa-file-pdf file-icon" style = "color: rgb(255, 86, 86); font-size: 20px;"></i>
                      {{ last(explode('_', $template->file_name)) }}
                    </a>
                  @elseif (Str::endsWith($template->file_data, ['.xlsx', '.xls']))
                    <a href="{{ asset('storage/' . $template->file_data) }}" download>
                      <i class="fa fa-file-excel file-icon" style = "color: rgb(39, 158, 81); font-size: 20px;"></i>
                      {{ last(explode('_', $template->file_name)) }}
                    </a>
                  @else
                  <a href="{{ asset('storage/' . $template->file_data) }}" download>
                    <i class="fa fa-file-word file-icon" style = "color: rgb(39, 77, 158); font-size: 20px;" ></i>
                    {{ last(explode('_', $template->file_name)) }}
                  </a>
                  @endif
                </p>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    </div>
    {{-- END row for presentation --}}

    {{-- START display calendar, create event, event-list START --}}
    <div class="row">
      <div class="col-md-3" style="padding:0px">
        <div class="sticky-top mb-3">
          {{-- start card event list --}}
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Daily Event List</h5>
            </div>

            <div class="card-body">
              <div id="external-events">
                <ul id="daily-event-list">
                </ul>
              </div>

            </div>

          </div>
          {{-- end card event list --}}
        </div>
        {{-- end sticky-top --}}
        </div>


        <div class="col-md-9">
          <div class="card card-primary">
            <div class="card-body p-0">
              <!-- THE CALENDAR -->
              <div id="calendar" style="padding: 20px;overflow: auto;">
              </div>
            </div>
            {{-- end card body --}}
          </div>
          {{--end calendar card  --}}

          <br>

        </div>
        {{-- end col-md-9 --}}
        {{-- END display calendar --}}

      </div>

        {{-- DISPLAY EVENT DETAILS CONFIRMATION MODAL --}}
  <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
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
  {{-- display evnet details modal end --}}

  {{-- </div> --}}
</main>

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
    themeSystem: 'bootstrap',
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
@endsection
