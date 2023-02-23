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