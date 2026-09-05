<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.frontend')]
class HomePage extends Component
{
    #[Validate('required|string')]
    public string $trackInvoice = '';

    public function goTrack(): void
    {
        $this->validate();

        $this->redirectRoute('order.track', ['invoice' => trim($this->trackInvoice)]);
    }

    public function render()
    {
        return view('welcome');
    }
}
