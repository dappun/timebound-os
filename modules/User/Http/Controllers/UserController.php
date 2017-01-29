<?php namespace TB\User\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 
use TB\User\Http\Requests\UpdateUserRequest;
use TB\User\Http\Requests\CreateUserRequest;
use TB\User\Repositories\UserRepository;
use TB\User\Repositories\RoleRepository;

class UserController extends BaseController 
{
	private $userRepository;
	private $roleRepository;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepository;
        
        $this->middleware('role:project-manager|admin');
    }

	/**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $users = $this->userRepository->all();

        return view('user::index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
    	$roles = $this->roleRepository->listBy();
        return view('user::create')->with('roles', $roles);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $values = [
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'status_id' => $request->get('status_id')
        ];

        $user = $this->userRepository->create($values);

        // Update role
        $role = $this->roleRepository->findByAttributes(['name' => $request->get('role')]);
        $user->attachRole($role);

        // Email user
        $emailContent = [
            'title' => 'New account',
            'name' => $user->name,
            'paragraphs' => [
                'A new account was created for you. Login using the link below:',
                'URL: ' . url('/login') . '<br/>' . 
                'Your password: ' . $request->get('password'),
                'We encourage you to change your password as soon as you login.'
            ]
        ];

        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('user::emails.account_changed', $emailContent, function($message) use($user)
        {
            $message
                ->from(env('SITE_EMAIL'))
                ->to($user->email, $user->name)
                ->subject(env('SITE_NAME') . ' - New account');
        });

        \Flash::success('User saved successfully.');
        return redirect(route('admin.users.index'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if ($user->roles()->count()) {
            $user->role = $user->roles()->first()->name;    
        }

        $roles = $this->roleRepository->listBy();

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('admin.users.index'));
        }

        return view('user::edit')
        	->with('user', $user)
        	->with('roles', $roles);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            \Flash::error('User not found');
            return redirect(route('admin.users.index'));
        }

        $values = [
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'status_id' => $request->get('status_id'),
            'id'        => $id
        ];

        if ($request->input('password')) {
            $values['password'] = bcrypt($request->get('password'));
        }

        $user = $this->userRepository->update($values);
        
        $role = $this->roleRepository->findByAttributes(['name' => $request->get('role')]);
        if (!$user->hasRole($role->name)) {
            $user->roles()->sync([]); 
            $user->attachRole($role);
        }

        \Flash::success('User updated successfully.');

        return redirect(route('admin.users.index'));
    }
	
    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('admin.users.index'));
        }

        $user->delete();
        \Flash::success('User deleted successfully.');

        return redirect(route('admin.users.index'));
    }
}