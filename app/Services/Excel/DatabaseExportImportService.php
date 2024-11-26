<?php

namespace App\Services\Excel;

use App\Contracts\ExportImport\DatabaseExportImportInterface;
use App\Contracts\ExportImport\ExportImportServiceInterface;
use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Http\Request;

class DatabaseExportImportService implements DatabaseExportImportInterface
{
    protected $exportImportService;
    protected $dynamoDbClient;

    public function __construct(ExportImportServiceInterface $exportImportService, DynamoDbClient $dynamoDbClient)
    {
        $this->exportImportService = $exportImportService;
        $this->dynamoDbClient = $dynamoDbClient;
    }

    public function showExportImportPage()
    {
        return view('admin.export-import');
    }

    public function exportData()
    {
        try {
            $this->exportImportService->exportDataToExcel();
            return response()->download(storage_path('app/exported_data.xlsx'));
        } catch (\Exception $e) {
            return back()->withErrors(['export' => 'There was an error exporting data: ' . $e->getMessage()]);
        }
    }

    public function importData(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $this->exportImportService->importDataFromExcel($request->file('excel_file'));
            return back()->with('success', 'Data imported successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['import' => 'There was an error importing data: ' . $e->getMessage()]);
        }
    }

    public function insertData()
    {
        $records = [];
        for ($i = 0; $i < 50; $i++) {
            $record = [
                'user_id' => ['S' => 'user' . uniqid()],
                'name' => ['S' => 'User ' . rand(1, 1000)],
                'email' => ['S' => 'user' . rand(1, 1000) . '@example.com'],
                'address' => ['S' => 'Address ' . rand(1, 1000)],
                'phone_number' => ['S' => '+1' . rand(1000000000, 9999999999)],
                'created_at' => ['S' => now()->toString()],
                'updated_at' => ['S' => now()->toString()],
                'custom_field_1' => ['S' => 'Custom Value 1 ' . rand(1, 1000)],
                'custom_field_2' => ['S' => 'Custom Value 2 ' . rand(1, 1000)],
                'custom_field_3' => ['S' => 'Custom Value 3 ' . rand(1, 1000)],
                'custom_field_4' => ['S' => 'Custom Value 4 ' . rand(1, 1000)],
                'custom_field_5' => ['S' => 'Custom Value 5 ' . rand(1, 1000)],
                'custom_field_6' => ['S' => 'Custom Value 6 ' . rand(1, 1000)],
                'custom_field_7' => ['S' => 'Custom Value 7 ' . rand(1, 1000)],
                'custom_field_8' => ['S' => 'Custom Value 8 ' . rand(1, 1000)],
                'custom_field_9' => ['S' => 'Custom Value 9 ' . rand(1, 1000)],
                'custom_field_10' => ['S' => 'Custom Value 10 ' . rand(1, 1000)],
                'custom_field_11' => ['S' => 'Custom Value 11 ' . rand(1, 1000)],
                'custom_field_12' => ['S' => 'Custom Value 12 ' . rand(1, 1000)],
                'custom_field_13' => ['S' => 'Custom Value 13 ' . rand(1, 1000)],
                'custom_field_14' => ['S' => 'Custom Value 14 ' . rand(1, 1000)],
                'custom_field_15' => ['S' => 'Custom Value 15 ' . rand(1, 1000)],
                'custom_field_16' => ['S' => 'Custom Value 16 ' . rand(1, 1000)],
                'custom_field_17' => ['S' => 'Custom Value 17 ' . rand(1, 1000)],
                'custom_field_18' => ['S' => 'Custom Value 18 ' . rand(1, 1000)],
                'custom_field_19' => ['S' => 'Custom Value 19 ' . rand(1, 1000)],
                'custom_field_20' => ['S' => 'Custom Value 20 ' . rand(1, 1000)],
            ];
            $records[] = $record;
        }

        $tableName = 'users';

        // Break records into batches of 25 
        $chunks = array_chunk($records, 25);

        try {
            foreach ($chunks as $chunk) {
                $batchWriteItem = [
                    'RequestItems' => [
                        $tableName => []
                    ]
                ];

                foreach ($chunk as $record) {
                    if (!isset($batchWriteItem['RequestItems'][$tableName])) {
                        $batchWriteItem['RequestItems'][$tableName] = [];
                    }

                    $batchWriteItem['RequestItems'][$tableName][] = [
                        'PutRequest' => [
                            'Item' => $record,
                        ]
                    ];
                }
                $this->dynamoDbClient->batchWriteItem($batchWriteItem);
            }

            return back()->with('success', '50 records inserted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['insert' => 'There was an error inserting data: ' . $e->getMessage()]);
        }
    }


    public function removeAllData()
    {
        $tableName = 'users';

        try {
            do {
                $result = $this->dynamoDbClient->scan([
                    'TableName' => $tableName,
                ]);

                if (empty($result['Items'])) {
                    break;
                }

                $deleteRequests = [];
                foreach ($result['Items'] as $item) {
                    $deleteRequests[] = [
                        'DeleteRequest' => [
                            'Key' => [
                                'user_id' => $item['user_id'],
                            ],
                        ],
                    ];
                }

                $chunks = array_chunk($deleteRequests, 25);

                foreach ($chunks as $chunk) {
                    $this->dynamoDbClient->batchWriteItem([
                        'RequestItems' => [
                            $tableName => $chunk,
                        ],
                    ]);
                }
            } while (isset($result['LastEvaluatedKey']));

            return back()->with('success', 'All data removed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['remove' => 'There was an error removing data: ' . $e->getMessage()]);
        }
    }
}
