<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\ExportImport\DatabaseExportImportInterface;

class DatabaseExportImportController extends Controller
{
    protected $exportImportService;

    public function __construct(DatabaseExportImportInterface $exportImportService)
    {
        $this->exportImportService = $exportImportService;
    }

    public function showExportImportPage()
    {
        return $this->exportImportService->showExportImportPage();
    }

    public function exportData()
    {
        return $this->exportImportService->exportData();
    }

    public function importData(Request $request)
    {
        return $this->exportImportService->importData($request);
    }

    public function insertData()
    {
        return $this->exportImportService->insertData();
    }

    public function removeAllData()
    {
        return $this->exportImportService->removeAllData();
    }
}
