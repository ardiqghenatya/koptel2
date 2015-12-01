<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\PermissionUser;
use Illuminate\Http\Request;
use App\Libraries\Repositories\PermissionUserRepository;
use Response;
use Schema;

class PermissionUserAPIController extends AppBaseController
{
    
    /** @var  PermissionUserRepository */
    private $permissionUserRepository;
    
    function __construct(PermissionUserRepository $permissionUserRepo) {
        $this->permissionUserRepository = $permissionUserRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the PermissionUser.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->permissionUserRepository->search($input);
        
        $permissionUsers = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($permissionUsers), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->permissionUserRepository->lastUpdated(), 'status' => "PermissionUsers retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissionUsers->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new PermissionUser.
     *
     * @return Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created PermissionUser in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(PermissionUser::$rules) > 0) {
            $this->validateRequest($request, PermissionUser::$rules);
        }
        $input = $request->all();
        
        $permissionUser = $this->permissionUserRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->permissionUserRepository->lastUpdated(), 'status' => "PermissionUser saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissionUser->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified PermissionUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $permissionUser = $this->permissionUserRepository->find($id);
        
        if (empty($permissionUser)) {
            $this->throwRecordNotFoundException("PermissionUser not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($permissionUser), 'count' => count($permissionUser), 'offset' => 0, 'last_updated' => $this->permissionUserRepository->lastUpdated(), 'status' => "PermissionUser retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($permissionUser->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified PermissionUser.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified PermissionUser in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $permissionUser = $this->permissionUserRepository->find($id);
        
        if (empty($permissionUser)) {
            $this->throwRecordNotFoundException("PermissionUser not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($permissionUser), 'count' => count($permissionUser), 'offset' => 0, 'last_updated' => $this->permissionUserRepository->lastUpdated(), 'status' => "PermissionUser updated successfully.", 'error' => 'Success');
        
        $permissionUser = $this->permissionUserRepository->updateRich($input, $id);
        
        if (!$permissionUser) {
            $this->throwRecordNotFoundException("PermissionUser not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $permissionUser = $this->permissionUserRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($permissionUser->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified PermissionUser from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $permissionUser = $this->permissionUserRepository->find($id);
        
        if (empty($permissionUser)) {
            $this->throwRecordNotFoundException("PermissionUser not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $permissionUser = $this->permissionUserRepository->delete($id);
        
        $meta = array('total' => count($permissionUser), 'count' => count($permissionUser), 'offset' => 0, 'last_updated' => $this->permissionUserRepository->lastUpdated(), 'status' => "PermissionUser deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
