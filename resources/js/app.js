import * as bootstrap from 'bootstrap';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.bootstrap = bootstrap;
window.Chart = Chart;

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-admin-sidebar]');
    const backdrop = document.querySelector('[data-admin-sidebar-backdrop]');
    const toggleButtons = document.querySelectorAll('[data-admin-sidebar-toggle]');

    const closeSidebar = () => {
        sidebar?.classList.remove('show');
        backdrop?.classList.remove('show');
    };

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            sidebar?.classList.toggle('show');
            backdrop?.classList.toggle('show');
        });
    });

    backdrop?.addEventListener('click', closeSidebar);

    // Dynamic repeatable rows (product specifications / variations).
    document.querySelectorAll('[data-repeater]').forEach((repeater) => {
        const body = repeater.querySelector('[data-repeater-body]');
        const template = repeater.querySelector('template');
        const addButton = repeater.querySelector('[data-repeater-add]');

        // Use a monotonically increasing index so removing then re-adding rows
        // never reuses an array key still referenced elsewhere in the form.
        let nextIndex = parseInt(
            repeater.dataset.repeaterStartIndex ?? body.querySelectorAll('[data-repeater-row]').length,
            10
        );

        addButton?.addEventListener('click', () => {
            const html = template.innerHTML.replaceAll('__INDEX__', nextIndex);
            nextIndex += 1;
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            body.appendChild(wrapper.firstElementChild);
        });

        body.addEventListener('click', (event) => {
            const removeTrigger = event.target.closest('[data-repeater-remove]');
            if (!removeTrigger) return;

            const row = removeTrigger.closest('[data-repeater-row]');
            const removedIdInput = row?.querySelector('[data-repeater-id]');

            if (removedIdInput && removedIdInput.value) {
                const hiddenFieldName = repeater.dataset.repeaterRemovedField;
                if (hiddenFieldName) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = `${hiddenFieldName}[]`;
                    hidden.value = removedIdInput.value;
                    repeater.appendChild(hidden);
                }
            }

            row?.remove();
        });
    });

    // Gold Purity field visibility toggle, scoped per variation row.
    document.body.addEventListener('change', (event) => {
        if (!event.target.matches('[data-metal-select]')) return;

        const row = event.target.closest('[data-repeater-row]');
        const purityWrap = row?.querySelector('[data-purity-wrap]');
        if (!purityWrap) return;

        purityWrap.classList.toggle('d-none', event.target.value !== 'gold');
    });

    // Image preview for file inputs.
    document.querySelectorAll('[data-image-preview-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const previewId = input.dataset.imagePreviewInput;
            const preview = document.getElementById(previewId);
            const file = input.files?.[0];

            if (preview && file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            }
        });
    });

    // Mark an existing (already saved) image for removal on submit.
    document.body.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-remove-existing-image]');
        if (!trigger) return;

        const fieldName = trigger.dataset.removeExistingImage;
        const id = trigger.dataset.imageId;
        const wrapper = trigger.closest('[data-existing-image]');

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = `${fieldName}[]`;
        hidden.value = id;
        trigger.closest('form')?.appendChild(hidden);

        wrapper?.remove();
    });

    // Auto-dismiss flash alerts.
    document.querySelectorAll('[data-auto-dismiss]').forEach((alert) => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert)?.close(), 5000);
    });
});
