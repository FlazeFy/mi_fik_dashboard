@if($info)
    @foreach($info as $in)
        @if($in->info_location == $location)
            <div class="info-box {{$in->info_type}}" id="info_body_holder_{{$location}}">
                <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                <?php echo $in->info_body; ?>
            </div>
        @endif
    @endforeach
@endif
<script>
    $(document).ready(function() {
        tidyUpRichText("info_body_holder_{{$location}}");
    });
</script>