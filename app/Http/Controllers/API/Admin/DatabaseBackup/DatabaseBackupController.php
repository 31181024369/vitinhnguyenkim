<?php
namespace App\Http\Controllers\API\Admin\DatabaseBackup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatabaseBackupController extends Controller {
    public function backupDatabse(){

        //ENTER THE RELEVANT INFO BELOW
        $mysqlHostName      = env('DB_HOST');
        $mysqlUserName      = env('DB_USERNAME');
        $mysqlPassword      = env('DB_PASSWORD');
        $DbName             = env('DB_DATABASE');
        $backup_name        = "mybackup.sql";
        $tables             = array("about","about_desc","admin","adminlogs","admin_group","admin_menu","admin_permission","admin_sessions","advertise","ad_pos","card_promotion","combo_products","comment","config","contact","contact_config","contact_config_desc",
        "contact_qoute","contact_search","contact_staff","coupon","coupondes","coupondesusing","coupon_status","coupon_wholesale_customer_name","department","failed_jobs","faqs",
        "faqs_category","faqs_category_desc","faqs_desc","guide","guide_desc","icon","introduce_partner","language","lang_phrase","list_cart","list_cart_time","log",
        "maillist_group","mail_template","members","mem_group","menu","menu_desc","messages","migrations","model_has_permissions","model_has_roles","modules",
        "news","news_advertise","news_category","news_category_desc","news_desc","notifications","oauth_access_tokens","oauth_auth_codes","oauth_clients","oauth_personal_access_clients",
        "oauth_refresh_tokens","order_address","order_detail","order_status","order_sum","order_transaction","page","page_desc","password_resets","payment_method","permissions","personal_access_tokens",
        "product","product_advertise","product_brand","product_brand_desc","product_category","product_category_desc","product_cat_option","product_cat_option_desc","product_cat_search",
        "product_cat_option_desc","product_desc","product_flash_sale","product_gift_description","product_group","product_import_excel","product_option","product_option_desc","product_picture",
        "product_price_search","product_shortcode","product_status","product_status_desc","promotion","promotion_desc","redirect","roles","role_has_permissions","seo_url","service",
        "service_desc","sessions","setting","shipping_method","sitedoc","statistics_exclusions","statistics_pages","statistics_setting","statistics_useronline","statistics_visit","statistics_visitor",
        "support","support_group"); 

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();


        $output = '';
        foreach($tables as $table)
        {
         $show_table_query = "SHOW CREATE TABLE " . $table . "";
         $statement = $connect->prepare($show_table_query);
         $statement->execute();
         $show_table_result = $statement->fetchAll();

         foreach($show_table_result as $show_table_row)
         {
          $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
         }
         $select_query = "SELECT * FROM " . $table . "";
         $statement = $connect->prepare($select_query);
         $statement->execute();
         $total_row = $statement->rowCount();

         for($count=0; $count<$total_row; $count++)
         {
          $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
          $table_column_array = array_keys($single_result);
          $table_value_array = array_values($single_result);
          $output .= "\nINSERT INTO $table (";
          $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
          $output .= "'" . implode("','", $table_value_array) . "');\n";
         }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
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
           ob_clean();
           flush();
           readfile($file_name);
           unlink($file_name);


}
}