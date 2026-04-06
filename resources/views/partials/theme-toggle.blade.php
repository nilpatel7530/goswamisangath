{{-- Theme Toggle Switch Component --}}
<div class="theme-toggle-wrapper" style="display: flex; align-items: center;">
    <label class="theme-toggle" title="{{ __('Toggle Theme') }}" style="position: relative; display: inline-block; width: 60px; height: 30px; cursor: pointer; margin: 0;">
        <input type="checkbox" class="theme-toggle-input" aria-label="{{ __('Toggle dark/light theme') }}" style="position: absolute; opacity: 0; width: 60px; height: 30px; margin: 0; padding: 0; border: none; appearance: none; -webkit-appearance: none; -moz-appearance: none; cursor: pointer; z-index: 10; pointer-events: auto;">
        <span class="theme-toggle-slider" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: block; width: 100%; height: 100%; background-color: #d1d5db; border-radius: 30px; transition: background-color 0.3s ease, transform 0.3s ease; cursor: pointer; box-sizing: border-box;"></span>
    </label>
</div>

<style>
/* Override any Tailwind or other CSS */
.theme-toggle input[type="checkbox"] {
    position: absolute !important;
    opacity: 0 !important;
    width: 60px !important;
    height: 30px !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    cursor: pointer !important;
    z-index: 2 !important;
}

.theme-toggle-slider:before {
    content: '' !important;
    position: absolute !important;
    bottom: 3px !important;
    left: 3px !important;
    height: 24px !important;
    width: 24px !important;
    background-color: #ffffff !important;
    border-radius: 50% !important;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
    display: block !important;
}

.theme-toggle-input:checked + .theme-toggle-slider {
    background-color: #ec3713 !important;
}

.theme-toggle-input:checked + .theme-toggle-slider:before {
    transform: translateX(30px) !important;
}

[data-theme="dark"] .theme-toggle-slider {
    background-color: #374151 !important;
}

.theme-toggle:hover .theme-toggle-slider:before {
    box-shadow: 0 0 0 8px rgba(0, 0, 0, 0.1) !important;
}

.theme-toggle:hover .theme-toggle-input:checked + .theme-toggle-slider:before {
    box-shadow: 0 0 0 8px rgba(236, 55, 19, 0.2) !important;
}
</style>

