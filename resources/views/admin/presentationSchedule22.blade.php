@extends('admin.template_page.adminpure')

@section('master_content')
<section class="content">
  <h1 style="margin-top: 20px; margin-bottom:20px;">PutraMas Calendar Schedule</h1>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="sticky-top mb-3">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Draggable Events</h4>
              </div>
              <div class="card-body">
                <!-- the events -->
                <div id="external-events">
                  <div class="external-event bg-success">Lunch</div>
                  <div class="external-event bg-warning">Go home</div>
                  <div class="external-event bg-info">Do homework</div>
                  <div class="external-event bg-primary">Work on UI design</div>
                  <div class="external-event bg-danger">Sleep tight</div>
                  <div class="checkbox">
                    <label for="drop-remove">
                      <input type="checkbox" id="drop-remove">
                      remove after drop
                    </label>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create Event</h3>
              </div>
              <div class="card-body">
                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                  <ul class="fc-color-picker" id="color-chooser">
                    <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                  </ul>
                </div>
                <!-- /btn-group -->
                <div class="input-group">
                  <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                  <div class="input-group-append">
                    <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                  </div>
                  <!-- /btn-group -->
                </div>
                <!-- /input-group -->
              </div>
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary">
            <div class="card-body p-0">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>


            </div>
            <!-- /.card-body -->

        </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModal">Event Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">x</span>
                  </button>
                </div>
                <div class="modal-body">
                    <h4 id="eventTitle"></h4>
                    <p>Description: <span id="eventDesc"></span></p>
                    <p>Start Date: <span id="eventStartDate"></span></p>
                    <p>End Date: <span id="eventEndDate"></span></p>
                    <p>Location: <span id="eventLoc"></span></p>

                    <!-- Add more event details here -->
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="editEventButton">Edit</button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit event input fields here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEventChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    </div><!-- /.container-fluid -->
  </section>
  <!-- jQuery -->
  {{-- <script src={{ asset('./plugins/jquery/jquery.min.js') }}></script>
  <script src={{ asset('./plugins/bootstrap/js/bootstrap.bundle.min.js') }}></script> --}}

  <!-- Bootstrap 4 -->
  {{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- jQuery UI -->
  {{-- <script src={{ asset('./plugins/jquery-ui/jquery-ui.min.js') }}></script> --}}

  <!-- AdminLTE App -->
  {{-- <script src="../dist/js/adminlte.min.js"></script> --}}
  {{-- <script src={{ asset('./dist/js/adminlte.min.js') }}></script> --}}

  <!-- fullCalendar 2.2.5 -->
  {{-- <script src="../plugins/moment/moment.min.js"></script> --}}
  <script src="../plugins/fullcalendar/main.js"></script>

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
      function ini_events(ele) {
        ele.each(function () {

          // create an Event Object (https://fullcalendar.io/docs/event-object)
          // it doesn't need to have a start or end
          var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
          }

          // store the Event Object in the DOM element so we can get to it later
          $(this).data('eventObject', eventObject)

          // make the event draggable using jQuery UI
          $(this).draggable({
            zIndex        : 1070,
            revert        : true, // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
          })

        })
      }

      ini_events($('#external-events div.external-event'))

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
      var calendar = new Calendar(calendarEl, {
      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
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
        @foreach($presentations as $presentation)
        {

            title: '{{ $presentation->title }}',
            start: '{{ $presentation->start }}',
            @if($presentation->end)
                end: '{{ $presentation->end }}',
            @endif
            description: '{{ $presentation->description }}',
            location: '{{ $presentation->location }}',
        },
        @endforeach

      ],
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      eventClick: function (info) {
        // Handle the event click
        displayEventDetails(info.event);

      },
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    function displayEventDetails(event) {
      console.log(event);
      // Replace these lines with your own code to display the event details.
      $('#eventTitle').text(event.title);
      $('#eventStartDate').text(event.start.toLocaleString());

      if (event.end) {
          $('#eventEndDate').text(event.end.toLocaleString());
      } else {
          $('#eventEndDate').text('N/A');
      }
      if (event.extendedProps.description !== null && event.extendedProps.description !== undefined) {
        $('#eventDesc').text(event.extendedProps.description);
      }else {
        $('#eventDesc').text('N/A');
      }
      if (event.extendedProps.location !== null && event.extendedProps.location !== undefined) {
        $('#eventLoc').text(event.extendedProps.location);
      }else {
        $('#eventLoc').text('N/A');
      }
      // Add additional event details as needed

      // Show the modal
      $('#eventDetailsModal').modal('show');
    }

    function getEventDetails(event) {
        // Implement this function to retrieve event details from the selected event.
        // You can access the event properties such as title, start, end, and other custom properties.
        // Return an object with the event details.

        return {
            id:event.id,
            title: event.title,
            start: event.start,
            end: event.end,
            // Add other event details here
        };
    }

    // $('#editEventButton').click(function() {
    //     // Get the event details and populate the edit modal
    //     var event = getEventDetails(); // Implement a function to get event details

    //     // Populate the edit modal with event data
    //     $('#editEventModalLabel').text('Edit Event: ' + event.title);
    //     $('#editEventModal #eventTitle').val(event.title);
    //     $('#editEventModal #eventStartDate').val(event.start);
    //     // Populate other fields as needed

    //     // Show the edit modal
    //     $('#editEventModal').modal('show');
    // })
    calendar.on('eventClick', function (info) {
      var eventId = info.event.id; // Get the ID of the selected event
      // Now you can use eventId to open the edit modal or perform other actions
    });

    $('#editEventButton').click(function() {
        // Get the currently selected event
        var selectedEvent = calendar.getEventById( eventDetails.id); // Replace 'eventId' with the actual event ID

        if (selectedEvent) {
            // Call the getEventDetails function with the selected event
            var eventDetails = getEventDetails(selectedEvent);

            // Populate the edit modal with event data
            $('#editEventModalLabel').text('Edit Event: ' + eventDetails.title);
            $('#editEventModal #eventTitle').val(eventDetails.title);
            $('#editEventModal #eventStartDate').val(eventDetails.start);
            // Populate other fields as needed

            // Show the edit modal
            $('#editEventModal').modal('show');
        }
    });

      calendar.render();
      // $('#calendar').fullCalendar()

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