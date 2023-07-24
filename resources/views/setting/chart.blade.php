@foreach($setting as $stg)
    @php($opt1 = "")
    @php($opt2 = "")

    @if($stg->CE_range == 6)
        @php($opt1 = "selected")
    @else 
        @php($opt2 = "selected")
    @endif

    <form action="/setting/update_chart" method="POST">
        @csrf
        <div class="form-floating mb-3 mt-4">
            <input type="number" class="form-control" name="MOT_range" min="3" max="10" value="{{$stg->MOT_range}}" required>
            <label for="floatingInput">Most Used Tag (Item)</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="MOL_range" min="3" max="10" value="{{$stg->MOL_range}}" required>
            <label for="floatingInput">Most Used Location (Item)</label>
        </div>
        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelect" name="CE_range" aria-label="Floating label select example">
                <option value="6" <?= $opt1; ?>>Semester</option>
                <option value="12" <?= $opt2; ?>>Year</option>
            </select>
            <label for="floatingSelect">Created Event</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="MVE_range" min="3" max="16" value="{{$stg->MVE_range}}" required>
            <label for="floatingInput">Most Viewed Event (Item)</label>
        </div>
        <button class="btn btn-submit w-100 py-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
@endforeach