<div class="container-fluid my-5 py-4">
    <h1 class="fw-bold mt-2 mb-5 text-primary text-center">{{ __('messages.faq') }}</h1>
	<div class="row">
        @php($i = 0)
        @php($total = count($faq))
        @foreach($faq as $fq)
            @php($close = false)
            @php($con = "")

            @if($i == 0 || $i == $total / 2)
                @if($i == 0)
                    @php($ctx = "first")
                @elseif($i == $total / 2)
                    @php($ctx = "second")
                @endif

                @php($con = "show")
                @php($close = true)
                <div class="col-lg-6 faq-{{$ctx}}">
                    <div id="accordion{{$ctx}}">
            @endif
                    <div class="card bg-transparent border-0 mb-3">
                        <div class="card-header faq-header" id="heading{{$i}}">
                            <button class="btn btn-faq" data-bs-toggle="collapse" href="#collapse{{$i}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <h6 class="text-secondary">{{$fq->question_body}}</h6>
                                <img src="{{asset('/assets/chevron_down.png')}}" class="btn-faq-icon">
                            </button>
                        </div>
                        <div id="collapse{{$i}}" class="collapse {{$con}}" aria-labelledby="heading{{$i}}" data-bs-parent="#accordion{{$ctx}}">
                            <div class="card-body text-secondary">
                                <b>{{ __('messages.answer') }} :</b>
                                {{$fq->question_answer}}
                            </div>
                        </div>
                    </div>

            @if($i == $total / 2 - 1)
                    </div>
                </div>
            @endif

            @php($i++)
        @endforeach
            </div> <!--Check this -->
        </div>
	</div>
</div>