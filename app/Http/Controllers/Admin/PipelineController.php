<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PipelineController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],

            'topic' => [
                'nullable',
                Rule::in([
                    'academic',
                    'personal',
                    'social',
                    'organization',
                    'other',
                ]),
            ],
        ]);

        $statuses = [
            'pending' => 'Menunggu',
            'reviewing' => 'Ditinjau',
            'scheduled' => 'Terjadwal',
            'completed' => 'Selesai',
        ];

        $columns = [];

        foreach ($statuses as $status => $label) {

            $query = ConsultationRequest::query()
                ->where('status', $status);

            if (!empty($validated['search'])) {

                $search = $validated['search'];

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'alias',
                        'like',
                        "%{$search}%"
                    )->orWhere(
                            'ticket_number',
                            'like',
                            "%{$search}%"
                        );

                });
            }

            if (!empty($validated['topic'])) {
                $query->where('topic', $validated['topic']);
            }

            $count = (clone $query)->count();

            $items = (clone $query)
                ->latest()
                ->limit(20)
                ->get();

            $columns[$status] = [
                'label' => $label,
                'count' => $count,
                'items' => $items,
            ];
        }

        $otherStatusesCount = ConsultationRequest::whereIn(
            'status',
            ['rejected', 'referred', 'cancelled']
        )->count();

        return view('admin.pipeline.index', compact(
            'columns',
            'otherStatusesCount'
        ));
    }
}