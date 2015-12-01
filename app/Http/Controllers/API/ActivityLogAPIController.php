<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ActivityLogRepository;
use Response;
use Schema;

class ActivityLogAPIController extends AppBaseController
{
    
    /** @var  ActivityLogRepository */
    private $activityLogRepository;
    
    function __construct(ActivityLogRepository $activityLogRepo) {
        $this->activityLogRepository = $activityLogRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth');
    }
    
    /**
     * Display a listing of the ActivityLog.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->activityLogRepository->search($input);
        
        $activityLogs = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($activityLogs), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->activityLogRepository->lastUpdated(), 'status' => "ActivityLogs retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($activityLogs->toArray(), $meta));
    }
    
    /**
     * Show the form for creating a new ActivityLog.
     *
     * @return Response
     */
    public function create() {
        
        //
        
        
    }
    
    /**
     * Store a newly created ActivityLog in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(ActivityLog::$rules) > 0) {
            $this->validateRequest($request, ActivityLog::$rules);
        }
        $input = $request->all();
        
        $activityLog = $this->activityLogRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->activityLogRepository->lastUpdated(), 'status' => "ActivityLog saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($activityLog->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified ActivityLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $activityLog = $this->activityLogRepository->find($id);
        
        if (empty($activityLog)) {
            $this->throwRecordNotFoundException("ActivityLog not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($activityLog), 'count' => count($activityLog), 'offset' => 0, 'last_updated' => $this->activityLogRepository->lastUpdated(), 'status' => "ActivityLog retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($activityLog->toArray(), $meta));
    }
    
    /**
     * Show the form for editing the specified ActivityLog.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        //
        
        
    }
    
    /**
     * Update the specified ActivityLog in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $activityLog = $this->activityLogRepository->find($id);
        
        if (empty($activityLog)) {
            $this->throwRecordNotFoundException("ActivityLog not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($activityLog), 'count' => count($activityLog), 'offset' => 0, 'last_updated' => $this->activityLogRepository->lastUpdated(), 'status' => "ActivityLog updated successfully.", 'error' => 'Success');
        
        $activityLog = $this->activityLogRepository->updateRich($input, $id);
        
        if (!$activityLog) {
            $this->throwRecordNotFoundException("ActivityLog not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $activityLog = $this->activityLogRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($activityLog->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified ActivityLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $activityLog = $this->activityLogRepository->find($id);
        
        if (empty($activityLog)) {
            $this->throwRecordNotFoundException("ActivityLog not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $activityLog = $this->activityLogRepository->delete($id);
        
        $meta = array('total' => count($activityLog), 'count' => count($activityLog), 'offset' => 0, 'last_updated' => $this->activityLogRepository->lastUpdated(), 'status' => "ActivityLog deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
