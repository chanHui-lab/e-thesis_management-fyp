<!-- resources/views/notifications/index.blade.php -->

@foreach ($notifications as $notification)
    @if ($notification->data['type'] === 'template_uploaded')
        <div>
            A new template has been uploaded! <a href="{{ $notification->data['link'] }}">View</a>
        </div>
    @elseif ($notification->data['type'] === 'deadline_reminder')
        <div>
            Submission deadline is approaching! <a href="{{ $notification->data['link'] }}">View</a>
        </div>
    @endif
@endforeach
