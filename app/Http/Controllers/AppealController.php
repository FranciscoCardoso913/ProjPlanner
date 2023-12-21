<?php

namespace App\Http\Controllers;
use App\Models\Appeal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AppealController extends Controller
{

    public function show(Request $request) {

        $this->authorize('view_appeals', [User::class]);

        $query = $request->input('query');
        
        $appeals = Appeal::query()->count();

        if( $query)
            return view('admin.appeals', ['appeals' => app(AppealController::class)->search($request),'query'=>$query,'amount'=>$appeals] );

        return view('admin.appeals', ['appeals' => Appeal::query()->paginate(10)->withQueryString(),'query'=>$query,'amount'=>$appeals] );
    }
    
    public function showBlocked(Request $request, User $user)
    {
        $this->authorize("showAppealForUnblock", User::class);

        return view('auth.blocked', ['user' => $user]);
    }
    
    public function search(Request $request)
    {
        $searchTerm = '%' . $request->input('query') . '%';

        $query = null;
        $this->authorize('view_appeals', [User::class]);
        $query = Appeal::query();
        $appeals = $query->where(function ($query) use ($searchTerm) {
            $query->where('user_id', 'like', $searchTerm)
                ->orWhere('content', 'like', $searchTerm);
        });

        if ($request->ajax())
            return response()->json($appeals->get());
        else {
            return $appeals->paginate(10)->withQueryString();
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'appeal' => 'required|max:2000',
        ]);

        $this->authorize("storeAppealForUnblock", User::class);

        $appeal = new Appeal;
        $appeal->user_id = Auth::user()->id;
        $appeal->content = $validated['appeal'];
        $appeal->save();

        return redirect()->route('init_page');
    }

    public function deny(Request $request, Appeal $appeal) {

        $this->authorize('view_appeals', [User::class]);

        $appeal->delete();

        $appeals = Appeal::all();

        return redirect()->route('admin_appeals', ['query' => $request['query']]);
    }

    public function accept(Request $request, Appeal $appeal) {

        $this->authorize('view_appeals', [User::class]);

        $user = $appeal->user;
        $user->is_blocked = false;
        $user->save();
        $appeal->delete();

        $appeals = Appeal::all();

        return redirect()->route('admin_appeals', ['query' => $request['query']]);
    }

}
