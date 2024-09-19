<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\User;
use Livewire\Component;

class UserSearchAutoComplete extends Component
{
    public $search = '';
    public $selectedUser = null;
    public $highlightIndex = 0;
    public $users = [];

    public function mount($userId = null)
    {
        $this->selectedUser = old('user_id', $userId);

        if ($this->selectedUser) {
            $user = User::find($this->selectedUser);
            if ($user) {
                $this->search = "{$user->name} ({$user->email})";
            }
        }
    }

    public function updated()
    {
        $this->highlightIndex = 0;
        $this->selectedUser = null;

        if (strlen($this->search) > 1) {
            $this->users = User::where('role', '!=', UserRole::Admin)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->take(5)
                ->get();
        } else {
            $this->users = [];
        }
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->users) - 1) {
            $this->highlightIndex = 0;
            return;
        }

        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->users) - 1;
            return;
        }

        $this->highlightIndex--;
    }

    public function selectUser($index = null)
    {
        if ($index !== null) {
            $user = $this->users[$index] ?? null;
        } else {
            $user = $this->users[$this->highlightIndex] ?? null;
        }

        if ($user) {
            $this->selectedUser = $user->id;
            $this->search = "{$user->name} ({$user->email})";
            $this->users = [];
        }
    }

    public function render()
    {
        return view('livewire.user-search-auto-complete');
    }
}
