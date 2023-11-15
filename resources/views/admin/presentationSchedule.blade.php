@extends('admin.template_page.adminpure')

@section('master_content')

<main>
  <h1 style="margin-top:20px;margin-bottom:10px;">PutraMas Calendar Schedule</h1>
  <div class="row" >
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


  {{-- Presentation Sche file card section --}}
  <div class="row">
    <div class="col-md-12">
      <div class="card"  style="padding:0px; margin-left: -15px; margin-bottom:20px;">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5>Presentation Schedule Template</h5>
            <div class="float-right" style = " color:white;">
              <a class="btn btn-success" data-toggle="modal" data-target="#uploadModal"><i class="fa fa-upload" style="margin-right: 5px;"></i>
                Upload New Presentation Sche File
                </a>
            </div>
        </div>

        <div class="card-body">
          <p>Download the presentation schedule template in Excel format:</p>
          <div >
            @foreach ($calen as $template)
            <div class="file-container">
            {{-- <div class="file-container" style="display: flex; justify-content: space-between;margin-bottom:1px;"> --}}
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
                  {{-- {{ str_replace('upload/templates/', '', $template->file_data) }} --}}
                  {{ last(explode('_', $template->file_name)) }}
                </a>
              @else
              <a href="{{ asset('storage/' . $template->file_data) }}" download>
                <i class="fa fa-file-word file-icon" style = "color: rgb(39, 77, 158); font-size: 20px;" ></i>
                {{-- {{ str_replace('upload/templates/', '', $template->file_data) }} --}}
                {{ last(explode('_', $template->file_name)) }}
              </a>
              @endif
            </p>
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-modal-id="confirmationModal{{ $template->file_data }}" data-target="#confirmationModal{{ $template->id }}">
              <i class="fa fa-trash remove-icon" style="margin-right: 5px;"></i>Remove
            </button>
          </div>

        <!-- Confirmation DELETE file Modal -->
        <div class="modal fade" id="confirmationModal{{ $template->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      Are you sure you want to delete this file?
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <a href="{{ route('delete.template', $template->id) }}" class="btn btn-danger">Delete</a>
                  </div>
              </div>
          </div>
        </div>
        {{-- end delete modal--}}

        @endforeach
        </div>
        {{-- end card body --}}
      </div>
      {{-- end card --}}
    </div>
    {{-- end card body --}}
  </div>
  {{-- col-md-12 --}}
  </div>
  {{-- end  row--}}

  {{-- display calendar, create event, event-list START --}}
  <div class="row">
    <div class="col-md-3" style="padding:0px">
      <div class="sticky-top mb-3">
        {{-- start card event list --}}
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Daily Event List</h5>
          </div>
          <div class="card-body">
            <!-- the events -->
            {{-- this div very important because without itm, calender wouldnt appear --}}
            <div id="external-events">

            <ul id="daily-event-list">
              <!-- Daily event list will be displayed here -->
              {{-- @foreach($eventsByDay as $day => $events)
                        <li>
                            <strong>{{ $day }}</strong>
                            <ul>
                                @foreach($events as $event)
                                    <li>{{ $event->title }}</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach --}}
            </ul>
            </div>
          </div> <!-- /.card-body -->
        </div>
        {{-- end card to display event list --}}

        {{-- start card to create event --}}
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Create Event</h5>
          </div>
          <div class="card-body">

            <!-- /btn-group -->
            <div class="input-group">
              <div class="col-md-12">
                {{-- <div class="col-md-12 col-lg-6"> --}}

              {{-- <input id="new-event" type="text" class="form-control" placeholder="Event Title"> --}}
              <form id="createEventForm">

                @csrf
                <div class="btn-group" style="width: 100%; margin-bottom: 2px;">
                  <ul class="fc-color-picker" id="color-chooser">
                    <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                  </ul>
                </div>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="start">Start Date & Time</label>
                    <input type="datetime-local" class="form-control" id="start" name="start" required>
                </div>
                <div class="form-group">
                    <label for="end">End Date & Time</label>
                    <input type="datetime-local" class="form-control" id="end" name="end">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location">
                </div>
              <div class="input-group-append">
                <button id="add-event-button" type="button" class="btn btn-primary">Create</button>
              </div>
            </form>
              <!-- /btn-group -->
            </div>
            <!-- /input-group -->
          </div>
          </div>
          </div>
          {{-- end card-body --}}
        </div>
        {{-- end card to create event --}}

      </div>

    <div class="col-md-9">
      <div class="card card-primary">
        <div class="card-body p-0">
          <!-- THE CALENDAR -->
          <div id="calendar" style="padding: 20px;overflow: auto;"></div>
        </div>
        <!-- /.card-body -->
    </div>
    </div>

    </div>
    {{-- end sticky-top-3 --}}

  </div>
  {{-- calendar, create event, event-list row end --}}

  {{-- Presentation Sche Upload File Modal starts --}}
  <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload New Presentation Schedule File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Your form for uploading Excel file goes here --}}
                <form action="{{ route('calendarsche.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <label for="file_name"  class = "labelling">File Name: (to be displayed)</label>
                      <input type="text" id="file_name" name="file_name" class = "form-control" placeholder=" Name" ><br>
                    </div>
                    <div class="form-group">
                        <label for="file_data">Please upload the presentation schedule file: </label>
                        <input type="file" class="form-control-file" id="file_data" name="file_data" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
  </div>
  {{-- Presentation Sche modal ends --}}

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
  {{-- display enet details modal end --}}

  {{-- START edit event modal --}}
  <div class="modal fade scrollable-modal" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
              <form id="editEventForm">
                @csrf
                {{-- @method('PUT') --}}

              <h4 id="eventTitle"></h4>
              <div class="form-group">
                  <label for="editTitle">Title</label>
                  <input type="text" class="form-control" id="editTitle" name="editTitle">
              </div>
              <div class="form-group">
                  <label for="editDescription">Description:</label>
                  <textarea class="form-control" id="editDescription" name="description"></textarea>
              </div>
              <div class="form-group">
                  <label for="editStartDate">Start Date and Time:</label>
                  <input type="datetime-local" class="form-control datetimepicker" id="editStartDate" name="startdate">
              </div>
              <div class="form-group">
                  <label for="editEndDate">End Date and Time:</label>
                  <input type="datetime-local" class="form-control datetimepicker" id="editEndDate" name="enddate">
              </div>
              <div class="form-group">
                <label for="editLocation">Location</label>
                <input type="text" class="form-control" id="editLocation" name="editLocation">
            </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEventChanges">Save Changes</button>
            </div>
            {{-- </form> --}}
        </div>
    </div>
 </div>
    {{-- edit modal end --}}

    {{-- START DELETE EVENT CONFIRMATION MODAL --}}
    <div class="modal fade dimmed-modal" id="confirmDeleteCountdownEventModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="confirmDeleteCountdownEventModal">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <p>Are you sure you want to delete this event?</p>
                  <p id="confirmationTimer">10</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-danger" id="confirmDeleteButton">Confirm Delete</button>
              </div>
          </div>
      </div>
  </div>
