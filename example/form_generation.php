<?php
use Jgauthi\Component\Bootstrap\Form\{Fields, FormGeneration};
use Jgauthi\Component\Bootstrap\Html\Notification;

require_once __DIR__.'/inc/init.inc.php';

$form = new FormGeneration(new Fields($_POST));
$fields = [
    'id' => ['type' => 'hidden'],
    'name' => ['type' => 'text', 'label' => 'Nom'],
    'password' => ['type' => 'password', 'label' => 'Mot de passe'],
    'mail' => ['type' => 'email', 'label' => 'Email'],
    'avatar' => ['type' => 'file', 'label' => 'Avatar'],
    'comment' => ['type' => 'textarea', 'label' => 'Commentaire'],
    'cat' => ['type' => 'radio', 'label' => 'Catégorie', 'values' => ['Guest', 'Editor', 'Admin']],
];

init_page();

try {
    echo $form->makeFormByArray($fields);

    // Yaml content example: asset/form.yaml
    // echo $form->makeFormByYaml(__DIR__.'/asset/form.yaml')

} catch (Exception $e) {
    echo Notification::error("{$e->getMessage()} on {$e->getFile()}:{$e->getLine()}");
}
