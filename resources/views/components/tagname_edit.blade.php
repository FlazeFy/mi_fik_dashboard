<form class="form-custom" method="POST" action="/event/tag/update/{{$tg->id}}">
    @csrf
    <i class="fa-solid fa-pencil position-absolute" style="top:3.5px; left:6px;"></i>
    <input name="tag_desc" value="{{$tg->tag_desc}}" hidden>
    <input name="update_type" value="name" hidden>
    <input class="input-custom" name="tag_name" required value="{{$tg->tag_name}}" onblur="this.form.submit()">
</form>