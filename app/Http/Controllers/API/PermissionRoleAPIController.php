<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Role;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use App\Libraries\Repositories\PermissionRoleRepository;
use App\Libraries\Repositories\RoleRepository;
use Response;
use Schema;

class PermissionRoleAPIController extends AppBaseController
{
    
    /** @var  PermissionRoleRepository */
    private $permissionRoleRepository;
    
    function __construct(PermissionRoleRepository $permissionRoleRepo, RoleRepository $roleRepo) {
        $this->permissionRoleRepository = $permissionRoleRepo;
        $this->roleRepository = $roleRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the PermissionRole.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->permissionRoleRepository->search($input);
        
        $permissionRoles = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($permissionRoles), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->permissionRoleRepository->lastUpdated(), 'status' => "PermissionRoles retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissionRoles->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new PermissionRole.
     *
     * @return Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created PermissionRole in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(PermissionRole::$rules) > 0) {
            $this->validateRequest($request, PermissionRole::$rules);
        }
        $input = $request->all();
        
        $permissionRole = $this->permissionRoleRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->permissionRoleRepository->lastUpdated(), 'status' => "PermissionRole saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissionRole->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified PermissionRole.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $permissionRole = $this->permissionRoleRepository->find($id);
        
        if (empty($permissionRole)) {
            $this->throwRecordNotFoundException("PermissionRole not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($permissionRole), 'count' => count($permissionRole), 'offset' => 0, 'last_updated' => $this->permissionRoleRepository->lastUpdated(), 'status' => "PermissionRole retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissionRole->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified PermissionRole.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified PermissionRole in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $permissionRole = $this->permissionRoleRepository->find($id);
        
        if (empty($permissionRole)) {
            $this->throwRecordNotFoundException("PermissionRole not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($permissionRole), 'count' => count($permissionRole), 'offset' => 0, 'last_updated' => $this->permissionRoleRepository->lastUpdated(), 'status' => "PermissionRole updated successfully.", 'error' => 'Success');
        
        $permissionRole = $this->permissionRoleRepository->updateRich($input, $id);
        
        if (!$permissionRole) {
            $this->throwRecordNotFoundException("PermissionRole not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $permissionRole = $this->permissionRoleRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($permissionRole->toArray(), $meta), 201);
    }
    
    /**
     * Update the specified PermissionRole in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function bulkUpdate(Request $request) {
        $input = $request->all();
        
        $role_id = isset($input['role_id']) ? $input['role_id'] : false;
        
        if (isset($input['is_create']) && $input['is_create']) {
            if (sizeof(Role::$rules) > 0) {
                $this->validateRequest($request, Role::$rules);
            }
            
            $input = $request->all();
            
            $role = $this->roleRepository->create($input);
            
            $role_id = $role->id;
        }
        
        if (!isset($input['data']) || !$role_id) {
            $this->throwRecordNotFoundException("PermissionRole not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        foreach ($input['data'] as $key => $data) {
            $permissionRole = PermissionRole::where('role_id', $role_id)->where('permission_id', $data['permission_id'])->first();
            
            /*
             **	If empty and it's true, Insert new permission
            */
            if (!$permissionRole && $data['value'] == true) {
                $permission['role_id'] = $role_id;
                $permission['permission_id'] = $data['permission_id'];
                
                $this->permissionRoleRepository->create($permission);
                
                continue;
            }
            
            /*
             ** If exist and it's false, Delete the permission
            */
            if ($permissionRole && $data['value'] == false) {
                $this->permissionRoleRepository->delete($permissionRole->id);
            }
        }
        
        $current_permission = PermissionRole::where('role_id', $role_id)->get();
        
        $meta = array('total' => count($current_permission), 'count' => count($current_permission), 'offset' => 0, 'last_updated' => $this->permissionRoleRepository->lastUpdated(), 'status' => "PermissionRole updated successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($current_permission->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified PermissionRole from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $permissionRole = $this->permissionRoleRepository->find($id);
        
        if (empty($permissionRole)) {
            $this->throwRecordNotFoundException("PermissionRole not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $permissionRole = $this->permissionRoleRepository->delete($id);
        
        $meta = array('total' => count($permissionRole), 'count' => count($permissionRole), 'offset' => 0, 'last_updated' => $this->permissionRoleRepository->lastUpdated(), 'status' => "PermissionRole deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
