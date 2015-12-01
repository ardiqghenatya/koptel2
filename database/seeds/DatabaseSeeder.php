<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;

use App\User;
use App\Models\PermissionRole;

use Illuminate\Support\Facades\Route;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();
        
        // Auth Seeder
        $this->call('UserTableSeeder');
        $this->call('OauthClientSeeder');
        $this->call('RoleSeeder');
        $this->call('PermissionSeeder');
        $this->call('PermissionRoleSeeder');
    }
}

class UserTableSeeder extends Seeder
{
    
    public function run() {
        DB::table('users')->where('id', '=', 1)->delete();
        DB::table('users')->insert(['id' => 1, 'name' => 'admin', 'email' => 'admin@web.co.id', 'password' => Hash::make('admin'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ]);
    }
}

class OauthClientSeeder extends Seeder
{
    
    public function run() {
        $data = array(['id' => '1', 'secret' => 'mIeCPklWs4MbbzmA2Mel', 'name' => 'Administrator', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ]);
        
        $exist = DB::table('oauth_clients')->where('id', '1')->count();
        
        if (!$exist) {
            DB::table('oauth_clients')->insert($data);
        }
    }
}

class RoleSeeder extends Seeder
{
    
    public function run() {
        DB::statement("SET foreign_key_checks = 0");
        DB::table('roles')->truncate();
        
        /*
         ** Define role
        */
        $superAdminRole = Role::create(['name' => 'Superadmin', 'slug' => 'superadmin']);
        
        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        
        $districtRole = Role::create(['name' => 'Admin Kecamatan', 'slug' => 'admin_district']);
        
        /*
         ** Attach role
        */
        $admin_id = 1;
        $user = User::find($admin_id);
        $user->attachRole($superAdminRole);
    }
}

class PermissionSeeder extends Seeder
{
    
    public function run() {
        DB::table('permissions')->truncate();
        
        $permissions = [];
        
        $routeCollection = Route::getRoutes();
        
        /*
         ** All route permission
        */
        foreach ($routeCollection as $value) {
            $action = $value->getAction();
            
            if (isset($action['prefix']) && $action['prefix'] = '/api/v1/' && isset($action['as'])) {
                $route = explode(".", $action['as'], 3);
                $route = $route[2];
                
                $route_name = ucfirst(preg_replace('/\./', ' ', $route));
                
                $route_group = explode(" ", $route_name);
                $route_group = $route_group[0];
                
                $permissions[] = ['name' => $route_name, 'name_group' => $route_group, 'slug' => $route, 'slug_view' => 'app.' . $route, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ];
            }
        }
        
        /*
         ** Custom route permission
        */
        $custom_permission = [['slug_view' => 'app.multimedia.upload'], ['slug_view' => 'app.activityLogs.index']];
        
        /*
         ** Re-define custom permission
        */
        foreach ($custom_permission as $value) {
            $slug_view = explode(".", $value['slug_view'], 2);
            
            $name_group = explode(".", $slug_view[1]);
            $route_name = ucfirst($name_group[0]) . ' ' . ucfirst($name_group[1]);
            $name_group = ucfirst($name_group[0]);
            $slug = $slug_view[1];
            
            $permissions[] = ['name' => $route_name, 'name_group' => $name_group, 'slug' => $slug, 'slug_view' => $value['slug_view'], 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ];
        }
        
        Permission::insert($permissions);
    }
}

class PermissionRoleSeeder extends Seeder
{
    public function run() {
        DB::table('permission_role')->truncate();
        
        $permission_role = [];
        
        $permissions = Permission::get();
        $role = Role::find(1);
        
        foreach ($permissions as $key => $value) {
            $permission_role[] = ['permission_id' => $value->id, 'role_id' => $role->id, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ];
        }
        
        PermissionRole::insert($permission_role);
    }
}
