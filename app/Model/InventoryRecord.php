<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryRecord extends Model
{
    use SoftDeletes;

    public function material()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }

    public function fix()
    {
        $inventory = Inventory::find($this->inventory_id);

        $stocksIn = Stock::where('inventory_id', $inventory->id)
            ->where('material_id', $this->material_id)
            ->where('way', 1)
            ->whereIn('type', [10, 12])
            ->sum('amount');
        $stocksOut = Stock::where('inventory_id', $inventory->id)
            ->where('material_id', $this->material_id)
            ->where('way', 2)
            ->whereIn('type', [10, 12])
            ->sum('amount');

        return $stocksIn - $stocksOut;
    }

    public function least()
    {
        return $this->original_inventory - $this->physical_inventory + $this->fix();
    }

    public function stocks()
    {
        $inventory = Inventory::find($this->inventory_id);
        $stocks = Stock::where('inventory_id', $inventory->id)
            ->where('material_id', $this->material_id)
            ->whereIn('type', [10, 12])
            ->get();

        return $stocks;
    }

}
