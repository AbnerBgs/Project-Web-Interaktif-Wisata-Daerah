<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Category;
use App\Models\Facility;
use App\Models\DestinationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Admin CRUD Destinasi.
 * Termasuk: upload foto, pin lokasi di Leaflet Map, manajemen fasilitas.
 */
class AdminDestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('category')
            ->latest()
            ->paginate(15);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();
        return view('admin.destinations.create', compact('categories', 'facilities'));
    }

    public function store(Request $request)
    {
        $data = $this->validateDestination($request);
        $data['slug']    = Str::slug($request->name);
        $data['user_id'] = auth()->id();

        // Upload thumbnail utama
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('destinations/thumbnails', 'public');
        }

        $destination = Destination::create($data);

        // Simpan fasilitas (pivot table)
        if ($request->filled('facilities')) {
            $destination->facilities()->sync($request->facilities);
        }

        // Simpan galeri foto tambahan
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $path = $file->store('destinations/gallery', 'public');
                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path'     => $path,
                    'sort_order'     => $i,
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function edit(Destination $destination)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();
        $destination->load(['images', 'facilities']);
        return view('admin.destinations.edit', compact('destination', 'categories', 'facilities'));
    }

    public function update(Request $request, Destination $destination)
    {
        $data = $this->validateDestination($request, $destination->id);

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama
            if ($destination->thumbnail) {
                Storage::disk('public')->delete($destination->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('destinations/thumbnails', 'public');
        }

        $destination->update($data);
        $destination->facilities()->sync($request->facilities ?? []);

        // Tambah foto galeri baru (jika ada)
        if ($request->hasFile('gallery')) {
            $lastOrder = $destination->images()->max('sort_order') ?? -1;
            foreach ($request->file('gallery') as $i => $file) {
                $path = $file->store('destinations/gallery', 'public');
                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path'     => $path,
                    'sort_order'     => $lastOrder + $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination)
    {
        // Hapus semua file foto dari storage
        if ($destination->thumbnail) {
            Storage::disk('public')->delete($destination->thumbnail);
        }
        foreach ($destination->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $destination->delete();
        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }

    /**
     * Aturan validasi destinasi (digunakan store & update).
     */
    private function validateDestination(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:200',
            'name_en'        => 'nullable|string|max:200',
            'description'    => 'required|string',
            'description_en' => 'nullable|string',
            'address'        => 'required|string|max:300',
            'region'         => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            // Koordinat latitude/longitude dari pin di Leaflet Map
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
            'thumbnail'      => 'nullable|image|max:2048',
            'gallery.*'      => 'nullable|image|max:2048',
            'open_hours'     => 'nullable|string|max:100',
            'ticket_price'   => 'nullable|string|max:100',
            'ticket_price_en'=> 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'website'        => 'nullable|url',
            'is_popular'     => 'boolean',
            'is_active'      => 'boolean',
            'facilities'     => 'nullable|array',
            'facilities.*'   => 'exists:facilities,id',
        ]);
    }
}
