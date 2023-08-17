<?php


namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase; // Rollback database changes after each test

    public function testIndex(Request $request)
{
    $obj=new User();
        
            $obj->name=$request->input('Maybell Ferry');
            $obj->email=$request->input('ecole@example.org');
            $obj->email_verified_at=$request->input(now());
            $obj->password=$request->input(bcrypt('password123'));
            $obj->role=$request->input('user');
            $obj->created_at=$request->input(now()->subDays(10));
            $obj->save();
            return 'ok';
        
}
}