<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Http\Resources\StudentResource;
use App\Http\Requests\addOpenMinutesToStudent;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StudentResource::collection(Student::all());
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
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        $studentExist = Student::where('email', $validated['email'])->exists();
        if ($studentExist) {
            return response()->json(['message', 'Er bestaat al een student met dit email.'], 409);
        }


        $student = Student::create($validated);

        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->clocks()->delete();

        $student->delete();
    }

    public function addOpenMinutesToStudent(AddOpenMinutesToStudent $request, Student $student)
    {
        $validated = $request->validated();

        $newMinutesOpen = $student->minutes_open + $validated['minutes'];

        $student->update(['minutes_open' => $newMinutesOpen]);

        return new StudentResource($student);
    }

}
