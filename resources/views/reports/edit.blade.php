<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-dark">Edit Laporan Skrining</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('reports.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Skor Total</label>
                        <input type="number" name="score_total" class="form-control"
                            value="{{ old('score_total', $report->score_total) }}" required>
                        @error('score_total')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Risiko</label>
                        <select name="risk_status" class="form-select" required>
                            <option value="Risiko Rendah" {{ old('risk_status', $report->risk_status) == 'Risiko Rendah' ? 'selected' : '' }}>Risiko Rendah</option>
                            <option value="Risiko Sedang" {{ old('risk_status', $report->risk_status) == 'Risiko Sedang' ? 'selected' : '' }}>Risiko Sedang</option>
                            <option value="Risiko Tinggi" {{ old('risk_status', $report->risk_status) == 'Risiko Tinggi' ? 'selected' : '' }}>Risiko Tinggi</option>
                        </select>
                        @error('risk_status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sesi</label>
                        <input type="text" name="screening_session" class="form-control"
                            value="{{ old('screening_session', $report->screening_session) }}" required>
                        @error('screening_session')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
