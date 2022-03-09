<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Settings;
use App\Categories;
use App\Lang;
use App\Foods;

class MainController extends Controller
{
    public function home(Request $request)
    {
        $search = $request->input('search') ?: "";
        return MainController::getPage(1, true, 0, 0, 100000000, -1, 0, $search, true);
    }

    public function main(Request $request)
    {
        $search = $request->input('search') ?: "";
        return MainController::getPage(1, true, 0, 0, 100000000, -1, 0, $search, false);
    }

    public function foodsGoPage(Request $request)
    {
        $home = $request->input('home', true) ?: true;
        $page = $request->input('page') ?: 1;
        $sort = $request->input('sort') ?: 0;
        $foodMinPrice = $request->input('foodMinPrice') ?: 0;
        $foodMaxPrice = $request->input('foodMaxPrice') ?: 0;
        $cat = $request->input('cat');
        $usecat = $request->input('usecat');
        $search = $request->input('search') ?: "";
        return MainController::getPage($page, false, $sort, $foodMinPrice, $foodMaxPrice, $cat, $usecat, $search, $home);
    }

    public static function getPage($page, $view, $sort, $foodMinPrice, $foodMaxPrice, $cat, $usecat, $search, $home){
        $path = Settings::getPath();
        if ($path == null)
            if ($view)
                return "Set URL_DASHBOARD variable in .env file";

        $t = DB::table('foods')->where("foods.published", "1")->where("price", ">=", $foodMinPrice)->where("price", "<=", $foodMaxPrice);

        if ($usecat == 1)
            $t = $t->where('foods.category', $cat);
        if ($sort == 1) // by date
            $t = $t->orderBy('updated_at', 'desc');
        if ($sort == 2) // by price
            $t = $t->orderBy('price', 'asc');
        if ($sort == 3) // by price
            $t = $t->orderBy('price', 'desc');
        $uselike = "no";
        if ($search != "") {
            $uselike = "true";
            $t = $t->where('foods.name', 'LIKE', '%' . $search . '%');
        }

        $count = count($t->get());

        $foods = $t->leftjoin("image_uploads", 'image_uploads.id', '=', 'foods.imageid')->limit(12)->offset(($page - 1) * 12)->
            select('foods.*', 'image_uploads.filename as image')->get();

        // min - max
        $min = 100000000;
        $max = 0;
        $tfoods = DB::table('foods')->where("foods.published", "1")->get();
        foreach ($tfoods as &$food) {
            if ($food->price < $min)
                $min = $food->price;
            if ($food->price > $max)
                $max = $food->price;
        }

        // fill
        $foods = Foods::fill($foods);
        // count
        $count_current = count($foods);
        $t = $count/12;
        if ($count/12 > 0)
            $t++;
        // categories
        $categories = Categories::get();
        $catNames = array();
        if ($usecat == '1'){
            $catNames = Categories::getCategoryName($categories, $cat, $catNames);
            $catNames = array_reverse($catNames);
        }
        // subcategories
        if ($cat == "0" || $cat == "-1")
            $subcat = DB::table('categories')->where('parent', '=', '-1')->orWhere('parent', '=', '0')->get();
        else
            $subcat = DB::table('categories')->where('parent', '=', $cat)->get();
        //
        $rest = DB::table('restaurants')->where('id', '=', "1")->get()->first();
        //
        if ($view) {
            if ($home) {
                $banners1 = DB::table('banners')->leftjoin("image_uploads", "image_uploads.id", "=", "banners.imageid")->
                    where('banners.position', "1")->where('banners.visible', "1")->select("banners.id", "banners.type", "banners.details", "image_uploads.filename")->get();
                foreach ($banners1 as &$data) {
                    if ($data->filename == null || $data->filename == "")
                        $data->filename = $path . "noimage.png";
                    else
                        $data->filename = $path . $data->filename;
                    if ($data->type == 1){
                        $data->link = "foodDetails?id=" . $data->details;
                    }else{
                        $data->link = $data->details;
                    }
                }
                $banners2 = DB::table('banners')->leftjoin("image_uploads", "image_uploads.id", "=", "banners.imageid")->
                    where('banners.position', "2")->where('banners.visible', "1")->select("banners.id", "banners.type", "banners.details", "image_uploads.filename")->get();
                foreach ($banners2 as &$data) {
                    if ($data->filename == null || $data->filename == "")
                        $data->filename = $path . "noimage.png";
                    else
                        $data->filename = $path . $data->filename;
                    if ($data->type == 1){
                        $data->link = "foodDetails?id=" . $data->details;
                    }else{
                        $data->link = $data->details;
                    }
                }
                // categories
                $categoriesAll = DB::select("SELECT categories.id, categories.name, image_uploads.filename FROM categories
                                                    LEFT JOIN image_uploads ON image_uploads.id=categories.imageid
                                                    WHERE visible='1' AND (parent='-1' OR parent='0')");
                foreach ($categoriesAll as &$value) {
                    if ($value->filename == null || $value->filename == "")
                        $value->filename = $path . "noimage.png";
                    else
                        $value->filename = $path . $value->filename;
                    $value->foods = DB::table('foods')->where('category',$value->id)->where("foods.published", "1")->
                        leftjoin("image_uploads", "image_uploads.id", "=", "foods.imageid")->
                        select("foods.id", "foods.name", "foods.discountprice", "foods.price", "image_uploads.filename as image", "foods.extras")->
                        orderBy('foods.updated_at', 'desc')->limit(10)->get();
                    $value->foods = Foods::fill($value->foods);
                }
                return view('home', [
                    'uselike' => $uselike,
                    'usecat' => $usecat,
                    'cat' => $cat,
                    'foods' => $foods,
                    'count_current' => $count_current,
                    'count' => $count,
                    'page' => $page,
                    'pages' => (int)$t,
                    'min' => $min,
                    'max' => $max,
                    'catNames' => $catNames,
                    'subcat' => $subcat,
                    'subcatCount' => count($subcat),
                    'title' => Lang::get(79), // "Welcome"
                    'banners1' => $banners1,
                    'banners2' => $banners2,
                    'categoriesAll' => $categoriesAll
                ]);
            }
            return view('main', [
                'uselike' => $uselike,
                'usecat' => $usecat,
                'cat' => $cat,
                'foods' => $foods,
                'count_current' => $count_current,
                'count' => $count,
                'page' => $page,
                'pages' => (int)$t,
                'min' => $min,
                'max' => $max,
                'catNames' => $catNames,
                'subcat' => $subcat,
                'subcatCount' => count($subcat),
                'title' => Lang::get(79) // "Welcome"
            ]);
        }else
            return response()->json([
                'uselike' => $uselike,
                'usecat' => $usecat,
                'cat' => $cat,
                'foods' => $foods,
                'count_current' => $count_current,
                'count' => $count,
                'page' => $page,
                'pages' => (int) $t,
                'path' => $path,
            ], 200);
    }

    public function category(Request $request){
        $cat = $request->input('cat') ?: -1;
        return MainController::getPage(1, true, 0, 0, 100000000, $cat, 1, "", false);
    }

    public function foodsInfo(Request $request){
        $id = $request->input('id') ?: 0;
        //
        return response()->json([
            'food' => MainController::getFood($id)
        ], 200);
    }

    public function setLang(Request $request){
        $lang = $request->input('lang') ?: 0;
        Lang::setDefLang($lang);
        return \Redirect::to('/');
    }

    public function details(Request $request){
        $id = $request->input('id');
        $food = MainController::getFood($id);
        //
        $cat = Categories::getIdByFoodId($id);
        $categories = Categories::get();
        $catNames = array();
        $catNames = Categories::getCategoryName($categories, $cat, $catNames);
        $catNames = array_reverse($catNames);
        //
        return view('details', [
            'food' => $food,
            'catNames' => $catNames,
        ]);
    }

    public static function getFood($id){
        $path = env('URL_DASHBOARD', null);
        if ($path == null)
            return "Set URL_DASHBOARD variable in .env file";
        $path = $path . "images/";
        //
        $food = DB::table('foods')->where("foods.id", $id)->leftjoin("image_uploads", 'image_uploads.id', '=', 'foods.imageid')->
            select('foods.*', 'image_uploads.filename as image')->get();

        Foods::fill($food);

        return ($food != null ? $food->first() : null);
    }


}
