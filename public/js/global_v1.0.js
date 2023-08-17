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

const dictionaries_collection = {
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
        max_char_len: "Reaching maximum character length",
        cant_empty: "Field can't be empty",
        no_item_selected: "You have not select any item",
        submit: "Submit",
        locked: "Locked",
        add_tag: "Add Tag",
        remove_tag: "Remove Tag",
        homepage: "Homepage",
        event: "Event",
        manage_user: "Manage User",
        system: "System",
        social: "Social",
        tag: "Tag",
        calendar: "Calendar",
        location: "Location",
        request: "Request",
        all_user: "All User",
        grouping: "Grouping",
        notif: "Notification",
        dct: "Dictionary",
        info: "Info",
        access: "Access",
        faq: "FAQ",
        feedback: "Feedback",
        statistic: "Statistic",
        setting: "Setting",
        about: "About",
        question: "Question",
        answer: "Answer",
        list: "List",
        most_suggest: "Most Suggest",
        about_us: "About Us",
        helps_editor: "Helps Editor",
        contact_us: "Contact Us",
        end_date_invalid: "The date end must after date start",
        end_date_now_invalid: "Unable to set content date to a past date",
        esc_submit:"Click outside the input to submit",
        no_cat_in_type:"No category on this type",
        cat_no_help:"This category has no help",
        help_detail:"Help Detail"
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
        max_char_len: "Mencapai batas maksimum karakter",
        cant_empty: "Input tidak boleh kosong",
        no_item_selected: "Anda belum memilih item",
        submit: "Submit",
        locked: "Terkunci",
        add_tag: "Tambah Tag",
        remove_tag: "Hapus Tag",
        homepage: "Beranda",
        event: "Event",
        manage_user: "Kelola Pengguna",
        system: "Sistem",
        social: "Sosial",
        tag: "Tag",
        calendar: "Kalender",
        location: "Lokasi",
        request: "Permintaan",
        all_user: "Semua Pengguna",
        grouping: "Pengelompokan",
        notif: "Notifikasi",
        dct: "Kamus",
        info: "Info",
        access: "Akses",
        faq: "FAQ",
        feedback: "Umpan Balik",
        statistic: "Statistik",
        setting: "Pengaturan",
        about: "Tentang",
        question: "Pertanyaan",
        answer: "Jawaban",
        list: "Daftar",
        most_suggest: "Masukan Terbanyak",
        about_us: "Tentang Kami",
        helps_editor: "Manajemen Bantuan",
        contact_us: "Hubungi Kami",
        end_date_invalid: "Tanggal berakhir harus sesudah tanggal mulai",
        end_date_now_invalid: "Gagal memilih tanggal konten untuk tanggal yang sudah lewat",
        esc_submit:"Klik diluar input untuk submit",
        no_cat_in_type:"Tidak ada kategori didalam tipe ini",
        cat_no_help:"Kategori ini tidak memiliki bantuan",
        help_detail:"Detail Bantuan"
    }
};

if(sessionStorage.getItem('locale') === null){
    sessionStorage.setItem('locale', 'id');
}

function translator(target){
    var text = document.getElementById(target).textContent;
    const lang = sessionStorage.getItem('locale') || 'en';
    const targetlang = lang === 'en' ? 'id' : 'en';
    var translatedWords = `Translate not found for : "${text}"`;

    var dct = dictionaries_collection[targetlang];
    
    for (const key in dct) {
        if (dct.hasOwnProperty(key) && dct[key] == text) {
            translatedWords = dictionaries_collection[lang][key];
        }
    }

    document.getElementById(target).innerHTML = translatedWords;
}   

function messages(key){
    const language = dictionaries_collection[sessionStorage.getItem('locale')] || dictionaries_collection.en;

    return language[key] || `Translate not found for : "${key}"`;
}
