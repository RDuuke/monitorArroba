<?php

$app->get("/", "AppController:index")->setName("home");
$app->post("/signin", "AuthController:signin")->setName("signin");
$app->get("/signout", "AuthController:signout")->setName("signout");
$app->group("/panel", function (){
    $this->get("", "AppController:home")->setName("admin.home");
    $this->get("/users", "AppController:users")->setName("admin.users");
    $this->get("/users/upload", "AppController:upload")->setName("admin.view.user.upload");
    $this->post("/users/upload", "StudentController:upload")->setName("admin.upload.users");
    $this->post("/users", "StudentController:store")->setName("admin.users.store");
    $this->get("/users/delete/{id}", "StudentController:delete")->setName("admin.users.delete");
    $this->get("/users/show/{id}", "StudentController:show")->setName("admin.users.show");
    $this->post("/users/update/{id}", "StudentController:update")->setName("admin.users.update");
    $this->get("/users/all", "StudentController:all")->setName("admin.users.all");
 });