<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $owner = new Role();
      $owner->name         = 'owner';
      $owner->display_name = 'Project Owner'; // optional
      $owner->description  = 'User is the owner of a given project'; // optional
      $owner->save();

      $admin = new Role();
      $admin->name         = 'admin';
      $admin->display_name = 'User Administrator'; // optional
      $admin->description  = 'User is allowed to manage and edit other users'; // optional
      $admin->save();

      $cashier = new Role();
      $cashier->name         = 'cashier';
      $cashier->display_name = 'User Cashier'; // optional
      $cashier->description  = 'User is the handler of POS'; // optional
      $cashier->save();

      $user1 = new User();
      $user1->name = 'Admin';
      $user1->username = 'admin';
      $user1->email = 'admin@jblpharmacy.com';
      $user1->password = bcrypt('xyz123');
      $user1->save();
      $user1->attachRole($admin);

      $user2 = new User();
      $user2->name = 'JB Lanting';
      $user2->username = 'jblanting';
      $user2->email = 'owner@jblpharmacy.com';
      $user2->password = bcrypt('abc123');
      $user2->save();
      $user2->attachRole($owner);

      $user3 = new User();
      $user3->name = 'Cashier';
      $user3->username = 'cashier';
      $user3->email = 'cashier@jblpharmacy.com';
      $user3->password = bcrypt('123123');
      $user3->save();
      $user3->attachRole($cashier);
    }
}
