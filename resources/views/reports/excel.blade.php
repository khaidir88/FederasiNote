<table>
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>

            <th>Score</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $index => $result)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $result->user->name ?? '-' }}</td>
            <td>{{ $result->category->name ?? '-' }}</td>
            <td>{{ $result->score_total }}</td>
            <td>{{ $result->risk_status }}</td>

            <td>{{ $result->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>