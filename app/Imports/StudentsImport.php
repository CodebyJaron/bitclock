<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport implements ToModel, WithStartRow
{
    // https://docs.laravel-excel.com/3.1/getting-started/

    private $headerValidated = false;

    public function startRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        if (!$this->headerValidated) {
            $expectedHeaders = ['Roepnaam', 'Tussenvoegsel', 'Achternaam', 'E-mailadres'];

            foreach ($expectedHeaders as $header) {
                if (!in_array($header, $row)) {
                    throw ValidationException::withMessages(['header' => 'Ongeldige header.']);
                }
            }

            $this->headerValidated = true;
            return null;
        }

        return new Student([
            'name' => $row[0] . ' ' . $row[1] . ' ' . $row[2],
            'email' => $row[3],
            'student_class_id' => StudentClass::latest()->first()->id,
        ]);
    }
}

