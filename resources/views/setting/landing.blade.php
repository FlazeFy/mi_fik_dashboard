<div class="position-relative px-3" style="padding-bottom:20px;">   
    @foreach($settingLanding as $stl)
        <form action="/setting/update_landing/{{$stl->id}}" method="POST">
            @csrf
            <div class="form-floating mb-3 mt-4">
                <input type="number" class="form-control" name="FAQ_range" min="4" max="100" value="{{$stl->FAQ_range}}" required>
                <label for="floatingInput">FAQ range</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="FBC_range" min="3" max="100" value="{{$stl->FBC_range}}" required>
                <label for="floatingInput">Feedback range</label>
            </div>
            <div class="position-absolute" style="right: 15px;">
                <button class="btn btn-submit" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </div>
        </form>
        <h6 class="text-secondary mb-1" style="font-size:12px;">Last Updated :</h6>
        <a style="font-size:12px;">{{$stl->updated_at}}</a>
    @endforeach
</div>