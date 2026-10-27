<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
class AreaController extends Controller
{
    public function index()
    {
        return response()->json(Area::all());
    }
    public function show($id) //si se pasa $id se utiliza la comentada
    {
        $area = Area::findOrFail($id);
        // $category = Category::with(['posts.user'])->findOrFail($id);
        // $category = Category::with(['posts'])->findOrFail($id);
        return response()->json($area);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('imagen');

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);

        $resultado = $cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => 'areas',
                'resource_type' => 'image',
            ]
        );

        $area = Area::create([
            'name' => $request->name,
            'imagen_url' => $resultado['secure_url'],
            'imagen_public_id' => $resultado['public_id'],
        ]);

        return response()->json([
            'message' => 'Area creada correctamente',
            'data' => $area,
        ], 201);
    }
    public function update(Request $request, int $id)
    {
        $area = Area::findOrFail($id);
        $area->update($request->validate(['name' => ['required', 'string', 'max:255']]));

        return response()->json($area);
    }

    public function destroy(int $id)
    {
        Area::findOrFail($id)->delete();

        return response()->json(['message' => 'Área eliminada correctamente.']);
    }
}
