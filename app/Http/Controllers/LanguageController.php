<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller untuk mengganti bahasa (Indonesia <-> English).
 * Locale disimpan di session sehingga persisten selama sesi.
 */
class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, ['id', 'en'])) {
            abort(400, 'Locale tidak didukung.');
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        // Kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Bahasa berhasil diubah.');
    }
}
