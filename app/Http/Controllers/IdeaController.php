<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $status = $request->status;

        if (! in_array($status, IdeaStatus::values())) {
            $status = null;
        }

        $ideas = $user
            ->ideas()
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        $statusCounts = Idea::statusCounts($user);

        return view('idea.index', [
            'ideas' => $ideas,
            'statusCounts' => $statusCounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {
        DB::transaction(function () use ($request): void {
            $idea = Auth::user()->ideas()->create($request->safe()->except(['steps', 'image']));

            $imagePath = $request->image->store('ideas', 'public');

            $idea->update(['image_path' => $imagePath]);

            $steps = collect($request->safe()->input('steps', []))
                ->map(fn ($step) => ['description' => $step])
                ->all();

            if ($steps !== []) {
                $idea->steps()->createMany($steps);
            }
        });

        return to_route('idea.index')
            ->with('success', 'Idea created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $id)
    {
        return view('idea.show', [
            'idea' => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, Idea $idea): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();

        return redirect('/idea');
    }
}
