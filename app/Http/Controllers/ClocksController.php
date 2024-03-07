<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClocksRequest;
use App\Http\Requests\UpdateClocksRequest;
use App\Http\Requests\UpdateClockStatusRequest;
use App\Models\Clocks;
use App\Http\Requests\ClockOutRequest;
use App\Http\Requests\ClockInRequest;
use App\Http\Resources\ClockResource;
use Carbon\Carbon;

class ClocksController extends Controller
{

    private $timezone = 'Europe/Amsterdam';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ClockResource::collection(Clocks::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClocksRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Clocks $clocks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clocks $clocks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClocksRequest $request, Clocks $clock)
    {
        $validated = $request->validated();
        if (!$clock) {
            $clock = new Clocks([
                'student_id' => $validated['student_id'],
                'status' => $validated['presence'],
                'inclock_time' => $this->getPresenceTimes($validated['status'])['in'],
                'outclock_time' => $this->getPresenceTimes($validated['new_presence'])['out'],
                'date' => $validated['date'],
                'excercise' => 'Niet ingevuld.',
                'note' => '',
            ]);
            $clock->save();
        }

        $clock->update($request->validated());
        return new ClockResource($clock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clocks $clocks)
    {
        //
    }

    public function clockIn(ClockInRequest $request)
    {
        $validated = $request->validated();

        $clock = Clocks::where('student_id', $validated['student_id'])->whereDate('date', date('Y-m-d'))->first();

        if ($clock && $clock->outclock_time != null) {
            return response()->json(['message' => 'Je bent al vrij..'], 400);
        }

        if ($clock) {
            return response()->json(['message' => 'Je bent al ingeklokt!'], 400);
        }

        $status = 'aanwezig';

        $currentHour = Carbon::now($this->timezone)->format('H');
        if ($currentHour < 8 || $currentHour >= 10) {
            $status = 'te_laat';
        }

        $clock = Clocks::create([
            'student_id' => $validated['student_id'],
            'date' => date('Y-m-d'),
            'excercise' => $validated['excercise'],
            'inclock_time' => date("H:i:s"),
            'outclock_time' => null,
            'status' => $status,
        ]);

        if (!$clock) {
            return response()->json(['message' => 'Er is iets fout gegaan!'], 400);
        }

        return new ClockResource($clock);
    }

    public function clockOut(ClockOutRequest $request)
    {
        $validated = $request->validated();

        $clock = Clocks::where('student_id', $validated['student_id'])->whereDate('date', date('Y-m-d'))->first();
        if (!$clock) {
            return response()->json(['message' => 'Je bent niet ingeklokt!'], 400);
        }

        if ($clock->outclock_time != null) {
            return response()->json(['message' => 'Je bent al uitgeklokt om ' . $clock->outclock_time], 400);
        }

        $checkoutTime = $this->getCheckoutTime($clock);
        if (!$this->canCheckout($clock)) {
            return response()->json(['message' => 'Je mag nog niet uitklokken! Je kunt uitklokken vanaf ' . $checkoutTime->format('H:i')], 400);
        }

        $nowTime = date('H:i:s');
        $timeLonger = Carbon::parse($nowTime)->diffInMinutes(Carbon::parse($checkoutTime), $this->timezone);

        $clock->outclock_time = date("H:i:s");
        $clock->save();

        if ($clock->student->minutes_open > 0) {
            $clock->student->minutes_open = $clock->student->minutes_open - $timeLonger;
            $clock->student->save();

            return response()->json(['message' => 'Je hebt '. $timeLonger.' minuten ingehaald!'], 200);
        }

        return response()->json(['message' => 'Je bent uitgeklokt om '. $clock->outclock_time], 200);
    }

    public function changePresence(UpdateClockStatusRequest $request)
    {
        $validated = $request->validated();

        $clock = Clocks::where('student_id', $validated['student_id'])->whereDate('date', $validated['date'])->first();

        if ($clock) {
            $clock->status = $validated['new_presence'];
            $clock->save();
        } else {
            $clock = new Clocks([
                'student_id' => $validated['student_id'],
                'status' => $validated['new_presence'],
                'inclock_time' => $this->getPresenceTimes($validated['new_presence'])['in'],
                'outclock_time' => $this->getPresenceTimes($validated['new_presence'])['out'],
                'date' => $validated['date'],
                'excercise' => 'Niet ingevuld.',
            ]);
            $clock->save();
        }

        return new ClockResource($clock);
    }



    private function canCheckout(Clocks $clock)
    {

        $nowTime = Carbon::now($this->timezone);
        $startTime = Carbon::parse($clock->inclock_time, $this->timezone);

        $checkoutTime = $startTime->copy()->setHour(16)->setMinute(30)->setSecond(0);
        $maxCheckoutTime = $startTime->copy()->addMinutes(390);

        if ($nowTime->greaterThanOrEqualTo($checkoutTime) || $nowTime->greaterThanOrEqualTo($maxCheckoutTime)) {
            return true;
        }

        return false;
    }

    private function getCheckoutTime(Clocks $clock)
    {
        $startTime = Carbon::parse($clock->inclock_time);
        $checkoutTime = $startTime->copy()->setHour(16)->setMinute(30)->setSecond(0);

        $nowTime = Carbon::now();


        $maxCheckoutTime = $startTime->copy()->addMinutes(390);

        if ($maxCheckoutTime->greaterThanOrEqualTo($checkoutTime)) {
            return $checkoutTime;
        }

        return $maxCheckoutTime;
    }

    private function getPresenceTimes(string $presence)
    {
        $times = [
            'aanwezig' => [
                'in' => '08:30',
                'out' => '15:00',
            ],
            'afwezig' => [
                'in' => null,
                'out' => null,
            ],
            'ziek' => [
                'in' => '8:30',
                'out' => '15:00',
            ],
            'te_laat' => [
                'in' => '08:00',
                'out' => '16:30',
            ],
            'vrij' => [
                'in' => '8:30',
                'out' => '15:00',
            ],
        ];

        return $times[$presence];
    }

}
