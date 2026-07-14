<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    public function create(Request $request)
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:customers,email',
            'mobile_number' => 'required|string|unique:customers,mobile_number',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,svg,png,gif,webp|max:5048',
            'password' => 'required|string',
        ]);

        $data = $request->only(['name', 'email', 'mobile_number', 'password', 'profile_image']);

        if($request->hasFile('profile_image'))
            {
                $data['profile_image'] = $request->file('profile_image')->store('profile', 'public');
            }
        Customer::create($data);
        return redirect()->route('admin.customers.index')->with('success', 'Customer Created Success');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function udpate(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:customers,email,' . $id,
            'mobile_number' => 'required|string|unique:customers,mobile_number,' . $id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,svg,png,gif,webp|max:5048',
            'password' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'email', 'mobile_number', 'profile_image']);

        if($request->hasFile('profile_image'))
            {
                if($customer->profile_image && Storage::disk('public')->exists($customer->profile_image)){
                    Storage::disk('public')->delete($customer->profile_image);
                }
            $data['profile_image'] = $request->file('profile_image')->store('profile', 'public');
            }
    Customer::update($data);
    return redirect()->route('admin.customers.index')->with('success', 'customer updated');
    }

    public function destroy(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        if($customer->profile_image && Storage::disk('public')->exists($customer->profile_image))
            {
                Storage::disk('public')->delete($customer->profile_image);
            }
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer Deleted');
    }
}
