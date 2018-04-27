<?php

$app->get("/", "AppController:index")->setName("home");
$app->post("/signin", "AuthController:signin")->setName("signin");
$app->get("/signout", "AuthController:signout")->setName("signout");
$app->group("/panel", function (){
    $this->get("", "AppController:home")->setName("admin.home");
    $this->get("/users", "AppController:users")->setName("admin.users");
    $this->get("/users/upload", "AppController:upload_users")->setName("admin.view.user.upload");
    $this->post("/users/upload", "StudentController:upload")->setName("admin.upload.users");
    $this->get("/users/delete/{id}", "StudentController:delete")->setName("admin.users.delete");
    $this->get("/users/show/{id}", "StudentController:show")->setName("admin.users.show");
    $this->post("/users/update/{id}", "StudentController:update")->setName("admin.users.update");
    $this->get("/users/all", "StudentController:all")->setName("admin.users.all");
    $this->post("/users", "StudentController:store")->setName("admin.users.store");

    /** */
    $this->get("/register/upload", "AppController:upload_registers")->setName("admin.upload.register");
    $this->post("/register/upload", "RegisterController:upload")->setName("admin.upload.register");
    $this->get("/register/delete/{id}", "RegisterController:delete")->setName("admin.delete.register");
    $this->get("/register/show/{id}", "RegisterController:show")->setName("admin.show.register");
    $this->post("/register/update/{id}", "RegisterController:update")->setName("admin.update.register");
    $this->get("/register/all", "RegisterController:all")->setName("admin.all.register");
    $this->get("/register", "AppController:registers")->setName("admin.register");
    $this->post("/register", "RegisterController:store")->setName("admin.store.register");

    /** */
    $this->get("/users/check", "StudentController:checkEmailUser")->setName('admin.check.user');
 });