<?php

namespace App\Services\Excel;

use App\Contracts\ExportImport\ExportImportServiceInterface;
use Aws\DynamoDb\DynamoDbClient;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExportImportService implements ExportImportServiceInterface
{
    protected $dynamoDb;

    public function __construct(DynamoDbClient $dynamoDb)
    {
        $this->dynamoDb = $dynamoDb;
    }

    public function exportDataToExcel()
    {
        $result = $this->dynamoDb->scan([
            'TableName' => 'users',
        ]);

        $items = $result['Items'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'UserID',
            'Name',
            'Email',
            'Created At',
            'Updated At',
            'Custom Field 1', 'Custom Field 2', 'Custom Field 3', 'Custom Field 4', 'Custom Field 5',
            'Custom Field 6', 'Custom Field 7', 'Custom Field 8', 'Custom Field 9', 'Custom Field 10',
            'Custom Field 11', 'Custom Field 12', 'Custom Field 13', 'Custom Field 14', 'Custom Field 15',
            'Custom Field 16', 'Custom Field 17', 'Custom Field 18', 'Custom Field 19', 'Custom Field 20'
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        $row = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item['user_id']['S'] ?? '');
            $sheet->setCellValue('B' . $row, $item['name']['S'] ?? '');
            $sheet->setCellValue('C' . $row, $item['email']['S'] ?? '');
            $sheet->setCellValue('D' . $row, $item['created_at']['S'] ?? '');
            $sheet->setCellValue('E' . $row, $item['updated_at']['S'] ?? '');

            for ($i = 1; $i <= 20; $i++) {
                $customFieldKey = 'custom_field_' . $i;
                $customValue = isset($item[$customFieldKey]) ? $item[$customFieldKey]['S'] : '';
                $columnLetter = chr(64 + $i + 5);
                $sheet->setCellValue($columnLetter . $row, $customValue);
            }

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/exported_data.xlsx');
        $writer->save($filePath);
    }

    public function importDataFromExcel($file)
    {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        foreach ($data as $row) {
            $this->dynamoDb->putItem([
                'TableName' => 'users',
                'Item' => [
                    'user_id' => ['S' => $row[0]],
                    'name' => ['S' => $row[1]],
                    'email' => ['S' => $row[2]],
                    'created_at' => ['S' => $row[3]],
                    'updated_at' => ['S' => $row[4]],
                    'custom_field_1' => ['S' => $row[5] ?? ''],
                    'custom_field_2' => ['S' => $row[6] ?? ''],
                    'custom_field_3' => ['S' => $row[7] ?? ''],
                    'custom_field_4' => ['S' => $row[8] ?? ''],
                    'custom_field_5' => ['S' => $row[9] ?? ''],
                    'custom_field_6' => ['S' => $row[10] ?? ''],
                    'custom_field_7' => ['S' => $row[11] ?? ''],
                    'custom_field_8' => ['S' => $row[12] ?? ''],
                    'custom_field_9' => ['S' => $row[13] ?? ''],
                    'custom_field_10' => ['S' => $row[14] ?? ''],
                    'custom_field_11' => ['S' => $row[15] ?? ''],
                    'custom_field_12' => ['S' => $row[16] ?? ''],
                    'custom_field_13' => ['S' => $row[17] ?? ''],
                    'custom_field_14' => ['S' => $row[18] ?? ''],
                    'custom_field_15' => ['S' => $row[19] ?? ''],
                    'custom_field_16' => ['S' => $row[20] ?? ''],
                    'custom_field_17' => ['S' => $row[21] ?? ''],
                    'custom_field_18' => ['S' => $row[22] ?? ''],
                    'custom_field_19' => ['S' => $row[23] ?? ''],
                    'custom_field_20' => ['S' => $row[24] ?? ''],
                ],
            ]);
        }
    }
}
