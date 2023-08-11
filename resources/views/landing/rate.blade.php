<script>
    let validation = [
        { id: "feedback_body", req: true, len: 255 },
    ];
</script>

<div class="container-fluid my-5 py-4 rate-container">
    <div class="container shadow d-block mx-auto p-4 my-5" style="max-width:500px; border-radius:20px;">
        <h2 class="fw-bold mt-2 mb-4 text-primary text-center">{{ __('messages.rate_us') }}</h2>
        <form class="p-2 mt-2" action="/add_feedback" method="POST">
            @csrf
            @include('components.infobox',['info'=>$info, 'location'=> 'add_feedback'])
            <div class="form-floating mb-2">
                <select class="form-select" id="feedback_suggest" name="feedback_suggest" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                    @php($i = 0)
                    @foreach($dictionary as $dct)
                        @if($i == 0)
                            <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                        @else 
                            <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                        @endif
                        @php($i++)
                    @endforeach
                </select>
                <label for="feedback_suggest">{{ __('messages.improvment_desc') }}</label>
                <a id="feedback_suggest_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <div class="form-floating">
                <textarea class="form-control" style="height: 100px" id="feedback_body" name="feedback_body" oninput="setrate()" maxlength="255"></textarea>
                <label for="feedback_body">{{ __('messages.wdyt') }}</label>
                <a id="feedback_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <div class="range mt-2">
                <label for="feedback_rate">{{ __('messages.rate_us_scale') }}</label>
                <input type="range" class="form-range" min="1" max="5" value="4" id="feedback_rate" name="feedback_rate" oninput="setrate(this.value)" required/>
            </div>
            <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
            <span class="text-success" style="font-weight:500;" id="feedback_rate_show"></span>
        </form>
    </div>
</div>

<script>
    function setrate(){
        validateForm(validation);
        var body = document.getElementById("feedback_body").value;
        if(body.length > 1 && body.length <= 255){
            var rate = document.getElementById("feedback_rate").value;
            document.getElementById("feedback_rate_show").innerHTML = `&nbsp with ${rate} stars`;
        }
    }
</script>