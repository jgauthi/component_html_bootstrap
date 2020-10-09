<?php
use Jgauthi\Component\Bootstrap\DataTransformer\YamlToAsgardForm;
use Jgauthi\Component\Bootstrap\Form\{Fields, FormGeneration};
use Jgauthi\Component\Bootstrap\Html\Notification;

require_once __DIR__.'/inc/init.inc.php';

$form = new FormGeneration(new Fields($_POST));
init_page();

try {
    $matrice = new YamlToAsgardForm(__DIR__.'/asset/film.asf.yaml');
    $fields = $matrice->exportToBootstratForm();
    echo $form->makeFormByArray($fields);

} catch (Exception $e) {
    echo Notification::error("{$e->getMessage()} on {$e->getFile()}:{$e->getLine()}");
}
