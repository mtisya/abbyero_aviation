<div id="calendar" style="height:500px"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

  const calendarEl = document.getElementById('calendar');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'resourceTimelineDay',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'resourceTimelineDay,resourceTimelineWeek'
    },
    editable: true, // allow drag & drop
    selectable: true,
    eventResizableFromStart: true,

    // Load aircraft resources dynamically
    resources: '/calendar/aircraft', 

    // Load events dynamically
    events: '/calendar/schedules',

    eventDrop: function(info) {
      // Handle drag-drop updates
      fetch('/calendar/update/' + info.event.id, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          start: info.event.start.toISOString(),
          end: info.event.end.toISOString()
        })
      })
      .then(res => res.json())
      .then(data => {
        if(!data.success){
          alert(data.message || 'Error updating schedule');
          info.revert();
        }
      });
    }
  });

  calendar.render();
});
 
</script>
@endpush
