@if(session()->get('role_key') == 1)
    <style>
        .ql-container.ql-snow{
            height: 50vh !important;
        }
    </style>   
    @if(session()->get('toogle_edit_app') == "true")
        <div class="position-relative">
            <form class="d-inline position-absolute" style="right: 0; top:-15px;" method="POST" action="/about/toogle/app/false">
                @csrf
                <button class="btn btn-danger rounded-pill mt-3 me-2 px-3 py-2" type="submit"><i class="fa-regular fa-pen-to-square"></i> Cancel Edit</button>
            </form>
            <form class="d-inline" method="POST" action="/about/edit/app">
                @csrf
                @foreach($about as $ab)
                    <a class="fst-italic text-decoration-none text-primary" style="font-size:14px; position:absolute; margin-left: 20px; top:10px; width:340px;"><span class="text-primary">Last Updated :</span> <span id="date_holder_1">{{($ab->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                @endforeach
                <input name="help_body" id="about_body" hidden>
                <button class="btn btn-success rounded-pill mt-3 px-3 py-2 position-absolute" style="right:160px; top:-15px;" onclick="getRichText()"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </form><br><br>
            <div id="rich_box">
                <?php
                    foreach($about as $ab){ 
                        echo $ab->help_body;
                    }
                ?>
            </div>
        </div>
    @else 
        <div class="px-4 position-relative">
            <form class="d-inline" method="POST" action="/about/toogle/app/true">
                @csrf
                <button class="btn btn-info rounded-pill mt-3 px-3 py-2 position-absolute" type="submit" style="right:10px; top:-20px;"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
            </form>
            <?php
                foreach($about as $ab){ 
                    echo $ab->help_body;
                }
            ?>
        </div>
    @endif

    <script>
        function getRichText(){
            var rawText = document.getElementById("rich_box").innerHTML;
            var desc = document.getElementById("about_body");

            //Remove quills element from raw text
            var cleanText = rawText.replace('<div class="ql-editor" data-gramm="false" contenteditable="true">','').replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true">');
            //Check this clean text 2!!!
            cleanText = cleanText.replace('</div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>','');
            
            //Pass html quilss as input value
            var characterToDeleteAfter = "</div>";
            var modifiedString = deleteAfterCharacter(cleanText, characterToDeleteAfter);
            desc.value = modifiedString;
        }

        <?php
            if(session()->get('toogle_edit_app') == "true"){
                echo "var quill = new Quill('#rich_box', {
                    theme: 'snow'
                });";
            }
        ?>
    </script>
@else 
    <div class="p-4">
        <?php
            foreach($about as $ab){ 
                echo $ab->help_body;
            }
        ?>
    </div>
@endif

<script>
    const date_holder_1 = document.getElementById('date_holder_1');

    const date = new Date(date_holder_1.textContent);
    date_holder_1.textContent = getDateToContext(date, "datetime");
</script>