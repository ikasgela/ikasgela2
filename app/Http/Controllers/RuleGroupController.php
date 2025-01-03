<?php

namespace App\Http\Controllers;

use App\Models\RuleGroup;
use App\Models\Selector;
use Illuminate\Http\Request;

class RuleGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:profesor|admin');
    }

    public function index()
    {
        abort(404);
    }

    public function create(Selector $selector)
    {
        return view('rule_groups.create', compact('selector'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'operador' => 'required|in:and,or',
            'accion' => 'required|in:siguiente',
            'resultado' => 'required',
        ]);

        RuleGroup::create($request->all());

        return retornar();
    }

    public function show(RuleGroup $rule_group)
    {
        abort(404);
    }

    public function edit(RuleGroup $rule_group)
    {
        $selector = $rule_group->selector;

        return view('rule_groups.edit', compact(['rule_group', 'selector']));
    }

    public function update(Request $request, RuleGroup $rule_group)
    {
        $this->validate($request, [
            'operador' => 'required|in:and,or',
            'accion' => 'required|in:siguiente',
            'resultado' => 'required',
        ]);

        $rule_group->update($request->all());

        return retornar();
    }

    public function destroy(RuleGroup $rule_group)
    {
        $rule_group->delete();

        return back();
    }

    public function duplicar(RuleGroup $rule_group)
    {
        $clon = $rule_group->duplicate();
        $clon->save();

        return back();
    }
}
