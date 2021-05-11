<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SeedAdminData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          // create a user.
          Administrator::truncate();
          Administrator::create([
              'username' => 'admin',
              'password' => Hash::make('admin'),
              'name'     => 'Administrator',
          ]);

          // create a role.
          Role::truncate();
          Role::create([
              'name' => 'Administrator',
              'slug' => 'administrator',
          ]);

          // add role to user.
          Administrator::first()->roles()->save(Role::first());

          //create a permission
          Permission::truncate();
          Permission::insert([
              [
                  'name'        => 'All permission',
                  'slug'        => '*',
                  'http_method' => '',
                  'http_path'   => '*',
              ],
              [
                  'name'        => 'Dashboard',
                  'slug'        => 'dashboard',
                  'http_method' => 'GET',
                  'http_path'   => '/',
              ],
              [
                  'name'        => 'Login',
                  'slug'        => 'auth.login',
                  'http_method' => '',
                  'http_path'   => "/auth/login\r\n/auth/logout",
              ],
              [
                  'name'        => 'User setting',
                  'slug'        => 'auth.setting',
                  'http_method' => 'GET,PUT',
                  'http_path'   => '/auth/setting',
              ],
              [
                  'name'        => 'Auth management',
                  'slug'        => 'auth.management',
                  'http_method' => '',
                  'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
              ],
          ]);

          Role::first()->permissions()->save(Permission::first());

          // add default menus.
          Menu::truncate();
          Menu::insert([
              [
                  'parent_id' => 0,
                  'order'     => 1,
                  'title'     => 'Dashboard',
                  'icon'      => 'fa-bar-chart',
                  'uri'       => '/',
              ],
              [
                  'parent_id' => 0,
                  'order'     => 2,
                  'title'     => 'Admin',
                  'icon'      => 'fa-tasks',
                  'uri'       => '',
              ],
              [
                  'parent_id' => 2,
                  'order'     => 3,
                  'title'     => 'Users',
                  'icon'      => 'fa-users',
                  'uri'       => 'auth/users',
              ],
              [
                  'parent_id' => 2,
                  'order'     => 4,
                  'title'     => 'Roles',
                  'icon'      => 'fa-user',
                  'uri'       => 'auth/roles',
              ],
              [
                  'parent_id' => 2,
                  'order'     => 5,
                  'title'     => 'Permission',
                  'icon'      => 'fa-ban',
                  'uri'       => 'auth/permissions',
              ],
              [
                  'parent_id' => 2,
                  'order'     => 6,
                  'title'     => 'Menu',
                  'icon'      => 'fa-bars',
                  'uri'       => 'auth/menu',
              ],
              [
                  'parent_id' => 2,
                  'order'     => 7,
                  'title'     => 'Operation log',
                  'icon'      => 'fa-history',
                  'uri'       => 'auth/logs',
              ],
          ]);

          // add role to menu.
          Menu::find(2)->roles()->save(Role::first());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('admin_menu')->truncate();
        DB::table('admin_permissions')->truncate();
        DB::table('admin_roles')->truncate();
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_users')->truncate();
        DB::table('admin_users')->truncate();
        DB::table('admin_user_permissions')->truncate();

    }
}
