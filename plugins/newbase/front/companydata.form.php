<?php

include ('../../../inc/includes.php');

use GlpiPlugin\Newbase\CompanyData;

\Session::checkLoginUser();

$plugin_item = new CompanyData();

if (isset($_POST["add"])) {
    $plugin_item->check(-1, CREATE, $_POST);
    if ($newID = $plugin_item->add($_POST)) {
        \Html::redirect($plugin_item->getFormURLWithID($newID));
    }
    \Html::back();

} elseif (isset($_POST["update"])) {
    $plugin_item->check($_POST["id"], UPDATE);
    $plugin_item->update($_POST);
    \Html::back();

} elseif (isset($_POST["delete"])) {
    $plugin_item->check($_POST["id"], DELETE);
    $plugin_item->delete($_POST);
    $plugin_item->redirectToList();

} elseif (isset($_POST["purge"])) {
    $plugin_item->check($_POST["id"], PURGE);
    $plugin_item->delete($_POST, 1);
    $plugin_item->redirectToList();

} elseif (isset($_POST["restore"])) {
    $plugin_item->check($_POST["id"], DELETE);
    $plugin_item->restore($_POST);
    \Html::back();
}

\Html::header(
    CompanyData::getTypeName(\Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "plugins",
    "newbase",
    "companydata"
);

$plugin_item->display($_GET);

\Html::footer();
