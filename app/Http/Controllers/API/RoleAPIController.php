<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Libraries\Repositories\RoleRepository;
use Response;
use Schema;

class RoleAPIController extends AppBaseController
{
    
    /** @var  RoleRepository */
    private $roleRepository;
    
    function __construct(RoleRepository $roleRepo) {
        $this->roleRepository = $roleRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the Role.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->roleRepository->search($input);
        
        $roles = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($roles), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->roleRepository->lastUpdated(), 'status' => "Roles retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($roles->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new Role.
     *
     * @return Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created Role in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(Role::$rules) > 0) {
            $this->validateRequest($request, Role::$rules);
        }
        $input = $request->all();
        
        $role = $this->roleRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->roleRepository->lastUpdated(), 'status' => "Role saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($role->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified Role.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $role = $this->roleRepository->find($id);
        
        if (empty($role)) {
            $this->throwRecordNotFoundException("Role not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($role), 'count' => count($role), 'offset' => 0, 'last_updated' => $this->roleRepository->lastUpdated(), 'status' => "Role retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($role->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified Role.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified Role in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $role = $this->roleRepository->find($id);
        
        if (empty($role)) {
            $this->throwRecordNotFoundException("Role not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($role), 'count' => count($role), 'offset' => 0, 'last_updated' => $this->roleRepository->lastUpdated(), 'status' => "Role updated successfully.", 'error' => 'Success');
        
        $role = $this->roleRepository->updateRich($input, $id);
        
        if (!$role) {
            $this->throwRecordNotFoundException("Role not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $role = $this->roleRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($role->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified Role from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $role = $this->roleRepository->find($id);
        
        if (empty($role)) {
            $this->throwRecordNotFoundException("Role not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $role = $this->roleRepository->delete($id);
        
        $meta = array('total' => count($role), 'count' => count($role), 'offset' => 0, 'last_updated' => $this->roleRepository->lastUpdated(), 'status' => "Role deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
