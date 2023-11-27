@extends('student.studentpure')

@section('master_content')
<main>
    <div class="row">
        <div class="col-lg-12">
            <div class=" titleforform">
                <h2 style = "padding-top: 20px;">Form Section</h2>
                {{-- <h6>(Total: {{ $template -> count() }})</h6> --}}
                <br>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
          <div class="card"  style="padding:0px; margin-left: -15px; margin-bottom:10px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Form Template</h5>
                {{-- <div class="float-right" style = " color:white;">
                  <a class="btn btn-success" data-toggle="modal" data-target="#uploadModal"><i class="fa fa-upload" style="margin-right: 5px;"></i>
                    Upload New Presentation Sche File
                    </a>
                </div> --}}
            </div>
            @foreach ($template as $formTem)
            @php
                $files = json_decode($formTem->file_data, true);
            @endphp
            <div class="card-body">
                <div class="file-container">
                    <p class="template-title">
                        {{ $formTem->file_name }}
                        <span class="toggle-arrow" onclick="toggleDetails(this)">&#9660;</span>
                    </p>
                    <div class="template-details" style="display: none;">
                        <p>{{$formTem->description}}</p>

                        <p class="file-link">

                        @if (is_array($files) && count($files) > 0)
                        &#x2514;

                        @foreach ($files as $filee)
                        <a href="{{ asset('storage/' . $filee['path']) }}" target="_blank" download class="downloadfile-link">
                            @if (Str::endsWith($filee['path'], '.pdf'))
                            <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>

                            @elseif (Str::endsWith($filee['path'], '.doc') || Str::endsWith($filee['path'], '.docx'))
                            <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>

                            @else
                            <i class="fa fa-solid fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                            @endif
                            {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }}

                        </a >
                        <span style="font-size: 80%; margin-left: 5px; color:gray">
                            {{ \Carbon\Carbon::parse($filee['uploaded_at'])->format('Y-m-d h:i A') }}
                        </span>
                </div>
                </div>
                @endforeach
                @endif

            </div>
          </div>
          @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
          <div class="card"  style="margin-top:5px; padding:0px; margin-left: -10px; margin-bottom:10px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Form Submission</h5>
            </div>

            <div class="card-body">
                     @foreach ($submissionDetails as $postform)

                        <div class="file-container">

                        {{-- <div class="templateDetails"> --}}
                            <p class="file-link"><i class="fa fa-solid fa-file-import"  style="margin-right: 10px; color: rgb(64, 31, 1);"></i>
                                <a class="brown-link" href="{{ route('stuFormSubmission.details', ['id' => $postform['submissionPost']->id]) }}">
                                {{ $postform['submissionPost']->title }}
                                </a>
                                <span class="v-chip chip--label {{ $postform['chipClass'] }}">{{ $postform['status'] }}</span>
                            </p>
                            {{-- <p>{{$postform->description}}</p> --}}
                        {{-- </div> --}}
                    </div>
                    <p class = "templateDetails">{{$postform['submissionPost']->description}}</p>
                    @endforeach
                </div>

          </div>
        </div>
    </div>

</main>

@endsection

<script>
    function toggleDetails(arrow) {
        // Find the closest template-details element
        var templateDetails = arrow.closest('.template-item').querySelector('.template-details');

        // Toggle the visibility of template-details
        templateDetails.style.display = (templateDetails.style.display === 'none' || templateDetails.style.display === '') ? 'block' : 'none';

        // Change the arrow direction based on visibility
        arrow.innerHTML = (templateDetails.style.display === 'none' || templateDetails.style.display === '') ? '&#9660;' : '&#9658;';
    }
</script>