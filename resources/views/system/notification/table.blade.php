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
        color:white;
    }
    .page-item .page-link{
        color:#414141;
    }
</style>

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
            @foreach($notification as $nt)
                <tr>
                    <td>
                        @php($split = explode("_",$nt->notif_type))
                        @php($type = ucfirst($split[1]))
                        {{$type}}
                    </td>
                    <td>{{$nt->notif_body}}</td>
                    <td>
                        @php($ntJson = $nt->notif_send_to)
                        
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
                                        <button class="btn btn-icon-preview collapse-group-box-toogle" title="Hide member" data-bs-toggle="collapse" href="#collapse_{{$nt['id']}}_{{$lg['id']}}">
                                            <i class="fa-regular fa-eye-slash"></i></button>
                                        @php($list_user = $lg['user_list'])
                                        <div class="collapse" id="collapse_{{$nt['id']}}_{{$lg['id']}}">
                                            @foreach($list_user as $lu)
                                                <a class="btn btn-tag me-0" style="font-size:12px;" data-bs-toggle="popover" 
                                                    title="Username" data-bs-content="<?= "@"; ?>{{$lu['username']}}">{{$lu['fullname']}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach 
                    </td>
                    <td>
                        @if($nt->is_pending)
                            Pending until {{date('Y-m-d H:i', strtotime($nt->pending_until))}}
                        @else 
                            Announced
                        @endif
                    </td>
                    <td>
                        <h6>Created By</h6>
                        <div class="row p-0 m-0">
                            <div class="col-3 p-0">
                                <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                            </div>
                            <div class="col-9 p-0 ps-2 pt-1">
                                <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                <h6 class="properties-date">{{date('Y-m-d H:i', strtotime($nt->created_at))}}</h6>
                            </div>
                        </div>    
                        @if($nt->updated_at)
                            <h6>Updated By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date">{{date('Y-m-d H:i', strtotime($nt->updated_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($nt->deleted_at)
                            <h6>Deleted By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date">{{date('Y-m-d H:i', strtotime($nt->deleted_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td>
                        @if(!$nt->notif_send_to)
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