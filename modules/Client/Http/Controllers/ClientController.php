<?php namespace TB\Client\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 
use TB\Client\Http\Requests\UpdateClientRequest;
use TB\Client\Http\Requests\CreateClientRequest;
use TB\Client\Repositories\ClientRepository;
use Flash;

class ClientController extends BaseController 
{
	private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        parent::__construct();
        $this->clientRepository = $clientRepo;
        $this->middleware('auth');
        $this->middleware('role:admin');

        // if (!\Entrust::can('manage-users')) {
        //     \Redirect::to('dashboard')->send();
        // }
    }

    /**
     * Display a listing of the Client.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $clients = $this->clientRepository->all();
        
        return view('client::index')
            ->with('clients', $clients);
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        return view('client::create');
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param CreateClientRequest $request
     *
     * @return Response
     */
    public function store(CreateClientRequest $request)
    {
        $values = $request->all();
        unset($values['_method']);
        unset($values['_token']);

        $client = $this->clientRepository->create($values);

        Flash::success('Client saved successfully.');
        return redirect(route('clients.index'));
    }

    /**
     * Display the specified Client.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');
            return redirect(route('clients.index'));
        }

        $cond = [
            'client_id' => $id,
            'start' => date('Y-m-d', strtotime('-1 year')),
            'end' => date('Y-m-d'),
        ];
        
        // Statistics
        $contributors = $this->clientRepository->contributors($id, $cond);
        $jsonContributors = [['Name', 'Worked hours']];
        foreach ($contributors as $key => $value) {
            $hours = (float)$value->total / 3600;
            $jsonContributors[] = [$value->name, $hours];
        }
        
        $total_hours = $this->clientRepository->totalHours($id, $cond);

        // \View::share('timesheets', $timesheets);
        \View::share('contributors', $jsonContributors);
        \View::share('hours', $total_hours);

        return view('client::show')->with('client', $client);
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');
            return redirect(route('clients.index'));
        }

        return view('client::edit')->with('client', $client);
    }

    /**
     * Update the specified Client in storage.
     *
     * @param  int              $id
     * @param UpdateClientRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClientRequest $request)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');
            return redirect(route('clients.index'));
        }

        $values = $request->all();
        $values['id'] = $id;
        unset($values['_method']);
        unset($values['_token']);

        $client = $this->clientRepository->update($values);

        Flash::success('Client updated successfully.');
        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified Client from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');
            return redirect(route('clients.index'));
        }

        $client->delete();
        Flash::success('Client deleted successfully.');

        return redirect(route('clients.index'));
    }	
}