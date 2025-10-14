<?php

namespace App\View\Composers;

use App\Models\Client;
use Illuminate\View\View;

class ClientComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $activeClients = Client::getActive();
        $view->with('activeClients', $activeClients);
    }
}
