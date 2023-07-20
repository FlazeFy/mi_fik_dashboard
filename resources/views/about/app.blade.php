@if(session()->get('role_key') == 1)
    <style>
        .ql-container.ql-snow{
            height: 50vh !important;
        }
        .toogle-edit-about{
            position: absolute;
            margin-top: 15px;
            margin-inline: 6px;
            min-width: 50px;
            padding: var(--spaceSM) var(--spaceLG); 
            font-size: var(--textLG) !important;  
        }
    </style>   

    @if(session()->get('toogle_edit_app') == "true")
        <div class="position-relative">
            <form class="d-inline position-absolute" style="right: 0; top:-15px;" method="POST" action="/about/toogle/app/false">
                @csrf
                <button class="btn btn-danger rounded-pill mt-3 me-2 px-3 py-2" style="font-size: var(--textLG) !important;" type="submit"><i class="fa-solid fa-xmark"></i>@if(!$isMobile) Cancel Edit @endif</button>
            </form>
            <form class="d-inline @if($isMobile) px-2 @endif" method="POST" action="/about/edit/app">
                @csrf
                @foreach($about as $ab)
                    @if(!$isMobile)
                        <a class="last-updated" style="top:20px;"><span class="text-primary">Last Updated :</span> <span id="date_holder_{{str_replace('-','_', $ab->id)}}">{{($ab->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                    @else
                        <a class="last-updated" style="top:0;"><span class="text-primary">Last Updated :</span></a>
                        <a class="last-updated" style="top:25px;"><span class="text-primary"><span id="date_holder_{{str_replace('-','_', $ab->id)}}">{{($ab->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                    @endif
                    <script>
                        const date_holder_<?= str_replace("-","_", $ab->id); ?> = document.getElementById("date_holder_{{str_replace('-','_', $ab->id)}}");

                        const date = new Date(date_holder_<?= str_replace("-","_", $ab->id); ?>.textContent);
                        date_holder_<?= str_replace("-","_", $ab->id); ?>.textContent = getDateToContext(date, "datetime");
                    </script>
                @endforeach
                <input name="help_body" id="about_body" hidden>
                <button class="btn btn-success rounded-pill toogle-edit-about" style="@if(!$isMobile) right:160px; @else right: 55px; @endif top:-15px;" onclick="getRichText()"><i class="fa-solid fa-floppy-disk"></i>@if(!$isMobile) Save Changes @endif</button>
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
                <button class="btn btn-info rounded-pill toogle-edit-about" type="submit" style="@if(!$isMobile) right:10px; @else right:0; @endif top:-20px;"><i class="fa-regular fa-pen-to-square"></i>@if(!$isMobile) Edit @endif</button>
            </form>
            <span id="about-app-holder">
                <?php
                    foreach($about as $ab){ 
                        echo $ab->help_body;
                    }
                ?>
            </span>
        </div>
    @endif

    <script>
        function getRichText(){
            var rawText = document.getElementById("rich_box").innerHTML;
            var desc = document.getElementById("about_body");
            var cleanText = splitOutRichTag(rawText);
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
    <div class="p-4" id="about-app-holder">
        <?php
            foreach($about as $ab){ 
                echo $ab->help_body;
            }
        ?>
    </div>
@endif