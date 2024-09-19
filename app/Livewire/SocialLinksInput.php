<?php

namespace App\Livewire;

use Livewire\Component;

class SocialLinksInput extends Component
{
    public $social_links = [];

    public function mount($merchant = null)
    {
        if ($merchant && $merchant->social_links) {
            // Format data sosial links dari JSON ke array of arrays
            $formattedSocialLinks = [];
            foreach ($merchant->social_links as $platform => $link) {
                $formattedSocialLinks[] = ['platform' => $platform, 'link' => $link];
            }
            $this->social_links = old('social_links', $formattedSocialLinks);
        } else {
            $this->social_links = old('social_links', [['platform' => '', 'link' => '']]);
        }
    }

    public function addSocialLink()
    {
        $this->social_links[] = ['platform' => '', 'link' => ''];
    }

    public function removeSocialLink($index)
    {
        unset($this->social_links[$index]);
        $this->social_links = array_values($this->social_links); // Reindex array
    }

    public function render()
    {
        return view('livewire.social-links-input');
    }
}
