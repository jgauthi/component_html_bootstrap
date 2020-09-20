<?php
use Jgauthi\Component\Bootstrap\Form\{Fields, FormGeneration};
use Jgauthi\Component\Bootstrap\Html\Notification;
use Twig\TwigFunction;

require_once __DIR__.'/inc/init.inc.php';
// Init twig... https://twig.symfony.com

// Twig Plugin: Form FIELDS
$formFields = new Fields($_POST);
$twig->addFunction(new TwigFunction('field', function ($method, ...$params) use ($formFields) {
    echo call_user_func_array([$formFields, $method], $params);
}));

// Twig Plugin: Form GENERATION
$formGeneration = new FormGeneration($formFields);
$twig->addFunction(new TwigFunction('formGeneration', function (array $fields, array $formAttributes = []) use ($formGeneration) {
    echo $formGeneration->makeFormByArray($fields, $formAttributes);
}));

// Twig Plugin: Notification Bootstrap
$twig->addFunction(new TwigFunction('notif', function (string $type, string $message) {
    echo Notification::html($type, $message);
}));

// Data
$formFields = [
    'id' => ['type' => 'hidden'],
    'name' => ['type' => 'text', 'label' => 'Name'],
    'password' => ['type' => 'password', 'label' => 'Password'],
    'mail' => ['type' => 'email', 'label' => 'Email'],
    'avatar' => ['type' => 'file', 'label' => 'Avatar'],
    'comment' => ['type' => 'textarea', 'label' => 'Commentaire'],
    'cat' => ['type' => 'radio', 'label' => 'Catégorie', 'values' => ['Guest', 'Editor', 'Admin']],
];

$context = [
    'title' => 'Form with the component BootstrapForm',
    'form' => $formFields,
];

// Render templating
try {
    echo $twig->render('inc/BootstrapForm.html.twig', $context);
} catch (Exception $e) {
    echo Notification::error("Error: {$e->getMessage()}");
}

