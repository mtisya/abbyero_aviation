<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AircraftPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'name',
        'category',
        'description',
        'quantity',
        'price',
        'status',
        'image',   // ✅ Add this so the image path is stored
    ];

    public function add(Request $request, $id)
    {
        $part = AircraftPart::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $part->name,
                "quantity" => 1,
                "price" => $part->price,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Part added to cart successfully!');
    }
}
