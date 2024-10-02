@props(['users'])

<div class="w-full overflow-x-scroll">
    <table class="app-table">
        <thead>
        <tr class="text-left">
            <th>Enum Code</th>
            <th>Name</th>
            <th>Survey</th>
            <th>Mode</th>
            <th class="action"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr wire:key="{{ $user->id }}" title="Click to see details about this user"
                class="cursor-pointer hover:bg-base-200"
                wire:click="handleRowClick({{$user->id}})">
                <td>{{ $user->enum_code }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->survey()->title }}</td>
                <td>-</td>

                <td class="action pr-2">
                    <div class="w-2 h-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path
                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"
                                fill="#676767" />
                        </svg>
                    </div>
                </td>
                <td class="action pr-2">
                    <div class="w-2 h-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path
                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"
                                fill="#676767" />
                        </svg>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
