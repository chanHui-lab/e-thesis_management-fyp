<!-- templates/show.blade.php -->
@if ($template->files)
    <h3>Attached Files:</h3>
    <ul>
        @foreach (json_decode($template->files) as $filePath)
            <li>
                <a href="{{ asset('storage/' . $filePath) }}" target="_blank">{{ basename($filePath) }}</a>
                {{-- <a href="{{ route('templates.download', ['template' => $template, 'filename' => $filePath]) }}">{{ basename($filePath) }}</a> --}}
            </li>
        @endforeach
    </ul>
@endif
