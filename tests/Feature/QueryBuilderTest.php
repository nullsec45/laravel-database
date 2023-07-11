<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Database\Query\Builder;
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

    public function testWhere(){
        $this->testInsert(true);

        $collection=DB::table("categories")->where(function(Builder $builder){
            $builder->where("id","=","SMARTPHONE");
            $builder->orWhere("id","=","LAPTOP");
        })->get();
        
        self::assertCount(2, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testWhereBetweenMethod(){
        $this->testInsert(true);
        
        $collection=DB::table("categories")->whereBetween("created_at",[NOW(), NOW()])->get();

        self::assertCount(6, $collection);

        for($i=0;$i<count($collection);$i++){
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testWhereIn(){
        $this->testInsert(true);
        
        $collection=DB::table("categories")->whereIn("id",["SMARTPHONE","LAPTOP"])->get();

        self::assertCount(2, $collection);

        for($i=0;$i<count($collection);$i++){
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testWhereNullMethod(){
        $this->testInsert(true);

        $collection=DB::table("categories")->whereNull("description")->get();
        self::assertCount(2, $collection);

        for($i=0;$i<count($collection);$i++){
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testWhereDateMethod(){
        $this->testInsert(true);

        $collection=DB::table("categories")->whereYear("created_at","2023")->get();
        self::assertCount(6, $collection);

        for($i=0;$i<count($collection);$i++){
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testUpdate(){
        $this->testInsert(true);

        DB::table("categories")->where("id","=","SMARTPHONE")->update(["id" => "HANDPHONE","name" => "Handphone"]);

        $collection=DB::table("categories")->where("name","=","Handphone")->get();
        self::assertCount(1, $collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testUpsert(){
        DB::table("categories")->updateOrInsert([
            "id" => "VOUCHER"
        ],[
            "id" => "VOUCHER",
            "name" => "Voucher",
            "description" => "Ticket and Voucher",
            "created_at" => NOW(),
            "updated_at" => NOW()
        ]);

        $collection=DB::table("categories")->where("id","=","VOUCHER")->get();
        self::assertCount(1, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testIncrement(){
        DB::table("counters")->where("id","=","sample")->increment("counter",1);

        $collection=DB::table("counters")->where("id","=","sample")->get();
        self::assertCount(1, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testDecrement(){
        DB::table("counters")->where("id","=","sample")->decrement("counter",1);

        $collection=DB::table("counters")->where("id","=","sample")->get();
        self::assertCount(1, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
}
