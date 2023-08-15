<style>
    #tagName_msg{
        text-decoration:none;
    }
</style>

<script>
    let validation = [
        { id: "tag_name", req: true, len: 30 },
        { id: "tag_desc", req: false, len: 255 },
        { id: "tag_category", req: true, len: 75 },
    ];
</script>

<div class="position-relative">
    <h5 class="section-title">{{ __('messages.new_tag') }}</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0;" type="button"
        data-bs-toggle="popover" title="Info" data-bs-content="Tag is like a category or label where you can define who should view event by matching the role of user."><i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <form class="p-2 mt-2" action="/event/tag/add" method="POST" id="form-add-tag">
        @csrf
        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="tag_name" name="tag_name" oninput="validateForm(validation)" maxlength="30" required>
            <label for="tag_name">{{ __('messages.tag_name') }}</label>
            <a id="tag_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="form-floating mb-2">
            <select class="form-select" id="tag_category" name="tag_category" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                @php($i = 0)
                @foreach($dct_tag as $dtag)
                    @if($dtag->slug_name != "general-role")
                        @if($i == 0)
                            <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                        @else 
                            <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                        @endif
                        @php($i++)
                    @endif
                @endforeach
            </select>
            <label for="tag_category">{{ __('messages.cat') }}</label>
            <a id="tag_category_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="form-floating">
            <textarea class="form-control" style="height: 100px" id="tag_desc" name="tag_desc" oninput="validateForm(validation)" maxlength="255"></textarea>
            <label for="tag_desc">{{ __('messages.description') }}</label>
            <a id="tag_desc_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
    </form>
</div>

<script>
    window.addEventListener('beforeunload', function(event) {
        if(!isFormSubmitted){
            var is_editing = false;
            const form = document.getElementById('form-add-tag');
            const inputs = form.querySelectorAll('input');

            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                if (input.value.trim() !== '' && input.name != "_token") {
                    is_editing = true;
                    break;
                }
            }

            if(is_editing){
                event.preventDefault();
                event.returnValue = '';
            }
        }
    });
</script>

