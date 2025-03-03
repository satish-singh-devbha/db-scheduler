<?php

namespace Satishsinghdevbha\DbScheduler\Schedule;

use Illuminate\Support\Facades\Log;
use Satishsinghdevbha\DbScheduler\Models\DBScheduler;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;

class Schedule extends BaseSchedule
{

    protected $isScheduleAdded = false;

    public function dueEvents($app) {
        
        if ($this->isScheduleAdded) {
            return parent::dueEvents($app);
        }

        $schedules = DBScheduler::all()->toArray();

        Log::info("TEST WORKS Insode Scheudle");

        foreach ($schedules as $s) {

            $event = $this->command($s['command'], [$s['arguments']] ?? [])->cron($s['cron_expression']);

            // if ($s['even_in_maintenance_mode']) {
            //     $event->evenInMaintenanceMode();
            // }

            // if ($s['without_overlapping']) {
            //     $event->withoutOverlapping();
            // }
        }

        return parent::dueEvents($app);
    }
}