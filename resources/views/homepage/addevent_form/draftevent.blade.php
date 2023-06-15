<div class="modal fade" id="browseDraftEventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Draft Event</h5>
                <p class="text-secondary">You still have some unfinished event, you can resume these event</p>
                <div class="row p-0">
                    <div class='col-lg-4 col-md-6 col-sm-12 pb-3'>
                        <button class="container shadow bg-primary w-100 h-100 border-0" style="border-radius:12px;" data-bs-dismiss="modal" data-bs-target="#addEventModal" data-bs-toggle="modal">
                            <i class="fa-solid fa-plus text-white" style="font-size:24px;"></i><br>
                            <h5 class="text-white">Create a new one</h5>
                        </button>
                    </div>

                    @foreach($mydraft as $md)
                        @if($md->admin_username_created != null)
                            @php($username = $md->admin_username_created)
                        @elseif($md->user_username_created != null)
                            @php($username = $md->user_username_created)
                        @endif

                        <div class='col-lg-4 col-md-6 col-sm-12 pb-3'>
                            <button class='card shadow event-box p-2' style="min-height:160px; height:auto;" onclick='location.href="/event/detail/{{$md->slug_name}}";'>
                                <div class='text-primary' style="font-size:12px;">{{date("Y/m/d H:i", strtotime($md->created_at))}}</div>
                                <div class='card-body py-2 px-0 w-100'>
                                    <div class=''>
                                        <div class='d-inline-block'>
                                            @if($md->admin_username_created != null)
                                                @if($md->admin_image_created != null)
                                                    <img class='img img-fluid user-image-content' src="{{$md->admin_image_created}}" alt='username-profile-pic.png'>
                                                @else
                                                    <img class='img img-fluid user-image-content' src="{{asset('assets/default_admin.png')}}" alt='username-profile-pic.png'>
                                                @endif
                                            @elseif($md->user_username_created != null)
                                                @if($md->user_image_created)
                                                    <img class='img img-fluid user-image-content' src="{{$md->user_image_created}}" alt='username-profile-pic.png'>
                                                @else
                                                    <img class='img img-fluid user-image-content' src="{{asset('assets/default_lecturer.png')}}" alt='username-profile-pic.png'>
                                                @endif
                                            @endif
                                        </div>
                                        <div class='d-inline-block position-relative w-75'>
                                            <h6 class='event-title'>{{$md->content_title}}</h6>
                                            <h6 class='event-subtitle'>{{$username}}</h6>
                                        </div>
                                    </div>
                                    <div style='height:45px;'>
                                        <p class='event-desc my-1'>{{strip_tags($md->content_desc)}}</p>
                                    </div>
                                    <div class='row d-inline-block px-2'>
                                        @if($md->content_tag)
                                            @php($tag = $md->content_tag)

                                            @if($md->content_loc)
                                                <span class='loc-limiter px-0 m-0'>
                                                    <a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> {{$md->content_loc[0]['detail']}}</a>
                                                </span>
                                            @endif
                                            
                                            @php($str = "")
                                            @for($i = 0; $i < count($tag); $i++)
                                                @if($i != count($tag) - 1)
                                                    @php($str .= "".$tag[$i]['tag_name'].", ")
                                                @else
                                                    @php($str .= "".$tag[$i]['tag_name']."")
                                                @endif
                                            @endfor
                                            <a class="btn-detail" title="{{$str}}"><i class="fa-solid fa-hashtag"></i>{{count($tag)}}</a>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>