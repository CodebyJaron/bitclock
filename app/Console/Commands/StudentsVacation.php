<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentClass;
use Carbon\Carbon;
use App\Models\Clocks;

class StudentsVacation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:students-vacation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check als de studenten vrij zijn.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDayOfWeek = Carbon::now()->dayOfWeek;
        $classesThatAreFree = StudentClass::where('day_off', $currentDayOfWeek)->get();

        if ($classesThatAreFree->count() > 0) {
            foreach ($classesThatAreFree as $class) {
                foreach ($class->students as $student) {
                    $clock = new Clocks([
                        'student_id' => $student->id,
                        'status' => 'vrij',
                        'inclock_time' => '08:30:00',
                        'outclock_time' => '15:00:00',
                        'date' => date('Y-m-d'),
                        'excercise' => 'Vrij.',
                    ]);
                    $clock->save();
                }
            }
            $this->info('Vrijgave van de studenten is succesvol uitgevoerd voor ' . $classesThatAreFree->count() . ' klassen.');
        }
    }
}
