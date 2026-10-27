<?php

namespace App\Http\Controllers;

use App\Models\AccountCredential;
use Illuminate\Http\Request;

class AccountCredentialController extends Controller
{
    public function index()
    {
        $accounts = AccountCredential::latest()->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_akun' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:1000'],
            'keterangan' => ['nullable', 'string'],
        ]);

        AccountCredential::create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Data akun berhasil ditambahkan.');
    }

    public function edit(AccountCredential $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, AccountCredential $account)
    {
        $validated = $request->validate([
            'nama_akun' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:1000'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $account->update($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(AccountCredential $account)
    {
        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Data akun berhasil dihapus.');
    }
}