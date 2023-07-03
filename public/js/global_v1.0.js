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

function storeLocal(name,val) {
    if (Array.isArray(val)) {
        val = JSON.stringify(val)
    } else {
        val = val.trim()
    }
    localStorage.setItem(name, val)
}

function getLocal(name) {
    return localStorage.getItem(name)
}

function storeSession(name,val) {
    if (Array.isArray(val)) {
        val = JSON.stringify(val)
    } else {
        val = val.trim()
    }
    sessionStorage.setItem(name, val)
}

function getSession(name) {
    return sessionStorage.getItem(name)
}