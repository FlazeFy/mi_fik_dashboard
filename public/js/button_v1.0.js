function setSelectedBtnStyle(sty, clas, def, check){
    // sty = style want to be adapted
    // clas = classname of div
    // def = default style
    // check = the condition want to be check

    document.getElementById(check).style = sty;
    
    var btn = document.getElementsByClassName(clas);
    for(var i = 0; i < btn.length; i++){
        if(btn[i].id === check){
            btn[i].style = sty;
        } else {
            btn[i].style = def;
        }
    }
}