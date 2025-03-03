<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-blue-600">Schedule List</h2>
            <button class="bg-blue-500 text-white px-4 py-2 rounded" id="createBtn">Create New</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Command</th>
                        <th class="border p-2">Arguments</th>
                        <th class="border p-2">Options</th>
                        <th class="border p-2">Cron Expression</th>
                        <th class="border p-2">Environments</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="scheduleTable">
                    <!-- Data will be populated here dynamically -->
                </tbody>
            </table>
        </div>

        <div>
            <p><a href="https://crontab.cronhub.io/">CRON CHECK</a></p>            
            <p><a href="https://crontab.guru/">CRON GURU</a></p>            
        </div>
    </div>
    
    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-1/3">
            <h3 class="text-lg font-semibold mb-4" id="modalTitle">Create Schedule</h3>
            <form id="scheduleForm">
                <input type="hidden" id="scheduleId">
                <div class="mb-2">
                    <label class="block text-sm font-medium">Command</label>
                    <input type="text" id="command" name="command" class="w-full border p-2 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">Arguments</label>
                    <input type="text" id="arguments" name="arguments" class="w-full border p-2 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">Options</label>
                    <input type="text" id="options" name="options" class="w-full border p-2 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">Cron Expression</label>
                    <input type="text" id="cron" name="cron_expression" class="w-full border p-2 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">Environments</label>
                    <input type="text" id="environments" name="environments" class="w-full border p-2 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium">Status</label>
                    <select id="status" name="status" class="w-full border p-2 rounded">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded mr-2" id="closeModal">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function fetchSchedules() {
                $.ajax({
                    url: '/db-scheduler',
                    method: 'GET',
                    success: function(response) {
                        let rows = '';
                        response.forEach(schedule => {
                            rows += `<tr class='border'>
                                <td class='p-2 border'>${schedule.command}</td>
                                <td class='p-2 border'>${schedule.arguments ?? "-"}</td>
                                <td class='p-2 border'>${schedule.options ?? "-"}</td>
                                <td class='p-2 border'>${schedule.cron_expression}</td>
                                <td class='p-2 border'>${schedule.environments}</td>
                                <td class='p-2 border'>${schedule.status ? 'Active' : 'Inactive'}</td>
                                <td class='p-2 border'>
                                    <button class='bg-green-500 text-white px-2 py-1 rounded editBtn' data-id='${schedule.id}'>Edit</button>
                                    <button class='bg-red-500 text-white px-2 py-1 rounded deleteBtn' data-id='${schedule.id}'>Delete</button>
                                </td>
                            </tr>`;
                        });
                        $('#scheduleTable').html(rows);
                    }
                });
            }

            fetchSchedules();

            $('#createBtn').click(function() {
                $('#modalTitle').text('Create Schedule');
                $('#scheduleId').val('');
                $('#scheduleForm')[0].reset();
                $('#modal').removeClass('hidden');
            });

            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/db-scheduler/${id}`,
                    method: 'GET',
                    success: function(data) {
                        $('#scheduleId').val(data.id);
                        $('#command').val(data.command);
                        $('#arguments').val(data.arguments);
                        $('#options').val(data.options);
                        $('#cron').val(data.cron_expression);
                        $('#status').val(data.status);
                        $('#modalTitle').text('Edit Schedule');
                        $('#modal').removeClass('hidden');
                    }
                });
            });

            $('#scheduleForm').submit(function(event) {
                event.preventDefault();
                let id = $('#scheduleId').val();
                let url = id ? `/db-scheduler/${id}` : '/db-scheduler';
                let method = id ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    method: method,
                    contentType: 'application/json',
                    data: JSON.stringify({
                        command: $('#command').val(),
                        arguments: $('#arguments').val(),
                        options: $('#options').val(),
                        cron_expression: $('#cron').val(),
                        environments: $('#environments').val(),
                        status: $('#status').val()
                    }),
                    success: function() {
                        $('#modal').addClass('hidden');
                        fetchSchedules();
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function() {
                if (confirm('Are you sure you want to delete this schedule?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: `/db-scheduler/${id}`,
                        method: 'DELETE',
                        success: function() {
                            fetchSchedules();
                        }
                    });
                }
            });

            $('#closeModal').click(function() {
                $('#modal').addClass('hidden');
            });
        });
    </script>
</body>
</html>
