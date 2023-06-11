<div class="container p-0">
    <h5 class="text-secondary fw-bold">{{ucFirst($category)}}</h5>
    <div class="holder-trash-category mt-2" >        
        <div class="accordion p-0 m-0 " id="data-wrapper-{{$category}}"></div>
        <!-- Loading -->
        <div class="auto-load text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 280px; height: 280px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </div>
        <div class="empty_item_holder"></div>
        <!-- <div id="empty_item_holder-{{$category}}"></div>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span> -->
    </div>
</div>