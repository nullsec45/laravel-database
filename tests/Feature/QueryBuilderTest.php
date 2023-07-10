<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryBuilderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp():void{
        parent::setUp();

        DB::delete("delete from categories");
    }

    public function testInsert($use=false): void
    {
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

       if($use == false){
        $results=DB::select("select COUNT(id) AS total FROM categories");
        self::assertEquals(2,$results[0]->total);
       }
    }

    public function testSelect(){
        $this->testInsert(true);

        $collection=DB::table("categories")->select(["id","name"])->get();
        self::assertNotNull($collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
}
