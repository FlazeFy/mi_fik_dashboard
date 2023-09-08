function getDateToContext(datetime, type){
    if(datetime){
        const result = new Date(datetime);

        if (type == "full") {
            const now = new Date(Date.now());
            const yesterday = new Date();
            const tomorrow = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            if (result.toDateString() === now.toDateString()) {
                return ` ${messages('today_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else if (result.toDateString() === yesterday.toDateString()) {
                return ` ${messages('yesterday_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else if (result.toDateString() === tomorrow.toDateString()) {
                return ` ${messages('tommorow_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else {
                return ` ${result.getFullYear()}/${(result.getMonth() + 1)}/${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            }
        } else if (type == "24h" || type == "12h") {
            return `${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
        } else if (type == "datetime") {
            return ` ${result.getFullYear()}/${(result.getMonth() + 1)}/${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
        } else if (type == "date") {
            return `${result.getFullYear()}-${("0" + (result.getMonth() + 1)).slice(-2)}-${("0" + result.getDate()).slice(-2)}`;
        } else if (type == "calendar") {
            const result = new Date(datetime);
            const offsetHours = getUTCHourOffset();
            result.setUTCHours(result.getUTCHours() + offsetHours);
        
            return `${result.getFullYear()}-${("0" + (result.getMonth() + 1)).slice(-2)}-${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}:00`;
        }        
    } else {
        return "-";
    }
}

function getEventDate(dateStart, dateEnd){
    if(dateStart && dateEnd){
        const ds = new Date(dateStart);
        const de = new Date(dateEnd);
        const offsetHours = getUTCHourOffset();
        ds.setUTCHours(ds.getUTCHours() + offsetHours);
        de.setUTCHours(de.getUTCHours() + offsetHours);

        if (ds.getFullYear() !== de.getFullYear()) {
            // Event year not same
            return `<a class='btn-detail' title='${messages('event_date')}'><i class='fa-regular fa-clock'></i> ${getDateMonth(ds)} ${ds.getFullYear()} ${getHourMinute(ds)} - ${getDateMonth(de)} ${de.getFullYear()} ${getHourMinute(de)}</a>`;
        } else if (ds.getMonth() !== de.getMonth()) {
            // If month not same
            return `<a class='btn-detail' title='${messages('event_date')}'><i class='fa-regular fa-clock'></i> ${getDateMonth(ds)} ${ds.getFullYear()} ${getHourMinute(ds)} - ${getDateMonth(de)} ${getHourMinute(de)}</a>`;
        } else if (ds.getDate() !== de.getDate()) {
            // If date not same
            return `<a class='btn-detail' title='${messages('event_date')}'><i class='fa-regular fa-clock'></i> ${getDateMonth(ds)} ${getHourMinute(ds)} - ${getDateMonth(de)} ${('0' + de.getDate()).slice(-2)} ${getHourMinute(de)}</a>`;
        } else if (ds.getDate() === de.getDate()) {
            return `<a class='btn-detail' title='${messages('event_date')}'><i class='fa-regular fa-clock'></i> ${getDateMonth(ds)} ${getHourMinute(ds)} - ${getHourMinute(de)}</a>`;
        }
    } else {
        return "";
    }
}

function getDateMonth(date){
    const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    return ("0" + date.getDate()).slice(-2) + " " + month[date.getMonth()].slice(0, 3);
}

function getHourMinute(date){
    return ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2);
}

const getRole = (role) => role ? role : '<span class="text-danger fw-bold" style="font-size:13px;"><i class="fa-solid fa-triangle-exclamation"></i> Has no general role </span>';

function removeTags(str) {
    if ((str===null) || (str==='') || (str === '<p><br></p>')){
        return "<span class='fst-italic'>No description provided</span>";
    } else {
        str = str.toString();
    }
        
    return str.replace( /(<([^>]+)>)/ig, '');
}

function getMinutesDifference(ds, de) {
    const diffMs = de.getTime() - ds.getTime();
    const minutes = Math.floor(diffMs / 1000 / 60);
    return minutes;
}

function getUTCHourOffset() {
    const offsetMi = new Date().getTimezoneOffset();
    const offsetHr = -offsetMi / 60;
    return offsetHr;
}

function subtractOffsetFromTime(timeStr) {
    const times = timeStr.split(':');
    const hr = parseInt(times[0]);
    const mi = parseInt(times[1]);
  
    const utcOffset = getUTCHourOffset();
    
    const time = new Date();
    time.setUTCHours(hr - utcOffset - utcOffset, mi);
  
    return time;
}

const getHourFromTime = (hr) => parseInt(hr.split(":")[0]);