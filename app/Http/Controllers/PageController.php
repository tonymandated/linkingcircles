<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home');
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function offerings(): View
    {
        return view('pages.offerings');
    }

    public function programs(): View
    {
        return view('pages.programs');
    }

    public function team(): View
    {
        return view('pages.team');
    }

    public function blog(): View
    {
        return view('pages.blog');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function programsTeachers(): View
    {
        return view('pages.programs_teachers');
    }

    public function projectTeci(): View
    {
        return view('pages.project_teci');
    }

    public function dashboard(): View
    {
        return view('dashboard');
    }
}
