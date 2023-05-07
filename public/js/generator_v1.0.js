function deleteAfterCharacter(str, character) {
    var index = str.indexOf(character);
    if (index !== -1) {
        return str.slice(0, index);
    } else {
        return str;
    }
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