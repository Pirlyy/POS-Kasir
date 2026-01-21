@extends('layouts.app')

@section('content_title', 'Data Users')

@section('content')
<div class="card">
    <div class="p-2 d-flex justify-content-between border">
        <h4 class="h5">Data User</h4>
        <div>
            <x-user.form-user />
        </div>
    </div>

    <div class="card-body">
        <x-alert :errors="$errors" />
        <table class="table table-sm" id="table2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Email</th>
                    <th>Nama Users</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-user.form-user :id="$user->id" />
                               <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline"
                                    onsubmit="return confirm('Apakah anda yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger mx-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                <x-user.reset-password :id="$user->id" />


                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
