<div class="position-relative px-3" style="padding-bottom:20px;">   
    @foreach($settingJobs as $stj)
        <form action="/setting/update_jobs/{{$stj->id}}" method="POST">
            @csrf
            <div class="form-floating mb-3 mt-4">
                <input type="number" class="form-control" name="DCD_range" min="7" max="100" value="{{$stj->DCD_range}}" required>
                <label for="floatingInput">Deleted Content Range (Days)</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="DTD_range" min="7" max="100" value="{{$stj->DTD_range}}" required>
                <label for="floatingInput">Deleted Task Range (Days)</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="DHD_range" min="7" max="100" value="{{$stj->DHD_range}}" required>
                <label for="floatingInput">Deleted History Range (Days)</label>
            </div>
            <div class="position-absolute" style="right: 15px;">
                <button class="btn btn-submit" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </div>
        </form>
        <h6 class="text-secondary mb-1" style="font-size:12px;">Last Updated :</h6>
        <a style="font-size:12px;">{{$stj->updated_at}}</a>
    @endforeach
</div>