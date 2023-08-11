<div class="modal fade" id="deleteCatModal-{{$dtag->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">{{ __('messages.del_validation') }} "{{$dtag->dct_name}}" category</p>
                <p>If so, please choose where's the tag with these category will move to</p>
                <form class="d-inline" action="/event/tag/delete/cat/{{$dtag->id}}" method="POST">
                    @csrf
                    <div class="form-floating mb-2">
                        <select class="form-select" id="tag_category" name="new_tag_category" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                            @php($i = 0)
                            @foreach($dct_tag as $localeTag)
                                @if($localeTag->slug_name != "general-role" && $localeTag->slug_name != $dtag->slug_name)
                                    @if($i == 0)
                                        <option value="{{$localeTag->slug_name}}" selected>{{$dtag->dct_name}} >> {{$localeTag->dct_name}}</option>
                                    @else 
                                        <option value="{{$localeTag->slug_name}}">{{$dtag->dct_name}} >> {{$localeTag->dct_name}}</option>
                                    @endif
                                    @php($i++)
                                @endif
                            @endforeach
                        </select>
                        <label for="tag_category">Category</label>
                        <a id="tag_category_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>

                    @include('components.infobox', ['info' => $info, 'location'=> "delete_category"])        
                    <input value="{{$dtag->dct_name}}" name="dct_name" hidden>
                    <button class="btn btn-danger float-end" type="submit">Delete and Remove Tag</button>
                </form>
            </div>
        </div>
    </div>
</div>