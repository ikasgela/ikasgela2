<?php

namespace App\Http\Controllers;

use App\Group;
use App\Period;
use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $groups = Group::all();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $periods = Period::orderBy('name')->get();

        return view('groups.create', compact('periods'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'period_id' => 'required',
            'name' => 'required',
        ]);

        try {
            Group::create([
                'period_id' => request('period_id'),
                'name' => request('name'),
                'slug' => Str::slug(request('name'))
            ]);
        } catch (\Exception $e) {
            // Slug repetido
        }

        return redirect(route('groups.index'));
    }

    public function show(Group $group)
    {
        throw new BadMethodCallException(__('Not implemented.'));
    }

    public function edit(Group $group)
    {
        $periods = Period::orderBy('name')->get();

        return view('groups.edit', compact(['group', 'periods']));
    }

    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'period_id' => 'required',
            'name' => 'required',
        ]);

        try {
            $group->update([
                'period_id' => request('period_id'),
                'name' => request('name'),
                'slug' => strlen(request('slug')) > 0
                    ? Str::slug(request('slug'))
                    : Str::slug(request('name'))
            ]);
        } catch (\Exception $e) {
            // Slug repetido
        }

        return redirect(route('groups.index'));
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return redirect(route('groups.index'));
    }
}