<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Currency;
use App\Utils;

class Foods
{
    public static function fill($topfoods){
        return Foods::fill2($topfoods, 0);
    }

    public static function fill2($topfoods, $step){
        if ($topfoods == null)
            return [];
        $path = env('URL_DASHBOARD', null);
        if ($path == null)
            return "Set URL_DASHBOARD variable in .env file";
        $path = $path . "images/";
        //
        foreach ($topfoods as &$food) {
            if ($food == null)
                continue;
            if ($food->image == null || $food->image == "")
                $food->image = $path . "noimage.png";
            else
                $food->image = $path . $food->image;
            //
            $food->price2 = Currency::makePrice($food->price);
            if ($food->discountprice != "0.00")
                $food->discountprice2 = Currency::makePrice($food->discountprice);
            else
                $food->discountprice2 = "";

            // extras
            $food->extrasdata = DB::select("SELECT extras.*,
                                                CASE
                                                WHEN image_uploads.filename IS NULL THEN CONCAT('$path', \"noimage.png\")
                                                 ELSE CONCAT('$path', image_uploads.filename)
                                                END AS image
                                                FROM extras
                                                LEFT JOIN image_uploads ON image_uploads.id=extras.imageid
                                                WHERE  extrasgroup=$food->extras
                                                ");

            // variants
            $food->variants = DB::select("SELECT variants.*, image_uploads.filename as image
                FROM variants
                LEFT JOIN image_uploads ON variants.imageid=image_uploads.id
                WHERE food=$food->id ORDER BY variants.price ASC
                ");
            $first = 1;
            foreach ($food->variants as &$value){
                if ($value->image == null || $value->image == "")
                    $value->image = "";
                else
                    $value->image = $path . $value->image;
                //
                $value->timeago = Utils::timeago($value->updated_at);
                $value->price2 = Currency::makePrice($value->price);
                if ($value->dprice != "0.00")
                    $value->dprice2 = Currency::makePrice($value->dprice);
                else
                    $value->dprice2 = "";
                if ($first == 1){
                    $first = 0;
                    $food->price = $value->price;
                    $food->price2 = $value->price2;
                    if ($value->dprice != "0.00"){
                        $food->discountprice = $value->dprice;
                        $food->discountprice2 = $value->dprice2;
                    }else{
                        $food->discountprice = "0.00";
                        $food->discountprice2 = 0;
                    }
                }
            }
            if ($step == 0) {
                $food->rproducts = DB::select("SELECT rproducts.rp FROM rproducts WHERE food=$food->id");
                $food->rproducts_foods = [];
                foreach ($food->rproducts as &$value2) {
                    $food->rproducts_foods[] = DB::table('foods')->where("foods.id", $value2->rp)->leftjoin("image_uploads", 'image_uploads.id', '=', 'foods.imageid')->
                        select('foods.*', 'image_uploads.filename as image')->get()->first();
                }
                Foods::fill2($food->rproducts_foods, '1');
            }
        }
        return $topfoods;
    }
}
