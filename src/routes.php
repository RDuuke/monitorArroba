<?php

$app->get("/", "AppController:index")->setName("home");
$app->post("/signin", "AuthController:signin")->setName("signin");
$app->get("/signout", "AuthController:signout")->setName("signout");
$app->group("/panel", function (){
    $this->get("", "AppController:home")->setName("admin.home");
    $this->get("/users", "AppController:users")->setName("admin.users");
    $this->post("/users", "StudentController:store")->setName("admin.users.store");
    $this->get("/users/{id}", "StudentController:delete")->setName("admin.users.delete");
 });