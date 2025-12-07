<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Edit Account</title>
    <link rel="stylesheet" href="{{ asset('css/akun/editAkun.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header class="header">
    <a href="{{ url('/admin/Account') }}" style="color: inherit; text-decoration: none;">
        <i class="fa-solid fa-arrow-left back"></i>
    </a>
    
    <i class="fa-solid fa-user icon"></i>
    <h1>Edit Account</h1>
</header>

<main class="form-container">
    
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px; text-align: center;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editForm" class="form-box" action="{{ url('/admin/Update-Profile') }}" method="POST">
        @csrf
        @method('PUT') <div class="input-group">
            <input type="text" name="name" placeholder="Name" required value="{{ old('name', $admin->name) }}">
        </div>

        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required value="{{ old('username', $admin->username) }}">
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required value="{{ old('email', $admin->email) }}">
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password Baru (Kosongkan jika tidak ingin ubah)">
            <small style="font-size: 11px; color: #666; display:block; margin-top: 5px;">*Isi hanya jika ingin mengganti password</small>
        </div>

        <div class="gender-section">
            <label>Jenis Kelamin</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="gender" value="1" {{ (old('gender', $admin->gender) == 1) ? 'checked' : '' }} required> 
                    Laki-laki
                </label>
                
                <label>
                    <input type="radio" name="gender" value="0" {{ (old('gender', $admin->gender) == 0) ? 'checked' : '' }} required> 
                    Perempuan
                </label>
            </div>
        </div>

        <button type="submit" class="save-btn">Save Changes</button>
    </form>
</main>

</body>
</html>