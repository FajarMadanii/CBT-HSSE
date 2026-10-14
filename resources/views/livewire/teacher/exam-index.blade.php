<div>
  <div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Ujian CBT</h2>
    </div>
    @unless(auth()->user()->hasRole('admin'))
    <button wire:click="create"
      class="mt-3 sm:mt-0 inline-flex items-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">+
      Buat Ujian</button>
    @endunless
  </div>
  <div class="mb-4"><input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari ujian..."
      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-emerald-600 sm:text-sm">
  </div>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mapel</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Soal</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @forelse($exams as $e)
          <tr>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $e->title }}</td>
            <td class="px-6 py-4 text-sm"><span
                class="inline-flex rounded-full px-2 text-xs font-semibold bg-indigo-100 text-indigo-800">{{ strtoupper($e->type) }}</span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ $e->subject?->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ $e->classroom?->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ $e->questions_count }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ $e->attempts_count }}</td>
            <td class="px-6 py-4 text-sm"><span
                class="inline-flex rounded-full px-2 text-xs font-semibold {{ $e->status === 'published' ? 'bg-green-100 text-green-800' : ($e->status === 'closed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($e->status) }}</span>
            </td>
            <td class="px-6 py-4 text-right text-sm" x-data="{ detailOpen: false }">
              @if(auth()->user()->hasRole('admin'))
              <button type="button" @click="detailOpen = true" class="mr-3 font-semibold text-emerald-600 hover:text-emerald-900">Lihat</button>
              <a href="{{ route('admin.questions.index', ['exam_id' => $e->id]) }}" class="font-semibold text-blue-600 hover:text-blue-900">Lihat Soal</a>
              <template x-teleport="body"><div x-show="detailOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-900/60" @click="detailOpen = false"></div>
                <div class="relative w-full max-w-lg rounded-xl bg-white p-6 text-left shadow-2xl">
                  <div class="flex items-start justify-between gap-4"><div><h3 class="text-lg font-bold text-gray-900">{{ $e->title }}</h3><p class="mt-1 text-sm text-gray-500">{{ $e->subject?->name ?? '-' }} · {{ $e->classroom?->name ?? '-' }}</p></div><button @click="detailOpen = false" class="text-xl text-gray-400">×</button></div>
                  <dl class="mt-5 grid grid-cols-2 gap-4 text-sm"><div class="col-span-2"><dt class="font-semibold text-gray-700">Deskripsi</dt><dd class="whitespace-pre-line text-gray-600">{{ $e->description ?: '-' }}</dd></div><div><dt class="font-semibold text-gray-700">Tipe</dt><dd class="text-gray-600">{{ strtoupper($e->type) }}</dd></div><div><dt class="font-semibold text-gray-700">Durasi</dt><dd class="text-gray-600">{{ $e->duration_minutes }} menit</dd></div><div><dt class="font-semibold text-gray-700">Mulai</dt><dd class="text-gray-600">{{ $e->start_at->format('d M Y H:i') }}</dd></div><div><dt class="font-semibold text-gray-700">Selesai</dt><dd class="text-gray-600">{{ $e->end_at->format('d M Y H:i') }}</dd></div><div><dt class="font-semibold text-gray-700">KKM</dt><dd class="text-gray-600">{{ $e->passing_score }}</dd></div><div><dt class="font-semibold text-gray-700">Status</dt><dd class="text-gray-600">{{ ucfirst($e->status) }}</dd></div></dl>
                  <button @click="detailOpen = false" class="mt-6 w-full rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Tutup</button>
                </div></div></template>
              @else
              <a href="{{ route('teacher.exams.monitor', $e->id) }}"
                class="text-purple-600 hover:text-purple-900 mr-2">Monitor</a>
              <a href="{{ route('teacher.exams.print', $e->id) }}"
                class="text-orange-600 hover:text-orange-900 mr-2">Print</a>
              <a href="{{ route('teacher.questions.index', ['exam_id' => $e->id]) }}"
                class="text-blue-600 hover:text-blue-900 mr-2">Soal</a>
              <button wire:click="edit({{ $e->id }})" class="text-emerald-600 hover:text-emerald-900 mr-2">Edit</button>
              <button wire:click="confirmDelete({{ $e->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada ujian</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="px-6 py-3 border-t">{{ $exams->links() }}</div>
  </div>
  @if($showForm)
    <div class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('showForm', false)"></div>
        <div class="relative rounded-lg bg-white shadow-xl sm:w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto">
          <form wire:submit="save">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4">{{ $editingId ? 'Edit' : 'Buat' }} Ujian</h3>
              <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Judul</label><input wire:model="title"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">@error('title')<span
                    class="text-sm text-red-600">{{ $message }}</span>@enderror</div>
                <div class="grid grid-cols-3 gap-4">
                  <div><label class="block text-sm font-medium text-gray-700">Tipe</label><select wire:model="type"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                      <option value="quiz">Quiz</option>
                      <option value="uts">UTS</option>
                      <option value="uas">UAS</option>
                      <option value="praktik">Praktik</option>
                      <option value="tryout">Tryout</option>
                    </select></div>
                  <div><label class="block text-sm font-medium text-gray-700">Mapel</label><select wire:model="subject_id"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                      <option value="">Pilih</option>@foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}
                      </option>@endforeach
                    </select>@error('subject_id')<span class="text-sm text-red-600">{{ $message }}</span>@enderror</div>
                  <div><label class="block text-sm font-medium text-gray-700">Kelas</label><select
                      wire:model="classroom_id"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                      <option value="">Pilih</option>@foreach($classrooms as $c)<option value="{{ $c->id }}">
                        {{ $c->name }}
                      </option>@endforeach
                    </select></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Deskripsi</label><textarea
                    wire:model="description" rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                  <div><label class="block text-sm font-medium text-gray-700">Durasi (menit)</label><input
                      wire:model="duration_minutes" type="number" min="1"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                  </div>
                  <div><label class="block text-sm font-medium text-gray-700">KKM</label><input wire:model="passing_score"
                      type="number" min="0" max="100"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                  </div>
                  <div><label class="block text-sm font-medium text-gray-700">Status</label><select wire:model="status"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                      <option value="draft">Draft</option>
                      <option value="published">Published</option>
                      <option value="closed">Closed</option>
                    </select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div><label class="block text-sm font-medium text-gray-700">Mulai</label><input wire:model="start_at"
                      type="datetime-local"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">@error('start_at')<span
                      class="text-sm text-red-600">{{ $message }}</span>@enderror</div>
                  <div><label class="block text-sm font-medium text-gray-700">Selesai</label><input wire:model="end_at"
                      type="datetime-local"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">@error('end_at')<span
                      class="text-sm text-red-600">{{ $message }}</span>@enderror</div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div class="space-y-2">
                    <label class="flex items-center"><input wire:model="shuffle_questions" type="checkbox"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"><span
                        class="ml-2 text-sm text-gray-700">Acak soal</span></label>
                    <label class="flex items-center"><input wire:model="shuffle_options" type="checkbox"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"><span
                        class="ml-2 text-sm text-gray-700">Acak pilihan jawaban</span></label>
                  </div>
                  <div class="space-y-2">
                    <label class="flex items-center"><input wire:model="show_result" type="checkbox"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"><span
                        class="ml-2 text-sm text-gray-700">Tampilkan hasil</span></label>
                    <label class="flex items-center"><input wire:model="allow_retry" type="checkbox"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"><span
                        class="ml-2 text-sm text-gray-700">Izinkan retry</span></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="submit"
                class="inline-flex w-full justify-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500 sm:ml-3 sm:w-auto">Simpan</button>
              <button type="button" wire:click="$set('showForm', false)"
                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 sm:mt-0 sm:w-auto">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
  @if($showDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
        <div class="relative rounded-lg bg-white shadow-xl sm:max-w-sm p-6 text-center">
          <p class="text-sm text-gray-500 mb-4">Yakin ingin menghapus ujian ini?</p>
          <div class="flex justify-center gap-3"><button wire:click="$set('showDeleteModal', false)"
              class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300">Batal</button><button
              wire:click="delete"
              class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Hapus</button>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
