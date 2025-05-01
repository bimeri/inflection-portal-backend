<?php

namespace App\Http\Controllers;

use App\Models\SuccessfulEmail;
use App\Utils\HtmlContentExtractor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuccessfulEmailController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $email = SuccessfulEmail::create($request->all());
        $email->raw_text = HtmlContentExtractor::extractPlainText($email->email);
        $email->save();

        return response()->json($email, 201);
    }

    public function getById($id)
    {
        return SuccessfulEmail::findOrFail($id);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $email = SuccessfulEmail::findOrFail($id);
        $email->update($request->all());

        return response()->json($email);
    }

    public function index()
    {
        return SuccessfulEmail::whereNull('deleted_at')->get();
    }

    public function destroy($id): JsonResponse
    {
        $email = SuccessfulEmail::findOrFail($id);
        $email->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }

}
