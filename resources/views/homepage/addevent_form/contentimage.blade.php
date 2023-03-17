<style>
    .content-image-holder{
        position: relative;
        margin-top: 6px;
        margin-bottom: 6px; 
    }
    .content-image-holder .content-image{
        margin-inline: auto;
        display: block;
        border-radius: 10px !important;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: black;
        height:200px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    .image-upload{
        position: absolute;
        bottom: 3px;
        right: 10px;
    }
    .image-upload>input {
        display: none;
    }
    .btn.change-image{
        width:40px; 
        height:40px; 
        background:#F78A00;
        border-radius: 100%;
        display: block;
        margin-inline: auto;
    }
    .btn-icon-reset-image{
        position: absolute; 
        bottom: 10px; 
        left: 10px;
        background: #e74645 !important;
        color:white !important;
    }
</style>

<div class="content-image-holder">
    <img id="frame" class="content-image img img-fluid" src="{{asset('assets/default_content.jpg')}}">
    <div class='image-upload' id='formFileImg' onchange='previewImage()'>
        <label for='file-input'>
            <img class='btn change-image shadow position-relative p-1' title='Change Image' src="{{asset('assets/change_image.png')}}"/>
        </label>
        <input id='file-input' type='file' name='content_image' accept="image/*"/>
    </div>
    <a class="btn btn-icon-reset-image shadow" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can"></i></a>
</div>

<script>
    //Image upload preview.
    function previewImage() {
        document.getElementById('frame').src = URL.createObjectURL(event.target.files[0]);
    }

    function clearImage() {
        document.getElementById('formFileImg').value = null;
        document.getElementById('frame').src = "{{asset('assets/default_content.jpg')}}";
    }
</script>

