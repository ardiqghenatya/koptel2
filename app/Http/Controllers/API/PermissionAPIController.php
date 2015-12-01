<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Libraries\Repositories\PermissionRepository;
use Response;
use Schema;

class PermissionAPIController extends AppBaseController
{
    
    /** @var  PermissionRepository */
    private $permissionRepository;
    
    function __construct(PermissionRepository $permissionRepo) {
        $this->permissionRepository = $permissionRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the Permission.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->permissionRepository->search($input);
        
        $permissions = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($permissions), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->permissionRepository->lastUpdated(), 'status' => "Permissions retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissions->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new Permission.
     *
     * @return Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created Permission in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(Permission::$rules) > 0) {
            $this->validateRequest($request, Permission::$rules);
        }
        $input = $request->all();
        
        $permission = $this->permissionRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->permissionRepository->lastUpdated(), 'status' => "Permission saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permission->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified Permission.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $permission = $this->permissionRepository->find($id);
        
        if (empty($permission)) {
            $this->throwRecordNotFoundException("Permission not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($permission), 'count' => count($permission), 'offset' => 0, 'last_updated' => $this->permissionRepository->lastUpdated(), 'status' => "Permission retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permission->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified Permission.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified Permission in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $permission = $this->permissionRepository->find($id);
        
        if (empty($permission)) {
            $this->throwRecordNotFoundException("Permission not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($permission), 'count' => count($permission), 'offset' => 0, 'last_updated' => $this->permissionRepository->lastUpdated(), 'status' => "Permission updated successfully.", 'error' => 'Success');
        
        $permission = $this->permissionRepository->updateRich($input, $id);
        
        if (!$permission) {
            $this->throwRecordNotFoundException("Permission not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $permission = $this->permissionRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($permission->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified Permission from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $permission = $this->permissionRepository->find($id);
        
        if (empty($permission)) {
            $this->throwRecordNotFoundException("Permission not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $permission = $this->permissionRepository->delete($id);
        
        $meta = array('total' => count($permission), 'count' => count($permission), 'offset' => 0, 'last_updated' => $this->permissionRepository->lastUpdated(), 'status' => "Permission deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
