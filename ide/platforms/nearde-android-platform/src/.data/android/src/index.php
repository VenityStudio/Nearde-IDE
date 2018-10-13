<?php

use php\android\app\Application;
use php\android\widget\Button;
use php\android\widget\Toast;
use php\android\widget\EditText;
use php\android\widget\CheckBox;
use php\android\widget\LinearLayout;

$activity = Application::getMainActivity();
$activity->setTitle("Hello Android World!");

$layout = new LinearLayout($activity);

$button = new Button($activity);
$button->text = "Hello world!";
$button->on("click", function () use ($button) {
    // on button click
});

$layout->addView($button);
$activity->setContentView($layout);