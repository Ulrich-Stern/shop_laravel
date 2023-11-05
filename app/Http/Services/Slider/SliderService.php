<?php

namespace App\Http\Services\Slider;


use Illuminate\Support\Facades\Session;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;


class SliderService
{

    public function get() {
        return Slider::orderByDesc('id')->paginate(15);
    }

    public function insert($request) {
        try {
            Slider::create($request->input());
            $request->session()->flash('success', 'Thêm Slider thành công');
        } catch (\Exception $err) {
            $request->session()->flash('error', 'Thêm Slider lỗi');
            \Log::info($err->getMessage());

            return false;
        }

        return true;
    }

    public function update($request, $slider) {
        try {
            $slider->fill($request->input());
            $slider->save();
            $request->session()->flash('success', 'Cập nhật Slider thành công');
        } catch (\Exception $err) {
            $request->session()->flash('error', 'Cập nhật Slider lỗi');
            \Log::info($err->getMessage());

            return false;
        }
        
        return true;
    }

    public function destroy($request) {
        $slider = Slider::where('id', $request->input('id'))->first();
        if ($slider) {
            $path = str_replace('storage', 'public', $slider->thumb);
            Storage::delete($path);
            $slider->delete();
            return true;
        }

        return false;
    }

    public function show() {
        return Slider::where('active', 1)->orderByDesc('sort_by')->get();
    }
}