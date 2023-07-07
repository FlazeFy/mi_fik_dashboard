<style>
    .modal-content{
        background-color: var(--whiteColor);
        border:none;
    }
</style>


<div class="modal fade" id="sorry_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sorry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('assets/sorry.png')}}" alt='failed.png' style='width:30vh;'><br>
                <h7 class="m-2" id="text-sorry"></h7>
            </div>
        </div>
    </div>
</div>

@if(Session::has('sorry_message'))
    <script>
        //Modal setting.
        $(window).on('load', function() {
            $('#sorry_modal').modal('show');
        });
    </script>
@endif