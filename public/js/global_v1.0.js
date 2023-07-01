function isMobile() {
    const key = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
    
    return key.test(navigator.userAgent);
}