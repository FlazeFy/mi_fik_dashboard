<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;
    }
</style>

<div class="table-responsive">
    <table class="table table-paginate" id="notifTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Page / Location</th>
                <th scope="col">Body</th>
                <th scope="col">Is Active</th>
                <th scope="col">Properties</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 0)
            @foreach($info as $in)
                <tr>
                    <td>
                        <form action="/system/info/update/{{$in->id}}" method="POST">
                            @csrf
                            <select class="form-select" name="info_type" title="Change Type" onchange="this.form.submit()">
                                @foreach($dictionary as $dct)
                                    @if($in->info_type == strtolower($dct->dct_name))
                                        <option selected value="{{strtolower($dct->dct_name)}}">{{$dct->dct_name}}</option>
                                    @else 
                                        <option value="{{strtolower($dct->dct_name)}}">{{$dct->dct_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <p class="mb-0">Page : <a class="text-primary" href="{{url($in->info_page)}}" style="cursor:pointer;">{{$in->info_page}}</a></p>
                        <p>Location : {{$in->info_location}}</p>
                    </td>
                    <td >
                        <div style="word-break: break-all; width: 300px;">
                            <?= $in->info_body; ?>
                        </div>
                    </td>
                    <td>
                        <h6>Created By</h6>
                        <div class="row p-0 m-0">
                            <div class="col-3 p-0">
                                <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                            </div>
                            <div class="col-9 p-0 ps-2 pt-1">
                                <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                <h6 class="properties-date">{{date('Y-m-d h:i', strtotime($in->created_at))}}</h6>
                            </div>
                        </div>    
                        @if($in->updated_at)
                            <h6>Updated By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date">{{date('Y-m-d h:i', strtotime($in->updated_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($in->deleted_at)
                            <h6>Deleted By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date">{{date('Y-m-d h:i', strtotime($in->deleted_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning" data-bs-target="#editModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-edit"></i></button>
                        <button class="btn btn-danger" data-bs-target="#deleteModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                
                @php($i++)
            @endforeach
        </tbody>
    </table>
</div>