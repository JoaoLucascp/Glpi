<?php
declare(strict_types=1);

namespace GlpiPlugin\Newbase\Sections;

use Document_Item;
use GlpiPlugin\Newbase\CompanyData;

class SectionDocumentos
{
    public static function show(CompanyData $item): void
    {
        // Exibe o bloco padrão de documentos associados a este item
        Document_Item::showForItem($item);
    }
}