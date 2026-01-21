<?php

/**
* Company Data Form - Handles create, update, and delete operations for CompanyData entities
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\CompanyData;

include('../../../inc/includes.php');

Session::checkRight('plugin_newbase', READ);

$company = new CompanyData();

if (isset($_POST['add'])) {
    $company->check(-1, CREATE, $_POST);
    $newID = $company->add($_POST);
    Html::back();

} elseif (isset($_POST['update'])) {
    $company->check($_POST['id'], UPDATE);
    $company->update($_POST);
    Html::back();

} elseif (isset($_POST['purge'])) {
    $company->check($_POST['id'], PURGE);
    $company->delete($_POST, 1);
    $company->redirectToList();

} else {
    $id = $_GET['id'] ?? 0;

    Html::header(
        CompanyData::getTypeName(1),
        $_SERVER['PHP_SELF'],
        'management',
        CompanyData::class
    );

    $company->display(['id' => $id]);

    // JavaScript para integração com API do CNPJ
    echo "<script type='text/javascript'>";
    echo "
    $(document).ready(function() {
        // Format CNPJ while typing
        $('#cnpj_field').mask('00.000.000/0000-00');

        // Search CNPJ button
        $('#search_cnpj').click(function() {
            var cnpj = $('#cnpj_field').val().replace(/[^0-9]/g, '');

            if (cnpj.length !== 14) {
                alert('" . __('Invalid CNPJ', 'newbase') . "');
                return;
            }

            // Show loading
            $(this).prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> " . __('Searching...', 'newbase') . "');

            // Call AJAX
            $.ajax({
                url: CFG_GLPI['root_doc'] + '/plugins/newbase/ajax/searchCompany.php',
                type: 'POST',
                data: {
                    cnpj: cnpj,
                    '_token': '" . Session::getNewCSRFToken() . "'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#corporate_name_field').val(response.data.legal_name);
                        $('#fantasy_name_field').val(response.data.fantasy_name);
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('" . __('Error searching CNPJ', 'newbase') . "');
                    console.error('AJAX Error:', error);
                },
                complete: function() {
                    $('#search_cnpj').prop('disabled', false).html('<i class=\"fas fa-search\"></i> " . __('Search', 'newbase') . "');
                }
            });
    });
    });
    ";
    echo "</script>";

    Html::footer();
}
