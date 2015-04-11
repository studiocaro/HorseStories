<?php 
namespace HorseStories\Http\Controllers\Statuses;

use Auth;
use DB;
use HorseStories\Models\Statuses\Status;
use Illuminate\Routing\Controller;

class LikeController extends Controller
{
    /**
     * @param int $status
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function like($status)
    {
        $status = Status::findOrFail($status);

        $likes = DB::table('likes')->whereUserId(Auth::user()->id)->lists('status_id');

        if (in_array($status->id, $likes)) {
            Auth::user()->likes()->detach($status);
        } else {
            Auth::user()->likes()->attach($status);
        }

        return response()->json('success', 200);
    }
}