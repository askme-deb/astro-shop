<?php

namespace App\Traits;

/**
 * Trait WithSweetAlert
 *
 * Provides a reusable method to trigger SweetAlert toasts from controllers or Livewire components.
 */
trait WithSweetAlert
{
    /**
     * Show a reusable SweetAlert toast.
     *
     * @param string $title
     * @param string $message
     * @param string $icon
     * @return void
     */
    public function toast($title, $message = '', $icon = 'success')
    {
        // For Livewire: $this->dispatchBrowserEvent('swal', [...])
        // For Inertia: Inertia::share([...])
        // For plain controllers: session()->flash([...])
        // Here, we use a JS helper for maximum compatibility
        if (method_exists($this, 'js')) {
            $this->js(<<<JS
                Swal.fire({
                    icon: '{$icon}',
                    title: '{$title}',
                    text: '{$message}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            JS);
        }
    }
}
