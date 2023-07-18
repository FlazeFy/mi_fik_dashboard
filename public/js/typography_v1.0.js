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
        .replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true" data-dl-input-translation="true">','')
        .replace('</div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>','')
        .replace('<deepl-inline-translate style="z-index: 1999999999;"></deepl-inline-translate>','');
    return res;
}

function validateTextNull(val, msg){
    if(val != null && val.trim() != ""){
        return val;
    } else {
        return msg;
    }
}