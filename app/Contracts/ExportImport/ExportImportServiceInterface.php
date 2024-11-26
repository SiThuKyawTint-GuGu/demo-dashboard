<?php

namespace App\Contracts\ExportImport;

interface ExportImportServiceInterface
{
    public function exportDataToExcel();
    public function importDataFromExcel($file);
}
