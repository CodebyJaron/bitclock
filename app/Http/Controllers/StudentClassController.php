<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentClassRequest;
use App\Http\Requests\UpdateStudentClassRequest;
use App\Http\Requests\AddStudentsToClassWithFile;
use App\Models\StudentClass;
use App\Http\Resources\StudentClassResource;
use App\Models\User;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

class StudentClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StudentClassResource::collection(StudentClass::all());
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
    public function store(StoreStudentClassRequest $request)
    {
        $validated = $request->validated();

        $classExist = StudentClass::where('name', $validated['name'])->exists();
        if ($classExist) {
            return response()->json(['message', 'Er bestaat al een klas met deze naam.'], 409);
        }

        $studentClass = StudentClass::create($validated);

        $teacherIds = is_array($validated['teachers']) ? $validated['teachers'] : [$validated['teachers']];

        foreach ($teacherIds as $teacherId) {
            $user = User::find($teacherId);

            if ($user) {
                $studentClass->teachers()->syncWithoutDetaching([$user->id]);
            } else {
                return response()->json(['message', 'Docent is niet gevonden.'], 404);
            }
        }

        return new StudentClassResource($studentClass);
    }


    /**
     * Display the specified resource.
     */
    public function show(StudentClass $studentClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentClass $studentClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentClassRequest $request, StudentClass $studentClass)
    {
        $validated = $request->validated();

        $classExist = StudentClass::where('name', $validated['name'])
            ->where('id', '!=', $studentClass->id)
            ->exists();

        if ($classExist) {
            return response()->json(['message' => 'Er bestaat al een klas met deze naam.'], 409);
        }

        $studentClass->update($validated);

        $teacherIds = is_array($validated['teachers']) ? $validated['teachers'] : [$validated['teachers']];

        foreach ($teacherIds as $teacherId) {
            $user = User::find($teacherId);

            if ($user) {
                $studentClass->teachers()->syncWithoutDetaching([$user->id]);
            } else {
                return response()->json(['message' => 'Docent is niet gevonden.'], 404);
            }
        }

        return new StudentClassResource($studentClass);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentClass $studentClass)
    {
        foreach ($studentClass->students as $student) {
            $student->clocks()->delete();
            $student->delete();
        }

        $studentClass->teachers()->detach();
        $studentClass->delete();

    }

    public function latestCreated()
    {
        return new StudentClassResource(StudentClass::latest()->first());
    }

    public function importStudents(StudentClass $studentClass, AddStudentsToClassWithFile $request)
    {
        if ($request->validated()['file']) {
            $file = $request->file('file');

            if (!$file) return response()->json(['message', 'Bestand niet goedgekeurd of ongeldig.'], 400);

            try {
                Excel::import(new StudentsImport($studentClass), $file);
                return response()->json(['message', 'Bestand succesvol geïmporteerd.'], 200);
            } catch (ValidationException $e) {
                return response()->json(['message', 'Bestand niet goedgekeurd of ongeldig.'], 400);
            }
        }
    }

}

