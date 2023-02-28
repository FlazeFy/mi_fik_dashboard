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

    .input-custom{
        border-radius:6px;
        padding:4px 4px 4px 25px;
        border:none;
    }
    .input-custom:hover, .input-custom:focus{
        background:#f0f0f0;
    }
    
    /*Icon color must change on input focus*/
</style>

<style>
    .input-warning{
        font-size:14px;
    }
    .btn-quick-action{
        border-radius:6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height:15vh;
        border:none;
        width:100%;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        transition: 0.5s;
        text-align:left;
        padding:10px;
    }
    .btn-quick-action:hover{
        background: #F78A00 !important;
        background-image:none !important;
    }
    .quick-action-text{
        font-size:24px;
        color:white;
        transition: 0.5s;
        margin-top:9vh;
    }
    .quick-action-info{
        font-size:16px;
        color:white;
        transition: 0.5s;
        display:none;
    }
    .btn-quick-action:hover .quick-action-text{
        margin-top:-4vh;
    }
    .btn-quick-action:hover .quick-action-info{
        display:block;
    }
    .btn-tag{
        background:white;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:#414141;
        font-weight:400;
        border:1.5px solid #F78A00;
    }
    .btn-tag:hover, .btn-tag-selected{
        background:#F78A00;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:whitesmoke !important;
    }

    /*Richtext header*/
    .ql-toolbar.ql-snow{
        border:1.7px solid #F78A00;
        margin-top:10px;
        border-radius:10px 10px 0 0;
    }
    .ql-snow .ql-stroke {
        stroke:#414141;
    }
    .ql-snow.ql-toolbar button{
        border-radius:6px;
        height:25px;
        width:25px;
        padding-left:3px;
        margin-right:10px;
        transition: all 0.4s;
    }
    .ql-snow.ql-toolbar button:hover .ql-stroke{
        stroke:#F78A00;
    }
    button.ql-active{
        background:#F78A00 !important;
    }
    button.ql-active svg .ql-stroke{
        stroke:white !important;
    }

    /*Richtext body*/
    .ql-toolbar.ql-snow + .ql-container.ql-snow{
        height:30vh;
    }
    .input-title{
        font-weight: 500;
    }
</style>

<div class="text-nowrap table-responsive">
    <table class="table table-paginate" id="notifTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Body</th>
                <th scope="col">Send To</th>
                <th scope="col">Pending</th>
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
                        @php($ntJson = json_decode($nt->notif_send_to))
                            
                        @foreach($ntJson as $nj)
                            @if($nj->send_to == "all")
                                {{ucfirst($nj->send_to)}}
                            @else 
                                {{$nj->user_id}}
                            @endif
                        @endforeach 
                    </td>
                    <td>
                        @if($nt->is_pending)
                            Pending until {{date('Y-m-d h:i', strtotime($nt->pending_until))}}
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
                                <h5 class="user-username">Budi</h5>
                                <h6 class="user-role">{{date('Y-m-d h:i', strtotime($nt->created_at))}}</h6>
                            </div>
                        </div>    
                        @if($nt->updated_at)
                            <h6>Updated By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username">Budi</h5>
                                    <h6 class="user-role">{{date('Y-m-d h:i', strtotime($nt->updated_at))}}</h6>
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
                                    <h5 class="user-username">Budi</h5>
                                    <h6 class="user-role">{{date('Y-m-d h:i', strtotime($nt->deleted_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning" data-bs-target="#editModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-edit"></i></button>
                        <button class="btn btn-danger" data-bs-target="#deleteModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>

                @include('system.notification.edit')
                @php($i++)
            @endforeach
        </tbody>
    </table>
</div>