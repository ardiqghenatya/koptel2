<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\User;
use Illuminate\Http\Request;
use App\Libraries\Repositories\UserRepository;
use Response;
use Schema;
use Hash;

class UserAPIController extends AppBaseController
{
    
    /** @var  UserRepository */
    private $userRepository;
    
    function __construct(UserRepository $userRepo) {
        $this->userRepository = $userRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth');
    }
    
    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->userRepository->search($input);
        
        $users = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($users), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->userRepository->lastUpdated(), 'status' => "Users retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($users->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create() {
        
        //
        
    }
    
    /**
     * Store a newly created User in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(User::$rules) > 0) {
            $this->validateRequest($request, User::$rules);
        }
        
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        
        $user = $this->userRepository->create($input);
        
        if (isset($input['role_id'])) {
            $user_id = $user->id;
            
            $role = User::find($user_id);
            $role->attachRole($input['role_id']);
        }
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->userRepository->lastUpdated(), 'status' => "User saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($user->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $user = $this->userRepository->find($id);
        
        if (empty($user)) {
            $this->throwRecordNotFoundException("User not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($user), 'count' => count($user), 'offset' => 0, 'last_updated' => $this->userRepository->lastUpdated(), 'status' => "User retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($user->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified User.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
    }
    
    /**
     * Update the specified User in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $user = $this->userRepository->find($id);
        
        if (empty($user)) {
            $this->throwRecordNotFoundException("User not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        
        $meta = array('total' => count($user), 'count' => count($user), 'offset' => 0, 'last_updated' => $this->userRepository->lastUpdated(), 'status' => "User updated successfully.", 'error' => 'Success');
        
        $user = $this->userRepository->updateRich($input, $id);
        
        if (!$user) {
            $this->throwRecordNotFoundException("User not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $user = $this->userRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($user->toArray(), $meta), 201);
    }
    
    /**
     * Display a listing of the Menu.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function me(Request $request) {
        $user_id = \Authorizer::getResourceOwnerId();
        $me = $this->userRepository->getMe($user_id);
        //cannot use this because conflict in model
        // $me = $this->userRepository->find($user_id);
        
        return Response::json($me);
    }
    
    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $user = $this->userRepository->find($id);
        
        if (empty($user)) {
            $this->throwRecordNotFoundException("User not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $user = $this->userRepository->delete($id);
        
        $meta = array('total' => count($user), 'count' => count($user), 'offset' => 0, 'last_updated' => $this->userRepository->lastUpdated(), 'status' => "User deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
