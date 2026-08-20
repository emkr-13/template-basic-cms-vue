<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans;font-size:11px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#f3f4f6}</style></head>
<body>
<h1>Daftar User</h1>
<table><thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Dibuat</th></tr></thead><tbody>
@foreach ($users as $user)
<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->pluck('name')->join(', ') }}</td><td>{{ $user->status->value }}</td><td>{{ $user->created_at->format('d M Y H:i') }}</td></tr>
@endforeach
</tbody></table>
</body>
</html>
