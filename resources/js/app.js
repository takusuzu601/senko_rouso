

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('audioPlayer', {
        open: false,
        src: null,
        title: '',

        show(src, title) {
            this.src = src;
            this.title = title;
            this.open = true;
        },

        close() {
            this.open = false;
            this.src = null;
        },
    });
});

Alpine.start();
