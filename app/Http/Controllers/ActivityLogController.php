<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateActivityLogRequest;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ActivityLogRepository;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class ActivityLogController extends AppBaseController
{

	/** @var  ActivityLogRepository */
	private $activityLogRepository;

	function __construct(ActivityLogRepository $activityLogRepo)
	{
		$this->activityLogRepository = $activityLogRepo;
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the ActivityLog.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->activityLogRepository->search($input);

		$activityLogs = $result[0];

		$attributes = $result[1];

		return view('activityLogs.index')
		    ->with('activityLogs', $activityLogs)
		    ->with('attributes', $attributes);;
	}

	/**
	 * Show the form for creating a new ActivityLog.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('activityLogs.create');
	}

	/**
	 * Store a newly created ActivityLog in storage.
	 *
	 * @param CreateActivityLogRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateActivityLogRequest $request)
	{
    $input = $request->all();

		$activityLog = $this->activityLogRepository->create($input);

		Flash::message('ActivityLog saved successfully.');

		return redirect(route('activityLogs.index'));
	}

	/**
	 * Display the specified ActivityLog.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$activityLog = $this->activityLogRepository->find($id);

		if(empty($activityLog))
		{
			Flash::error('ActivityLog not found');
			return redirect(route('activityLogs.index'));
		}

		return view('activityLogs.show')->with('activityLog', $activityLog);
	}

	/**
	 * Show the form for editing the specified ActivityLog.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$activityLog = $this->activityLogRepository->find($id);

		if(empty($activityLog))
		{
			Flash::error('ActivityLog not found');
			return redirect(route('activityLogs.index'));
		}

		return view('activityLogs.edit')->with('activityLog', $activityLog);
	}

	/**
	 * Update the specified ActivityLog in storage.
	 *
	 * @param  int    $id
	 * @param CreateActivityLogRequest $request
	 *
	 * @return Response
	 */
	public function update($id, CreateActivityLogRequest $request)
	{
		$activityLog = $this->activityLogRepository->find($id);

		if(empty($activityLog))
		{
			Flash::error('ActivityLog not found');
			return redirect(route('activityLogs.index'));
		}

		$activityLog = $this->activityLogRepository->updateRich($request->all(), $id);

		Flash::message('ActivityLog updated successfully.');

		return redirect(route('activityLogs.index'));
	}

	/**
	 * Remove the specified ActivityLog from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$activityLog = $this->activityLogRepository->find($id);

		if(empty($activityLog))
		{
			Flash::error('ActivityLog not found');
			return redirect(route('activityLogs.index'));
		}

		$activityLog = $this->activityLogRepository->delete($id);
		
    Flash::message('ActivityLog deleted successfully.');

		return redirect(route('activityLogs.index'));
	}

}
