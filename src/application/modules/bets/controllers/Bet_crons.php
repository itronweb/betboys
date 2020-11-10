<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Cron\Cron;

class Bet_crons {

    //put your code here

    public function index () {

// Write folder content to log every five minutes.
        $job1 = new \Cron\Job\ShellJob();
        $job1->setCommand('php ' . FCPATH . 'index.php bets/api/GetUpcomingOdds');
//        $job1->setCommand('php -v');
        $job1->setSchedule(new \Cron\Schedule\CrontabSchedule('*/1 * * * *'));

// Remove folder contents every hour.
//        $job2 = new \Cron\Job\ShellJob();
//        $job2->setCommand('rm -rf /path/to/folder/*');
//        $job2->setSchedule(new \Cron\Schedule\CrontabSchedule('0 0 * * *'));

        $resolver = new \Cron\Resolver\ArrayResolver();
        $resolver->addJob($job1);
//        $resolver->addJob($job2);

        $cron = new \Cron\Cron();
        $cron->setExecutor(new \Cron\Executor\Executor());
        $cron->setResolver($resolver);

        $cron->run();
        
        dd($cron);

    }

}
