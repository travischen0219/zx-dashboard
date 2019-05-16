<?php

namespace App\Model;

use App\Model\Supplier;
use Illuminate\Database\Eloquent\Model;

class Account_payable extends Model
{
    public function supplier_name()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier');
    }

    public function material_name()
    {
        return $this->hasOne(Material::class, 'id', 'supplier');
    }

    public function getTotalPayAttribute()
    {
        $total = 0;
        $materials = unserialize($this->materials);

        $count = count($materials['material']);

        for ($i = 0; $i < $count; $i++) {
            $total += ($materials['materialAmount'][$i] * $materials['materialPrice'][$i]);
        }

        return number_format($total, 2);
    }

    static public function getTotalPayBySerialData($searialData)
    {
        $total = 0;
        $materials = unserialize($searialData);

        $count = count($materials['material']);

        for ($i = 0; $i < $count; $i++) {
            $total += ($materials['materialAmount'][$i] * $materials['materialPrice'][$i]);
        }

        return $total;
    }

    public function getMaterialsDetailAttribute()
    {
        $total = 0;
        $materials = unserialize($this->materials);

        $count = count($materials['material']);

        $details = [];

        for ($i = 0; $i < $count; $i++) {
            $details[$i]['id'] = $materials['material'][$i];
        }

        return $details;
    }

    static public function getUnpays()
    {
        $data = Account_payable::where('account_payables.delete_flag', 0)
            ->where('account_payables.status', 1)
            ->get();

        return $data;
    }

    static public function getUnpayBySupplier($supplierID)
    {
        $data = Account_payable::where('account_payables.delete_flag', 0)
            ->where('account_payables.status', 1)
            ->where('account_payables.supplier', $supplierID)
            ->get();

        return $data;
    }

}
