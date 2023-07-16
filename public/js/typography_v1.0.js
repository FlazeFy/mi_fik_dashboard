function ucEachWord(val){
    const arr = val.split(" ");

    for (var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
    }
    
    const res = arr.join(" ");

    return res;
}

function ucFirst(val) {
    if (typeof val !== 'string' || val.length === 0) {
        var res = val;
    } else {
        var res = val.charAt(0).toUpperCase() + val.slice(1);
    }

    return res;
}

function tidyUpRichText(id){
    var descHolder = document.getElementById(id);

    if(descHolder){
        var elmt_img = descHolder.querySelectorAll('img');
        if (elmt_img) {
            elmt_img.forEach(e => {
                e.style = 'width:100%;';
            });
        }

        var elmt_a = descHolder.querySelectorAll('a');
        if (elmt_a) {
            elmt_a.forEach(e => {
                e.style = 'color:var(--primaryColor); text-decoration:none;';
            });
        }
    }
}

function splitOutRichTag(text){
    var res = text.replace('<div class="ql-editor" data-gramm="false" contenteditable="true">','')
        .replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true">','')
        .replace('<div class="ql-editor" data-gramm="false" contenteditable="true" data-dl-input-translation="true">','')
        .replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true" data-dl-input-translation="true">','');
    
    return res;
}