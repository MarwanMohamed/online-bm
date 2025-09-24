<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Backup Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Backup Status</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Monitor your backup health and storage</p>
                </div>
                @if($this->getBackupDestinations()->first()->healthy ?? false)
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-green-600 dark:text-green-400 font-medium">All systems healthy</span>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    </div>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($this->getBackupDestinations() as $destination)
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $destination->amount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Backup files</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $destination->newest }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Last backup</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-medium text-blue-600 dark:text-blue-400 mb-2">{{ $destination->used_storage }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Storage used</div>
                    </div>
                    <div class="text-center">
{{--                        @if($this->getQueuedJobsCount() > 0)--}}
{{--                            <div class="text-lg font-medium text-orange-600 dark:text-orange-400 mb-2 animate-pulse">{{ $this->getQueuedJobsCount() }}</div>--}}
{{--                            <div class="text-sm text-gray-600 dark:text-gray-400">Queued backups</div>--}}
{{--                        @else--}}
                            <div class="text-lg font-medium text-green-600 dark:text-green-400 mb-2">0</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Queued backups</div>
{{--                        @endif--}}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Backup Files --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Backup Files</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Download or delete your backup archives</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">File Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if($this->getBackups()->count() > 0)
                            @foreach($this->getBackups() as $backup)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ basename($backup->path) }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ dirname($backup->path) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $backup->date }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $backup->size }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button wire:click="downloadBackup('{{ $backup->filename }}')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download
                                            </button>
                                            <button wire:click="deleteBackup('{{ $backup->filename }}')" onclick="return confirm('Are you sure you want to delete this backup?')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-center">
                                        <div class="flex justify-center mb-4">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-white text-lg">No backups</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>