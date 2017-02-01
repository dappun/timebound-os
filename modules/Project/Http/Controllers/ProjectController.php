<?php namespace TB\Project\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 
use TB\Project\Http\Requests\UpdateProjectRequest;
use TB\Project\Http\Requests\CreateProjectRequest;
use TB\Project\Repositories\ProjectRepository;
use TB\Client\Repositories\ClientRepository;
use TB\Timesheet\Repositories\TimesheetRepository;
use Flash;

class ProjectController extends BaseController 
{
    /** @var  ProjectRepository */
    private $projectRepository;

    public function __construct(
        ProjectRepository $projectRepo, 
        ClientRepository $clientRepo, 
        TimesheetRepository $timesheetRepo
        )
    {
        parent::__construct();
        $this->projectRepository = $projectRepo;
        $this->clientRepository = $clientRepo;
        $this->timesheetRepository = $timesheetRepo;

        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the Project.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $projects = $this->projectRepository->all();
        $clients = $this->clientRepository->listAll()->all();

        return view('project::index')
            ->with('projects', $projects)
            ->with('clients', $clients);
    }

    /**
     * Show the form for creating a new Project.
     *
     * @return Response
     */
    public function create()
    {
        $clients = $this->clientRepository->listAll()->all();
        return view('project::create')
            ->with('clients', $clients);
    }

    /**
     * Store a newly created Project in storage.
     *
     * @param CreateProjectRequest $request
     *
     * @return Response
     */
    public function store(CreateProjectRequest $request)
    {
        $values = $request->all();
        unset($values['_method']);
        unset($values['_token']);

        $project = $this->projectRepository->create($values);

        Flash::success('Project saved successfully.');

        return redirect(route('projects.index'));
    }

    /**
     * Display the specified Project.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $project = $this->projectRepository->find($id);

        if (empty($project)) {
            Flash::error('Project not found');
            return redirect(route('projects.index'));
        }

        $cond = [
            'project_id' => $id,
            'start' => date('Y-m-d', strtotime('-1 year')),
            'end' => date('Y-m-d'),
        ];

        $timesheets = $this->timesheetRepository->history($cond, 0, 50);
        
        // Statistics
        $contributors = $this->projectRepository->contributors($id, $cond);
        $chartData = [
            'label' => [],
            'data' => [],
            'bgcolor' => [],
        ];

        $colors = [
            "#FF6384",
            "#4BC0C0",
            "#FFCE56",
            "#E7E9ED",
            "#36A2EB"
        ];

        $ctr = 0;
        foreach ($contributors as $key => $value) {
            $hours = (float)$value->total / 3600;

            $chartData['label'][] = $value->name;
            $chartData['data'][] = number_format($hours, 2);

            if (!isset($colors[$ctr])) {
                $ctr = 0;
            }

            $chartData['bgcolor'][] = $colors[$ctr];
            $ctr++;
        }

        $total_hours = $this->projectRepository->totalHours($id, $cond);

        \View::share('timesheets', $timesheets);
        \View::share('contributors', $chartData);
        \View::share('hours', $total_hours);

        return view('project::show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified Project.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $project = $this->projectRepository->find($id);
        $clients = $this->clientRepository->listAll()->all();

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        return view('project::edit')
            ->with('project', $project)
            ->with('clients', $clients);
    }

    /**
     * Update the specified Project in storage.
     *
     * @param  int              $id
     * @param UpdateProjectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProjectRequest $request)
    {
        $project = $this->projectRepository->find($id);

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        $values = $request->all();
        $values['id'] = $id;
        unset($values['_method']);
        unset($values['_token']);
        $project = $this->projectRepository->update($values);

        Flash::success('Project updated successfully.');

        return redirect(route('projects.index'));
    }

    /**
     * Remove the specified Project from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $project = $this->projectRepository->find($id);

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        $this->projectRepository->delete($id);

        Flash::success('Project deleted successfully.');

        return redirect(route('projects.index'));
    }
}