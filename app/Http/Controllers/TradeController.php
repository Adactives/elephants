<?php

namespace App\Http\Controllers;

use App\Models\Elephant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function trade(Request $request) : JsonResponse
    {
        $request->validate([
            'offer_elephant_id' => 'required|integer|exists:elephants,id',
            'target_elephant_id' => 'required|integer|exists:elephants,id|different:offer_elephant_id',
        ]);

        $offerElephant = Elephant::findOrFail($request->input('offer_elephant_id'));
        $targetElephant = Elephant::findOrFail($request->input('target_elephant_id'));

        $offerUser = Auth::user();
        $targetUser = User::findOrFail($targetElephant->user_id);

        if ($offerElephant->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You can only offer your own elephants.'], 403);
        }

        if (empty($offerElephant->user_id) || empty($targetElephant->user_id)) {
            return response()->json(['message' => 'Elephants are not in a collection.'], 403);
        }

        $offerElephant->user_id = $targetUser->id;
        $offerElephant->save();

        $targetElephant->user_id = $offerUser->id;
        $targetElephant->save();

        return response()->json(['message ' => 'Trade successful.']);
    }
}
