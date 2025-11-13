import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";

// 1. Daftarkan plugin persist
Alpine.plugin(persist);

// 2. Definisikan state global untuk Tema (Dark Mode)
Alpine.store('theme', {
    // Gunakan persist untuk menyimpan di localStorage
    isDark: Alpine.$persist(false).as('theme_isDark'),

    toggle() {
        this.isDark = !this.isDark;
    }
});


// 3. Definisikan state global untuk Sidebar
Alpine.store('sidebar', {
    open: false, // Kondisi awal sidebar
    pinned: false,

    togglePin() {
        // Balikkan status pinned
        this.pinned = !this.pinned; 
        // Jika dipin, paksa terbuka. Jika dilepas, biarkan tertutup (hover akan buka jika perlu)
        this.open = this.pinned; 
    },
    // Fungsi hover hanya buka jika tidak di-pin
    hoverOpen() {
        if (!this.pinned) {
            this.open = true;
        }
    },
    // Fungsi hover hanya tutup jika tidak di-pin
    hoverClose() {
         if (!this.pinned) {
            this.open = false;
        }
    }
});

// 4. Tetapkan Alpine ke window
window.Alpine = Alpine;

// 5. Mulai Alpine
Alpine.start();

// Inisialisasi Flatpickr setelah halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    flatpickr(".datepicker", {
        mode: "single",         // Hanya pilih satu tanggal
        dateFormat: "Y-m-d",    // Format standar untuk database
        altInput: true,         // Tampilkan format yg ramah utk manusia
        altFormat: "F j, Y",    // Format yg ramah (mis: Oktober 22, 2025)
        locale: Indonesian,     // Gunakan Bahasa Indonesia

        // (Opsional: Salin SVG arrow dari bundle.js Anda agar konsisten)
        prevArrow: '<svg class="stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.25 6L9 12.25L15.25 18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        nextArrow: '<svg class="stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.75 19L15 12.75L8.75 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    });
});