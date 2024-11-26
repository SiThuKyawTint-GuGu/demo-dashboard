<?php

namespace App\Contracts\ExportImport;

use Illuminate\Http\Request;

interface DatabaseExportImportInterface
{
    public function showExportImportPage();
    public function exportData();
    public function importData(Request $request);
    public function insertData();
    public function removeAllData();
}