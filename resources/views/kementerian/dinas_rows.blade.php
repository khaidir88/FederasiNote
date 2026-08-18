@forelse($dinass as $index => $dinas)
<tr>
    <td>{{ $index+1 }}</td>

    <td class="fw-semibold text-start">
        <a href="{{ route('kementerian.dinas', $dinas->slug) }}" class="text-decoration-none">
            {{ $dinas->nama }}
        </a>
    </td>

    <td class="text-start text-muted">
        {{ Str::limit($dinas->struktur, 60) ?: '-' }}
    </td>

    <td class="text-start text-muted">
        {{ Str::limit($dinas->ket, 80) ?: '-' }}
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted py-4">
        <i class="bi bi-info-circle me-1"></i> Data tidak ditemukan
    </td>
</tr>
@endforelse