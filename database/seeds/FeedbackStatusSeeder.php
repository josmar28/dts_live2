<?php

use Illuminate\Database\Seeder;
use App\FeedbackStatus;
class FeedbackStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stat = new FeedbackStatus();
        $stat->action = "Fixed";
        $stat->save();

        $stat = new FeedbackStatus();
        $stat->action = "Pending";
        $stat->save();
    }
}
