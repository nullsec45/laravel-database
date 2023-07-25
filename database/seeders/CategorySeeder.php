<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            DB::transaction(function () {
                DB::table("categories")->insert(
                    [
                        "id" => "FASHION",
                        "name" => "Fashion",
                        "description" => "Fashion Category",
                        "created_at" => NOW(),
                        "updated_at" => NOW(),
                    ]
                 );
                 
                DB::table("categories")->insert(
                    [
                        "id" => "FOOD",
                        "name" => "Food",
                        "description" => "Food Category",
                        "created_at" => NOW(),
                        "updated_at" => NOW(),
                    ]
                );
        
                DB::table("categories")->insert(
                    [
                        "id" => "SMARTPHONE",
                        "name" => "Smartphone",
                        "description" => "Smartphone Category",
                        "created_at" => NOW(),
                        "updated_at" => NOW(),
                    ]
                );
                DB::table("categories")->insert(
                    [
                        "id" => "JEWELLERY",
                        "name" => "Jewellery",
                        "description" => "Jewellery Category",
                        "created_at" => NOW(),
                        "updated_at" => NOW(),
                    ]
                );
                DB::table("categories")->insert(
                    [
                        "id" => "LAPTOP",
                        "name" => "Laptop",
                        "description" => NULL,
                        "created_at" => NOW(),
                        "updated_at" => NOW(),
                    ]
                );
                DB::table("categories")->insert(
                    [
                        "id" => "HEALT",
                        "name" => "Health",
                        "description" => NULL,
                        "created_at" => NOW(),
                        "updated_at" => NOW(),
                    ]
                );
            });
        }catch(QueryException $error){
        
        }
    }
}
