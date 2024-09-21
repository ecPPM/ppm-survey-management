<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        return view('surveys');
    }

    public function show($id)
    {
        return redirect()->route('surveys.settings', ['id' => $id]);
    }

    public function settings($id)
    {
        $survey = Survey::find($id);
        return view('survey-settings', ['survey' => $survey]);
    }

    public function respondents($id)
    {
        $survey = Survey::find($id);
        return view('survey', ['survey' => $survey]);
    }

    public function questionnaire($id)
    {
        $survey = Survey::find($id);
        return view('survey', ['survey' => $survey]);
    }
}
