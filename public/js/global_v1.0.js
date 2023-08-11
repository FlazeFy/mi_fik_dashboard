function isMobile() {
    const key = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
    
    return key.test(navigator.userAgent);
}

function storeLocal(name,val) {
    Array.isArray(val) ? val = JSON.stringify(val) : val = val.trim();
    localStorage.setItem(name, val);
}

function getLocal(name) {
    return localStorage.getItem(name);
}

function storeSession(name,val) {
    Array.isArray(val) ? val = JSON.stringify(val) : val = val.trim();
    sessionStorage.setItem(name, val);
}

function getSession(name) {
    return sessionStorage.getItem(name);
}

function messages(key){
    const dictionaries = {
        en: {
            live: "Live",
            toend: "About to end",
            jstart: "Just Started",
            jend: "Just Ended",
            finished: "Finished",
            astart: "About to start",
            you: "You",
            unknownuser: "Unknown User",
            invalid: "Invalid",
            att_url: "Attachment URL",
            att_name: "Attachment Name",
            removed_att: "Attachment has been removed",
            failed_removed: "Failed to deleted the Attachment",
            file_upload_is: "File upload is",
            today_at: "Today at",
            yesterday_at: "Yesterday at",
            tommorow_at: "Tommorow at",
            max_file_size: "Upload failed. Maximum file size is",
            event_date: "Event Date",
            too_many_req: "System is busy. Please wait some moment",
            blocked_access: "Sorry. But you don't have access",
            unknown_error: "Unknown error. Please contact Admin",
        },
        id: {
            live: "Berlangsung",
            toend: "Akan berakhir",
            jstart: "Baru mulai",
            jend: "Baru selesai",
            finished: "Berakhir",
            astart: "Akan berlangsung",
            you: "Anda",
            unknownuser: "Tak dikenal",
            invalid: "Tidak valid",
            att_url: "Lampiran URL",
            att_name: "Nama Lampiran",
            removed_att: "Lampiran dihapus",
            failed_removed: "Gagal menghapus lampiran",
            file_upload_is: "File unggahan ",
            today_at: "Hari ini pada",
            yesterday_at: "Kemarin pada",
            tommorow_at: "Besok pada",
            max_file_size: "Gagal mengunggah. Ukuran maksimum",
            event_date: "Tanggal Event",
            too_many_req: "Sistem sedang sibuk. Mohon menunggu sebentar",
            blocked_access: "Maaf. Tetapi Anda tidak memiliki akses",
            unknown_error: "Error tak dikenal. Segera hubungi Admin",
        }
    };
    const language = dictionaries[sessionStorage.getItem('localization')] || dictionaries.en;

    return language[key] || `Translate not found for : "${key}"`;
}