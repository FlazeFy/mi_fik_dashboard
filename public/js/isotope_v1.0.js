(function() {
    "use strict";
  
    const select = (el, all = false) => {
        el = el.trim()
        if (all) {
            return [...document.querySelectorAll(el)]
        } else {
            return document.querySelector(el)
        }
    }  
    window.addEventListener('load', () => {
        let contentContainer = select('.content-container');
        if (contentContainer) {
                let contentIsotope = new Isotope(contentContainer, {
                itemSelector: '.content-item'
            });
        }
  
    });  
})()