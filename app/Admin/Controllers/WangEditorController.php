<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WangEditorController extends Controller
{

    public function images(Request $request)
    {
        $data = $this->validate($request, [
            'images.*' => 'required|image|mimes:jpeg',
        ], [], [
            'images.*' => '图片',
        ]);

        $paths = array();
        foreach ($data['images'] as $item)
        {
            $paths[] = \Storage::url(\Storage::disk('public')->putFile('images', $item));
        }

        return response()->json([
            'errno' => 0,
            'data' => $paths
        ]);
    }

}
