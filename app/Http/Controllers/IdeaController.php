<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IdeaRequest;
use App\Models\Idea;
use App\Notifications\IdeaPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ideas = Auth::user()
            ->ideas()
            ->when($request->state, fn ($query, $status) => $query->where('state', $status))
            ->latest()
            ->get();

        return view('ideas.index', [
            'ideas' => $ideas,
            'counts' => Idea::statusCount(Auth::user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ideas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request)
    {
        $validated = $request->validated();

        $idea = Auth::user()->ideas()->create([
            ...Arr::except($validated, ['steps', 'image']),
            'image_path' => $request->file('image')?->store('ideas', 'public'),
        ]);

        $idea->steps()->createMany(
            array_map(
                fn (array $step) => ['description' => $step['description']],
                $validated['steps'] ?? [],
            ),
        );

        Auth::user()->notify(new IdeaPublished($idea));

        return redirect()->route('ideas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        Gate::authorize('update', $idea);

        return view('ideas.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        Gate::authorize('update', $idea);

        return view('ideas.edit', [
            'idea' => $idea,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea)
    {
        Gate::authorize('update', $idea);

        $validated = $request->validated();

        if ($image = $request->file('image')) {
            $previousPath = $idea->image_path;

            $idea->image_path = $image->store('ideas', 'public');

            if ($previousPath) {
                Storage::disk('public')->delete($previousPath);
            }
        }

        $idea->update([
            ...Arr::except($validated, ['steps', 'image']),
            'links' => $validated['links'] ?? [],
            'image_path' => $idea->image_path,
        ]);

        $this->syncSteps($idea, $validated['steps'] ?? []);

        return redirect()->route('idea.show', $idea);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        Gate::authorize('update', $idea);
        $idea->delete();

        return redirect()->route('ideas.index');
    }

    /**
     * Update the steps still present, drop the removed ones and create the new ones.
     *
     * @param  array<int, array{id?: int|string|null, description: string}>  $steps
     */
    private function syncSteps(Idea $idea, array $steps): void
    {
        $idea->steps()->whereNotIn('id', array_filter(array_column($steps, 'id')))->delete();

        foreach ($steps as $step) {
            if (empty($step['id'])) {
                $idea->steps()->create(['description' => $step['description']]);

                continue;
            }

            $idea->steps()->whereKey($step['id'])->update(['description' => $step['description']]);
        }
    }
}
