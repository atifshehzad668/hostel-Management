<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommonController extends Controller
{
    public function backup()
    {
        try {
            $tables = DB::select('SHOW TABLES');

            $output = '';
            foreach ($tables as $table) {
                $table = get_object_vars($table);
                $tableName = current($table);

                $showTableQuery = "SHOW CREATE TABLE $tableName";
                $createTable = DB::select($showTableQuery)[0]->{'Create Table'};
                $output .= "\n\n" . $createTable . ";\n\n";

                $selectRowsQuery = "SELECT * FROM $tableName";
                $rows = DB::select($selectRowsQuery);

                foreach ($rows as $row) {
                    $row = (array)$row;
                    $values = implode("', '", array_map('addslashes', $row));
                    $output .= "INSERT INTO $tableName VALUES ('$values');\n";
                }
            }

            // Set file name and path
            $fileName = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = storage_path('app/' . $fileName);

            // Write output to the backup file
            file_put_contents($filePath, $output);

            // Set headers for file download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Pragma: no-cache');
            header('Cache-Control: must-revalidate');
            header('Expires: 0');
            readfile($filePath);

            // Delete the backup file after download
            unlink($filePath);

            exit;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
