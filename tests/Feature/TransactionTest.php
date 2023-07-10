<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp():void{
        parent::setUp();

        DB::delete("delete from categories");
    }

    public function testTransactionSuccess(): void
    {
        DB::transaction(function () {
            DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Gadget","Gadget Category", NOW(), NOW()]);
            DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["FOOD","Food","Food Category", NOW(), NOW()]);
        }, 2);

        $results=DB::select("select * from categories");
        self::assertCount(2, $results);
    }

    public function testTransactionFail(): void
    {
       try{
            DB::transaction(function () {
                DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Gadget","Gadget Category", NOW(), NOW()]);
                DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Food","Food Category", NOW(), NOW()]);
            });
       }catch(QueryException $error){
        
       }

       
        $results=DB::select("select * from categories");
        self::assertCount(0, $results);
    }

    public function testManualTransactionSuccess(): void
    {
       try{
            DB::beginTransaction();
            DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Gadget","Gadget Category", NOW(), NOW()]);
            DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["FOOD","Food","Food Category", NOW(), NOW()]);
            DB::commit();
       }catch(QueryException $error){
            DB::rollBack();
       }

        $results=DB::select("select * from categories");
        self::assertCount(2, $results);
    }

    public function testManualTransactionFail(): void
    {
       try{
            DB::beginTransaction();
            DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Gadget","Gadget Category", NOW(), NOW()]);
            DB::insert("insert into categories(id, name, description, created_at, updated_at) values (?, ?, ?, ?, ?)",["GADGET","Food","Food Category", NOW(), NOW()]);
            DB::commit();
       }catch(QueryException $error){
            DB::rollBack();
       }

        $results=DB::select("select * from categories");
        self::assertCount(0, $results);
    }
}
