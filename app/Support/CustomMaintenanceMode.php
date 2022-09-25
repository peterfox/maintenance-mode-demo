<?php

namespace App\Support;

use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Filesystem\FilesystemManager;

class CustomMaintenanceMode implements MaintenanceMode
{
    public function __construct(protected FilesystemManager $manager, protected ?string $disk)
    {
    }

    public function activate(array $payload): void
    {
        $this->manager->disk($this->disk)->put('down.json', json_encode($payload));
    }

    public function deactivate(): void
    {
        $this->manager->disk($this->disk)->delete('down.json');
    }

    public function active(): bool
    {
        return $this->manager->disk($this->disk)->exists('down.json');
    }

    public function data(): array
    {
        return json_decode(
            $this->manager->disk($this->disk)->get('down.json'),
            associative: true,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
