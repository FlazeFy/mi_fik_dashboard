function deleteAfterCharacter(str, character) {
    var index = str.indexOf(character);
    if (index !== -1) {
        return str.slice(0, index);
    } else {
        return str;
    }
}

function getAttCode() {
    let col = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        let index = Math.floor(Math.random() * col.length);
        code += col[index];
    }
    return code;
}

function getTag(obj, padding, sz, margin){
    var res = " ";

    if(obj != null && obj.length > 0){
        obj.forEach(e => {
            res += `
                <a class='btn btn-primary ${padding} ${margin}' style='font-size:${sz}'>
                    ${e.tag_name}
                </a>
            `;
        });
    } 

    return res;
}

function getLocationName(loc){
    if(loc && loc.length == 2){
        loc[0].detail != null ? res = loc[0].detail : res = loc[1].detail;

        return `
            <span class='loc-limiter px-0 m-0'> 
                <a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> ${res}</a>
            </span>
        `;
    } else if(loc && loc.length != 2){
        return `
            <span class='loc-limiter px-0 m-0'> 
                <a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> ${messages('invalid')}</a> 
            </span>
        `;
    } else {
        return "";
    }
}

function getEventTag(tag){
    if(tag){
        var str = "";

        for(var i = 0; i < tag.length; i++){
            if(i != tag.length - 1){
                str += tag[i].tag_name +", ";
            } else {
                str += tag[i].tag_name;
            }
        }

        return `<a class="btn-detail" title="${str}"><i class="fa-solid fa-hashtag"></i>${tag.length}</a>`;
    } else {
        return "";
    }
}

function getName(val1 ,val2){
    if(val1 == null || val1 == "null"){
        return val2;
    } else {
        return val1;
    }
}

function messageCopy(val){
    navigator.clipboard.writeText(val)
    .then(function() {
        document.getElementById("success_toast_msg").innerHTML = "Token has been added to clipboard";
        $('#success_toast').toast('show');
    })
    .catch(function(err) {
        document.getElementById("err_modal_msg").innerHTML = err;
        $('#error_modal').modal('show');
    });
}

function getContentImage(img){
    if(img){
        return "url('"+img+"')";
    } else {
        return "url('http://127.0.0.1:8000/assets/default_content.jpg')";
    }
}

function getUserImage(img1, img2, user1){
    if(img1 || img2){
        if(img1){
            return img1;
        } else {
            return img2;
        }
    } else {
        if(user1){
            return 'http://127.0.0.1:8000/assets/default_admin.png';
        } else {
            return 'http://127.0.0.1:8000/assets/default_lecturer.png';
        }
    }
}

function getUserImageGeneral(img, role){
    if(img){
        return img;
    } else {
        if(Array.isArray(role)){
            for(var i = 0; i < role.length; i++){
                if(role[i].slug_name == "student"){
                    return "http://127.0.0.1:8000/assets/default_student.png";
                } else if(role[i].slug_name == "lecturer" || role[i].slug_name == "staff"){
                    return "http://127.0.0.1:8000/assets/default_lecturer.png";
                }
            }
        } else {
            return "http://127.0.0.1:8000/assets/default_lecturer.png";
        }
    }
}

function getUUID() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function getUsername(username1, username2){
    if(username1){
        if(username1 == myname){
            return `${messages('you')}`;
        } else {
            return "@"+username1;
        }
    } else if (username2){
        if(username2 == myname){
            return `${messages('you')}`;
        } else {
            return "@"+username2;
        }
    } else {
        return `<span class='text-danger'>${messages('unknownuser')}</span>`;
    }
}

function getEventStatus(start, end){
    const c_start = new Date(start);
    const c_end = new Date(end);
    const offsetHours = getUTCHourOffset();

    c_start.setUTCHours(c_start.getUTCHours() + offsetHours);
    c_end.setUTCHours(c_end.getUTCHours() + offsetHours);

    const now = new Date();

    const msDiff_start = c_start.getTime() - now.getTime();
    const msDiff_end = c_end.getTime() - now.getTime();

    const hourDiff_start = Math.round(msDiff_start / (1000 * 60));
    const hourDiff_end = Math.round(msDiff_end / (1000 * 60));

    if (c_start >= now && c_end >= now && hourDiff_start >= 0 && hourDiff_start <= 15) {
        return `<div class='event-status bg-primary'><i class='fa-solid fa-circle fa-2xs'></i> ${messages('astart')}</div>`;
    } else if (c_start <= now && c_end >= now) {
        if (hourDiff_end > 1 && hourDiff_start > -15) {
            var ctx_live = ` ${messages('jstart')}`;
        } else if (hourDiff_end > 15) {
            var ctx_live = ` ${messages('live')}`;
        } else {
            var ctx_live = ` ${messages('toend')}`;
        }
        return `<div class='event-status bg-danger'><i class='fa-solid fa-circle fa-2xs'></i>${ctx_live}</div>`;
    } else if (c_start <= now && c_end <= now && hourDiff_end <= 0 && hourDiff_end >= -15) {
        return `<div class='event-status bg-success'><i class='fa-solid fa-circle fa-2xs'></i> ${messages('jend')}</div>`;
    } else if (c_start <= now && c_end <= now && hourDiff_end <= -15){
        return `<div class='event-status bg-success'><i class='fa-solid fa-check'></i> ${messages('finished')}</div>`;
    } else {
        return "";
    }
}

