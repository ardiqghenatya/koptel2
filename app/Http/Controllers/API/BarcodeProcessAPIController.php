<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\BarcodeProcess;
use Illuminate\Http\Request;
use App\Libraries\Repositories\BarcodeProcessRepository;
use Response;
use Schema;

class BarcodeProcessAPIController extends AppBaseController
{
    
    /** @var  BarcodeProcessRepository */
    private $barcodeProcessRepository;
    
    function __construct(BarcodeProcessRepository $barcodeProcessRepo) {
        $this->barcodeProcessRepository = $barcodeProcessRepo;
        
        $this->middleware('oauth_permission');
        $this->beforeFilter('oauth', ['except' => ['index', 'show', 'store', 'update', 'destroy', 'take', 'statistic']]);
    }
    
    /**
     * Display a listing of the BarcodeProcess.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $input = $request->all();
        
        $result = $this->barcodeProcessRepository->search($input);
        
        $barcodeProcesses = $result[0];
        
        $meta = array('total' => $result['total'], 'count' => count($barcodeProcesses), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcesses retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($barcodeProcesses->toArray(), $meta));
    }
    
    /**
     * Store a newly created BarcodeProcess in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if (sizeof(BarcodeProcess::$rules) > 0) {
            $this->validateRequest($request, BarcodeProcess::$rules);
        }
        $input = $request->all();
        
        /**
         * Validasi supaya barcode yang belum di ambil tidak bisa disimpan
         */
        
        // $hasPendingProcess = $this->barcodeProcessRepository->find(['barcode' => $input['barcode'], 'status' => 0, 'taken_id' => 0]);
        $hasPendingProcess = \DB::table('barcode_processes')->where('barcode', '=', $input['barcode'])->where('status', '=', 0)->where('taken_id', '=', 0)->get();
        if (!empty($hasPendingProcess)) {
            $this->throwRecordNotFoundException("BarcodeProcess was saved before.", 400);
        }
        
        $barcodeProcess = $this->barcodeProcessRepository->create($input);
        
        /**
         * Update status Lemari menjadi Digunakan
         */
        if (!empty($barcodeProcess)) {
            $barcodeProcess->shelf->update(['status' => 1]);
        }
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcess saved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($barcodeProcess->toArray(), $meta), 201);
    }
    
    /**
     * Display the specified BarcodeProcess.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        $barcodeProcess = $this->barcodeProcessRepository->find($id);
        
        if (empty($barcodeProcess)) {
            $this->throwRecordNotFoundException("BarcodeProcess not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $meta = array('total' => count($barcodeProcess), 'count' => count($barcodeProcess), 'offset' => 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcess retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($barcodeProcess->toArray(), $meta));
    }
    
    /**
     * Update the specified BarcodeProcess in storage.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $barcodeProcess = $this->barcodeProcessRepository->find($id);
        
        if (empty($barcodeProcess)) {
            $this->throwRecordNotFoundException("BarcodeProcess not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $input = $request->all();
        
        $meta = array('total' => count($barcodeProcess), 'count' => count($barcodeProcess), 'offset' => 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcess updated successfully.", 'error' => 'Success');
        
        $barcodeProcess = $this->barcodeProcessRepository->updateRich($input, $id);
        
        if (!$barcodeProcess) {
            $this->throwRecordNotFoundException("BarcodeProcess not saved", ERROR_CODE_VALIDATION_FAILED);
        }
        
        $barcodeProcess = $this->barcodeProcessRepository->find($id);
        
        return Response::json(ResponseManager::makeResult($barcodeProcess->toArray(), $meta), 201);
    }
    
    /**
     * Remove the specified BarcodeProcess from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $barcodeProcess = $this->barcodeProcessRepository->find($id);
        
        if (empty($barcodeProcess)) {
            $this->throwRecordNotFoundException("BarcodeProcess not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        $barcodeProcess = $this->barcodeProcessRepository->delete($id);
        
        $meta = array('total' => count($barcodeProcess), 'count' => count($barcodeProcess), 'offset' => 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcess deleted successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($id, $meta));
    }
    
    public function take($id) {
        $barcodeProcess = $this->barcodeProcessRepository->find($id);
        
        if (empty($barcodeProcess)) {
            $this->throwRecordNotFoundException("BarcodeProcess not found", ERROR_CODE_RECORD_NOT_FOUND);
        }
        
        // Duplicate data sebelumnya
        $prev = $barcodeProcess->toArray();
        $prev['status'] = 1;
        
        // Duplicate record
        $newBarcodeProcess = $this->barcodeProcessRepository->create($prev);
        
        // Update prev taken_id, dan kembalikan status lemari agar bisa digunakan oleh yang lain
        $barcodeProcess->taken_id = $newBarcodeProcess->id;
        $barcodeProcess->shelf->status = 0;
        $barcodeProcess->shelf->save();
        $barcodeProcess->save();
        
        $meta = array('total' => 1, 'count' => 1, 'offset' => 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcess take successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($newBarcodeProcess->toArray(), $meta), 201);
    }
    
    /**
     * Display a listing of the BarcodeProcess.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function statistic(Request $request) {
        $input = $request->all();
        
        $query = BarcodeProcess::select(\DB::raw('count(*) as total_transactions, shelf_id, created_at'))->groupBy('shelf_id');
        
        /**
         * Filter
         */
        $this->barcodeProcessRepository->filter($input, $query);
        
        /**
         * Get count
         */
        $total = $query->count();
        
        /**
         * Pagination
         */
        $this->barcodeProcessRepository->pagination($input, $query);
        
        $barcodeProcesses = $query->get();
        
        $meta = array('total' => $total, 'count' => count($barcodeProcesses), 'offset' => isset($input['offset']) ? (int)$input['offset'] : 0, 'last_updated' => $this->barcodeProcessRepository->lastUpdated(), 'status' => "BarcodeProcesses retrieved successfully.", 'error' => 'Success');
        
        return Response::json(ResponseManager::makeResult($barcodeProcesses->toArray(), $meta));
    }
}
