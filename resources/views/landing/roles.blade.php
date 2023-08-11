<div class="container-fluid rounded mt-5 py-5">
    <h1 class="fw-bold mt-2 mb-5 text-primary text-center">{{ __('messages.type_of_user') }}</h1>
    <div class="row">
        <div class="col-lg-6">
            <div class="feature-first text-center p-2 roles-left">
                <img src="{{ asset('/assets/default_lecturer.png')}}" alt="{{ asset('/assets/default_lecturer.png')}}" width="200" class="img-fluid d-block mx-auto">
                <h3 class="text-primary mt-3">Lecturer <span class="btn btn-tag" title="General Tag">Lecturer</span>
                    / Staff <span class="btn btn-tag" title="General Tag">Staff</span></h3>
                <p class="text-secondary">{{ __('messages.lecturer_desc') }}</p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="feature-first text-center p-2 roles-right">
                <img src="{{ asset('/assets/default_student.png')}}" alt="{{ asset('/assets/default_student.png')}}" width="200" class="img-fluid d-block mx-auto">
                <h3 class="text-primary mt-3">Student <span class="btn btn-tag" title="General Tag">Student</span></h3>
                <p class="text-secondary">{{ __('messages.student_desc') }}</p>
            </div>
        </div>
    </div>
</div>