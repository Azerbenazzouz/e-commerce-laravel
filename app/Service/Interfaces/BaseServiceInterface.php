<?php

namespace App\Service\Interfaces;

use Illuminate\Http\Request;

interface BaseServiceInterface {
    public function getList();
    public function paginate(Request $request, string $recordType);
    public function save(Request $request, $id = null, string $method);
    public function delete(Request $request, int $id);
    public function deleteMultiple(array $ids);
    public function show(int $id);
}