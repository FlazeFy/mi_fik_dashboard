<style>
    #about-app-holder{
        margin: 0;
        padding: 0;
        display: flex;
        max-height: 75vh;
        flex-direction: column;
        overflow-y: scroll;
    }
</style>
<div>
    <h2 class="text-primary text-center">Welcome to MI-FIK</h2><br>
    <span id="about-app-holder">
        <?php
            foreach($about as $ab){ 
                echo $ab->help_body;
            }
        ?>
    </span>
</div>
<span id="btn-next-terms-holder" class="d-flex justify-content-end">
    <button class="btn-next-steps" id="btn-next-terms" data-bs-toggle="collapse" data-bs-target="#terms" onclick="routeStep('next', 'welcoming')"><i class="fa-solid fa-arrow-right"></i> Next</button>
</span>

