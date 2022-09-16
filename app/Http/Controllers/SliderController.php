<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Slider;

class SliderController extends Controller
{
    public function addslider(){
        return view('admin.addslider');
    }

    public function sliders(){
        $sliders = Slider::All();

        return view('admin.sliders')->with('sliders', $sliders);
    }

    public function saveslider(Request $request){
        $this->validate($request, ['description1'=>'required',
                                   'description2' => 'required',
                                   'slider_image' => 'image|nullable|max:1999|required' ]);

            $fileNameWithExt = $request->file('slider_image')->getClientOriginalName();

             $ext = $request->file('slider_image')->getClientOriginalExtension();

             $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

             $fileNameToStore = $fileName.'_'.time().'.'.$ext;   
             
             $path = $request->file('slider_image')->storeAs('public/slider_images', $fileNameToStore);

        $slider = new Slider();
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        $slider->slider_image = $fileNameToStore;
        $slider->status = 1;
        $slider->save();

        return back()->with('status', 'Slider has been added!');
    }

    public function editslider($id){
        $slider = Slider::find($id);

        return view('admin.editslider')->with('slider', $slider);        
    }

    public function updateslider(Request $request){

        $this->validate($request, ['description1'=>'required',
                                   'description2' => 'required',
                                   'slider_image' => 'image|nullable|max:1999|required' ]);

        $slider = Slider::find($request->input('id'));

        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        $slider->save();

       if($request->hasfile('slider_image'))
        {
            $fileNameWithExt = $request->file('slider_image')->getClientOriginalName();

             $ext = $request->file('slider_image')->getClientOriginalExtension();

             $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

             $fileNameToStore = $fileName.'_'.time().'.'.$ext;   
             
             $path = $request->file('slider_image')->storeAs('public/slider_images', $fileNameToStore);

             Storage::delete('public/slider_image'. $slider->slider_image);

             $slider->slider_image = $fileNameToStore;
        }

       $slider->update(); 
       return redirect('/sliders')->with('status', 'Slider has been updated!');
    }

    public function deleteslider($id){
        $slider = Slider::find($id);

        if($slider->slider_image != 'noimage.jpg')
        {
           Storage::delete('public/slider_images'. $slider->slider_image);
        }
         
        $slider->delete();

        return back()->with('status', 'Slider has been deleted!'); 
    }

    public function activateslider($id)
    {
        $slider = Slider::find($id);

        $slider->status = 1;
        $slider->update();

        return back()->with('status', 'Slider has been activated!'); 
    }
    
    public function unactivateslider($id)
    {
        $slider = Slider::find($id);

        $slider->status = 0;
        $slider->update();

        return back()->with('status', 'Slider has been unactivated!'); 
    }
}
