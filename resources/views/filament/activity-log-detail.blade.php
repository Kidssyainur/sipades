@php
    /** @var array $perubahan */
    $attributes = $perubahan['attributes'] ?? [];
    $old = $perubahan['old'] ?? [];
@endphp

<div class="space-y-3 text-sm">
    @if (empty($attributes))
        <p class="text-gray-500">Tidak ada rincian perubahan.</p>
    @else
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th class="py-2 pr-4">Atribut</th>
                    <th class="py-2 pr-4">Sebelum</th>
                    <th class="py-2">Sesudah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($attributes as $key => $value)
                    <tr>
                        <td class="py-2 pr-4 font-medium text-gray-700 dark:text-gray-200">{{ $key }}</td>
                        <td class="py-2 pr-4 text-gray-500">
                            {{ is_scalar($old[$key] ?? null) ? ($old[$key] ?? '—') : json_encode($old[$key] ?? null) }}
                        </td>
                        <td class="py-2 text-gray-900 dark:text-gray-100">
                            {{ is_scalar($value) ? $value : json_encode($value) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
