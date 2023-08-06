<?php

namespace App\Http\Livewire\SourceControls;

use App\Actions\SourceControl\ConnectSourceControl;
use App\Models\SourceControl;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Gitlab extends Component
{
    public string $token;

    public ?string $url;

    public function mount(): void
    {
        $this->url = request()->input('redirect') ?? null;

        $this->token = SourceControl::query()
            ->where('provider', \App\Enums\SourceControl::GITLAB)
            ->first()?->access_token ?? '';
    }

    public function connect(): void
    {
        app(ConnectSourceControl::class)->connect(\App\Enums\SourceControl::GITLAB, $this->all());

        session()->flash('status', 'gitlab-updated');

        if ($this->url) {
            $this->redirect($this->url);
        }
    }

    public function render(): View
    {
        return view('livewire.source-controls.gitlab', [
            'sourceControl' => SourceControl::query()
                ->where('provider', \App\Enums\SourceControl::GITLAB)
                ->first(),
        ]);
    }
}