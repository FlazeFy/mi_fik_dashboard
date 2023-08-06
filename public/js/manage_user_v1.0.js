function getLifeButton(acc, acc_date, type, id, username, fullname, width){
    if (type == "new") {
        if (!acc && !acc_date) {
          return `<a class="btn btn-detail-config success ${width}" title="Approve Account" data-bs-toggle="modal" href="#acc_user_${username}"><i class="fa-solid fa-check"></i></a>`;
        } else if (!acc && acc_date) {
          return `<a class="btn btn-detail-config success ${width}" title="Recover Account" data-bs-toggle="modal" href="#recover_user_${username}"><i class="fa-solid fa-rotate-right"></i></a>`;
        } else if (acc && acc_date) {
          return `<a class="btn btn-detail-config danger ${width}" title="Suspend Account" data-bs-toggle="modal" href="#suspend_user_${username}"><i class="fa-solid fa-power-off"></i></a>`;
        }
    } else if (type == "old") {
        const req_type = document.getElementById(`type_holder_${username}${id}`).value;
        if (req_type == "add") {
          return `<a class="btn btn-detail-config success ${width}" onclick="cleanReq(); addSelected('${id}','${username}','${req_type}','${fullname}', true)" title="Accept Request" data-bs-toggle="modal" data-bs-target="#accOldReqModal"><i class="fa-solid fa-check"></i></a>`;
        } else if (req_type == "remove") {
          return `<a class="btn btn-detail-config danger ${width}" onclick="cleanReq(); addSelected('${id}','${username}','${req_type}','${fullname}', true)" title="Reject Request" data-bs-toggle="modal" data-bs-target="#rejOldReqModal"><i class="fa-solid fa-xmark"></i></a>`;
        }
    }
}