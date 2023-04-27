<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Support\Str;
use Exception;

class BankAccountController extends Controller
{
    public function list(Request $request) {
        $list = DB::table('fumaco_bank_account')
            ->when($request->q, function ($query) use ($request) {
                return $query->where('account_name', 'LIKE', "%".$request->q."%");
            })
            ->paginate(10);

        return view('backend.bank_accounts.list', compact('list'));
    }

    public function save(Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'bank_name' => 'required',
                'account_name' => 'required',
                'account_number' => 'required',
            ]);

            $existing = DB::table('fumaco_bank_account')->where('bank_name', $request->bank_name)
                ->where('account_name', $request->account_name)->exists();

            if ($existing) {
                return redirect()->back()->with('error', 'Record already exists.');
            }

            $image_name = null;
            if($request->hasFile('bank_logo')){
                $image = $request->file('bank_logo');

                $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
                $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

                $destinationPath = storage_path('/app/public/bank_account_images/');

                $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
			    $extension = strtolower(pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));

                $filename = Str::slug($filename, '-');

                $image_name = $filename.".".$extension;

                if(!in_array($extension, $allowed_extensions)){
                    return redirect()->back()->with('error', $extension_error);
                }
                
                $image->move($destinationPath, $image_name);
            }

            $data = [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'bank_logo' => $image_name,
                'remarks' => $request->remarks,
                'show_bank_logo' => $request->show_icon ? 1 : 0,
                'is_active' => $request->is_active ? 1 : 0,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_bank_account')->insert($data);

            DB::commit();

            return redirect()->back()->with('success', 'Bank account has been added.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function update($id, Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'bank_name' => 'required',
                'account_name' => 'required',
                'account_number' => 'required',
            ]);

            $data = [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'remarks' => $request->remarks,
                'show_bank_logo' => $request->show_icon ? 1 : 0,
                'is_active' => $request->is_active ? 1 : 0,
                'last_modified_by' => Auth::user()->username
            ];

            if ($request->bank_logo) {
                $image_name = null;
                if($request->hasFile('bank_logo')){
                    $image = $request->file('bank_logo');

    
                    $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
                    $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";
    
                    $destinationPath = storage_path('/app/public/bank_account_images/');
    
                    $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower(pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));
    
                    $filename = Str::slug($filename, '-');
    
                    $image_name = $filename.".".$extension;
    
                    if(!in_array($extension, $allowed_extensions)){
                        return redirect()->back()->with('error', $extension_error);
                    }
                    
                    $has_existing_image = DB::table('fumaco_bank_account')->where('bank_account_id', $id)->whereNotNull('bank_logo')->first();
                    if ($has_existing_image) {
                        $jpg_path = storage_path('app/public/bank_account_images/'.$has_existing_image->bank_logo);
            
                        if (file_exists($jpg_path)) {
                            unlink($jpg_path);
                        }
                    }

                    $image->move($destinationPath, $image_name);

                    $data['bank_logo'] = $image_name;
                }
            }

            DB::table('fumaco_bank_account')->where('bank_account_id', $id)->update($data);

            DB::commit();

            return redirect()->back()->with('success', 'Bank account has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function delete($id) {
        $has_existing_image = DB::table('fumaco_bank_account')->where('bank_account_id', $id)->whereNotNull('bank_logo')->first();
        if ($has_existing_image) {
            $jpg_path = storage_path('app/public/bank_account_images/'.$has_existing_image->bank_logo);

            if (file_exists($jpg_path)) {
                unlink($jpg_path);
            }
        }

        DB::table('fumaco_bank_account')->where('bank_account_id', $id)->delete();

        return redirect()->back()->with('success', 'Bank account has been deleted.');
    }
}