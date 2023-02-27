<style>
    .btn-quick-action{
        border-radius:6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height:15vh;
        border:none;
        width:100%;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        transition: 0.5s;
        text-align:left;
        padding:10px;
    }
    .btn-quick-action:hover{
        background: #F78A00 !important;
        background-image:none !important;
    }
    .quick-action-text{
        font-size:24px;
        color:white;
        transition: 0.5s;
        margin-top:9vh;
    }
    .quick-action-info{
        font-size:16px;
        color:white;
        transition: 0.5s;
        display:none;
    }
    .btn-quick-action:hover .quick-action-text{
        margin-top:-4vh;
    }
    .btn-quick-action:hover .quick-action-info{
        display:block;
    }
    .btn-tag{
        background:white;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:#414141;
        font-weight:400;
        border:1.5px solid #F78A00;
    }
    .btn-tag:hover, .btn-tag-selected{
        background:#F78A00;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:whitesmoke !important;
    }
</style>

<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("http://127.0.0.1:8000/assets/task.png"); background-color:#FB5E5B;'
    data-bs-target="#addTaskModal" data-bs-toggle="modal">
    <h5 class="quick-action-text">Add Task</h5>
    <p class="quick-action-info">Task is a bla bla....</p>
</button>

<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <form action="/homepage/add_task" method="POST">
                @csrf 
                <div class="modal-body pt-4">
                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <h5>Create Task</h5>
                    <div class="row my-2">
                        <div class="col-lg-7 pb-2">
                            @include('homepage.addtask_form.titleinput')
                            <div class="form-floating mt-3">
                                <textarea class="form-control" id="floatingTextarea2" style="height: 100px"></textarea>
                                <label for="floatingTextarea2">Descriptions</label>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating">
                                <select class="form-select" id="floatingSelect" name="task_reminder" aria-label="Floating label select example">
                                    @php($i = 0)

                                    @foreach($dictionary as $dct)
                                        @if($dct->type_name == "Reminder")
                                            @if($i == 0)
                                                <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                            @else
                                                <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                            @endif

                                            @php($i++)
                                        @endif
                                    @endforeach
                                </select>
                                <label for="floatingSelect">Reminder</label>
                            </div>
                            @include('homepage.addtask_form.datepicker')
                        </div>
                    </div>
                    <p style="font-weight:400;"><i class="fa-solid fa-circle-info text-primary"></i> ...</p>
                    <span id="btn-submit-holder-task"><button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </div>
            </form>
        </div>
    </div>
</div>