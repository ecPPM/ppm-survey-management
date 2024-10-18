<div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-body border flex flex-col items-center bg-gray-50">
        <div class="mx-auto text-center my-2">
            <h2 class="card-title">{{ $survey->title }}</h2>
            <div class="flex flex-row justify-center my-2">
                <div class="h-auto my-auto w-[12px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l352 0 0 256c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256z"/>
                    </svg>
                </div>
                <div class="ps-2">
                    <p>{{ \Carbon\Carbon::parse($survey->start_date)->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex flex-row justify-center">
                <div class="h-auto my-auto w-[12px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M128 0c13.3 0 24 10.7 24 24l0 40 144 0 0-40c0-13.3 10.7-24 24-24s24 10.7 24 24l0 40 40 0c35.3 0 64 28.7 64 64l0 16 0 48 0 256c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 192l0-48 0-16C0 92.7 28.7 64 64 64l40 0 0-40c0-13.3 10.7-24 24-24zM400 192L48 192l0 256c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-256zM329 297L217 409c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 95-95c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                    </svg>
                </div>
                <div class="ps-2">
                    <p>{{ \Carbon\Carbon::parse($survey->end_date)->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-auto">
        <table class="table-auto table-zebra w-full">
            <tbody>
                @foreach ($tableData as $row)
                    <tr>
                        <td class="border px-4 py-2">{{ $row[0] }}</td>
                        <td class="border px-4 py-2">{{ $row[1] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



