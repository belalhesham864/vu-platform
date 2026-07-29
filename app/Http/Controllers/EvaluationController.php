<?php

namespace App\Http\Controllers;

use App\Http\Requests\Evaluation\EvaluationRequest;
use App\Http\Resources\Evaluation\EvaluationResource;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_evaluations')) {
            return apiResponse(403 , 'You Can Not Crwate Job');
        }

        $evaluations = Evaluation::with('answers', 'application')->paginate();

        return apiResponse(200, 'Success', EvaluationResource::collection($evaluations));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluationRequest $request)
    {
        if (!auth()->user()->can('create_evaluation')) {
            return apiResponse(403 , 'You Can Not Create Evaluation');
        }

        $validated = $request->validated();

        try {

            DB::beginTransaction();

            $evaluation = Evaluation::create([
                'application_id' => $validated['application_id'],
                'interview_id' => $validated['interview_id'],
                'overall_score' => $validated['overall_score'],
                'strengths' => $validated['strengths'] ?? null,
                'weaknesses' => $validated['weaknesses'] ?? null,
                'recording_url' => $validated['recording_url'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['answers'] as $answer) {

                $evaluation->answers()->create([
                    'question' => $answer['question'],
                    'answer' => $answer['answer'],
                ]);
            }

            DB::commit();

            return apiResponse(201, 'Evaluation created successfully', new EvaluationResource($evaluation));
        } catch (\Exception $e) {

            DB::rollBack();

            return apiResponse(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        return apiResponse(
            200,
            'Success',
            $evaluation->load('answers', 'application')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluationRequest $request, Evaluation $evaluation)
    {
        if (!auth()->user()->can('edit_evaluation')) {
            return apiResponse(403 , 'You Can Not Edit Evaluation');
        }

        $validated = $request->validated();

        $evaluation->update($validated);

        return apiResponse(200, 'Updated successfully', new EvaluationResource($evaluation));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        if (!auth()->user()->can('delete_evaluation')) {
            return apiResponse(403 , 'You Can Not Delete Evaluation');
        }

        $evaluation->delete();

        return apiResponse(200, 'Deleted successfully', new EvaluationResource($evaluation));
    }
}
