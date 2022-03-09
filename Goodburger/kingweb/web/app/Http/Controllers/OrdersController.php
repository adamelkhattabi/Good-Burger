<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use App\Lang;
use Auth;

class OrdersController extends Controller
{
    public function getBasket(Request $request){
        $path = Settings::getPath();
        if ($path == null)
            return "Set URL_DASHBOARD variable in .env file (getBasket)";

        $orderdetails = null;
        $dataValue = [];
        $fee = null;
        $percent = null;
        $order = null;
        $auth = "0";
        if (Auth::user() != null) {
            $auth = "1";
            $id = Auth::user()->id;
            // basket
            $order = DB::table('orders')->where('user', '=', "$id")->where('send', '=', "0")->get()->first();
            if ($order != null) {
                $orderdetails = DB::table('ordersdetails')->where('order', '=', "$order->id")->get();
                $dataValue = [];
                foreach ($orderdetails as &$value) {
                    // extras
                    if ($value->count != 0) {
//                        $value->extras = array(
//                            'id' => $value->id,
//                            'name' => $value->extras,
//                            'image' => ,
//                            'price' => $value->extrasprice,
//                            'select' => true
//                        );
//                        if (($key = array_search($value, (array) $orderdetails)) !== false)
//                            unset($orderdetails[$key]);
                        //
                        $foodCategory = DB::table('foods')->where('id', '=', "$value->foodid")->get()->first();
                        if ($foodCategory != null)
                            $value->category = $foodCategory->category;
                        $food = DB::table('foods')->where('id', '=', "$value->id")->get()->first();
                        if ($food != null)
                            $value->discountprice = $food->discountprice;
                        else
                            $value->discountprice = 0;
                        $value->image = $path . $value->image;
                        $dataValue[] = $value;
                    }
                }
                $fee = DB::table('restaurants')->where('id', '=', $order->restaurant)->get()->first()->fee;
                $percent = DB::table('restaurants')->where('id', '=', $order->restaurant)->get()->first()->percent;
            }
        }
        $tax = DB::table('settings')->where('param', '=', "default_tax")->get()->first()->value;
        $currencies = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        $response = [
            'error' => '0',
            'currency' => $currencies,
            'default_tax' => $tax,
            'order' => $order,
            'orderdetails' => $dataValue, //$orderdetails,
            'fee' => $fee,
            'percent' => $percent,
            'auth' => $auth
        ];
        return response()->json($response, 200);
    }

