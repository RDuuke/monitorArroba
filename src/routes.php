<?php

$app->get("/", "AppController:index")->setName("home");
$app->post("/signin", "AuthController:signin")->setName("signin");
$app->get("/signout", "AuthController:signout")->setName("signout");
$app->group("/panel", function (){
    /** Controller for view */
    $this->get("", "AppController:home")->setName("admin.home");
    $this->get("/students", "AppController:students")->setName("admin.students");
    $this->get("/students/upload", "AppController:upload_students")->setName("admin.view.students.upload");
    $this->get("/register/upload", "AppController:upload_registers")->setName("admin.upload.register");
    $this->get("/register", "AppController:registers")->setName("admin.register");
    $this->get("/users", "AppController:users")->setName("admin.users");
    $this->get("/instances", "AppController:instance")->setName("admin.instance");
    $this->get("/programs", "AppController:program")->setName("admin.program");
    $this->get("/institutions", "AppController:institution")->setName("admin.institution");
    $this->get("/courses", "AppController:courses")->setName("admin.courses");
    $this->get("/search", "AppController:search")->setName("admin.search");
    $this->get("/search/student", "AppController:searchStudent")->setName("admin.search.student");
    $this->get("/search/course", "AppController:searchCourse")->setName("admin.search.course");
    $this->get("/search/program", "AppController:searchProgram")->setName("admin.search.program");

    /** Controller actions estudiante */
    $this->post("/students/upload", "StudentController:upload")->setName("admin.upload.students");
    $this->get("/students/delete/{id}", "StudentController:delete")->setName("admin.students.delete");
    $this->get("/students/show/{id}", "StudentController:show")->setName("admin.students.show");
    $this->post("/students/update/{id}", "StudentController:update")->setName("admin.students.update");
    $this->get("/students/all", "StudentController:all")->setName("admin.students.all");
    $this->post("/students", "StudentController:store")->setName("admin.students.store");

    /** Controller actions matricula */
    $this->post("/register/upload", "RegisterController:upload")->setName("admin.upload.register");
    $this->get("/register/delete/{id}", "RegisterController:delete")->setName("admin.delete.register");
    $this->get("/register/show/{id}", "RegisterController:show")->setName("admin.show.register");
    $this->post("/register/update/{id}", "RegisterController:update")->setName("admin.update.register");
    $this->get("/register/all", "RegisterController:all")->setName("admin.all.register");
    $this->post("/register", "RegisterController:store")->setName("admin.store.register");

    /** Controller actions user */
    $this->post('/users', 'UserController:store')->setName('admin.users.store');
    $this->get('/users/all', 'UserController:all')->setName('admin.users.all');
    $this->get('/users/delete/{id}', 'UserController:delete')->setName('admin.users.delete');
    $this->get('/users/show/{id}', 'UserController:show')->setName('admin.users.show');
    $this->post('/users/update/{id}', 'UserController:update')->setName('admin.users.update');

    /** Controller actions instance */
    $this->get("/instances/all", "InstanceController:all")->setName('admin.instance.all');
    $this->post("/instances", "InstanceController:store")->setName('admin.instance.store');
    $this->get("/instances/show/{id}", "InstanceController:show")->setName('admin.instance.show');
    $this->post("/instances/update/{id}", "InstanceController:update")->setName('admin.instance.update');
    $this->get("/instances/delete/{id}", "InstanceController:delete")->setName('admin.instance.delete');

    /** Controller actions programs */
    $this->get("/programs/all", "ProgramController:all")->setName('admin.program.all');
    $this->post("/programs", "ProgramController:store")->setName('admin.program.store');
    $this->get("/programs/delete/{id}", "ProgramController:delete")->setName('admin.program.delete');
    $this->post("/programs/update/{id}", "ProgramController:update")->setName('admin.program.update');
    $this->get("/programs/show/{id}", "ProgramController:show")->setName('admin.program.show');


    /** Controller actions institution */
    $this->get("/institutions/all", "InstitutionController:all")->setName('admin.institution.all');
    $this->post("/institutions", "InstitutionController:store")->setName('admin.institution.store');
    $this->get("/institutions/delete/{id}", "InstitutionController:delete")->setName('admin.institution.delete');
    $this->get("/institutions/show/{id}", "InstitutionController:show")->setName('admin.institution.show');
    $this->post("/institutions/update/{id}", "InstitutionController:update")->setName('admin.institution.update');

    /** Controller actions courses */

    $this->get("/courses/all", "CourseController:all")->setName('admin.courses.all');
    $this->post("/courses", "CourseController:store")->setName('admin.courses.store');
    $this->get("/courses/delete/{id}", "CourseController:delete")->setName('admin.courses.delete');
    $this->get("/courses/show/{id}", "CourseController:show")->setName('admin.courses.show');
    $this->post("/courses/update/{id}", "CourseController:update")->setName('admin.courses.update');

    /** Controller search general */

    $this->get("/search/program/courses/{id}", "AppController:searchCoursesForPogram")->setName('admin.search.program.course');
    $this->get("/search/program/courses/usuarios/{id}", "AppController:searchStudentsForCourse")->setName('admin.search.program.course.student');
    $this->get("/search/program/courses/usuarios/info/{id}", "AppController:searhDataForStudent")->setName('admin.search.program.course.student.data');

    /** Controller helpers */
    $this->get("/students/check", "StudentController:checkEmailStudents")->setName('admin.check.students');
    $this->get("/students/email", "StudentController:getDataForEmailStudents")->setName('admin.data.email.students');
    $this->get("/register/courses", "RegisterController:getCourses")->setName('admin.register.courses');
    $this->get("/courses/search[/{params}]", "CourseController:search")->setName('admin.courses.search');
    $this->get("/program/search[/{params}]", "ProgramController:search")->setName('admin.courses.search');
    $this->get("/students/search[/{params}]", "StudentController:search")->setName('admin.courses.search');
 });