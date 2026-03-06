<?php
declare(strict_types=1);

namespace GlpiPlugin\Newbase\Sections;

use GlpiPlugin\Newbase\CompanyData;

class SectionEmpresa
{
    public static function show(CompanyData $item): void
    {

        $formUrl = $item->getFormURL();
        \Glpi\Application\View\TemplateRenderer::getInstance()->display(
            '@newbase/companydata/sections/empresa.html.twig',
            [
                'item_id'    => $item->getID(),
                'form_url'   => $formUrl,
                'csrf_token' => \Session::getNewCSRFToken(),
                'data'       => $item->fields,
            ]
        );
    }
}