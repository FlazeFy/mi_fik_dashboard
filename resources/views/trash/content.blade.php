<div class="row px-3 mt-3">
    <!-- Event -->
    <div class="col-lg-4 col-md-6 col-sm-12 pb-3">
        <button class='card shadow event-box ultimate' onclick='location.href="+'"'+"/event/detail/" + slug_name + '"' +";"+"'>
            <div class='card-header header-image' style='background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url("https://firebasestorage.googleapis.com/v0/b/mifik-83723.appspot.com/o/content_image%2F7ab314c2-daab-48b1-93ec-3d3fa5f05bc8?alt=media&token=f5537ca0-1526-4451-b124-749860fccb4b");'></div>
            <div class='event-created-at'>" + getCreatedAt(created_at) + </div>
            <div class='event-views'><i class='fa-solid fa-eye'></i> total_views + </div>
            getEventStatus(content_date_start, content_date_end) +
            <div class='card-body p-2 w-100'>
                <div class='row px-2'>
                    <div class='col-lg-2 px-1'>
                        <img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'>
                    </div>
                    <div class='col-lg-9 p-0 py-1'>
                        <h6 class='event-title'>" + content_title + </h6>
                        <h6 class='event-subtitle'>[username]</h6>
                    </div>
                </div>
                <div style='height:45px;'>
                    <p class='event-desc my-1'>" + removeTags(content_desc) + </p>
                </div>
                <div class='row d-inline-block px-2'>
                    getEventLoc(content_loc) +
                    getEventDate(content_date_start, content_date_end) +
                    getEventTag(content_tag) +
                </div>
                <hr>
                <div class="position-relative">
                    <a class="btn btn-info px-3 me-1" title="See deleted info" data-bs-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
                        <i class="fa-solid fa-info"></i>
                    </a>
                    <a class="btn btn-submit me-1" role="button" title="Recover this content">
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                    </a>
                    <a class="btn btn-danger" role="button" title="Permanently delete">
                        <i class="fa-solid fa-fire-flame-curved"></i>
                    </a>
                    <div class="form-check position-absolute" style="top:-5px; right:5px;">
                        <input class="form-check-input" style="width:30px; height:30px;" name="event_check[]" type="checkbox" value="" id="check_event_1">
                    </div>
                </div>
                <div class="collapse" id="collapseInfo">
                    <hr>
                    <div class='row px-2'>
                        <div class='col-lg-2 px-1'>
                            <img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'>
                        </div>
                        <div class='col-lg-9 p-0 py-1'>
                            <h6 class='event-title'>Deleted By / Deleted At</h6>
                            <h6 class='event-subtitle'>[username] / [datetime]</h6>
                        </div>
                    </div>
                </div>
            </div>
        </button>
    </div>

    <!-- Task -->
    <div class="col-lg-4 col-md-6 col-sm-12 p-0">
        <button class='card shadow task-box ultimate' onclick='location.href="+'"'+"/event/detail/" + slug_name + '"' +";"+"'>
            <div class='task-created-at'>" + getCreatedAt(created_at) + </div>
            <div class='task-views'><i class='fa-solid fa-eye'></i> total_views + </div>
            getEventStatus(content_date_start, content_date_end) +
            <div class='card-body p-2 w-100'>
                <div class='row px-2'>
                    <div class='col-lg-2 px-1'>
                        <img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'>
                    </div>
                    <div class='col-lg-9 p-0 py-1'>
                        <h6 class='task-title'>" + content_title + </h6>
                        <h6 class='task-subtitle'>[username]</h6>
                    </div>
                </div>
                <div style='height:45px;'>
                    <p class='task-desc my-1'>Desc</p>
                </div>
                <div class='row d-inline-block px-2'>
                    getEventDate(content_date_start, content_date_end) +
                </div>
                <hr>
                <div class="position-relative">
                    <a class="btn btn-info px-3 me-1" title="See deleted info" data-bs-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
                        <i class="fa-solid fa-info"></i>
                    </a>
                    <a class="btn btn-submit me-1" role="button" title="Recover this content">
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                    </a>
                    <a class="btn btn-danger" role="button" title="Permanently delete">
                        <i class="fa-solid fa-fire-flame-curved"></i>
                    </a>
                    <div class="form-check position-absolute" style="top:-5px; right:5px;">
                        <input class="form-check-input" style="width:30px; height:30px;" name="task_check[]" type="checkbox" value="" id="check_task_1">
                    </div>
                </div>
                <div class="collapse" id="collapseInfo">
                    <hr>
                    <div class='row px-2'>
                        <div class='col-lg-2 px-1'>
                            <img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'>
                        </div>
                        <div class='col-lg-9 p-0 py-1'>
                            <h6 class='task-title'>Deleted By / Deleted At</h6>
                            <h6 class='task-subtitle'>[username] / [datetime]</h6>
                        </div>
                    </div>
                </div>
            </div>
        </button>
    </div>
</div>

<script>

</script>