<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\DatabaseBackupEmail;
use Illuminate\Support\Facades\Mail;
class DatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $mysqlHostName = env('DB_HOST');
        $mysqlUserName = env('DB_USERNAME');
        $mysqlPassword = env('DB_PASSWORD');
        $DbName = env('DB_DATABASE');
        $backup_name = "mybackup.sql";
        $tables = array("statistics_pages", "statistics_setting", "statistics_useronline", "statistics_visit", "statistics_visitor");
        
        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();
        
        $output = '';
        foreach ($tables as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";
            $statement = $connect->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();
        
            foreach ($show_table_result as $show_table_row) {
                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table . "";
            $statement = $connect->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();
        
            for ($count = 0; $count < $total_row; $count++) {
                $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                $table_column_array = array_keys($single_result);
                $table_value_array = array_values($single_result);
                $output .= "\nINSERT INTO $table (";
                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
        }
        
        $directory = public_path('uploads/database/');
        $file_name = $directory.'database_backup_on_' . date('y-m-d') . '.sql';
        Mail::to('long542.nt@gmail.com')
        ->send(new DatabaseBackupEmail($file_name));
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        flush();
        readfile($file_name);
        unlink($file_name);
        
        $this->info('databaseBackup success.');
    }
}
