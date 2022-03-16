<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Auth;
use DB;
use Webp;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public function viewList(Request $request) {
        $list = DB::table('fumaco_payment_method')
            ->when($request->q, function ($query) use ($request) {
                return $query->where('payment_method_name', 'LIKE', "%".$request->q."%");
            })
            ->paginate(10);

        return view('backend.payment_method.list', compact('list'));
    }

    public function savePaymentMethod(Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'payment_method_name' => 'required',
            ]);

            $existing = DB::table('fumaco_payment_method')->where('payment_method_name', $request->payment_method_name)
                ->where('issuing_bank', $request->issuing_bank)->exists();

            if ($existing) {
                return redirect()->back()->with('error', 'Record already exists.');
            }

            $image_name = null;
            if($request->hasFile('payment_icon')){
                $image = $request->file('payment_icon');

                $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
                $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

                $destinationPath = storage_path('/app/public/payment_method/');

                $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
			    $extension = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);

                $filename = Str::slug($filename, '-');

                $image_name = $filename.".".$extension;

                if(!in_array($extension, $allowed_extensions)){
                    return redirect()->back()->with('error', $extension_error);
                }

                $webp = Webp::make($request->file('payment_icon'));

                if ($webp->save(storage_path('/app/public/payment_method/'.$filename.'.webp'))) {
                    $image->move($destinationPath, $image_name);
                }
            }

            $data = [
                'payment_method_name' => $request->payment_method_name,
                'payment_type' => $request->payment_type,
                'issuing_bank' => $request->issuing_bank,
                'remarks' => $request->remarks,
                'image' => $image_name,
                'show_image' => $request->show_icon ? 1 : 0,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_payment_method')->insert($data);

            DB::commit();

            return redirect()->back()->with('success', 'Payment method has been added.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updatePaymentMethod($id, Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'payment_method_name' => 'required',
                'payment_type' => 'required',
                'issuing_bank' => 'required',
            ]);

            $data = [
                'payment_method_name' => $request->payment_method_name,
                'payment_type' => $request->payment_type,
                'issuing_bank' => $request->issuing_bank,
                'remarks' => $request->remarks,
                'is_enabled' => $request->is_enabled ? 1 : 0,
                'show_image' => $request->show_icon ? 1 : 0,
                'created_by' => Auth::user()->username
            ];

            if ($request->payment_icon) {
                $image_name = null;
                if($request->hasFile('payment_icon')){
                    $image = $request->file('payment_icon');
    
                    $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
                    $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";
    
                    $destinationPath = storage_path('/app/public/payment_method/');
    
                    $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
    
                    $filename = Str::slug($filename, '-');
    
                    $image_name = $filename.".".$extension;
    
                    if(!in_array($extension, $allowed_extensions)){
                        return redirect()->back()->with('error', $extension_error);
                    }
    
                    $webp = Webp::make($request->file('payment_icon'));
    
                    if ($webp->save(storage_path('/app/public/payment_method/'.$filename.'.webp'))) {
                        $image->move($destinationPath, $image_name);
                    }

                    $has_existing_image = DB::table('fumaco_payment_method')->where('payment_method_id', $id)->whereNotNull('image')->first();
                    if ($has_existing_image) {
                        $existing_image_name = explode('.', $has_existing_image->image)[0];
                        $jpg_path = storage_path('app/public/payment_method/'.$has_existing_image->image);
                        $webp_path = storage_path('app/public/payment_method/'.$existing_image_name.'.webp');
            
                        if (file_exists($jpg_path)) {
                            unlink($jpg_path);
                        }
            
                        if (file_exists($webp_path)) {
                            unlink($webp_path);
                        }
                    }

                    $data['image'] = $image_name;
                }
            }

            DB::table('fumaco_payment_method')->where('payment_method_id', $id)->update($data);

            DB::commit();

            return redirect()->back()->with('success', 'Payment method has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deletePaymentMethod($id) {
        $has_existing_image = DB::table('fumaco_payment_method')->where('payment_method_id', $id)->whereNotNull('image')->first();
        if ($has_existing_image) {
            $existing_image_name = explode('.', $has_existing_image->image)[0];
            $jpg_path = storage_path('app/public/payment_method/'.$has_existing_image->image);
            $webp_path = storage_path('app/public/payment_method/'.$existing_image_name.'.webp');

            if (file_exists($jpg_path)) {
                unlink($jpg_path);
            }

            if (file_exists($webp_path)) {
                unlink($webp_path);
            }
        }

        DB::table('fumaco_payment_method')->where('payment_method_id', $id)->delete();

        return redirect()->back()->with('success', 'Payment method has been deleted.');
    }
}