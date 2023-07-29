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
        <div class="info-box tips m-0">
            <label><i class="fa-solid fa-circle-info"></i> Tips</label>
            <p>This will affect whole system</p>
        </div>
        <br>
        <button class="btn btn-submit w-100 py-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
@endforeach