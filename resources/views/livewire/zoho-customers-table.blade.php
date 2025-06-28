<div>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Customer</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact ID</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
        @forelse ($customers as $customer)
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer['contact_name'] ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer['contact_id'] ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer['email'] ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer['company_name'] ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer['mobile'] ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer['status'] ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Tidak ada data customer.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>