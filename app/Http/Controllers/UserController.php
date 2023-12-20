<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Appeal;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function searchUsers(Request $request)
    {
        $searchTerm = '%' . $request->input('query') . '%';

        $project = $request->input('project');
        $query = null;
        if ($project !== null) {
            $this->authorize('viewTeam', [User::class,Project::find($project)]);
            $query = Project::find($project)->users();
        }
        else{
            $this->authorize('viewAny',User::class);
            $query = User::query();
        }
        $users = $query->where(function ($query) use ($searchTerm) {
            $query->where('email', 'like', $searchTerm)
                ->orWhere('name', 'like', $searchTerm)->with('getProfileImage');
        });
        if ($request->ajax())
            return response()->json($users->get());
        else {
            if($project ===null)
                return $users->paginate(10)->withQueryString();
        }
    }

    public function block(Request $request, User $user)
    {
        $this->authorize("block", $user);

        $user->is_blocked = true;
        $user->save();

        return redirect()->route('admin');
    }

    public function unblock(Request $request, User $user)
    {
        $this->authorize("block", $user);

        $user->is_blocked = false;
        $user->save();

        return redirect()->route('admin');
    }

    
    public function showBlocked(Request $request, User $user)
    {
        $this->authorize("showAppealForUnblock", User::class);

        return view('auth.blocked', ['user' => $user]);
    }

    public function storeAppeal(Request $request, User $user)
    {
        $this->authorize("storeAppealForUnblock", User::class);

        $appeal = new Appeal;
        $appeal->user_id = $user->id;
        $appeal->content = $request->input('text');
        $appeal->save();

        return redirect()->route('init_page');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize("delete", $user);

        $user->delete();

        return redirect()->route('init_page');
    }
}
