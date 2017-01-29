<?php namespace TB\Timesheet\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use TB\Timesheet\Repositories\TimesheetRepository;
use TB\User\Repositories\UserRepository;

class ReportCommand extends Command 
{

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timebound:report {type?}';

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
    public function __construct(TimesheetRepository $timeEntryRepo, UserRepository $userRepos)
    {
        parent::__construct();
        $this->timesheetRepo = $timeEntryRepo;
        $this->userRepository = $userRepos;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('type')) {
            case 'daily_ot':
                $this->report_daily_ot();
                break;
            
            case 'weekly_summary':
                $this->report_weekly_summary();
                break;
            default:
                # code...
                break;
        }
    }

    public function report_daily_ot()
    {
        $sendTo = $this->userRepository->userWithSettingsOn('daily_ot_report');
        foreach ($sendTo as $info) {
            $account = $this->userRepository->find($info->user_id);
            
            if ($account->hasRole('admin')) {
                // send all
                $users = $this->userRepository->activeAll();
            } else {
                // send self
                $users = [$info->user_id => $account->name];
            }

            $content = $this->_generateOTContent($users);
            if ($content) {
                $data = ['rows' => $content];
                $subject = env('SITE_NAME') . ' - Overtime Notifications';
                $this->_sendEmail($account, 'timesheet::emails/notif8', $subject, $data);
            }
        }

        $this->line('Done!');
    }

    private function _generateOTContent($users)
    {
        $ot = [];
        foreach ($users as $userId => $name) {
            $lateDuration = \Config::get('timesheet.overtime.notice');
            $superLateDuration = \Config::get('timesheet.overtime.warning');

            $list = $this->timesheetRepo->overtimeList($userId, date('Y-m-d', strtotime('-3 day')), $lateDuration, 0, 10000);

            foreach ($list as $key => $entry) {
                $text = '<span style="color: orange">'.$entry->duration.'</span>';
                if ($entry->getAttributes()['duration'] > $superLateDuration) {
                    $text = '<span style="color: red">'.$entry->duration.'</span>';
                }

                $ot[] = "[{$name}]: {$entry->description} ($text)";
            }
        }

        return $ot;
    }

    public function report_weekly_summary()
    {
        $sendTo = $this->userRepository->userWithSettingsOn('daily_ot_report');
        foreach ($sendTo as $info) {
            $account = $this->userRepository->find($info->user_id);
            
            if ($account->hasRole('admin')) {
                // send all
                $users = $this->userRepository->activeAll();
            } else {
                // send self
                $users = [$info->user_id => $account->name];
            }

            $content = $this->_generateWeeklyContent($users);
            if ($content) {
                $subject = env('SITE_NAME') . ' - Weekly Summary';
                $this->_sendEmail($account, 'timesheet::emails/notifweekly', $subject, $content);
            }
        }

        $this->line('Done!');
    }

    // Meant to be run on sundays
    private function _generateWeeklyContent($users)
    {
        $conditions = [
            'start' => date('Y-m-d', strtotime('Monday last week')),
            'end' => date('Y-m-d', strtotime('Sunday this week')), 
        ];

        $report = $this->timesheetRepo->groupByDay($conditions);
        $dateRanges = calculateDateRanges([
            'from' => $conditions['start'],
            'to' => $conditions['end']
        ], 'day');

        $header = ['Name'];
        foreach ($dateRanges as $pairDate) {
            $t = strtotime($pairDate['from']);
            $header[] = date('M-d', $t) . '<br/><i>' . date('D', $t) . '</i>';
        }

        foreach ($users as $userID => $name) {
            if (!$userID) continue;

            $row = [$name];
            foreach ($dateRanges as $pairDate) {
                $total = isset($report[$userID][$pairDate['from']]) ? $report[$userID][$pairDate['from']] : 0;
                $row[] = gmhours($total, 'short');
            }
                
            $rows[] = $row;
        }

        $data = [
            'header' => $header,
            'rows' => $rows,
        ];

        return $data;
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
