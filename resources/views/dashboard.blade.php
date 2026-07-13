<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- ✅ LEFT SIDEBAR -->
    <div class="w-64 bg-gray-900 text-white p-4 overflow-y-auto">

        <h2 class="text-xl font-bold mb-6">Admin Panel</h2>

        <ul class="space-y-2 text-sm">

            <li><a href="{{ route('admin.user-management') }}" class="block hover:bg-gray-700 p-2 rounded">User Management</a></li>
            <li><a href="{{ route('admin.customer-management') }}" class="block hover:bg-gray-700 p-2 rounded">Customer Management</a></li>
            <li><a href="{{ route('admin.agent-management') }}" class="block hover:bg-gray-700 p-2 rounded">Agent Management</a></li>
            <li><a href="{{ route('admin.airline-ticket-management') }}" class="block hover:bg-gray-700 p-2 rounded">Airline / Ticket Management</a></li>
            <li><a href="{{ route('admin.hotel-management') }}" class="block hover:bg-gray-700 p-2 rounded">Hotel Management</a></li>
            <li><a href="{{ route('admin.visa-management') }}" class="block hover:bg-gray-700 p-2 rounded">Visa Management</a></li>
            <li><a href="{{ route('admin.package-builder') }}" class="block hover:bg-gray-700 p-2 rounded">Package Builder</a></li>
            <li><a href="{{ route('admin.dynamic-package-calculator') }}" class="block hover:bg-gray-700 p-2 rounded">Dynamic Package Calculator</a></li>
            <li><a href="{{ route('admin.quote-management') }}" class="block hover:bg-gray-700 p-2 rounded">Quote Management</a></li>
            <li><a href="{{ route('admin.booking-management') }}" class="block hover:bg-gray-700 p-2 rounded">Booking Management</a></li>
            <li><a href="{{ route('admin.transport-management') }}" class="block hover:bg-gray-700 p-2 rounded">Transport Management</a></li>
            <li><a href="{{ route('admin.voucher-management') }}" class="block hover:bg-gray-700 p-2 rounded">Voucher Management</a></li>
            <li><a href="{{ route('admin.payment-management') }}" class="block hover:bg-gray-700 p-2 rounded">Payment Management</a></li>
            <li><a href="{{ route('admin.accounting') }}" class="block hover:bg-gray-700 p-2 rounded">Accounting</a></li>
            <li><a href="{{ route('admin.crm') }}" class="block hover:bg-gray-700 p-2 rounded">CRM</a></li>
            <li><a href="{{ route('admin.reports') }}" class="block hover:bg-gray-700 p-2 rounded">Reports</a></li>
            <li><a href="{{ route('admin.notifications') }}" class="block hover:bg-gray-700 p-2 rounded">Notifications</a></li>
            <li><a href="{{ route('admin.website-cms') }}" class="block hover:bg-gray-700 p-2 rounded">Website CMS</a></li>
            <li><a href="{{ route('admin.dynamic-package-builder') }}" class="block hover:bg-gray-700 p-2 rounded">Dynamic Package Builder</a></li>

        </ul>
    </div>

    <div class="flex-1 p-6 overflow-y-auto">

        <!-- 🔹 Top Cards -->
        <div class="grid grid-cols-4 gap-4 mb-6">

            <div class="bg-orange-500 text-white p-4 rounded-lg shadow">
                <h3 class="text-lg">183</h3>
                <p>Invoices</p>
            </div>

            <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
                <h3 class="text-lg">233</h3>
                <p>Jobs</p>
            </div>

            <div class="bg-yellow-400 p-4 rounded-lg shadow">
                <h3 class="text-lg">46</h3>
                <p>Leads</p>
            </div>

            <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                <h3 class="text-lg">97</h3>
                <p>Tickets</p>
            </div>

        </div>

        <!-- 🔹 FORM + DATA -->
        <div class="grid grid-cols-2 gap-6">

            <!-- LEFT FORM -->
            <div class="bg-white p-5 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4">Add Data</h2>

                <form method="POST" class="space-y-4">

                    <input type="text" name="name" placeholder="Name"
                        class="w-full border p-2 rounded">

                    <input type="email" name="email" placeholder="Email"
                        class="w-full border p-2 rounded">

                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Submit
                    </button>

                </form>
            </div>

            <!-- RIGHT DATA -->
            <div class="bg-white p-5 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4">Data List</h2>

                <table class="w-full border">

                    <tr class="bg-gray-200">
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Email</th>
                    </tr>

                    <?php
                    $data = [
                        ["Ali", "ali@mail.com"],
                        ["Ahmed", "ahmed@mail.com"]
                    ];

                    foreach($data as $row){
                        echo "<tr>
                                <td class='p-2 border'>{$row[0]}</td>
                                <td class='p-2 border'>{$row[1]}</td>
                              </tr>";
                    }
                    ?>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>