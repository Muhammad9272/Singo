<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $stores = Store::query()
            ->when(request('searchQuery'), function ($query) {
                $query->where('title', 'LIKE', '%'.request('searchQuery').'%')
                    ->orWhere('fuga_store_id', 'LIKE', '%'.request('searchQuery').'%');
            })
            ->with('creator')
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('admin.store.index', ['stores' => $stores]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('admin.store.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreRequest $request)
    {
        Store::create(
            array_merge($request->validated(), ['created_by' => auth()->id()])
        );

        return redirect()->route('admin.stores.index')->with('success', 'Created successfully.');
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Store $store)
    {
        return view('admin.store.show', ['store' => $store]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Store $store)
    {
        return view('admin.store.edit', ['store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(StoreRequest $request, Store $store)
    {
        $store->update(
            array_merge(
                $request->validated(),
                [
                    'updated_by' => auth()->id()
                ]
            )
        );

        return redirect()->route('admin.stores.index')->with('success', 'Updated successfully.');
    }
}
