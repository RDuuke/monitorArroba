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
    $this->get('/users', 'AppController:users')->setName('admin.users');

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

    /** Controller helpers */
    $this->get("/students/check", "StudentController:checkEmailStudents")->setName('admin.check.students');
    $this->get("/students/email", "StudentController:getDataForEmailStudents")->setName('admin.data.email.students');
    $this->get("/register/courses", "RegisterController:getCourses")->setName('admin.register.courses');
 });