<x-app-layout>
    <x-admin.header>
        {{ __('Statistics') }}
    </x-admin.header>

    <x-admin.main>

        <style>
            table {
                border: 1px solid;
                border-collapse: collapse;
            }

            table th,
            table td {
                width: 150px;
                border: 1px solid;
            }
        </style>

        <table class="table-auto">
            <thead>
                <tr>
                    <th>Name</th>
                    @foreach($dates as $date)
                        <th>{{ $date }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($sortedUserStatuses as $sortedUserStatus)
                    <tr>
                        <td>{{ $sortedUserStatus->user->name }} {{ $sortedUserStatus->user->id }}</td>
                        @foreach($sortedUserStatus->countStatusesPreMonth as $monthPreview)
                            <td>{{ $monthPreview['attended'] }} | {{ $monthPreview['canceled'] }} | {{ $monthPreview['no-show'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </x-admin.main>
</x-app-layout>
