<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RawQueryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp():void{
        parent::setUp();

        DB::delete("delete from categories");
    }
  
    public function testCrud(){
        DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Gadget","Gadget Category", NOW(), NOW()]);

        $results=DB::select("SELECT * FROM categories WHERE id=?", ["GADGET"]);
        $date=NOW();
        self::assertCount(1, $results);
        self::assertEquals("GADGET", $results[0]->id);
        self::assertEquals("Gadget", $results[0]->name);
        self::assertEquals("Gadget Category", $results[0]->description);
        self::assertEquals($date, $results[0]->created_at);
        self::assertEquals($date, $results[0]->updated_at);
    }

    public function testCrudNamedParameter(){
        
        DB::insert("insert into categories(id, name, description, created_at, updated_at) values (:id, :name, :description, :created_at, :updated_at)",
            ["id" => "FASHION",
             "name" => "Fashion",
             "description"=>"Fashion Category",
             "created_at" => NOW(), 
             "updated_at" => NOW()]);

        $results=DB::select("SELECT * FROM categories WHERE id=?", ["FASHION"]);
        $date=NOW();
        self::assertCount(1, $results);
        self::assertEquals("FASHION", $results[0]->id);
        self::assertEquals("Fashion", $results[0]->name);
        self::assertEquals("Fashion Category", $results[0]->description);
        self::assertEquals($date, $results[0]->created_at);
        self::assertEquals($date, $results[0]->updated_at);
    }

    
}
