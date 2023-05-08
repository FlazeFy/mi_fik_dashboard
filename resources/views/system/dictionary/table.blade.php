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
    <table class="table table-paginate" id="dctTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Slug</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Properties</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 0)
            @foreach($dictionary as $dc)
                <tr>
                    <td style="width: 140px;">
                        <select class="form-select" title="Change Type" onchange="validateChange(this.value, '{{$dc->id}}')" id="select-{{$dc->id}}">
                            @foreach($dictionaryType as $dct)
                                @if($dc->dct_type == $dct->app_code)
                                    <option selected value="{{$dct->app_code}}" id="{{$dct->app_code}}{{$dc->id}}">{{$dct->type_name}}</option>
                                @else 
                                    <option value="{{$dct->app_code}}" id="{{$dct->app_code}}{{$dc->id}}">{{$dct->type_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="modal fade" id="edit-type-{{$dc->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">   
                                    <div class="modal-body text-center pt-4">
                                        <button type="button" class="custom-close-modal" onclick="resetChange('select-{{$dc->id}}', '{{$dct->app_code}}')" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                        <p style="font-weight:500;">Are you sure want to change type to '<span id="dct_name{{$dc->id}}"></span>'</p>
                                        <form action="/system/dictionary/update/type/{{$dc->id}}" method="POST">
                                            @csrf
                                            <input hidden id="dct_type_{{$dc->id}}" name="dct_type" value="">
                                            <button class='btn btn-submit-form' type='submit'><i class='fa-solid fa-paper-plane'></i> Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{$dc->slug_name}}</td>
                    <td>{{$dc->dct_name}}</td>
                    <td>
                        <div style="word-break: break-all; width: 200px;">
                            @if($dc->dct_desc)
                                {{$dc->dct_desc}}
                            @else 
                                -
                            @endif
                        </div>
                    </td>
                    <td style="width: 180px;">
                        <h6>Created By</h6>
                        <div class="row p-0 m-0">
                            <div class="col-3 p-0">
                                <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                            </div>
                            <div class="col-9 p-0 ps-2 pt-1">
                                <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                <h6 class="properties-date date_holder_1">{{($dc->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                            </div>
                        </div>    
                        @if($dc->updated_at)
                            <h6>Updated By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date date_holder_2">{{($dc->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($dc->deleted_at)
                            <h6>Deleted By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date date_holder_3">{{($dc->deleted_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                </tr>
                
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

    function validateChange(slct, id){
        document.getElementById("dct_type_"+id).value = slct;
        document.getElementById("dct_name"+id).innerHTML = document.getElementById(slct+id).textContent;

        var myModal = document.getElementById('edit-type-'+id);
        var modal = new bootstrap.Modal(myModal);
        modal.show();
    }

    function resetChange(id, app_code){
        document.getElementById(id).value= app_code;
    }
</script>