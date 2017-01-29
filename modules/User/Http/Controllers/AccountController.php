<?php namespace TB\User\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 
use TB\User\Http\Requests\UpdateUserRequest;
use TB\User\Http\Requests\UpdatePasswordRequest;
use TB\User\Repositories\UserRepository;
use Illuminate\Http\Request;

class AccountController extends BaseController 
{
	
	private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->middleware('auth');
    }

	public function index()
	{
		$user = $this->userRepository->find(\Auth::id());

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('account.view'));
        }

        return view('user::account/index')->with('user', $user);
	}
	
	public function update(UpdateUserRequest $request)
    {
        $id = \Auth::id();
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            \Flash::error('User not found');
            return redirect(route('account.view'));
        }

        $values = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'id' => $id
        ];

        if ($user->email != $request->get('email')) {
            // Email to old email
            $emailContent = [
                'title' => 'Email Changed',
                'name' => $user->name,
                'paragraphs' => [
                    'Your Account email was just changed to ' . $request->get('email') . ' on ' . date('Y-m-d H:i:s'),
                    'Don\'t recognize this activity? Please contact support for immediate action.'
                ]
            ];

            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('user::emails.account_changed', $emailContent, function($message) use($user)
            {
                $message
                    ->from(env('SITE_EMAIL'))
                    ->to($user->email, $user->name)
                    ->subject(env('SITE_NAME') . ' - Email changed');
            });
        }

        $user = $this->userRepository->update($values, $id);

        \Flash::success('Account updated successfully.');

        return redirect(route('account.view'));
    }

    public function password(UpdatePasswordRequest $request)
    {
        $id = \Auth::id();
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            \Flash::error('User not found');
            return redirect(route('account.view'));
        }

        $values['password'] = bcrypt($request->get('password'));
        $user = $this->userRepository->updatePassword($values['password'], $id);

        \Flash::success('Password updated successfully.');

        // Email this activity
        $emailContent = [
            'title' => 'Password Changed',
            'name' => $user->name,
            'paragraphs' => [
                'Your Account password was just updated on ' . date('Y-m-d H:i:s'),
                'Don\'t recognize this activity? Please contact support for immediate action.'
            ]
        ];

        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('user::emails.account_changed', $emailContent, function($message) use($user)
        {
            $message
                ->from(env('SITE_EMAIL'))
                ->to($user->email, $user->name)
                ->subject(env('SITE_NAME') . ' - Password changed');
        });

        return redirect(route('account.view'));
    }	

    /**
     * Profile image upload
     */
    public function imageUpload(Request $request, $id){
        $user = $this->userRepository->find($id);
        
        if ($request->hasFile('file')) {
            \File::delete(public_path($user->profile_image));
            $this->validate($request, ['file' => 'mimes:jpeg,jpg,png|max:10000']);
            
            $profile_image = $request->file('file');
            $filename = time() . '.' . $profile_image->getClientOriginalExtension();
            \Image::make($profile_image)->resize(200,200)->save(public_path('images/profile/' . $filename));
            $user->profile_image = "/images/profile/" . $filename;
            $user->update();
        }

        if (!$request->ajax()) {
            return redirect()->route('account.view');
        }

        return json_encode(['success' => 1]);
    }
    
    /**
     * Profile image delete
     */
    public function imageDelete(Request $request, $id){
        unlink(public_path($user->profile_image));
        
        $user = $this->userRepository->find($id);
        $user->profile_image = '/images/default.jpg';
        $user->update();

        if (!$request->ajax()) {
            return redirect()->route('account.view');
        }

        return json_encode(['success' => 1]);
    }

    public function setting()
    {
        $id = \Auth::id();
        $user = $this->userRepository->find($id);
        \Config::set('app.timezone', $user->getTimezone());
        
        $tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        return view('user::account/setting')
            ->with('user', $user)
            ->with('tzlist', $tzlist)
            ->with('setting', $user->listSettings())
            ;
    }

    public function setting_update(Request $request)
    {
        $id = \Auth::id();
        $user = $this->userRepository->find($id);
        
        $input = $request->all();
        $this->userRepository->saveSettings($user, $input);

        \Flash::success('Settings updated successfully.');

        return redirect(route('account.setting'));
    }
}