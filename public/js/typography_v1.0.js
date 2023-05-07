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