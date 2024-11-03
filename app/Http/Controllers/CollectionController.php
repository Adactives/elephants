<?php

namespace App\Http\Controllers;

use App\Models\Elephant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function storeElephants(Request $request) : JsonResponse
    {
        $request->validate([
           'elephants'      => 'required|array',
           'elephants.*.id' => 'required|integer|exists:elephants,id',
       ]);

        $errors = [];

        foreach ($request->input('elephants') as $elephant) {
            $elephant = Elephant::findOrFail($elephant['id']);
            if ( ! empty($elephant->user_id)) {
                $error = ['message' => 'Elephant' . $elephant->name . ' already in a collection, maybe you can trade it.'];
                if ($elephant->user_id == $request->user()->id) {
                    $error = ['message' => 'Elephant' . $elephant->name . ' already in your collection'];
                }
                $errors[] = $error;
                continue;
            }

            $elephant->user_id = $request->user()->id;
            $elephant->save();
        }

        $response = [
            'collectedElephants' => Auth::user()->load('elephants')->elephants,
        ];

        if (count($errors) > 0) {
            $response['errors'] = $errors;

            return response()->json($response, 207);
        }

        return response()->json($response);
    }
}
