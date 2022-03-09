<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;

class AddressController extends Controller
{
    public function get(Request $request)
    {
        $response = [
            'error' => '0',
            'address' => DB::table('address')->where("user", Auth::user()->id)->get(),
        ];
        return response()->json($response, 200);
    }

    public function save(Request $request)
    {
        $def = $request->input('default');
        if ($def = "true")
            DB::table('address')->where("user", Auth::user()->id)->update(array(
                'default' => "false",
                'updated_at' => new \DateTime(),
            ));

        $values = array(
            'user' => Auth::user()->id,
            'text' => $request->input('text'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
            'type' => $request->input('type'),
            'default' => $def,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('address')->insert($values);

        $response = [
            'error' => '0',
            'address' => DB::table('address')->where("user", Auth::user()->id)->get(),
        ];
        return response()->json($response, 200);
    }

    public function del(Request $request)
    {
        DB::table('address')->where('id', '=', $request->input('id'))->where('user', '=', Auth::user()->id)->delete();

        $response = [
            'error' => '0',
            'address' => DB::table('address')->where("user", Auth::user()->id)->get(),
        ];
        return response()->json($response, 200);
    }
}
