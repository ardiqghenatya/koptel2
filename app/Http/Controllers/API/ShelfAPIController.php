<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Shelf;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ShelfRepository;
use Response;
use Schema;

class ShelfAPIController extends AppBaseController
{
    
    /** @var  ShelfRepository */
    private $shelfRepository;
    
    function __construct(ShelfRepository $shelfRepo) {
        $this->shelfRepository = $shelfRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show', 'store', 'update', 'destroy']]);
    }
    
    /**
     * Display a listing of the Shelf.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->shelfRepository->search($input);
        
        $shelves = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($shelves), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->shelfRepository->lastUpdated(), 'status' => "Shelves retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($shelves->toArray(), $meta));
    }
    
    /**
     * Store a newly created Shelf in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(Shelf::$rules) > 0) {
            $this->validateRequest($request, Shelf::$rules);
        }
        $input = $request->all();
        
        $shelf = $this->shelfRepository->create($input);
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->shelfRepository->lastUpdated(), 'status' => "Shelf saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($shelf->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified Shelf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $shelf = $this->shelfRepository->find($id);
        
        if (empty($shelf)) {
            $this->throwRecordNotFoundException("Shelf not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($shelf), 'count' => count($shelf), 'offset' => 0, 'last_updated' => $this->shelfRepository->lastUpdated(), 'status' => "Shelf retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($shelf->toArray(), $meta));
    }
    
    /**
     * Update the specified Shelf in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $shelf = $this->shelfRepository->find($id);
        
        if (empty($shelf)) {
            $this->throwRecordNotFoundException("Shelf not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($shelf), 'count' => count($shelf), 'offset' => 0, 'last_updated' => $this->shelfRepository->lastUpdated(), 'status' => "Shelf updated successfully.", 'error' => 'Success');
        
        $shelf = $this->shelfRepository->updateRich($input, $id);
        
        if (!$shelf) {
            $this->throwRecordNotFoundException("Shelf not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $shelf = $this->shelfRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($shelf->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified Shelf from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $shelf = $this->shelfRepository->find($id);
        
        if (empty($shelf)) {
            $this->throwRecordNotFoundException("Shelf not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $shelf = $this->shelfRepository->delete($id);
        
        $meta = array('total' => count($shelf), 'count' => count($shelf), 'offset' => 0, 'last_updated' => $this->shelfRepository->lastUpdated(), 'status' => "Shelf deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
}
