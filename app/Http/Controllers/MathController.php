<?php

namespace App\Http\Controllers;

use Auth;
use App\Act;
use App\Math;
use App\Category;
use Illuminate\Http\Request;

class MathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $maths = \DB::table('maths')->paginate(15);

        return view('math.index', compact('maths'));
    }
    /**
     * Display a listing of the resource with specific level.
     *
     * @return \Illuminate\Http\Response
     */
    public function level($level)
    {
        //
        $maths = \DB::table('maths')->where('level',$level)->paginate(15);
        $levelString = "";
        if($level == 1) {
            $levelString =  "Α' Δημοτικού";
        }

        if($level == 2) {
            $levelString =  "Β' Δημοτικού";
        }

        if($level == 3) {
            $levelString =  "Γ' Δημοτικού";
        }

        if($level == 4) {
            $levelString =  "Δ' Δημοτικού";
        }

        if($level == 5) {
            $levelString =  "Ε' Δημοτικού";
        }

        if($level == 6) {
            $levelString =  "ΣΤ' Δημοτικού";
        }

        if($level == 7) {
            $levelString =  "Α' Γυμνασίού";
        }

        if($level == 8) {
            $levelString =  "Β' Γυμνασίου";
        }

        if($level == 9) {
            $levelString =  "Γ' Γυμνασίου";
        }


        return view('math.level', compact('maths', 'levelString'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        //
        return view('math.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        //
        $validatedData = $request->validate([
            'category' => 'required',
            'mathlevel' => 'required',
            'mathquestion' => 'required|max:255',
            'mathanswer' => 'required|max:255',
        ]);

        $math = new Math();
        $math->level = $request->mathlevel;
        $math->question = $request->mathquestion;
        $math->answer = $request->mathanswer;
        $math->category_id = $request->category;
        $math->creator_user_id = $user->id;
        $math->updater_user_id = $user->id;
        $math->save();

        return redirect(route('maths.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Math  $math
     * @return \Illuminate\Http\Response
     */
    public function show(Math $math)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Math  $math
     * @return \Illuminate\Http\Response
     */
    public function edit(Math $math)
    {
        //
        $categories = Category::all();

        return view('math.edit', compact('categories', 'math'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Math  $math
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Math $math)
    {
        $user = Auth::user();
        //
        $validatedData = $request->validate([
            'category' => 'required',
            'mathlevel' => 'required',
            'mathquestion' => 'required|max:255',
            'mathanswer' => 'required|max:255',
        ]);

        $math->level = $request->mathlevel;
        $math->question = $request->mathquestion;
        $math->answer = $request->mathanswer;
        $math->category_id = $request->category;
        $math->updater_user_id = $user->id;
        $math->save();

        return redirect(route('maths.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Math  $math
     * @return \Illuminate\Http\Response
     */
    public function destroy(Math $math)
    {
        //
    }

    /**
     * ajax call to fetch math id data
     */
    public function question(Request $request) {
        $mathId = $request->id;

        $math = Math::find($mathId);

        $math->question = fix_equation($math->question);
        $math->answer = fix_equation($math->answer);

        return response()->json(['question'=>$math->question, 'id'=>$math->id, 'answer' => $math->answer]);
    }
    /**
     * ajax call to fetch math id data
     */
    public function previewQuestion(Request $request) {
        $number = $request->question;

        $number = fix_equation($number);

        return response()->json(['answer'=>$number]);
    }
}
