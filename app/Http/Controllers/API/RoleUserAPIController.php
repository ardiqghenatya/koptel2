<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Libraries\Repositories\RoleUserRepository;
use Response;
use Schema;

class RoleUserAPIController extends AppBaseController
{
    
    /** @var  RoleUserRepository */
    private $roleUserRepository;
    
    function __construct(RoleUserRepository $roleUserRepo) {
        $this->roleUserRepository = $roleUserRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the RoleUser.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->roleUserRepository->search($input);
        
        $roleUsers = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($roleUsers), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->roleUserRepository->lastUpdated(), 'status' => "RoleUsers retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($roleUsers->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new RoleUser.
     *
     * @return Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created RoleUser in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(RoleUser::$rules) > 0) {
            $this->validateRequest($request, RoleUser::$rules);
        }
        $input = $request->all();
        
        $roleUser = $this->roleUserRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->roleUserRepository->lastUpdated(), 'status' => "RoleUser saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($roleUser->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified RoleUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $roleUser = $this->roleUserRepository->find($id);
        
        if (empty($roleUser)) {
            $this->throwRecordNotFoundException("RoleUser not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($roleUser), 'count' => count($roleUser), 'offset' => 0, 'last_updated' => $this->roleUserRepository->lastUpdated(), 'status' => "RoleUser retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($roleUser->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified RoleUser.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified RoleUser in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $roleUser = $this->roleUserRepository->find($id);
        
        if (empty($roleUser)) {
            $this->throwRecordNotFoundException("RoleUser not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($roleUser), 'count' => count($roleUser), 'offset' => 0, 'last_updated' => $this->roleUserRepository->lastUpdated(), 'status' => "RoleUser updated successfully.", 'error' => 'Success');
        
        $roleUser = $this->roleUserRepository->updateRich($input, $id);
        
        if (!$roleUser) {
            $this->throwRecordNotFoundException("RoleUser not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $roleUser = $this->roleUserRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($roleUser->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified RoleUser from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $roleUser = $this->roleUserRepository->find($id);
        
        if (empty($roleUser)) {
            $this->throwRecordNotFoundException("RoleUser not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $roleUser = $this->roleUserRepository->delete($id);
        
        $meta = array('total' => count($roleUser), 'count' => count($roleUser), 'offset' => 0, 'last_updated' => $this->roleUserRepository->lastUpdated(), 'status' => "RoleUser deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
