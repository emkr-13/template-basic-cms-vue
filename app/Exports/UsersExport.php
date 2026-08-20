<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Collection $users) {}

    public function headings(): array
    {
        return ['Name', 'Email', 'Role', 'Status', 'Created At'];
    }

    public function collection(): Collection
    {
        return $this->users->map(fn ($user): array => [
            $user->name,
            $user->email,
            $user->roles->pluck('name')->join(', '),
            $user->status->value,
            $user->created_at->format('Y-m-d H:i'),
        ]);
    }
}
