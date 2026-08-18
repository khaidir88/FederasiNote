<tr>
    <td>
        {{-- indent --}}
        @if($level > 0)
        <span class="text-muted">
            {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}└─
        </span>
        @endif

        {{-- nama menu --}}
        <strong>{{ $menu->name }}</strong>

        {{-- tampilkan parent setelah nama (hanya jika punya parent) --}}
        @if($menu->parent)
        <span class="text-muted ms-1">
            ({{ $menu->parent->name }})
        </span>
        @endif
    </td>

    <td>{{ $menu->parent->name ?? '-' }}</td>
    <td class="text-center">{{ ucfirst($menu->position) }}</td>
    <td class="text-center">{{ $menu->order }}</td>
    <td class="text-center">
        <span class="badge bg-{{ $menu->is_active ? 'success' : 'secondary' }}">
            {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
        </span>
    </td>
    <td class="text-center">
        <button class="btn btn-sm btn-warning btn-edit"
            data-id="{{ $menu->id }}"
            data-name="{{ $menu->name }}"
            data-parent="{{ $menu->parent_id }}"
            data-position="{{ $menu->position }}"
            data-order="{{ $menu->order }}"
            data-active="{{ $menu->is_active }}">
            <i class="bi bi-pencil"></i>
        </button>

        <button class="btn btn-sm btn-danger btn-delete"
            data-id="{{ $menu->id }}"
            data-name="{{ $menu->name }}">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>

@foreach($menu->children as $child)
@include('menus.partials.row', [
'menu' => $child,
'level' => $level + 1
])
@endforeach