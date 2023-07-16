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
            res += " " + "<a class='btn btn-primary " + padding + " " + margin + " ' style='font-size:" + sz + "'>" + e.tag_name + "</a>";
        });
    } 

    return res;
}

function getLocationName(loc){
    if(loc && loc.length == 2){
        if(loc[0].detail != null){
            res = loc[0].detail;
        } else {
            res = loc[1].detail;
        }
        return "<span class='loc-limiter px-0 m-0'> " +
                "<a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> "+res+"</a> " +
            "</span>";
    } else if(loc && loc.length != 2){
        return "<span class='loc-limiter px-0 m-0'> " +
                "<a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> Invalid</a> " +
            "</span>";
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

        return '<a class="btn-detail" title="'+ str +'"><i class="fa-solid fa-hashtag"></i>'+ tag.length +'</a>';
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
            return "You";
        } else {
            return "@"+username1;
        }
    } else if (username2){
        if(username2 == myname){
            return "You";
        } else {
            return "@"+username2;
        }
    } else {
        return "<span class='text-danger'>Unknown User</span>"
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
        return "<div class='event-status text-primary'><i class='fa-solid fa-circle fa-xs'></i> About to start</div>";
    } else if (c_start <= now && c_end >= now) {
        if (hourDiff_end > 1 && hourDiff_start > -15) {
            var ctx_live = " Just Started";
        } else if (hourDiff_end > 15) {
            var ctx_live = " Live";
        } else {
            var ctx_live = " About to end";
        }
        return "<div class='event-status text-danger'><i class='fa-solid fa-circle fa-xs'></i>"+ctx_live+"</div>";
    } else if (c_start <= now && c_end <= now && hourDiff_end <= 0 && hourDiff_end >= -15) {
        return "<div class='event-status text-success'><i class='fa-solid fa-circle fa-xs'></i> Just Ended</div>";
    } else if (c_start <= now && c_end <= now && hourDiff_end <= -15){
        return "<div class='event-status text-success'><i class='fa-solid fa-check'></i> Finished</div>";
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