<?php

namespace App\Service\Interfaces;

use Illuminate\Http\Request;

interface BaseServiceInterface {
    public function getList();
    public function paginate(Request $request);
    public function save(Request $request, $id = null);
    public function delete(int $id);
}