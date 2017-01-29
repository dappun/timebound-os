<?php namespace TB\Timesheet\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use TB\Timesheet\Repositories\TimesheetRepository;
use TB\User\Repositories\UserRepository;
use TB\Notification\Repositories\NotificationRepository;

class WatcherCommand extends Command 
{

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timebound:watch {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reports to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        TimesheetRepository $timeEntryRepo, 
        UserRepository $userRepos,
        NotificationRepository $notifRepo
        )
    {
        parent::__construct();
        $this->timesheetRepo = $timeEntryRepo;
        $this->userRepository = $userRepos;
        $this->notifRepo = $notifRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('type')) {
            case 'to_close':
                $this->watch_overlimit_closing();
                break;
            case 'to_warn':
                $this->watch_overlimit_warning();
                break;
        }
    }

    public function watch_overlimit_closing()
    {
        $dayLimit = 24 * 60 * 60;
        $closing = [];

        $list = $this->timesheetRepo->overlimit($dayLimit);
        foreach ($list as $timesheet) {
            $diff = (float)$timesheet->diff_in_seconds / 60 / 60;
            
            // must end now. do not continue. notify user
            $closing[$timesheet->user_id][] = $timesheet;
            $message = '#' . $timesheet->id . ': Exceeded 24 hours';
            $this->timesheetRepo->stop(['id' => $timesheet->id]);

            $this->line($message);
        }

        $subject = env('SITE_NAME') . ' - Closed Timesheets';
        foreach ($closing as $userID => $timesheets) {
            $account = $this->userRepository->find($userID);
            $this->line('Sending closed timesheet notification to ' . $account->email);
            $data = [
                'title' => 'Closed Timesheets',
                'timesheets' => $timesheets
            ];  

            $account->email = 'rachel@koodi.ph';

            foreach ($timesheets as $key => $ts) {
                $notif = [
                    'user_id' => $account->id,
                    'sent_to_email' => $account->email,
                    'message' => $message,
                    'type' => 'timebound_watch_warning',
                    'ref_id' => $ts->id
                ];

                $this->notifRepo->create($notif);
            }

            $this->_sendEmail($account, 'timesheet::emails/watch_closed', $subject, $data);    
        }
    }

    public function watch_overlimit_warning()
    {
        $limit = \Config::get('timesheet.overlimit');
        $warning = [];

        $list = $this->timesheetRepo->overlimit($limit);

        foreach ($list as $timesheet) {
            $exist = $this->notifRepo->existThisDate([
                'user_id' => $timesheet->user_id,
                'type' => 'timebound_watch_warning',
                'ref_id' => $timesheet->id,
                'date' => date('Y-m-d')
            ]);

            // do not continue if we already sent a warning today
            if ($exist->count()) continue;

            $diff = (float)$timesheet->diff_in_seconds / 60 / 60;
            
            // notify user
            $link       = "#<a href='" . route('timer.edit', $timesheet->id) . "'>{$timesheet->id}</a>";
            $duration   = gmhours(computeDuration($timesheet->start, date('Y-m-d H:i:s')));
            $message    = $link . ': Warning. Running at ' . $duration;
            
            $timesheet->message = $message;
            $warning[$timesheet->user_id][] = $timesheet;
            $this->line($message);
        }

        $subject = env('SITE_NAME') . ' - Running Timesheets';
        foreach ($warning as $userID => $timesheets) {
            $account = $this->userRepository->find($userID);
            
            $this->line('Sending warning email to ' . $account->email);
            $data = [
                'title' => 'Running Timesheets',
                'timesheets' => $timesheets
            ];  

            foreach ($timesheets as $key => $ts) {
                $notif = [
                    'user_id' => $account->id,
                    'sent_to_email' => $account->email,
                    'message' => $message,
                    'type' => 'timebound_watch_warning',
                    'ref_id' => $ts->id
                ];

                $this->notifRepo->create($notif);
            }
            
            $this->_sendEmail($account, 'timesheet::emails/watch_warning', $subject, $data);    
        }

        $this->line('Done!');
    }

    private function _sendEmail($account, $template, $subject, $content)
    {   
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send($template, $content, function($message) use($account, $subject)
        {
            $message
                ->from(env('SITE_EMAIL', 'changeme@nobody.com'))
                ->to($account->email, $account->first_name . ' ' . $account->last_name)
                ->subject($subject);
        });

        $this->line('Email sent to ' . $account->email);
    }

}
