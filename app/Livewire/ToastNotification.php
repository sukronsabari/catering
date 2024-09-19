<?php

namespace App\Livewire;

use Livewire\Component;

class ToastNotification extends Component
{
    public string $message;
    public string $type;
    public int $duration;

    public function mount($type = 'success', $message = 'Action successfull', $duration = 4000)
    {
        $this->type = $type;
        $this->message = $message;
        $this->duration = $duration;
    }

    public function render()
    {
        return view('livewire.toast-notification');
    }
}
