<?php

namespace App\Http\Services\Menu;

use App\Models\Menu;

class MenuService {

    public function getParent() {
        return Menu::where('parent_id', 0)->get();
    }

    public function getAll() {
        return Menu::orderbyDesc('id')->paginate(20);
    }

    public function show() {
        return Menu::select('name', 'id')
                    ->where('parent_id', 0)
                    ->orderbyDesc('id')
                    ->get();
    }

    public function create($request) {
        try {
            Menu::create([
                'name' => (string) $request->input('name'),
                'parent_id' => (int) $request->input('parent_id'),
                'description' => (string) $request->input('description'),
                'content' => (string) $request->input('content'),
                'active' => (int) $request->input('active')
            ]);
            $request->session()->flash('success', 'Tạo danh mục thành công');
        } catch (\Exception $err) {
            $request->session()->flash('error', $err->getMessage());
            return false;
        }
        return true;
    }

    public function update($request, $menu): bool {
        if ($menu->id != $request->input('parent_id')) {
            $menu->fill($request->input());
        }
        
        $menu->save();
        $request->session()->flash('success', 'Cập nhật thành công danh mục');
        return true;
    }

    public function destroy($request) {
        $id = (int) $request->input('id');
        $menu = Menu::where('id', $id)->first();

        if ($menu) {
            return Menu::where('id', $id)->orWhere('parent_id', $id)->delete();
        }

        return false; 
    }

    public function getId($id) {
        return Menu::where('id', $id)->where('active', 1)->firstOrFail();
    }

    public function getProduct($menu, $request) {
        $query = $menu->products()->select('id','name','price','price_sale','thumb')
                    ->where('active', 1);
        if ($request->input('price')) {
            $query->orderBy('price', $request->input('price'));
        }
        return $query->orderbyDesc('id') 
                    ->paginate(12);
    }
}