<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StepRequest;
use App\Models\Step;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class StepController extends Controller
{
    /**
     * Toggle the completion of the given step.
     */
    public function update(StepRequest $request, Step $step): RedirectResponse
    {
        Gate::authorize('update', $step->idea);

        $step->update([
            'completed' => $request->boolean('completed'),
        ]);

        return back();
    }
}
