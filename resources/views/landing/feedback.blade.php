<div class="container-fluid my-5 py-4">
    <h2 class="fw-bold mt-2 mb-5 text-primary text-center">What They Say About Us</h2>
    <div class="row">
        @php($i = 1)
        @foreach($fbc as $fc)
            @if($i % 2 != 0)
                <div class="card border-0 d-block mx-auto text-center feedback-first text-center" style="width:25%;">
                    <img class="rounded-circle img-fluid rounded shadow w-75 mx-auto d-block mb-3" src="{{asset('assets/default_lecturer.png')}}" alt="">
                    <h4 class="fw-bold">{{ __('messages.based') }} {{ucfirst($fc->feedback_suggest)}}
                        <h6 class="text-primary">
                        @for($j = 1; $j <= (int)$fc->feedback_rate; $j++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                        </h6>
                    </h4>
                    <a class="text-dark text-decoration-none">"{{$fc->feedback_body}}"</a>
                    <a class="text-dark text-decoration-none fst-italic mt-2">on <span class="date_holder_1">{{DateTime::createFromFormat('Y-m-d h:i', date("Y-m-d h:i", strtotime($fc->created_at)))->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                </div>
            @else 
                <div class="card border-0 d-block mx-auto text-center mx-4 mt-5 pt-5 p-3 text-center feedback-second" style="width:25%;">
                    <img class="rounded-circle img-fluid rounded shadow w-75 mx-auto d-block mb-3" src="{{asset('assets/default_student.png')}}" alt="">
                    <h4 class="fw-bold">{{ __('messages.based') }} {{ucfirst($fc->feedback_suggest)}}
                        <h6 class="text-primary">
                        @for($j = 1; $j <= (int)$fc->feedback_rate; $j++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                        </h6>
                    </h4>
                    <a class="text-dark text-decoration-none">"{{$fc->feedback_body}}"</a>
                    <a class="text-dark fst-italic text-decoration-none mt-2">on <span class="date_holder_1">{{DateTime::createFromFormat('Y-m-d h:i', date("Y-m-d h:i", strtotime($fc->created_at)))->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                </div>
            @endif

            @php($i++)
        @endforeach
    </div>
</div>

<script>
    const date_holder_1 = document.querySelectorAll('.date_holder_1');

    date_holder_1.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });
</script>