<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('lms.learner.home', [
            'courseCount' => Course::query()->where('status', 'published')->count(),
        ]);
    }
}
