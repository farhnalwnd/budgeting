<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Budgeting\CategoryMaster;
use App\Models\User;
use App\Models\Level;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Permissions
         Permission::create(['name' => 'view role']);
         Permission::create(['name' => 'create role']);
         Permission::create(['name' => 'update role']);
         Permission::create(['name' => 'delete role']);

         Permission::create(['name' => 'view permission']);
         Permission::create(['name' => 'create permission']);
         Permission::create(['name' => 'update permission']);
         Permission::create(['name' => 'delete permission']);

         Permission::create(['name' => 'view user']);
         Permission::create(['name' => 'create user']);
         Permission::create(['name' => 'update user']);
         Permission::create(['name' => 'delete user']);

         Permission::create(['name' => 'view product']);
         Permission::create(['name' => 'create product']);
         Permission::create(['name' => 'update product']);
         Permission::create(['name' => 'delete product']);

         Permission::create(['name' => 'view finance']);
         Permission::create(['name' => 'create finance']);
         Permission::create(['name' => 'update finance']);
         Permission::create(['name' => 'delete finance']);

         Permission::create(['name' => 'view department']);
         Permission::create(['name' => 'create department']);
         Permission::create(['name' => 'update department']);
         Permission::create(['name' => 'delete department']);

         Permission::create(['name' => 'view position']);
         Permission::create(['name' => 'create position']);
         Permission::create(['name' => 'update position']);
         Permission::create(['name' => 'delete position']);

         Permission::create(['name' => 'view level']);
         Permission::create(['name' => 'create level']);
         Permission::create(['name' => 'update level']);
         Permission::create(['name' => 'delete level']);

         Permission::create(['name' => 'view dashboard Finance']);
         Permission::create(['name' => 'view dashboard Sales & Marketing']);
         Permission::create(['name' => 'view dashboard Supply Chain']);
         Permission::create(['name' => 'view dashboard HCD']);
         Permission::create(['name' => 'view dashboard Engineering & Maintainance']);
         Permission::create(['name' => 'view dashboard Manufacturing']);
         Permission::create(['name' => 'view dashboard QM & HSE']);
         Permission::create(['name' => 'view dashboard R&D']);


         Permission::create(['name' => 'view requisition']);
         Permission::create(['name' => 'get data master']);
         Permission::create(['name' => 'view browse requisition']);
         Permission::create(['name' => 'create maintenance requisition']);
         Permission::create(['name' => 'update maintenance requisition']);
         Permission::create(['name' => 'delete maintenance requisition']);
         Permission::create(['name' => 'print maintenance requisition']);

        Permission::create(['name' => 'view production dashboard']);
        Permission::create(['name' => 'view sales dashboard']);
        Permission::create(['name' => 'view data dashboard']);
        Permission::create(['name' => 'view inventory dashboard']);

        


         //create departements

        Department::create(['department_name' => 'Engineering & Maintainance']);
        Department::create(['department_name' => 'Finance Admin']);
        Department::create(['department_name' => 'HCD']);
        Department::create(['department_name' => 'Manufacturing']);
        Department::create(['department_name' => 'QM & HSE']);
        Department::create(['department_name' => 'R&D']);
        Department::create(['department_name' => 'Sales & Marketing']);
        Department::create(['department_name' => 'Supply Chain']);
        Department::create(['department_name' => 'Secret']);

         //create Level
         Level::create(['level_name' => 'I']);
         Level::create(['level_name' => 'II']);
         Level::create(['level_name' => 'III']);
         Level::create(['level_name' => 'IV']);
         Level::create(['level_name' => 'V']);
         Level::create(['level_name' => 'VI']);
         Level::create(['level_name' => 'VII']);
         Level::create(['level_name' => 'Developer']);

          // department finance and admin
        Position::create(['position_name' => 'Developer','level_id' => 8,'department_id' => 9 ]);
        Position::create(['position_name' => 'General Manager','level_id' => 7,'department_id' => 1 ]);
        Position::create(['position_name' => 'Department Head Finance','level_id' => 6,'department_id' =>2 ]);
        Position::create(['position_name' => 'Assistant Manager MIS','level_id' => 5,'department_id' =>2 ]);
        Position::create(['position_name' => 'Manager Accounting & Tax','level_id' => 5,'department_id' =>2 ]);
        Position::create(['position_name' => 'Manager Bussiness Opr. Control','level_id' => 5,'department_id' => 2]);
        Position::create(['position_name' => 'IT Support','level_id' => 3,'department_id' =>2 ]);
        Position::create(['position_name' => 'Web Developer','level_id' => 4,'department_id' =>2 ]);
        Position::create(['position_name' => 'Supervisor - MIS ','level_id' => 4,'department_id' =>2 ]);

        // department suppply chain
        Position::create(['position_name' => 'Department Head Supply Chain','level_id' => 6,'department_id' =>8 ]);
        Position::create(['position_name' => 'Manager - Logistic','level_id' => 6,'department_id' =>8]);
        Position::create(['position_name' => 'Manager - PPIC','level_id' => 6,'department_id' =>8 ]);
        Position::create(['position_name' => 'Manager - Purchasing','level_id' => 6,'department_id' =>8 ]);
        Position::create(['position_name' => 'Supervisor - Export','level_id' => 5,'department_id' =>8 ]);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $userRole = Role::create(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role']);
        $adminRole->givePermissionTo(['create permission', 'view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $adminRole->givePermissionTo(['create product', 'view product', 'update product']);

        $staffRole->givePermissionTo(['view dashboard Finance']);
        $userRole->givePermissionTo(['view dashboard Sales & Marketing']);
        $userRole->givePermissionTo(['view browse requisition']);

        $superAdminRole->givePermissionTo(['view requisition', 'get data master', 'view browse requisition', 'create maintenance requisition', 'update maintenance requisition', 'delete maintenance requisition', 'print maintenance requisition']);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'username' => 'super',
                    'nik' => 'AG1111',
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make ('password'),
                    'email_verified_at' => now(),
                    'position_id' => 1,
                    'department_id' => 1,
                ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'username' => 'admin',
                            'nik' => 'AG2222',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('password'),
                            'email_verified_at' => now(),
                            'position_id' => 3,
                            'department_id' => 1,
                        ]);

        $adminUser->assignRole($adminRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            'username' => 'staff',
                            'nik' => 'AG3333',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('password'),
                            'email_verified_at' => now(),
                            'position_id' => 3,
                            'department_id' => 1,
                        ]);

        $staffUser->assignRole($staffRole);


        CategoryMaster::Create(['name' => 'Plant & Machineries']);
        CategoryMaster::Create(['name' => 'Factory Building']);
        CategoryMaster::Create(['name' => 'Storage Equipment']);
        CategoryMaster::Create(['name' => 'Laboratory Equipment']);
        CategoryMaster::Create(['name' => 'Test Bakery Equipment']);
        CategoryMaster::Create(['name' => 'Office Building']);
        CategoryMaster::Create(['name' => 'Furniture & Fixture']);
        CategoryMaster::Create(['name' => 'Office Equipment']);
        CategoryMaster::Create(['name' => 'Passanger Vehicle']);
        CategoryMaster::Create(['name' => 'Delivery Truck']);
        CategoryMaster::Create(['name' => 'Forklift']);
        CategoryMaster::Create(['name' => 'Land improvement']);
    }
}
