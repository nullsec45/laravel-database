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

        DB::delete("delete from products");
        DB::delete("delete from categories");
        DB::delete("delete from counters");
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

    public function testDelete(){
        $this->testInsert(true);

        DB::table("categories")->where("id","=","SMARTPHONE")->delete();

        $collection=DB::    table("categories")->where("id","=","SMARTPHONE")->get();
        self::assertCount(0, $collection);

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function insertProducts(){
        $this->testInsert(true);

        try{
            DB::transaction(function () {
                DB::table("products")->insert([
                    "id" => "1",
                    "name" => "Iphone 14 Pro Max",
                    "category_id" => "SMARTPHONE",
                    "price" => 200000000
                ]);

                DB::table("products")->insert([
                    "id" => "2",
                    "name" => "Samsung Galaxy S21 Ultra",
                    "category_id" => "SMARTPHONE",
                    "price" => 100000000
                ]);

                DB::table("products")->insert([
                    "id" => "3",
                    "name" => "Realme C5",
                    "category_id" => "SMARTPHONE",
                    "price" => 5000000
                ]);
            });
        }catch(QueryException $error){
       }
    }

    public function testQueryBuilderJoin(){
        $this->insertProducts();

        $collection=DB::table("products")
                    ->join("categories","products.category_id","=","categories.id")
                    ->select("products.id","products.name","categories.name as category_name","products.price")
                    ->get();

        self::assertCount(3, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testOrdering(){
        $this->insertProducts();

        $collection=DB::table("products")->whereNotNull("id")
                                         ->orderBy("price","asc")
                                         ->orderBy("name","asc")
                                         ->get();
        self::assertCount(3, $collection);
        $collection->each(function($item){
            Log::info(\json_encode($item));
        });
    }

    public function testPaging(){
        $this->testInsert(true);
        
        $collection=DB::table("categories")
                        ->skip(0)
                        ->take(5)
                        ->get();

        self::assertCount(5, $collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function inserManyCounters(){
        for($i=0;$i<100;$i++){
            DB::table("counters")->insert([
                "id" => "sample ".$i,
                "counter" => $i,
            ]);
        }
    }

    public function testChunk(){
        $this->inserManyCounters();

        DB::table("counters")->orderBy("id")
            ->chunk(10, function($counters){
                self::assertNotNull($counters);
                Log::info("Start Chunk");
                $counters->each(function($counter){
                    Log::info(json_encode($counter));
                });
                Log::info("End Chunk");

            });
    }

    public function testLazy(){
        $this->inserManyCounters();

        $collection=DB::table("counters")->orderBy("id")->lazy(10)->take(3);
        self::assertNotNull($collection);
        
        $collection->each(function($item){
            Log::info(json_encode($item));
        });

    }

    public function testCursor(){
        $this->inserManyCounters();

        $collection=DB::table("counters")->orderBy("id")->cursor();
        self::assertNotNull($collection);
        
        $collection->each(function($item){
            Log::info(json_encode($item));
        });

    }

    public function testAggregate(){
        $this->inserManyCounters();
        $this->insertProducts();

        $result=DB::table("counters")->count("id");
        self::assertEquals(100, $result);
       
        echo $result.PHP_EOL;
  
        $result=DB::table("counters")->min("counter");
        self::assertEquals(0, $result);
        echo $result.PHP_EOL;
        
        $result=DB::table("counters")->max("counter");
        self::assertEquals(99, $result);
        echo $result.PHP_EOL;
        
        $result=DB::table("products")->sum("price");
        self::assertEquals(305000000, $result);
        echo $result.PHP_EOL;
        
        $result=DB::table("products")->avg("price");
        self::assertEquals(101666666.6667, $result);
        echo $result.PHP_EOL;
    }

    public function testRawAggregate(){
        $this->insertProducts();

        $collection=DB::table("products")
                    ->select(
                        DB::raw("count(*) as total_product"),
                        DB::raw("min(price) as min_price"),
                        DB::raw("max(price) as max_price")
                    )->get();

                    
        self::assertEquals(3, $collection[0]->total_product);
        self::assertEquals(5000000, $collection[0]->min_price);
        self::assertEquals(200000000, $collection[0]->max_price);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });

    }

    public function insertProductFood(){
        DB::table("products")->insert([
            "id" => "4",
            "name" => "Bakso",
            "category_id" => "FOOD",
            "price" => 20000
        ]);
        DB::table("products")->insert([
            "id" => "5",
            "name" => "Mie Ayam Bakso",
            "category_id" => "FOOD",
            "price" => 25000
        ]);
    }

    public function testGrouping(){
        $this->insertProducts();
        $this->insertProductFood();

        $collection=DB::table("products")
                        ->select("category_id", DB::raw("count(*) as total_product"))
                        ->groupBy("category_id")
                        ->orderBy("category_id", "desc")
                        ->get();

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
        self::assertCount(2, $collection);
        self::assertEquals("SMARTPHONE", $collection[0]->category_id);
        self::assertEquals("FOOD", $collection[1]->category_id);
        self::assertEquals(3, $collection[0]->total_product);
        self::assertEquals(2, $collection[1]->total_product);
    }

    public function testGroupByHaving(){
        $this->insertProducts();
        $this->insertProductFood();

        $collection=DB::table("products")
                        ->select("category_id", DB::raw("count(*) as total_product"))
                        ->groupBy("category_id")
                        ->having(DB::raw("count(*)"),">", 2)
                        ->orderBy("category_id", "desc")
                        ->get();

        $collection->each(function($item){
            Log::info(json_encode($item));
        });
       
        self::assertCount(1, $collection);
    }

    public function testLocking(){
        $this->insertProducts();

        DB::transaction(function(){    
            $collection=DB::table("products")
            ->where("id","=","1")
            ->lockForUpdate()
            ->get();
             self::assertCount(1, $collection);
        });
    }

    public function testPagination(){
        $this->testInsert(true);

        $paginate=DB::table("categories")->paginate(perPage:2, page:2);

        self::assertEquals(2, $paginate->currentPage());
        self::assertEquals(2, $paginate->perPage());
        self::assertEquals(3, $paginate->lastPage());
        self::assertEquals(6, $paginate->total());

        $collection=$paginate->items();
        self::assertCount(2, $collection);

        foreach($collection as $item){
            Log::info(json_encode($item));
        }
    }

    public function testIteratePagination(){
      $this->testInsert(true);

      $page=1;

        while(true){
            $paginate=DB::table("categories")->paginate(perPage:2, page:$page);

            if($paginate->isEmpty()){
                break;
            }else{
                $collection=$paginate->items();
                self::assertCount(2, $collection);
    
                foreach($collection as $item){
                    Log::info(json_encode($item));
                }
                $page++;
            }   
        }
    }
}
