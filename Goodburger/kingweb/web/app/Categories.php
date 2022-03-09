<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Categories
{
    public static function get(){
        $path = env('URL_DASHBOARD', null);
        $path = $path . "images/";

        // categories
        $categories = DB::table('categories')->where('visible', '=', "1")->leftjoin("image_uploads", 'image_uploads.id', '=', 'categories.imageid')->
            select('categories.*', 'image_uploads.filename as image')->get();
        foreach ($categories as &$value) {
            if ($value->image == null || $value->image == "")
                $value->image = $path . "noimage.png";
            else
                $value->image = $path . $value->image;
            if ($value->parent == '0')
                $value->parent = '-1';
        }
        return $categories;
    }

    public static function getCategoryName($categories, $cat, $catNames){
        foreach ($categories as &$value) {
            if ($value->id == $cat){
                $catNames[] = $value->name;
                $catNames = Categories::getCategoryName($categories, $value->parent, $catNames);
            }
        }
        return $catNames;
    }

    public static function getNameByFoodId($id){
        $foods = DB::table('foods')->where('id', '=', "$id")->get()->first();
        if ($foods == null)
            return "";
        $category = DB::table('categories')->where('id', '=', $foods->category)->get()->first();
        if ($category == null)
            return "";
        return $category->name;
    }

    public static function getIdByFoodId($id){
        $foods = DB::table('foods')->where('id', '=', "$id")->get()->first();
        if ($foods == null)
            return -1;
        return $foods->category;
    }


}
