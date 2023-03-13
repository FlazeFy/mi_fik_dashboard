<style>
    .incoming-req-box{
        position: relative;
        height: 400px;
    }
    .user-req-holder{
        margin-top: 20px;
        padding: 0 16px 0 5px;
        display: flex;
        flex-direction: column;
        max-height: 90%;
        overflow-y: scroll;
    }
    .user-box{
        border-left: 3px solid #808080;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        padding: 5px;
        width: 100%;
        border: none;
        text-align: start;
        background: white;
        margin-bottom: 15px;
    }
    .user-box-desc{
        font-size: 13px;
        font-weight: normal;
    }

    /*Global*/
    .btn-icon-rounded-danger{
        color: #D5534C;
        border-radius: 100%;
        width: 40px;
        height: 40px;
    }
    .btn-icon-rounded-danger:hover{
        background: #D5534C;
        color: whitesmoke;
    }

    .btn-icon-rounded-success{
        color: #58C06E;
        border-radius: 100%;
        width: 40px;
        height: 40px;
    }
    .btn-icon-rounded-success:hover{
        background: #58C06E;
        color: whitesmoke;
    }

    .btn-icon-rounded-primary{
        color: #F78A00;
        border-radius: 100%;
        width: 40px;
        height: 40px;
    }
    .btn-icon-rounded-primary:hover{
        background: #F78A00;
        color: whitesmoke;
    }
</style>

<div class="incoming-req-box">
    <h5 class="text-secondary fw-bold"><span class="text-primary">3</span> Incoming Request</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:15px; top:0px;" type="button" id="section-more-incoming-req" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-incoming-req">
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-check text-success"></i> Accept All</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-xmark text-danger"></i>&nbsp Reject All</a>
    </div>

    <div class="user-req-holder">

        <!--Request tag-->
        <button class="btn user-box">
            <div class="row ps-2">
                <div class="col-2 p-0 py-2 ps-2">
                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                </div>
                <div class="col-10 p-0 py-2 ps-2 position-relative">
                    <h6 class="text-secondary fw-normal">username</h6>
                    <h6 class="user-box-desc">Requested <span class="text-primary fw-bold">#dkv</span> tag?</h6>
                    <a class="btn btn-icon-rounded-danger" style="position:absolute; right: 15px; top:15px;" title="Reject Request"><i class="fa-solid fa-xmark"></i></a>
                    <a class="btn btn-icon-rounded-success" style="position:absolute; right: 55px; top:15px;" title="Accept Request"><i class="fa-solid fa-check"></i></a>
                </div>
            </div>
        </button>

        <!--New user-->
        <button class="btn user-box">
            <div class="row ps-2">
                <div class="col-2 p-0 py-2 ps-2">
                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                </div>
                <div class="col-10 p-0 py-2 ps-2 position-relative">
                    <h6 class="text-secondary fw-normal">username</h6>
                    <h6 class="user-box-desc">Want to join Mi-FIK</h6>
                    <a class="btn btn-icon-rounded-primary" style="position:absolute; right: 15px; top:15px;" title="Accept Request & Give Role"><i class="fa-solid fa-add"></i></a>
                    <a class="btn btn-icon-rounded-success" style="position:absolute; right: 55px; top:15px;" title="Accept Request"><i class="fa-solid fa-check"></i></a>
                </div>
            </div>
        </button>

        <!--Remove tag-->
        <button class="btn user-box">
            <div class="row ps-2">
                <div class="col-2 p-0 py-2 ps-2">
                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                </div>
                <div class="col-10 p-0 py-2 ps-2 position-relative">
                    <h6 class="text-secondary fw-normal">username</h6>
                    <h6 class="user-box-desc">Want to remove <span class="text-primary fw-bold">#dkv</span> tag?</h6>
                    <a class="btn btn-icon-rounded-danger" style="position:absolute; right: 15px; top:15px;" title="Reject Request"><i class="fa-solid fa-xmark"></i></a>
                    <a class="btn btn-icon-rounded-success" style="position:absolute; right: 55px; top:15px;" title="Accept Request"><i class="fa-solid fa-check"></i></a>
                </div>
            </div>
        </button>

    </div>
</div>

<script>

</script>