</main>

<script src={{ asset('./plugins/bootstrap/js/bootstrap.bundle.min.js') }}></script>

  <!-- Bootstrap 4 -->
  {{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
  {{-- <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- jQuery UI -->
  {{-- <script src={{ asset('./plugins/jquery-ui/jquery-ui.min.js') }}></script> --}}

  <!-- AdminLTE App -->
  {{-- <script src="../dist/js/adminlte.min.js"></script> --}}
  {{-- <script src={{ asset('./dist/js/adminlte.min.js') }}></script> --}}

  <!-- fullCalendar 2.2.5 -->
  <script src={{ asset('./plugins/moment/moment.min.js') }}></script>
  <script src={{ asset('./plugins/fullcalendar/main.js') }}></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> --}}

  <script>
  // var $j = jQuery.noConflict();

  $(document).ready(function () {

      $.ajaxSetup({
          headers:{
              'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
          }
      });

      // /* initialize the external events
      //  -----------------------------------------------------------------*/
      // function ini_events(ele) {
      //   ele.each(function () {

      //     // create an Event Object (https://fullcalendar.io/docs/event-object)
      //     // it doesn't need to have a start or end
      //     var eventObject = {
      //       title: $.trim($(this).text()) // use the element's text as the event title
      //     }

      //     // store the Event Object in the DOM element so we can get to it later
      //     $(this).data('eventObject', eventObject)

      //     // make the event draggable using jQuery UI
      //     $(this).draggable({
      //       zIndex        : 1070,
      //       revert        : true, // will cause the event to go back to its
      //       revertDuration: 0  //  original position after the drag
      //     })

      //   })
      // }

      // ini_events($('#external-events div.external-event'))

      // /* initialize the calendar
      //  -----------------------------------------------------------------*/
      //Date for the calendar events (dummy data)
      var date = new Date()
      var d    = date.getDate(),
          m    = date.getMonth(),
          y    = date.getFullYear()

      var Calendar = FullCalendar.Calendar;
      var Draggable = FullCalendar.Draggable;

      var containerEl = document.getElementById('external-events');
      var checkbox = document.getElementById('drop-remove');
      var calendarEl = document.getElementById('calendar');

      // // initialize the external events
      // // -----------------------------------------------------------------

      new Draggable(containerEl, {
        itemSelector: '.external-event',
        eventData: function(eventEl) {
          return {
            title: eventEl.innerText,
            backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
            borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
            textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
          };
        }
      })

      // refer the calendar part
      // $('#calendar').fullCalendar({

      var calendar = new Calendar(calendarEl, {
      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      // initialView: 'timeGridMonth', // Set the initial view to day view
      themeSystem: 'bootstrap',
      //Random default events
      // events: [
      //   {
      //     title          : 'All Day Event',
      //     start          : new Date('2023-10-11 21:59:42'),
      //     allDay         : true
      //   },
      //   {
      //     title          : 'Long Event',
      //     start          : new Date(y, m, d - 5),
      //     end            : new Date(y, m, d - 2),
      //     backgroundColor: '#f39c12', //yellow
      //     borderColor    : '#f39c12' //yellow
      //   },
      //   {
      //     title          : 'Meeting',
      //     start          : new Date(y, m, d, 10, 30),
      //     allDay         : false,
      //     backgroundColor: '#0073b7', //Blue
      //     borderColor    : '#0073b7' //Blue
      //   },
      //   {
      //     title          : 'Lunch',
      //     start          : new Date(y, m, d, 12, 0),
      //     end            : new Date(y, m, d, 14, 0),
      //     allDay         : false,
      //     backgroundColor: '#00c0ef', //Info (aqua)
      //     borderColor    : '#00c0ef' //Info (aqua)
      //   },
      //   {
      //     title          : 'Birthday Party',
      //     start          : new Date(y, m, d + 1, 19, 0),
      //     end            : new Date(y, m, d + 1, 22, 30),
      //     allDay         : false,
      //     backgroundColor: '#00a65a', //Success (green)
      //     borderColor    : '#00a65a' //Success (green)
      //   },
      //   {
      //     title          : 'Click for Google',
      //     start          : new Date(y, m, 28),
      //     end            : new Date(y, m, 29),
      //     url            : 'https://www.google.com/',
      //     backgroundColor: '#3c8dbc', //Primary (light-blue)
      //     borderColor    : '#3c8dbc' //Primary (light-blue)
      //   }
      // ],
      // events: '/calendar',
      events: [

        @foreach($combinedEvents as $event)
        {
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
      // data: { // Cache-busting query parameter
      //       timestamp: new Date().getTime()
      //   },
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
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

      // eventRender: function (info) {
      //   // Check event type and customize appearance
      //   if (info.event.extendedProps.type === 'presentation') {
      //       // info.el.style.backgroundColor = 'blue'; // Set background color
      //       // info.el.style.borderColor = 'blue'; // Set border color (for the dot)
      //       info.el.classList.add('presentation-event');

      //   } else if (info.event.extendedProps.type === 'forms') {
      //       info.el.style.backgroundColor = 'green'; // Set background color
      //       info.el.style.borderColor = 'green'; // Set border color (for the dot)
      //   }
      // },
      dayMaxEvents: 2,
      eventLimitClick: 'popover',
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    // For the eventDetailsModal
    var detailsModalFooter = $('#eventModalFooter');
    detailsModalFooter.empty(); // Clear existing buttons


    // if (eventType === 'forms') {
    //   // var eventId = '{{ $event['id'] }}';

    //   // var viewAllButton = $('<button class="btn btn-success" id="viewAllSubmissionButton">View All Submissions</button>');
    //   //   viewAllButton.on('click', function () {
    //   //     window.location.href = '{{ route("formpost.showAll", ["submissionPostId" => "event.id"]) }}';
    //   //   });

    //     // detailsModalFooter.append(viewAllButton);
    // }

    function changeEvent(event) {
          const updatedEvent = {
          id: event.id, // Assuming your event object has an 'id' property
          start: event.start.toISOString().slice(0, 19).replace("T", " "), // Format as 'Y-m-d H:i:s'
          end: event.end.toISOString().slice(0, 19).replace("T", " "), // Format as 'Y-m-d H:i:s'
          title: event.title,
          location: event.extendedProps.location,
          description: event.extendedProps.description,

          // description: event.description,
          // Include any other properties you want to update
      };
      console.log('inc ahenfs:', updatedEvent);

      // Make an AJAX request to update the event in the databases
      $.ajax({
          url: '/admin/calendar/updateEvent/' + updatedEvent.id,
          type: 'POST', // Adjust the HTTP method as needed
          data: updatedEvent,
          success: function (response) {
              console.log('Event updated successfully');
          },
          error: function (xhr, status, error) {
              console.log('Errordddd updating event:', error);
              // Handle errors as needed
              console.log(xhr.responseText);  // Log the full response for more details

          }
      });
    }

    // function to display evnent details in modal
    function displayEventDetails(event) {
      detailsModalFooter.empty();
      console.log("displayEventDetails",event);
      // Replace these lines with your own code to display the event details.
      $('#eventid').text(event.id);
      $('#eventTitle').text(event.title);
      $('#eventDesc').text(event.extendedProps.description);

      console.log(event);

      if (event.extendedProps.type === 'presentation') {
        // Display additional presentation details
        $('#additionalDetails').html('<p>Start Date: ' + event.start.toLocaleString() + '</p>' +
                                 '<p>End Date: ' + event.end.toLocaleString() + '</p>');
        var editButton = $('<button class="btn btn-primary" id="editEventButton">Edit</button>');
        editButton.on('click', function () {
          clickingEditButton(event);
        });

        var deleteButton = $('<button class="btn btn-danger" id="deleteEventButton">Delete</button>');
        deleteButton.on('click', function () {
          $('#confirmDeleteCountdownEventModal').modal('show');
          // $('#eventDetailsModal').modal('hide'); // Hide the event details modal
          $('#eventDetailsModal .modal-content').css('opacity', '0.5');
          $('#eventDetailsModal').attr('data-backdrop', 'static');

          startConfirmationCountdown();
        });

        detailsModalFooter.append(editButton);
        detailsModalFooter.append(deleteButton);

      } else if (event.extendedProps.type === 'forms') {
        // Display additional thesis details
        $('#additionalDetails').html('<p>Submission Deadline: ' + event.start.toLocaleString()+ '</p>');
        // var eventId = '{{ $event['id'] }}';
        var eventId = event.id;
        console.log("hereeee" + eventId);
          var viewAllButton = $('<button class="btn btn-success" id="viewAllSubmissionButton">View All Submissions</button>');
          viewAllButton.on('click', function () {
            var eventId = event.id;
            console.log("hereeee after clicked" + eventId);

            var viewAllUrl = '{{ route("formpost.showAll", ["submissionPostId" => ":eventId"]) }}';
            viewAllUrl = viewAllUrl.replace(':eventId', eventId);

            window.location.href = viewAllUrl;
          });
        detailsModalFooter.append(viewAllButton);
      }

      // Show the modal
      $('#eventDetailsModal').modal('show');
    }

    // Define the updateEventList function
    function updateEventList(clickedDate, info) {
      const formattedDate = moment(clickedDate).format('YYYY-MM-DD');
      console.log(formattedDate);
      $.ajax({
            url: '/admin/calendar/events/' + formattedDate,
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
                      console.log(event.type);
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

    $('#saveEventChanges').on('click', function () {
        // var formData = $('#editEventForm').serialize();
        const editedEvent = {
          title: $('#editTitle').val(),
          description: $('#editDescription').val(),
          start: $('#editStartDate').val(),
          end: $('#editEndDate').val(),
          location: $('#editLocation').val()
      };
      const eventToUpdate = calendar.getEventById(selectedEventId);
      const startDate = moment(editedEvent.start); // Convert start date to moment object
      console.log(startDate);
      const formattedDate = startDate.format('YYYY-MM-DD'); // Format the start date
      console.log(formattedDate);

      if (eventToUpdate) {
          eventToUpdate.setProp('title', editedEvent.title);
          eventToUpdate.setExtendedProp('description', editedEvent.description);
          eventToUpdate.setStart(editedEvent.start);
          eventToUpdate.setEnd(editedEvent.end);
          eventToUpdate.setExtendedProp('location', editedEvent.location);

          // Render the updated event
          eventToUpdate.remove(); // Remove the event
          calendar.addEvent(eventToUpdate); // Add the updated event back

          $('#editEventModal').modal('hide');
          updateEventList(formattedDate);

      }
      // updateEventList(formattedDate); // Make sure to pass the correct date

      $.ajax({
          url: '/admin/calendar/update/' + selectedEventId, // Use the correct route URL
          // type: 'PUT',
          type: 'POST',
          // data: formData,
          data: editedEvent, // Send the edited event data
          success: function (response) {
            // const updatedEventSource = fetchUpdatedEventSource();
              // Update the event source with the new data
              // calendar.setOption('events', updatedEventSource);

              // Refresh the calendar to reflect the changes
              // $('#calendar').fullCalendar('refetchEvents');

              calendar.refetchEvents();
              // Close the edit modal
          },
          error: function (xhr, status, error) {
              // Handle errors and show error messages
              console.log(xhr.responseText);
          }
      })
    });

    // calendar.on('dateClick', function (info) {
    //   const clickedDate = info.date;
    //   updateEventList(clickedDate);
    // });

    // $('#editEventButton').click(function() {
      function clickingEditButton(event){
        // You can use window.location.href to navigate to the edit page/modal
        // var eventId = getEventId(); // Implement a function to get the event ID
        // window.location.href = '/edit-event/' + eventId; // Redirect to the edit page/modal
        if (selectedEventId !== null) {
          const event = retrieveEventDetails(selectedEventId); // Implement the function to fetch event details
          // Pre-populate the form fields with the current event details
          $('#editTitle').val(event.title);
          $('#editDescription').val(event.description);
          $('#editStartDate').val(event.start);
          $('#editEndDate').val(event.end);
          $('#editLocation').val(event.location);

        $('#eventDetailsModal').modal('hide');

        // Show the edit modal
        $('#editEventModal').modal('show');
        }
      }
    // });

  function retrieveEventDetails(eventId) {
    let eventDetails = null;

    // Send an AJAX request to fetch event details from the server
    $.ajax({
        method: 'GET',
        url: `/admin/calendar/${eventId}`, // Adjust the URL to match your Laravel route
        success: function (response) {
            // Assuming the response is a JSON object containing event details
            eventDetails = response;
        },
        async: false, // Use synchronous AJAX request to wait for the response
    });

    return eventDetails;
  }

  // when user click on confirm delete button in the modal
  $('#confirmDeleteButton').on('click', function () {
    // Call the function to delete the event
    clickingDeleteButton(event);

    // Close the confirmation modal after deletion
    $('#confirmDeleteCountdownEventModal').modal('hide');
  });

  //PENDINGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG
  function clickingDeleteButton(event){
    // Get the event ID
    var eventId = $('#eventDetailsModal').data('event-id');

    // Send an AJAX request to delete the event on the server
    $.ajax({
        url: '/calendar/delete-event/' + eventId, // Adjust the URL to your server endpoint
        method: 'DELETE',
        success: function (response) {
            // Handle success, e.g., remove the event from the calendar
            $('#calendar').fullCalendar('removeEvents', eventId);

            // Optionally, show a success message or perform other actions
            console.log('Event deleted successfully.');
        },
        error: function (xhr, status, error) {
            // Handle error, e.g., show an error message
            console.error('Error deleting event:', error);
        }
    });

  }


// Function to start the countdown timer in the confirmation modal
function startConfirmationCountdown() {
    var countdown = 10; // Set the initial countdown time
    var confirmationTimer = $('#confirmationTimer');

    var countdownInterval = setInterval(function () {
        countdown--;
        confirmationTimer.text(countdown);

        if (countdown <= 0) {
            // Time's up, trigger delete function
            clearInterval(countdownInterval);
            var eventId = $('#eventDetailsModal').data('event-id');
            deleteEvent(eventId);
            // // Close the confirmation modal
            // $('#confirmationModal').modal('hide');
        }
    }, 1000);
  };

  $('#add-event-button').click(function () {
        var title = $('#title').val();
        var description = $('#description').val();
        var start = $('#start').val();
        var end = $('#end').val();
        var location = $('#location').val();
        // var color = selectedColorClass;

        var eventData = {
            title: title,
            description: description,
            start: start,
            end: end,
            location: location,
            // color: color,
        };

        $.ajax({
            url: '{{ route('calendar.store') }}',
            type: 'POST',
            data: eventData,
            success: function (data) {
                // Add the new event to the calendar
                calendar.addEvent({
                    id: data.id,
                    title: title,
                    start: start,
                    end: end,
                    location: location,
                    // color: color,
                });

                // Clear the form
                $('#createEventForm')[0].reset();
                // $('#color-chooser a').removeClass('active');
                // $('#color-chooser a.text-primary').addClass('active');

                updateDailyEventList();
                updateEventList(formattedDate); // Make sure to pass the correct date

            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // var dailyEventList = document.getElementById('daily-event-list');

    // function updateDailyEventList() {
    //     // Clear the daily event list
    //     dailyEventList.innerHTML = '';

    //     // Rebuild the daily event list
    //     calendar.getEvents().forEach(function(event) {
    //         var startDay = event.start.toISOString().substring(0, 10);
    //         var listItem = document.createElement('li');
    //         listItem.innerHTML = `<strong>${startDay}</strong>: ${event.title}`;
    //         dailyEventList.appendChild(listItem);
    //     });
    // }

    // calendar.on('dateClick', function (info) {
    //   // var clickedDate = info.event.date;
    //   // calendar.refetchEvents();
    //   const clickedDate = info.date; // Get the date you clicked on
    //   // const formatdate = new Date(clickedDate).toLocaleDateString(); // Format the date

    //   // const date = new Date(clickedDate);
    //   // const formattedDate = date.toISOString().split('T')[0]; // Format the date as "YYYY-MM-DD"

    //   // const year = clickedDate.getFullYear();
    //   // const month = (clickedDate.getMonth() + 1).toString().padStart(2, '0');
    //   // const day = clickedDate.getDate().toString().padStart(2, '0');
    //   // const formattedDate = `${day}/${month}/${year}`;

    //   const formattedDate = moment(clickedDate).format("YYYY-MM-DD HH:mm:ss");
    //   // var dateParts = formattedDate.split(" ");
    //   // var formatdate = dateParts[dateParts.length - 2]; // This extracts "2023-11-16"

    //   // const encodedDate = encodeURIComponent(formattedDate);

    //   // const formattedDate = '2023-10-31'; // Format the date as "YYYY-MM-DD"
    //   // var formattedDate = date.format('YYYY-MM-DD');
    //   // var formattedDate = new Intl.DateTimeFormat('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(date);

    //   console.log("Clicked date: " + formattedDate);
    //   $.ajax({
    //       // url: '/calendar/events/${formattedDate}',
    //       url: '/calendar/events/' + formattedDate,

    //       method: 'GET',
    //       success: function (events) {
    //           console.log(events);
    //           var eventList = $('#external-events');
    //           eventList.empty();

    //           var formattedClickedDate = moment(formattedDate).format('D MMMM YYYY');
    //           // Create a list item for the clicked date
    //           // eventList.append('<li style="font-size: larger;"><strong>' + formattedClickedDate + '</strong></li>');

    //           // // Iterate through the events and add them to the list
    //           // events.forEach(function (event) {
    //           //     eventList.append('<li class="event-title"><strong>Title:</strong> ' + event.title + '</li>');
    //           //     eventList.append('<li class="event-detail"><strong>Description:</strong> ' + event.description + '</li>');
    //           //     eventList.append('<li class="event-detail"><strong>Time:</strong> ' + event.start + '</li>');
    //           //     eventList.append('<li class="event-detail"><strong>Location:</strong> ' + event.location + '</li>');
    //           // });
    //           eventList.append('<li class="event-title"><strong>' + formattedClickedDate + '</strong></li>');

    //           events.forEach(function (event) {
    //               var eventItem = $('<li class="event-item"></li>');
    //               eventItem.append('<div class="event-title"><strong>Title:</strong> ' + event.title + '</div>');
    //               eventItem.append('<div class="event-detail"><strong>Description:</strong> ' + event.description + '</div>');
    //               eventItem.append('<div class="event-detail"><strong>Time:</strong> ' + event.start + '</div>');
    //               eventItem.append('<div class="event-detail"><strong>Location:</strong> ' + event.location + '</div>');

    //               // Add the event item to the event list
    //               eventList.append(eventItem);
    //           });
    //       },
    //       error: function (xhr, status, error) {
    //           console.log(error);
    //       }
    //   });
    // });

    // for the update event list part


    // function fetchUpdatedEventSource() {
    // $.ajax({
    //     url: '/fetch-updated-events', // Update with the correct route URL
    //     type: 'GET',
    //     success: function (data) {
    //         // Assuming the response is a JSON representation of the updated events
    //         const updatedEvents = data;

    //         // Do any necessary data processing

    //         // Return the updated event source
    //         return updatedEvents;
    //     },
    //     error: function (xhr, status, error) {
    //         console.log(xhr.responseText);
    //     }
    // });
    // }

    // from here until every interval refresh--
    // Define a function to fetch and update events
    // function refreshEvents() {
    //     // Fetch new event data from your data source
    //     // You may use AJAX or any method to retrieve the updated events
    //     $.ajax({
    //         url: '/events', // Adjust the URL to your data source
    //         method: 'GET',
    //         success: function(events) {
    //             // Update FullCalendar with the new events
    //             $('#calendar').fullCalendar('removeEventSources');
    //             $('#calendar').fullCalendar('addEventSource', events);
    //         },
    //         error: function() {
    //             // Handle errors if the data retrieval fails
    //         }
    //     });
    // }

    // Set up automatic refresh every 5 minutes (adjust the interval as needed)
    // setInterval(refreshEvents, 300000); // 300,000 milliseconds (5 minutes)
    // -- until here

//   function renderEventList(events) {
//     var eventList = $('#daily-event-list');

//     eventList.empty(); // Clear the existing list

//     if (events.length > 0) {
//         // Loop through the events and add them to the list
//         var list = $('<ul>');
//         events.forEach(function(event) {
//             list.append('<li>' + event.title + '</li>');
//         });
//         eventList.append(list);
//     } else {
//         eventList.append('<li>No events for this date.</li>');
//     }
// }

      calendar.render();

      /* ADDING EVENTS */
      // var currColor = '#3c8dbc' //Red by default
      // // Color chooser button
      // $('#color-chooser > li > a').click(function (e) {
      //   e.preventDefault()
      //   // Save color
      //   currColor = $(this).css('color')
      //   // Add color effect to button
      //   $('#add-new-event').css({
      //     'background-color': currColor,
      //     'border-color'    : currColor
      //   })
      // })
      // $('#add-new-event').click(function (e) {
      //   e.preventDefault()
      //   // Get value and make sure it is not null
      //   var val = $('#new-event').val()
      //   if (val.length == 0) {
      //     return
      //   }

      //   // Create events
      //   var event = $('<div />')
      //   event.css({
      //     'background-color': currColor,
      //     'border-color'    : currColor,
      //     'color'           : '#fff'
      //   }).addClass('external-event')
      //   event.text(val)
      //   $('#external-events').prepend(event)

      //   // Add draggable funtionality
      //   ini_events(event)

      //   // Remove event from text input
      //   $('#new-event').val('')
      // })
    });

  </script>



@endsection