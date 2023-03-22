<div class="position-relative px-3" style="padding-bottom:50px;">   
    @foreach($setting as $stg)
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
                <input type="number" class="form-control" name="CE_range" min="3" max="12" value="{{$stg->CE_range}}" required>
                <label for="floatingInput">Created Event (Month)</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="MVE_range" min="3" max="16" value="{{$stg->MVE_range}}" required>
                <label for="floatingInput">Most Viewed Event (Item)</label>
            </div>
            <div class="position-absolute" style="right: 15px;">
                <button class="btn btn-submit" type="submit">Save Changes</button>
            </div>
        </form>
    @endforeach
</div>