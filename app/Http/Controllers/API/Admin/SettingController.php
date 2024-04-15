<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;

class SettingController extends AbstractController
 {

    protected function getModel()
 {
        return new Setting();
    }

    public function index()
 {
        try {
            $list = Setting::get();
            $response = [
                'status' => 'success',
                'list' => $list,

            ];

            return response()->json( $response, 200 );
        } catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];

            return response()->json( $response, 500 );
        }
    }

    public function edit( $id )
 {
        $listSetting = Setting::get()->find( $id );
        return response()->json( [
            'listSetting' => $listSetting,
            'status' => true
        ] );
    }

    public function update( Request $request, $id )
 {
        $list = Setting::find( $id );
        $data =  $list[ 'options' ];
        echo '<pre>';
        print_r( $data );
        exit;
        $data = preg_replace_callback(
            '/(?<=^|\{|;)s:(\d+):\"(.*?)\";(?=[asbdiO]\:\d|N;|\}|$)/s',
            function( $m ) {
                return 's:' . strlen( $m[ 2 ] ) . ':"' . $m[ 2 ] . '";';
            }
            ,
            $data
        );
        $data = unserialize( $data );

        $list->save();
    }
}

?>
