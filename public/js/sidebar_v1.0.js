(function($) {
    "use strict";
    if(isMobile()){
        $('#close-sidebar').css("display","none");
    }
    var fullHeight = function() {
        $('.js-fullheight').css('height', $(window).height());
        $(window).resize(function(){
            $('.js-fullheight').css('height', $(window).height());
        });
    };
    fullHeight();
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        if(isMobile()){
            $('#content').hide();
            $('#close-sidebar').css("display","block");
        }
    });
    if(isMobile()){
        $('#close-sidebar').on('click', function () {
            $('#sidebar').toggleClass('active');
            $('#content').show();
            $('#close-sidebar').css("display","none");
        });
    }
})(jQuery);