@extends('student.studentpure')

@section('master_content')
<main>
    <div class="form-section" style="display: flex; align-items: center; ">
        <i class="fas fa-clipboard" style="font-size: 30px; margin-right:15px"></i>
        <h1 style="padding-top: 1%">Proposal Section</h1>
    </div>

    <div class="row" style="margin-left: 0%">
        <div class="col-md-12">
            <div class="card"  style="z-index:10;margin-top:20px; margin-left: -15px; margin-bottom:30px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Proposal Template</h3>
                </div>
                {{-- end card header --}}

                <div class="card-body">
                    @foreach ($template as $theTem)
                    @php
                        $files = json_decode($theTem->file_data, true);
                    @endphp
                    <div class="template-item">
                        {{-- <div class="file-container"> --}}
                            <p class="template-title">
                                {{ $theTem->file_name }}
                                {{-- <span class="toggle-arrow" onclick="toggleDetails(this)">
                                    <i class="mdi mdi-arrow-right-drop-circle"></i>
                                </span> --}}
                                <span class="toggle-arrow mdi mdi-arrow-right-drop-circle" onclick="toggleDetails(this)"></span>

                            </p>
                        {{-- <div class="file-container"> --}}
                            <div class="template-details" style="display: none;">
                            {{-- </div> --}}
                        <p class="template-description">{{ $theTem->description }}</p>
                            @if (is_array($files) && count($files) > 0)
                                @foreach ($files as $filee)
                                &#x2514;
                                <a href="{{ asset('storage/' . $filee['path']) }}" target="_blank" download class="downloadfile-link">
                                    @if (Str::endsWith($filee['path'], '.pdf'))
                                    <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>
                                    {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }}
                                    {{ basename($filee['path']) }}

                                    @elseif (Str::endsWith($filee['path'], '.doc') || Str::endsWith($filee['path'], '.docx'))
                                    <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>
                                    {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }}

                                    @else
                                    <i class="fa fa-solid fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                                    {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }}

                                    @endif

                                </a>
                                <br>
                                @endforeach
                            @endif
                            {{-- end if for files --}}
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- end card-body --}}
            </div>
        </div>
    </div>
    {{-- end row --}}

    {{-- start row for submission column --}}
    <div class="row" style="margin-left: 0%">
        <div class="col-md-12">
            <div class="card"  style="margin-top:5px; padding:0px; margin-left: -15px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Proposal Submission</h3>
                </div>
                {{-- end card header --}}

                <div class="card-body">
                    @foreach ($submissionDetails as $postform)
                    <div class="file-container eachSubRow">
                        <h2 class="file-link"><i class="fa fa-solid fa-file-import"  style="margin-right: 10px; color: rgb(64, 31, 1);"></i>
                            <a class="brown-link" style="font-size: 14px" href="{{ route('stuProposalSubmission.details', ['submissionPostId' => $postform['submissionPost']->id]) }}">

                            {{ $postform['submissionPost']->title }}
                            </a>
                            <span class="v-chip chip--label {{ $postform['chipClass'] }}">{{ $postform['status'] }}</span>
                        </h2>
                    </div>
                    {{-- <p class = "templateDetails">{{$postform['submissionPost']->description}}</p> --}}

                    @endforeach
                </div>
            </div>
            {{-- end card --}}
        </div>
    </div>
    {{-- end row --}}

</main>
@endsection

<script>
    function toggleDetails(arrow) {
        // Find the closest template-details element
        var templateDetails = arrow.closest('.template-item').querySelector('.template-details');

        // Toggle the visibility of template-details
        templateDetails.style.display = (templateDetails.style.display === 'none' || templateDetails.style.display === '') ? 'block' : 'none';
        // Toggle the rotation class for the arrow icon
        arrow.classList.toggle('rotate-down');

    }
</script>