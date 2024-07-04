<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $id = Crypt::decryptString($id);
        $user = user::find($id);
        return view("profile", compact('user'));
    }

    public function update(Request $request, $id)
    {
        try {
            // dd($request->file('image'));
            $id = Crypt::decryptString($id);
            $user = user::find($id);

            if (Hash::check($request->password, $user->password) && ($request->passwordBaru == null && $request->konfirmasiPassword == null) ) {

                $user->name = $request->nama;

                if ( auth()->user()->path_image == null && $request->image != null ) 
                {
                    $file = $request->file('image');
                    $path = $file->storeAs('public/Foto-User', uniqid() . '_' . $file->getClientOriginalName());

                    $storagePath = str_replace('public/', 'storage/', $path);

                    $user->path_image = $storagePath;

                } else if ( $request->image != null )
                {
                    $fileImageOld = $user->path_image;
                    if (file_exists($fileImageOld)) {
                        unlink($fileImageOld);
                    }

                    $file = $request->file('image');
                    $path = $file->storeAs('public/Foto-User', uniqid() . '_' . $file->getClientOriginalName());

                    $storagePath = str_replace('public/', 'storage/', $path);

                    $user->path_image = $storagePath;
                }

                $user->save();
                
                return redirect()->back()->with("berhasil", "Akun Berhasil Di Update");

            }else if ((Hash::check($request->password, $user->password) && ($request->passwordBaru != null && $request->konfirmasiPassword != null)) && ($request->passwordBaru == $request->konfirmasiPassword)) {

                $user->name = $request->nama;

                if ( auth()->user()->path_image == null && $request->image != null ) 
                {
                    $file = $request->file('image');
                    $path = $file->storeAs('public/Foto-User', uniqid() . '_' . $file->getClientOriginalName());

                    $storagePath = str_replace('public/', 'storage/', $path);

                    $user->path_image = $storagePath;

                } else if ( $request->image != null )
                {
                    $fileImageOld = $user->path_image;
                    if (file_exists($fileImageOld)) {
                        unlink($fileImageOld);
                    }

                    $file = $request->file('image');
                    $path = $file->storeAs('public/Foto-User', uniqid() . '_' . $file->getClientOriginalName());

                    $storagePath = str_replace('public/', 'storage/', $path);

                    $user->path_image = $storagePath;
                }
                
                $user->password = bcrypt($request->passwordBaru);
                $user->save();

                return redirect()->back()->with("berhasil", "Akun Berhasil Di Update");

            }else {

                return redirect()->back()->with("gagal", "Terjadi kesalahan pada sistem dengan kode : 500")->withInput();

            }
        }
        catch (\Exception $e) {
            return redirect()->back()->with("error", "Terjadi kesalahan pada sistem dengan kode : 500")->withInput();
        }
    }
}
