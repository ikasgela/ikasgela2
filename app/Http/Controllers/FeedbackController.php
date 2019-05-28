<?php

namespace App\Http\Controllers;

use App\Curso;
use App\Feedback;
use BadMethodCallException;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $feedbacks = Feedback::all();

        return view('feedbacks.index', compact('feedbacks'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('nombre')->get();

        return view('feedbacks.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'curso_id' => 'required',
            'mensaje' => 'required',
        ]);

        Feedback::create($request->all());

        return redirect(route('feedbacks.index'));
    }

    public function show(Feedback $feedback)
    {
        throw new BadMethodCallException(__('Not implemented.'));
    }

    public function edit(Feedback $feedback)
    {
        $cursos = Curso::orderBy('nombre')->get();

        return view('feedbacks.edit', compact(['feedback', 'cursos']));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $this->validate($request, [
            'curso_id' => 'required',
            'mensaje' => 'required',
        ]);

        $feedback->update($request->all());

        return redirect(route('feedbacks.index'));
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect(route('feedbacks.index'));
    }
}