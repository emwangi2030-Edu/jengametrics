<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Library;
use App\Models\LibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:items,id',
        ]);

        $itemIds = collect($validated['items'])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($itemIds->isEmpty()) {
            return redirect()
                ->route('bq_documents.index')
                ->with('danger', __('Please select at least one item for the library.'));
        }

        $items = Item::query()
            ->with(['Element.section'])
            ->whereIn('id', $itemIds)
            ->get();

        if ($items->count() !== $itemIds->count()) {
            return redirect()
                ->route('bq_documents.index')
                ->with('danger', __('One or more selected items could not be found.'));
        }

        $missingContext = $items->first(function (Item $item) {
            return ! $item->Element || ! $item->Element->section;
        });

        if ($missingContext) {
            return redirect()
                ->route('bq_documents.index')
                ->with('danger', __('Selected items must be linked to both an element and section before they can be stored in a library.'));
        }

        DB::transaction(function () use ($validated, $items) {
            $owner = Auth::user()->libraryOwner();
            $library = $owner->libraries()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($items as $item) {
                $element = $item->Element;
                $section = $element->section;

                LibraryItem::create([
                    'library_id' => $library->id,
                    'section_id' => $section->id,
                    'element_id' => $element->id,
                    'item_id' => $item->id,
                ]);
            }
        });

        return redirect()
            ->route('bq_documents.index')
            ->with('success', __('Library created successfully.'));
    }

    public function update(Request $request, Library $library)
    {
        $this->authorizeLibrary($library);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:items,id',
        ]);

        $itemIds = collect($validated['items'])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($itemIds->isEmpty()) {
            return redirect()
                ->route('bq_documents.index')
                ->with('danger', __('Please select at least one item for the library.'));
        }

        $items = Item::query()
            ->with(['Element.section'])
            ->whereIn('id', $itemIds)
            ->get();

        if ($items->count() !== $itemIds->count()) {
            return redirect()
                ->route('bq_documents.index')
                ->with('danger', __('One or more selected items could not be found.'));
        }

        $missingContext = $items->first(function (Item $item) {
            return ! $item->Element || ! $item->Element->section;
        });

        if ($missingContext) {
            return redirect()
                ->route('bq_documents.index')
                ->with('danger', __('Selected items must be linked to both an element and section before they can be stored in a library.'));
        }

        DB::transaction(function () use ($library, $validated, $items) {
            $library->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            LibraryItem::where('library_id', $library->id)->delete();

            foreach ($items as $item) {
                $element = $item->Element;
                $section = $element->section;

                LibraryItem::create([
                    'library_id' => $library->id,
                    'section_id' => $section->id,
                    'element_id' => $element->id,
                    'item_id' => $item->id,
                ]);
            }
        });

        return redirect()
            ->route('bq_documents.index')
            ->with('success', __('Library updated successfully.'));
    }

    public function destroy(Library $library)
    {
        $this->authorizeLibrary($library);

        $library->delete();

        return redirect()
            ->route('bq_documents.index')
            ->with('success', __('Library deleted successfully.'));
    }

    public function items(Library $library)
    {
        $this->authorizeLibrary($library);

        $library->load([
            'items.item',
            'items.section',
            'items.element',
        ]);

        $payload = $library->items->map(function (LibraryItem $item) {
            return [
                'id' => $item->id,
                'section_id' => $item->section_id,
                'section' => $item->section?->name,
                'element_id' => $item->element_id,
                'element' => $item->element?->name,
                'item_id' => $item->item_id,
                'item' => $item->item?->name,
                'unit' => $item->item?->unit_of_measurement,
            ];
        });

        $firstItem = $library->items->first();

        return response()->json([
            'library' => [
                'id' => $library->id,
                'name' => $library->name,
                'description' => $library->description,
                'section_id' => $firstItem?->section_id,
                'element_id' => $firstItem?->element_id,
            ],
            'items' => $payload,
        ]);
    }

    protected function authorizeLibrary(Library $library): void
    {
        $user = Auth::user();
        $ownerId = $user?->libraryOwner()->id;
        if (! $ownerId || $library->user_id !== $ownerId) {
            abort(403);
        }
    }
}