function setDatePickerMinNow(elmt){
    const now = new Date();
    document.getElementById(elmt).setAttribute("min",getDateToContext(now, "date"));
}

function setDatePickerMin(elmt, date){
    const dt = new Date(date);
    document.getElementById(elmt).setAttribute("min",getDateToContext(dt, "date"));
}

function modifyTableControl(id,ext){
    var table = document.getElementById(id+"_wrapper");

    if (table) {
        var header = table.firstElementChild;
        var col = header.children;

        for (var i = 0; i < col.length; i++) {
            var e = col[i];
            e.style = 'width:auto; display:inline-block;';
        }

        if(ext !== null){
            ext.forEach(el => {
                var id_elmt = document.getElementById(el.id);
                header.appendChild(id_elmt);
                id_elmt.style = 'width:auto; display:inline-block;';
            });
        }
    }
}

// Guidelines
function getDivPosition(id) {
    const element = document.getElementById(id);
    if (element) {
        const rect = element.getBoundingClientRect();
        return {
            top: rect.top + rect.height * 0.75 + window.pageYOffset + "px",
            left: rect.left + window.pageXOffset + "px",
            right: rect.right + window.pageXOffset + "px",
            bottom: rect.bottom + rect.height * 0.75 + window.pageYOffset + "px",
            width: rect.width + "px",
            height: rect.height + "px",
            top_raw : rect.top + window.pageYOffset + "px",
        };
    }
    return null;
}

function getModalResponsive(id, target, dir){
    const pos = getDivPosition(target);

    if(dir == "bottom"){
        document.getElementById(id).style = `position:fixed; left: ${pos['left']}; top: ${pos['top']}; width: ${pos['width']}; min-width:250px;`;
    } else if(dir == "right"){
        document.getElementById(id).style = `position:fixed; left: ${pos['right']}; top: ${pos['top_raw']}; width: ${pos['width']}; min-width:250px;`;
    } 
}

function navigateGuidelines(num){
    var next_num = num + 1;
    $("#modal-parent-"+num).modal({ backdrop: 'static' }).modal('hide'); 
    $("#modal-parent-"+next_num).modal('show'); 
}

function setGuidelinesModal(conf, is_show_all){
    var i = 0;

    if(is_show_all == true){
        var total = conf.length;
    }

    function getGuidelinesButton(num, is_show_all){
        if(is_show_all == true){
            var numPrev = num - 1;
            return `<div class='d-flex justify-content-between mt-1 mb-2'><h6 class='mt-2'>${num} / ${total}</h6><a class='btn btn-success py-1' onclick='navigateGuidelines(${numPrev})'>Next</a></div>`;
        } else {
            return "";
        }
    }

    function getGuideLinesArrow(dir){
        if(dir == "bottom"){
            return "top:-20px; left:20px;";
        } else if(dir == "right"){
            return "top:30px; left:-30px; transform: rotate(270deg);";
        }
    }

    function getGuideImage(img){
        if(img != null){
            return `<img class='w-100 mb-2 rounded' src='http://127.0.0.1:8000/${img}'>`;
        } else {
            return "";
        }
    }

    conf.forEach(e => {
        document.getElementById(e.holder).innerHTML = `
            <div class='modal fade' style='var(--darkColor)' data-bs-backdrop='static' data-bs-keyboard='false' id='modal-parent-${i}' tabindex='-1'>
                <div class='modal-dialog border-0' id='modal-content-${i}'>
                    <div class='modal-content border-0'>
                        <div class='triangle-container position-absolute' style='${getGuideLinesArrow(e.direction)}'></div>
                        <div class='modal-header p-3 border-0'>
                            <h6 class='modal-title' id='exampleModalLabel'>${e.title}</h6>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body p-3 py-1 text-start' style='font-size:var(--textXMD);'>
                            ${getGuideImage(e.image)}
                            ${e.body}
                            ${getGuidelinesButton(i + 1, is_show_all)}
                        </div>
                    </div>
                </div>
            </div>
        `;

        getModalResponsive("modal-content-"+i, e.target, e.direction);   
        
        if(is_show_all == false){
            $("#modal-parent-"+i).modal('show'); 
        } else {
            $("#modal-parent-0").modal('show'); 
        }

        i++;
    });
}

function copylink(id) {
    var copyText = document.getElementById("copy_url_"+id);

    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(copyText.value);
}