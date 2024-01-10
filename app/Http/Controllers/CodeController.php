<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Http\Requests\CodeLoginRequest;
use App\Http\Resources\CodeResource;
use App\Http\Responses\NoContentResponse;
use Illuminate\Support\Str;
use App\Models\User;

class CodeController extends Controller
{
    public function generateNewCode(Request $request)
    {
        Code::truncate();

        if (!$request->user()) {
            return response(['message' => 'Unauthenticated'], 401);
        }

        $inputCode = $request->input('code');
        $code = !empty($inputCode) ? $inputCode : Str::random(10);

        $edited_by = $request->input('edited_by') || $request->user()->id;
        if (!$edited_by) return response(['message' => 'Er ging iets fout.'], 401);

        try {
            $codeModel = Code::create(['code' => $code, 'edited_by' => $request->user()->id]);

            return ['code' => $codeModel->code];
        } catch (\Exception $e) {
            return response(['message' => 'Code generation failed'], 500);
        }
    }

    public function getCode(Request $request)
    {
        if ($request->user() == null) {
            return response()->json(['message', 'Unauthenticated'], 401);
        }

        $code = Code::first();
        if ($code !== null) {
            return new CodeResource($code);
        }

        return new NoContentResponse();
    }


    public function submitCode(CodeLoginRequest $request)
    {
        $validated = $request->validated();

        $code = Code::first();
        if (!$code) {
            return response()->json(['message' => 'Geen code gevonden'], 404);
        }

        if ($code->getCode() == $validated['code']) {
            return response()->json(['message' => 'Welkom!'], 200);
        } else {
            return response()->json(['message' => 'Foute code!'], 404);
        }
    }
}
