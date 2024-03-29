<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;  
    }
    .page-item.active .page-link{
        background:var(--primaryColor) !important;
        border:none;
        color:var(--whiteColor);
    }
    .page-item .page-link{
        color:var(--darkColor);
    }
    .notif-result{
        font-size: 12.5px;
        font-weight: 400;
        color: #5B5B5B;
    }
    .notif-result-box{
        border-radius: var(--roundedSM);
        padding: 8px;
        position: relative;
        background: #C6EFF8;
    }
    .notif-result-box label{
        font-size: 12.5px;
    }
</style>

<?php
    use Carbon\Carbon;
    use App\Helpers\Generator;
?>

<script>
    var no_values = {};
</script>

<h5 class="section-title">{{ __('messages.all_notif') }}</h5>
<div class="table-responsive">
    <table class="table table-paginate" id="notifTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" style="min-width:var(--tcolMinSM);">{{ __('messages.type') }}</th>
                <th scope="col" style="min-width:var(--tcolMinSM);">{{ __('messages.content') }}</th>
                <th scope="col" style="<?php if(!$isMobile){ echo'min-width:var(--tcolMinJumbo);'; } else { echo 'min-width:calc(var(--tcolMinSM) + var(--tcolMinMD));'; } ?>">{{ __('messages.send_to') }}</th>
                <th scope="col" style="min-width:var(--tcolMinSM);">Status</th>
                <th scope="col" style="min-width:calc(var(--tcolMinJumbo) - 30px);">{{ __('messages.manage_by') }}</th>
                <th scope="col" style="min-width:var(--tcolMinXSM);">{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 0)
            @php($j = 0)
            @foreach($notification as $nt)
                <tr>
                    <td style="min-width:var(--tcolMinSM);">
                        @php($split = explode("_",$nt['notif_type']))
                        @php($type = ucfirst($split[1]))
                        {{$type}}
                    </td>
                    <td style="min-width:var(--tcolMinSM);">
                        <h6 class="mb-0">{{ __('messages.title') }}</h6>
                        {{$nt['notif_title']}}
                        <h6 class="mb-0 mt-2">{{ __('messages.body') }}</h6>
                        {{$nt['notif_body']}}
                    </td>
                    <td style="<?php if(!$isMobile){ echo'min-width:var(--tcolMinJumbo);'; } else { echo 'min-width:calc(var(--tcolMinSM) + var(--tcolMinMD));'; } ?>">
                        @php($ntJson = $nt['notif_send_to'])

                        @if($nt['is_pending'] == 0)
                            @foreach($ntJson as $nj)
                                @if($nj['send_to'] == "all")
                                    <h6>Send to {{ucfirst($nj['send_to'])}}</h6>
                                @elseif($nj['send_to'] == "person")
                                    <h6>{{ __('messages.send_by') }} {{ucfirst($nj['send_to'])}} : </h6>
                                    @if(is_array($nj['context_id']))
                                        @php($list_user = $nj['context_id'])
                                        @foreach($list_user as $lu)
                                            <a tabindex="0" class="btn btn-tag me-0" style="font-size:12px;" data-bs-toggle="popover" data-bs-trigger="focus"
                                                title="Username" data-bs-content="<?= "@"; ?>{{$lu['username']}}"><i class="fa-solid fa-user"></i> {{$lu['fullname']}}</a>
                                        @endforeach
                                    @else
                                        {{$nj['context_id']}}
                                    @endif
                                @elseif($nj['send_to'] == "grouping")
                                    <h6>{{ __('messages.send_by') }} {{ucfirst($nj['send_to'])}} : </h6>
                                    @php($list_group = $nj['context_id'])
                                    @foreach($list_group as $lg)
                                        <div class="group-box-notif">
                                            <h6 class="mt-1">{{$lg['groupname']}} </h6>
                                            <button class="btn btn-icon-preview collapse-group-box-toogle" title="Hide member" data-bs-toggle="collapse" href="#collapse_{{$nt['id']}}_{{$lg['id']}}" 
                                                onclick="toggleIcon('fa-regular fa-eye-slash','fa-regular fa-eye','{{$j}}')">
                                                <i class="fa-regular fa-eye-slash" id="icon-show-{{$j}}"></i></button>
                                            @php($list_user = $lg['user_list'])
                                            <div class="collapse" id="collapse_{{$nt['id']}}_{{$lg['id']}}">
                                                @if($list_user)
                                                    @foreach($list_user as $lu)
                                                        <a tabindex="0" class="btn btn-tag me-0" style="font-size:12px;" data-bs-toggle="popover" data-bs-trigger="focus"
                                                            title="Username" data-bs-content="<?= "@"; ?>{{$lu['username']}}"><i class="fa-solid fa-user"></i> {{$lu['fullname']}}</a>
                                                    @endforeach
                                                @else
                                                    <a style="font-size:13px;"><i class="fa-solid fa-triangle-exclamation"></i> {{ __('messages.no_member') }}</a>
                                                @endif
                                            </div>
                                        </div>

                                        <script>
                                            <?php 
                                                echo "var no_".$j." = 1;"; 
                                                echo "no_values[".$j."] = window['no_' + ".$j."];";
                                            ?>
                                        </script>
                                        @php($j++)
                                    @endforeach
                                @elseif($nj['send_to'] == "role")
                                    <h6>{{ __('messages.send_by') }} {{ucfirst($nj['send_to'])}} : </h6>
                                    <div class="group-box-notif">
                                        <div class="mt-1">
                                            @php($tag_list = $nj['context_id']['tag_list'])
                                            @foreach($tag_list as $tgl)
                                                <a class="btn btn-tag me-0" style="font-size:12px;"><i class="fa-solid fa-hashtag"></i> {{$tgl['tag_name']}}</a>
                                            @endforeach
                                        </div>
                                        <button class="btn btn-icon-preview collapse-group-box-toogle" title="Hide member" data-bs-toggle="collapse" href="#collapse_{{$nt['id']}}_role" 
                                            onclick="toggleIcon('fa-regular fa-eye-slash','fa-regular fa-eye','{{$j}}')">
                                            <i class="fa-regular fa-eye-slash" id="icon-show-{{$j}}"></i></button>
                                        @php($user_list = $nj['context_id']['user_list'])
                                        <div class="collapse" id="collapse_{{$nt['id']}}_role">
                                            @foreach($user_list as $lu)
                                                <a tabindex="0" class="btn btn-tag me-0" style="font-size:12px;" data-bs-toggle="popover" data-bs-trigger="focus"
                                                    title="Username" data-bs-content="<?= "@"; ?>{{$lu['username']}}"><i class="fa-solid fa-user"></i> {{$lu['fullname']}}</a>
                                            @endforeach
                                        </div>

                                        <script>
                                            <?php 
                                                echo "var no_".$j." = 1;"; 
                                                echo "no_values[".$j."] = window['no_' + ".$j."];";
                                            ?>
                                        </script>
                                        @php($j++)
                                    </div>
                                @endif

                                @if(isset($nj['status']) && $nj['status'] != false) 
                                    <div class="notif-result-box mt-2">
                                        <label><i class="fa-solid fa-circle-info"></i> {{ __('messages.summary') }}</label><br>
                                        <h6 class="notif-result mb-0">{{$nj['status']}}</h6>
                                    </div>
                                @endif
                            @endforeach 
                        @else 
                            <div class="notif-result-box mt-2">
                                <h6 class="mt-1" style="font-size:16px;">{{ __('messages.resume') }}</h6>
                                <button class="btn btn-icon-preview collapse-group-box-toogle" title="Hide member" data-bs-toggle="collapse" href="#collapseResume_{{$nt['id']}}">
                                    <i class="fa-solid fa-play"></i></button>
                                <div class="collapse" id="collapseResume_{{$nt['id']}}">
                                    <button class="btn btn-info mb-2" onclick="setType('All User'); transfer('<?= $nt['id']; ?>','<?= $nt['notif_type']; ?>','<?= $nt['notif_title']; ?>','<?= $nt['notif_body']; ?>');" data-bs-dismiss="modal" data-bs-target="#addModal" title="All User" data-bs-toggle="modal">All User</button>
                                    <button class="btn btn-info mb-2" onclick="setType('Role'); transfer('<?= $nt['id']; ?>','<?= $nt['notif_type']; ?>','<?= $nt['notif_title']; ?>','<?= $nt['notif_body']; ?>');" data-bs-dismiss="modal" data-bs-target="#addModal" title="By Role" data-bs-toggle="modal">By Role</button>
                                    <button class="btn btn-info mb-2" onclick="setType('Grouping'); transfer('<?= $nt['id']; ?>','<?= $nt['notif_type']; ?>','<?= $nt['notif_title']; ?>','<?= $nt['notif_body']; ?>');" data-bs-dismiss="modal" data-bs-target="#addModal" title="By Group" data-bs-toggle="modal">By Group</button>
                                    <button class="btn btn-info mb-2" onclick="setType('Person'); transfer('<?= $nt['id']; ?>','<?= $nt['notif_type']; ?>','<?= $nt['notif_title']; ?>','<?= $nt['notif_body']; ?>');" data-bs-dismiss="modal" data-bs-target="#addModal" title="By Person" data-bs-toggle="modal">By Person</button>
                                </div>
                            </div>
                            @php($j++)
                        @endif
                    </td>
                    <td style="min-width:var(--tcolMinSM);" class="text-center">
                        @if($nt['is_pending'] && $nt['pending_until'])
                            <div class="status-info bg-danger" style="font-size:12px;">Pending until <br>
                                {{date('Y-m-d H:i', strtotime($nt['pending_until']))}}</div>
                        @elseif($nt['is_pending'] && !$nt['pending_until'])
                            <div class="status-info bg-danger w-100">{{ __('messages.draft') }}</div>
                        @else 
                            <div class="status-info bg-success w-100">{{ __('messages.announced') }}</div>
                        @endif
                    </td>
                    <td style="min-width:calc(var(--tcolMinJumbo) - 30px);">
                        <h6>{{ __('messages.created_at') }}</h6>
                        <div class="">
                            <div class="d-inline-block">
                                <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($nt['admin_username_created'], null, $nt['admin_image_created'], null)}}" 
                                    alt="{{Generator::getProfileImageContent($nt['admin_username_created'], null, $nt['admin_image_created'], null)}}">
                            </div>
                            <div class="d-inline-block position-relative w-75">
                                <h5 class="user-username-mini">{{"@"}}{{$nt['admin_username_created']}}</h5>
                                <h6 class="properties-date date_holder_1">{{Carbon::parse($nt['created_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                            </div>
                        </div>    
                        @if($nt['updated_at'])
                            <h6>{{ __('messages.resume_at') }}</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($nt['admin_username_updated'], null, $nt['admin_image_updated'], null)}}" 
                                        alt="{{Generator::getProfileImageContent($nt['admin_username_updated'], null, $nt['admin_image_updated'], null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini">{{"@"}}{{$nt['admin_username_updated']}}</h5>
                                    <h6 class="properties-date date_holder_2">{{Carbon::parse($nt['updated_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($nt['deleted_at'])
                            <h6>{{ __('messages.deleted_by') }}</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($nt['admin_username_deleted'], null, $nt['admin_image_deleted'], null)}}" 
                                        alt="{{Generator::getProfileImageContent($nt['admin_username_deleted'], null, $nt['admin_image_deleted'], null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini">{{"@"}}{{$nt['admin_username_deleted']}}</h5>
                                    <h6 class="properties-date date_holder_3">{{Carbon::parse($nt['deleted_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td style="min-width:var(--tcolMinXSM);">
                        @if(!$nt['notif_send_to'])
                            <button class="btn btn-info mb-2 me-1" data-bs-target="#editModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-edit"></i></button>
                        @endif
                        <button class="btn btn-danger" data-bs-target="#deleteModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>

                @include('system.notification.edit')
                @include('system.notification.delete')
                
                @php($i++)
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const date_holder_1 = document.querySelectorAll('.date_holder_1');
    const date_holder_2 = document.querySelectorAll('.date_holder_2');
    const date_holder_3 = document.querySelectorAll('.date_holder_3');

    date_holder_1.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    date_holder_2.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    date_holder_3.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    function toggleIcon(initIcon, tglIcon, id) {
        var icon = document.getElementById("icon-show-" + id);
        no_values[id]++;
        var check = no_values[id]; 

        if (check % 2 !== 0) {
            icon.setAttribute('class', initIcon);
        } else {
            icon.setAttribute('class', tglIcon);
        }
    }
</script>