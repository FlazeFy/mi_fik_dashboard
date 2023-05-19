<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;  
    }
    .page-item.active .page-link{
        background:#F78A00 !important;
        border:none;
        color:#FFFFFF;
    }
    .page-item .page-link{
        color:#414141;
    }
</style>

<?php
    use Carbon\Carbon;
    use App\Helpers\Generator;
?>

<script>
    var no_values = {};
</script>

<div class="table-responsive">
    <table class="table table-paginate" id="notifTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Body</th>
                <th scope="col" style="max-width:300px;">Send To</th>
                <th scope="col">Status</th>
                <th scope="col">Manage By</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 0)
            @php($j = 0)
            @foreach($notification as $nt)
                <tr>
                    <td>
                        @php($split = explode("_",$nt['notif_type']))
                        @php($type = ucfirst($split[1]))
                        {{$type}}
                    </td>
                    <td>{{$nt['notif_body']}}</td>
                    <td>
                        @php($ntJson = $nt['notif_send_to'])
                        
                        @foreach($ntJson as $nj)
                            @if($nj['send_to'] == "all")
                                <h6>Send to {{ucfirst($nj['send_to'])}}</h6>
                            @elseif($nj['send_to'] == "person")
                                <h6>Send by {{ucfirst($nj['send_to'])}} : </h6>
                                @if(is_array($nj['context_id']))
                                    @php($list_user = $nj['context_id'])
                                    @foreach($list_user as $lu)
                                        <a class="btn btn-tag me-0" style="font-size:12px;" data-bs-toggle="popover" 
                                            title="Username" data-bs-content="<?= "@"; ?>{{$lu['username']}}">{{$lu['fullname']}}</a>
                                    @endforeach
                                @else
                                    {{$nj['context_id']}}
                                @endif
                            @elseif($nj['send_to'] == "grouping")
                                <h6>Send by {{ucfirst($nj['send_to'])}} : </h6>
                                @php($list_group = $nj['context_id'])
                                @foreach($list_group as $lg)
                                    <div class="group-box-notif">
                                        <h6 class="mt-1">{{$lg['groupname']}} </h6>
                                        <button class="btn btn-icon-preview collapse-group-box-toogle" title="Hide member" data-bs-toggle="collapse" href="#collapse_{{$nt['id']}}_{{$lg['id']}}" 
                                            onclick="toggleIcon('fa-regular fa-eye-slash','fa-regular fa-eye','{{$j}}')">
                                            <i class="fa-regular fa-eye-slash" id="icon-show-{{$j}}"></i></button>
                                        @php($list_user = $lg['user_list'])
                                        <div class="collapse" id="collapse_{{$nt['id']}}_{{$lg['id']}}">
                                            @foreach($list_user as $lu)
                                                <a class="btn btn-tag me-0" style="font-size:12px;" data-bs-toggle="popover" 
                                                    title="Username" data-bs-content="<?= "@"; ?>{{$lu['username']}}">{{$lu['fullname']}}</a>
                                            @endforeach
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
                            @endif
                        @endforeach 
                    </td>
                    <td>
                        @if($nt['is_pending'])
                            Pending until {{date('Y-m-d H:i', strtotime($nt['pending_until']))}}
                        @else 
                            Announced
                        @endif
                    </td>
                    <td style="width: 220px;">
                        <h6>Created By</h6>
                        <div class="">
                            <div class="d-inline-block">
                                <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($nt['admin_username_created'], null, $nt['admin_image_created'], null)}}" 
                                    alt="{{Generator::getProfileImageContent($nt['admin_username_created'], null, $nt['admin_image_created'], null)}}">
                            </div>
                            <div class="d-inline-block position-relative w-75">
                                <h5 class="user-username-mini" title="View Profile">{{$nt['admin_username_created']}}</h5>
                                <h6 class="properties-date date_holder_1">{{Carbon::parse($nt['created_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                            </div>
                        </div>    
                        @if($nt['updated_at'])
                            <h6>Resume At</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($nt['admin_username_updated'], null, $nt['admin_image_updated'], null)}}" 
                                        alt="{{Generator::getProfileImageContent($nt['admin_username_updated'], null, $nt['admin_image_updated'], null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini" title="View Profile">{{$nt['admin_username_updated']}}</h5>
                                    <h6 class="properties-date date_holder_2">{{Carbon::parse($nt['updated_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($nt['deleted_at'])
                            <h6>Deleted By</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($nt['admin_username_deleted'], null, $nt['admin_image_deleted'], null)}}" 
                                        alt="{{Generator::getProfileImageContent($nt['admin_username_deleted'], null, $nt['admin_image_deleted'], null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini" title="View Profile">{{$nt['admin_username_deleted']}}</h5>
                                    <h6 class="properties-date date_holder_3">{{Carbon::parse($nt['deleted_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td>
                        @if(!$nt['notif_send_to'])
                            <button class="btn btn-warning mb-2 me-1" data-bs-target="#editModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-edit"></i></button>
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