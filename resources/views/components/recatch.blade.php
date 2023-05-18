@if(Session::has('recatch_message'))
    <div class="modal fade" id="recatchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">  
                <div class="modal-body pt-1">
                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <h5>Summary</h5>

                    @php($i = 0)
                    @foreach($count as $ct)
                        @php($req = 0)
                        @php($empty = 0)
                        @php($new = 0)

                        @if(isset($count->count_request))
                            @php($req = $count->count_request)
                        @endif
                        @if(isset($count->count_empty))
                            @php($empty = $count->count_empty)
                        @endif
                        @if(isset($count->count_new))
                            @php($new = $count->count_new)
                        @endif

                        @if(($req > 0 || $empty > 0  || $new > 0) && $i == 0)
                            <img class="img img-fluid d-block mx-auto" style="width: 320px;" src="{{'/assets/recatch.png'}}">
                            <h5 class="text-center">Welcome back {{session()->get('username_key')}}</h5>
                            <h6 class="text-center text-secondary">While you go. You have <span class="text-primary">{{$count->count_request}}</span> role's request, <span class="text-primary">{{$count->count_empty_role}}</span> empty role and <span class="text-primary">{{$count->count_new}}</span> new user who is waiting to join Mi-FIK</h6>
                            <a class="btn btn-submit-form" href="{{url('/user/request')}}"><i class="fa-solid fa-arrow-right"></i> Bring me there</a></span>
                        @endif
                        @php($i++)
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    //Modal setting.
    $(window).on('load', function() {
        $('#recatchModal').modal('show');
    });
</script>