    public function addToBasket(Request $request)
    {
        if (Auth::user() == null)
            return response()->json($response = ['error' => "1",], 200);

        $id = Auth::user()->id;
        $order = DB::table('orders')->where('user', '=', "$id")->where('send', '=', "0")->get()->first();
        $orderid = 0;
        $send = $request->input('send');
        $pstatus = "";
        $tax = $request->input('tax');

        $restaurant = "1";
        $method = $request->input('method');
        $hint = $request->input('hint');
        $fee = $request->input('fee');
        $address = $request->input('address');
        $phone = $request->input('phone');
        $total = $request->input('total') ?: 0;
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $couponName = $request->input('couponName') ?: "";
        $curbsidePickup = $request->input('curbsidePickup');
        $fee = DB::table('restaurants')->where('id', '=', "$restaurant")->get()->first()->fee;
        $percent = DB::table('restaurants')->where('id', '=', "$restaurant")->get()->first()->percent;
        if ($curbsidePickup == "true")
            $fee = "0";

        if ($order == null) {
            $values = array('driver' => "0",
                'address' => "$address", 'phone' => "$phone",
                'user' => "$id", 'status' => '1', 'pstatus' => "$pstatus",
                'tax' => "$tax", 'hint' => "$hint", 'active' => '1', 'restaurant' => "$restaurant",
                'method' => "$method", 'fee' => "$fee", 'send' => "$send",
                'lat' => "$lat", "lng" => "$lng",
                'total' => "$total", 'percent' => "$percent",
                'curbsidePickup' => "$curbsidePickup",
                'couponName' => $couponName,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            );
            DB::table('orders')->insert($values);
            $orderid = DB::getPdo()->lastInsertId();
        }else {
            $orderid = $order->id;
            $values = array(
                'address' => "$address", 'phone' => "$phone",
                'total' => "$total",
                'user' => "$id", 'status' => '1', 'pstatus' => "$pstatus",
                'tax' => "$tax", 'hint' => "$hint", 'active' => '1', 'restaurant' => "$restaurant",
                'lat' => "$lat", "lng" => "$lng", 'percent' => "$percent",
                'method' => "$method", 'fee' => "$fee", 'send' => "$send",
                'couponName' => $couponName,
                'curbsidePickup' => "$curbsidePickup",
                'updated_at' => new \DateTime());
            DB::table('orders')
                ->where('id',$orderid)
                ->update($values);
        }

        DB::table('ordersdetails')->where('order', '=', $orderid)->delete();

        $debug = [];
        //$pstatus = $request->input('pstatus');
        $data = $request->input('data');
        if ($data != null)
            foreach ($data as &$value) {
                $debug[] = array("value"=>$value);
                $food = $value['title'];
                $count = $value['count'];
                $foodprice = $value['price'];
                if ($value['discountprice'] != 0)
                    $foodprice = $value['discountprice'];
                $extras = ""; //$value['extras'];
                $extrascount = 0; //$value['extrascount'];
                $extrasprice = 0 ; //$value['extrasprice'];
                $foodid = $value['id'];
                $extrasid = 0; //value['extrasid'];
                $image = basename($value['image']);
                $values = array(
                    'order' => "$orderid", 'food' => "$food", 'count' => "$count",
                    'foodprice' => "$foodprice", 'extras' => "$extras", 'extrascount' => "$extrascount", 'extrasprice' => "$extrasprice",
                    'foodid' => "$foodid", 'extrasid' => "$extrasid", 'image' => "$image",
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                );
                DB::table('ordersdetails')->insert($values);
                //
                if (array_key_exists("extras", $value)) {
                    foreach ($value['extras'] as &$extras) {
                        $debug[] = array("extras"=>$extras);
                        if ($extras['select'] == "true") {
                            $filename = pathinfo($extras['image']);
                            DB::table('ordersdetails')->insert(array(
                                'order' => "$orderid",
                                'food' => "", 'count' => "0",
                                'foodprice' => "0",
                                'foodid' => "0",
                                'extras' => $extras['name'],
                                'extrascount' => $value['count'],
                                'extrasprice' => $extras['price'],
                                'extrasid' => $extras['id'],
                                'image' => $filename['basename'],
                                'created_at' => new \DateTime(),
                                'updated_at' => new \DateTime(),
                            ));
                        }
                    }
                }
            }

        if ($send == '1'){
            $values = array(
                'order_id' => "$orderid", 'status' => 1, 'driver' => 0,
                'comment' => "",
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            );
            DB::table('ordertimes')->insert($values);
            //
            $count = DB::table('settings')->where('param', '=', "ordersNotifications")->get()->first()->value;
            $count += 1;
            DB::table('settings')->where('param', '=', "ordersNotifications")->update(['value' => "$count", 'updated_at' => new \DateTime(),]);
            //
            // send notifications to manager and admin
            //
            // Send Notifications to Admin
            //
            $userid = DB::table('users')->where('role',"1")->get()->first();
            if ($userid != null) {
                $myRequest = new \Illuminate\Http\Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add(['user' => $userid->id]);
                $myRequest->request->add(['title' => Lang::get(128)]); // 'New order arrived',
                $myRequest->request->add(['text' => Lang::get(129) . $orderid]); // "Order #",
                $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
                $myRequest->request->add(['imageid' => $defaultImage]);
                MessagingController::sendNotify($myRequest);
            }
            //
            // Send Notifications to Managers
            //
            $managers = DB::table('users')->where('role',"2")->get();
            foreach ($managers as &$value) {
                //$restaurant
                //$manager = DB::table('manager_rest')->where('user', '=', $value->id)->where('restaurant', '=', $restaurant)->get();
                //if (count($manager) != 0){
                    $myRequest = new \Illuminate\Http\Request();
                    $myRequest->setMethod('POST');
                    $myRequest->request->add(['user' => $value->id]);
                    $myRequest->request->add(['title' => Lang::get(128)]); // 'New order arrived',
                    $myRequest->request->add(['text' => Lang::get(129) . $orderid]); // "Order #",
                    $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
                    $myRequest->request->add(['imageid' => $defaultImage]);
                    MessagingController::sendNotify($myRequest);
                //}
            }
        }

        $response = [
            'error' => "0",
            'debug'=> $debug,
            'orderid' => $orderid,
            'fee' => $fee,
            'percent' => $percent,
        ];
        return response()->json($response, 200);
    }
}
