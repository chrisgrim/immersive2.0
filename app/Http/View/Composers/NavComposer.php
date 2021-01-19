<?php

namespace App\Http\View\Composers;

use Illuminate\Contracts\View\View;

class NavComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = auth()->user();
        $view->with('user', $user);
    }
}