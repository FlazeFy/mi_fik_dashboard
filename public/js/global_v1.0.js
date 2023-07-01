function isMobile() {
    const key = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
    
    return key.test(navigator.userAgent);
}

function submitOnEnter(event) {
    if (event.keyCode === 13) { 
        event.preventDefault(); 
        checkTitleSearch();
        return false; 
    }
    return true; 
}