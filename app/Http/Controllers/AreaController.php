<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

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
        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);

        return response()->json(Area::create($data), 201);
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
