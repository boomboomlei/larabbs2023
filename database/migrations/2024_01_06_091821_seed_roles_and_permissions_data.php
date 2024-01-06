<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        

        Permission::create(['name'=>'manager_contents']);
        Permission::create(['name'=>'manager_users']);
        Permission::create(['name'=>'edit_settings']);

        $founder=Role::create(['name'=>'Founder']);
        $founder->givePermissionTo('manager_contents');
        $founder->givePermissionTo('manager_users');
        $founder->givePermissionTo('edit_settings');

        $maintainer=Role::create(['name'=>'Maintainer']);
        $maintainer->givePermissionTo('manager_contents');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $tableNames =config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permission'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();

    }
}
